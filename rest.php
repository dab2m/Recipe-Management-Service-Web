<?php
    include 'foto.php';
    $recipes = array();
    header("Content-type: application/json");

    function info(){
        $getjson = array(
            "GET all" => "get.php?list",
            "GET one" => "get.php?tarif=# where # is equal to id of the recipe",
            "LOGIN" => "get.php?username=#&password=# where #'s are equal to matching username and password",
            "DELETE one" => "get.php?delete=#&password=## where # is equal to id of the recipe and ## is matching password of the creator user",
        );
        $postjson = array(
            "REGISTER" => "JSON file with 'username' and 'password' values to post.php",
            "POST one" => "JSON file with 'tarif' as name of the recipe, 'tags' as array of tags and 'aciklama' as description of the recipe to post.php",
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