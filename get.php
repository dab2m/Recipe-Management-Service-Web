<?php
    include 'db.php';  // db scriptini bu scripte ekliyor
    
    $recipes = array();
    header("Content-type: application/json");

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
    
    if(isset($_GET["list"]) && empty($_GET["list"]))
        echo allRecipes();
    elseif(isset($_GET["tarif"]))
        echo recipe($_GET["tarif"]);
    elseif(isset($_GET["tariflerim"]))
        echo myRecipes($_GET["tariflerim"]);
    else{
            $outjson = array(
                "Status" => "Error",
                "Trace" => "No correct arguments are found check you json file",
            );
            return json_encode($outjson);
    }
?>