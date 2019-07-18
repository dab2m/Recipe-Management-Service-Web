<?php
include 'db.php';
header("Content-type: application/json");

    function readRecipe($tarif,$tagler,$acik,$user)
    {
        global $db;
        $name = $tarif;
        $tags = $tagler;
        $tags = $tagler;
        $desc = $acik;
        $user_name = $user;

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
                "username" => $user,
            );
            $outjson = array(
                "Success" => "Recipe is created",
                "Recipe" => $recipe,
            );
        }
        return json_encode($outjson);
    }

    function register($user,$pass)
    {
        global $db;
        $username = $user;
        $password = $pass;

        $check = "SELECT username FROM kullanici WHERE username='". $username."'";
        $result = mysqli_query($db,$check);
        if(mysqli_affected_rows($db) > 0)
        {
            $outjson = array(
                "Error" => "Username ".$username." already exists",
            );
        }else
        {
            $sql = "INSERT INTO `kullanici`(`username`,`password`) VALUES ('$username','$password')";
            $result = mysqli_query($db,$sql);
            if(mysqli_affected_rows($db) > 0)
            {
                $outjson = array(
                    "Success" => "User named".$username." is created",
                );
            }
            else
            {
                $outjson = array(
                    "Error" => "SQL query error",
                );
            }
        }
        return json_encode($outjson);
    }

    $data = file_get_contents('php://input');
    $injson = json_decode($data);

    if(!empty($injson))
    {
        if(isset($injson->username) && isset($injson->password))
            echo register($injson->username,$injson->password);
        if(isset($injson->tarif) && isset($injson->tags) && isset($injson->aciklama) && isset($injson->username))
            echo readRecipe($injson->tarif,$injson->tags,$injson->aciklama,$injson->username);
    }
    else
    {
        $outjson = array(
            "Error" => "Could not get json file",
        );
    }
?>