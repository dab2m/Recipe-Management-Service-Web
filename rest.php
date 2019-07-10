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
                $recipes[$counter] = array(
                    "recipeId" => $row["id"],
                    "recipeName" => $row["isim"],
                    "recipeImage" => $row["fotograf"],
                    "recipeDescription" => $row["aciklama"],
                    "recipeTags" => $tags,
                    "created" => $row["username"],
                    "recipeDate" => $row["creation_date"],
                );
                $counter++;
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
        global $recipes;
        allRecipes();
        $outjson = array(
            "Recipes" => $recipes[array_search($username, array_column($recipes,"created"))],
        );
        return json_encode($outjson);
    }

    if(isset($_GET["list"]) && empty($_GET["list"]))
        echo allRecipes();
    if(isset($_GET["tarif"]))
        echo recipe($_GET["tarif"]);
    if(isset($_GET["tariflerim"]) && empty($_GET["list"]))
        echo myRecipes($_SESSION["username"]);

?>