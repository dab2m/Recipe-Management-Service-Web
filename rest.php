<?php
    include 'foto.php';
    $recipes = array();
    header("Content-type: application/json");

    function info(){
        $getjson = array(
            "GET all" => "get.php?list",
            "GET one" => "get.php?tarif=# where # is equal to id of the recipe",
        );
        $postjson = array(
            "REGISTER" => "JSON file with 'register' with value true, 'username' and 'password' values to post.php",
            "POST one" => "JSON file with 'tarif' as name of the recipe, 'tags' as array of tags and 'aciklama' as description of the recipe, image' as URL of Cloudinary and 'username' as the creator of the recipe then upload to post.php",
            "LOGIN" => "JSON file with 'register' with value false, username' and 'password' to post.php",
            "DELETE one" => "JSON file with 'delete' as id of the recipe and 'password' for user who created the recipe to post.php",
            "UPLOAD image" => "Post Image file to postPhoto.php and store the link in returned JSON file",
            "LIKE" => "JSON file with 'like' with recipeId and 'username' as user who liked the recipe to post.php",
            "DISLIKE" => "JSON file with 'dislike' with recipeId and 'username' as user who wants to remove their like from recipe to post.php",
        );
        $outjson = array(
            "How-to" => "Basic usage information",
            "GET" => $getjson,
            "POST" => $postjson,
        );
        return json_encode($outjson);
    }

    echo info();

?>