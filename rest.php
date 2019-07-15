<?php
    include 'db.php';  // db scriptini bu scripte ekliyor
    $recipes = array();
    header("Content-type: application/json");

    function info(){
        $outjson = array(
            "How-to" => "Basic usage information",
            "GET-All" => "tarif.php?list will return all recipes as json file",
            "GET-my" => "tarif.php?tariflerim?username will return all recipes created by user named username",
            "GET-one" => "rest.php?tarif=t_id will return one recipe with id equal to t_id",
            "POST-one" => "Send json file with tarif, and array called tags and aciklama. Do not use any other name for tarif,tags or aciklama",
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
        $outjson = array(
            "Recipes" => $recipes,
        );
        return json_encode($outjson);
    }

    function recipe($recipeID)
    {
        global $recipes;
        allRecipes();
        $outjson = array(
            "Recipes" => $recipes[array_search($recipeID, array_column($recipes,"recipeId"))],
        );
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

        $outjson = array(
                    "Recipes" => $recipesByUser,
        );

        return json_encode($outjson);
    }

    function readPost()
    {
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
                "alert" => "Could not record",
                "error" => mysqli_error($db),
                "user" => $username,
            );
        }
        else // sorguyu calistirabilirse
        {
            $outjson = array(
                "success" => "Recipe is added",
            );
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
        }
        return json_encode($outjson);
    }

    if(isset($_GET["list"]) && empty($_GET["list"]))
        echo allRecipes();
    elseif(isset($_GET["tarif"]))
        echo recipe($_GET["tarif"]);
    elseif(isset($_GET["tariflerim"]))
        echo myRecipes($_GET["tariflerim"]);
    else
        echo info();

?>