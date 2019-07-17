<?php
    include 'db.php';  // db scriptini bu scripte ekliyor
    include 'foto.php';
    $recipes = array();
    header("Content-type: application/json");

    function info(){
        $getjson = array(
            "GET all" => "rest.php?list",
            "GET one" => "rest.php?tarif=# where # is equal to id of the recipe",
            "LOGIN" => "rest.php?username=#&password=# where #'s are equal to matching username and password",
            "DELETE one" => "rest.php?delete=#&password=## where # is equal to id of the recipe and ## is matching password of the creator user",
        );
        $postjson = array(
            "REGISTER" => "JSON file with 'username' and 'password' values",
            "POST one" => "JSON file with 'tarif' as name of the recipe, 'tags' as array of tags and 'aciklama' as description of the recipe",
        );
        $outjson = array(
            "How-to" => "Basic usage information",
            "GET" => $getjson,
            "POST" => $postjson,
        );
        return json_encode($outjson);
    }
    
    function allRecipes()
    {
        global $db, $recipes;
        $tags = array();
        $counter = 0;
        $sql = "SELECT * FROM `tarif`"; // sorgu
        $result = mysqli_query($db, $sql); //sorgu sonucu
        if (mysqli_affected_rows($db) > 0) //sorgu sonucunda sonuc donuyorsa
        {
            while ($row = mysqli_fetch_assoc($result))
            {
                $tagsql = "SELECT * FROM `tarif_tag` WHERE tarif_id = " . $row['id'];
				$tagres = mysqli_query($db, $tagsql);
				while ($tagrow = mysqli_fetch_assoc($tagres)) {
					$innersql = "SELECT * FROM `tag` WHERE tag_id = " . $tagrow['tag_id'];
					$innerres = mysqli_query($db, $innersql);
					$innerrow = mysqli_fetch_assoc($innerres);
                    array_push($tags,$innerrow["isim"]);
                }
                
                $innersql = "SELECT * FROM begeni WHERE tarif_id = " . $row["id"];
                $innerres = mysqli_query($db, $innersql);
                $likecount = mysqli_affected_rows($db);
                
                $recipes[$counter] = array(
                    "recipeId" => $row["id"],
                    "recipeName" => $row["isim"],
                    "recipeImage" => $row["fotograf"],
                    "recipeDescription" => $row["aciklama"],
                    "recipeTags" => $tags,
                    "created" => $row["username"],
                    "recipeDate" => $row["creation_date"],
                    "likes" => $likecount,
                );
                $counter++;
                $tags = array(); //arrayi bosalt her seferinde
            }
        }
        if(empty($recipes))
        {
            $outjson = array(
                "Error" => "Could not show recipes",
            );
        }else{
            $outjson = array(
                "Recipes" => $recipes,
            );
        }
        
        return json_encode($outjson);
    }

    function recipe($recipeID)
    {
        global $recipes;
        allRecipes();
        $recipe = $recipes[array_search($recipeID, array_column($recipes,"recipeId"))];
        if(empty($recipe))
        {
            $outjson = array(
                "Error" => "Recipe with Id" . $recipeID . " is not found",
            );
        }else{
            $outjson = array(
                "Recipes" => $recipe,
            );
        }

        return json_encode($outjson);
    }

    function myRecipes($username)
    {
        $counter = 0; // Birden cok tarifi varsa diye counter
        global $recipes, $db;
        allRecipes();
        $recipesByUser = array();
        $tags = array();
        $sql = "SELECT * FROM `tarif` WHERE username= '$username'";
        $result = mysqli_query($db, $sql); //sorgu sonucu
        if (mysqli_affected_rows($db) > 0) //sorgu sonucunda sonuc donuyorsa
        {
            while ($row = mysqli_fetch_assoc($result))
            {
                $tagsql = "SELECT * FROM `tarif_tag` WHERE tarif_id = " . $row['id'];
				$tagres = mysqli_query($db, $tagsql);
				while ($tagrow = mysqli_fetch_assoc($tagres)) {
					$innersql = "SELECT * FROM `tag` WHERE tag_id = " . $tagrow['tag_id'];
					$innerres = mysqli_query($db, $innersql);
					$innerrow = mysqli_fetch_assoc($innerres);
                    array_push($tags,$innerrow["isim"]);
                }
                
                $innersql = "SELECT * FROM begeni WHERE tarif_id = " . $row["id"];
                $innerres = mysqli_query($db, $innersql);
                $likecount = mysqli_affected_rows($db);
                
                $recipesByUser[$counter] = array(
                    "recipeId" => $row["id"],
                    "recipeName" => $row["isim"],
                    "recipeImage" => $row["fotograf"],
                    "recipeDescription" => $row["aciklama"],
                    "recipeTags" => $tags,
                    "created" => $row["username"],
                    "recipeDate" => $row["creation_date"],
                    "likes" => $likecount,
                );
                $counter++;
                $tags = array(); //arrayi bosalt her seferinde
            }
        }

        if(empty($recipesByUser))
        {
            $outjson = array(
                "Error" => "Could not fetch recipes by username = " .$username,
            );
        }else{
            $outjson = array(
                "Recipes" => $recipesByUser,
            );
        }

        return json_encode($outjson);
    }

    function readPost()
    {
        global $db;
        $data = json_decode(file_get_contents("php://input"));
        $name = $data['tarif'];
        $tags = $data['tags'];
        $tags = $myArray = explode(',', $tags);
        $desc = $data['aciklama'];

        //if(isset($_FILES["photo"]) && $_FILES["photo"]["name"] != ""){
		//	$photoname = uploadCloud($_FILES["photo"]["tmp_name"]);
		//	echo $photoname;
		//}else 
            $photoname = addslashes("fotograflar\\no.png" );
            
        $sql = "INSERT INTO `tarif`(`isim`,`fotograf`,`aciklama`,`username`,`creation_date`) VALUES ('$name','$photoname','$desc','$user_name',CURDATE())";
        
        if(!mysqli_query($db, $sql)) // sorguyu calistiramazsa
        {
            $outjson = array(
                "Error" => "Could not record: " . mysqli_error($db),
            );
        }
        else // sorguyu calistirabilirse
        {

            $tarif_id = $db -> insert_id; //yeni tarif kaydinin idsi
            foreach ($tags as $tag)
            {
                $sql = "INSERT INTO `tag`(isim) VALUES ('$tag')";
                mysqli_query($db, $sql);
                $tag_id = $db -> insert_id; //yeni tagin idsi
                $sql = "INSERT INTO `tarif_tag`(`tarif_id`,`tag_id`) VALUES ($tarif_id,$tag_id)";
                
                //echo "<script> console.log('$sql') </script>";
                mysqli_query($db, $sql);
            }
            $recipe = array(
                "tarif" => $name,
                "tags" => $tags,
                "aciklama" => $desc,
            );
            $outjson = array(
                "Success" => "Recipe is created",
                "Recipe" => $recipe,
            );
        }
        return json_encode($outjson);
    }

    function delete($del_id,$pass)
    {
        global $db;
        $sql = "SELECT username FROM tarif WHERE tarif.id ='".$del_id."'";
        $result = mysqli_query($db,$sql); 
        if(mysqli_affected_rows($db) == 0)
        {
            $outjson = array(
                "Error" => "Could not find tarif with id ".$del_id,
            );
        }
        else
        {
            $username = mysqli_fetch_assoc($result);
            $ssql = "SELECT password FROM kullanici WHERE username ='".$username["username"]."'";
            $result = mysqli_query($db,$ssql);

            if(mysqli_affected_rows($db) > 0)
            {
                $password = mysqli_fetch_assoc($result);
                if($pass == $password["password"])
                {
                    removeCloud($del_id);
	                $sql="DELETE FROM tarif WHERE id='".$del_id."'";
	                mysqli_query($db, $sql);
		            $sql="DELETE tag FROM tag INNER JOIN tarif_tag ON tag.tag_id=tarif_tag.tag_id WHERE tarif_id='".$del_id."'";
	                mysqli_query($db, $sql);
	                $sql="DELETE FROM tarif_tag WHERE tarif_id='".$del_id."'";
                    mysqli_query($db, $sql);
                    $outjson = array(
                        "Success" => "Recipe succesfully deleted",
                    );
                }else
                {
                    $outjson = array(
                        "Error" => "Wrong password for user ".$username["username"],
                    );
                }
            }
            else
            {
                $outjson = array(
                    "Error" => "Could not find user with name ".$username["username"],
                );
            }
        }
        return json_encode($outjson);
    }

    function login($username,$password)
    {
        global $db;
        $sql = "SELECT password FROM kullanici WHERE username ='".$username."'";
        $result = mysqli_query($db,$sql);
        if(mysqli_affected_rows($db) > 0)
        {
            $pass = mysqli_fetch_assoc($result);
            if($pass["password"] == $password)
            {
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                header("location:anasayfa.php");
            }
            else
            {
                $outjson = array(
                    "Error" => "Wrong password for user ".$username,
                );
            }
        }else
        {
            $outjson = array(
                "Error" => "User named ".$username."is not found",
            );
        }

        return json_encode($outjson);
    }

    if(isset($_GET["list"]) && empty($_GET["list"]))
        echo allRecipes();
    elseif(isset($_GET["tarif"]))
        echo recipe($_GET["tarif"]);
    elseif(isset($_GET["tariflerim"]))
        echo myRecipes($_GET["tariflerim"]);
    elseif(isset($_GET["username"]) && isset($_GET["password"]))
        echo login($_GET["username"],$_GET["password"]);
    elseif(isset($_GET["delete"]) && isset($_GET["password"]))
        echo delete($_GET["delete"],$_GET["password"]);
    else
        echo info();

?>