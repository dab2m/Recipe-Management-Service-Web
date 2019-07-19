<?php
include 'db.php';
header("Content-type: application/json");

    function readRecipe($tarif,$tagler,$acik,$user,$url)
    {
        global $db;
        $name = $tarif;
        $tags = $tagler;
        $tags = $tagler;
        $desc = $acik;
        $user_name = $user;

        if(isset($url))
            $photoname = $url;
        else
            $photoname = addslashes("fotograflar\\no.png" );
            
        $sql = "INSERT INTO `tarif`(`isim`,`fotograf`,`aciklama`,`username`,`creation_date`) VALUES ('$name','$photoname','$desc','$user_name',CURDATE())";
        
        if(!mysqli_query($db, $sql)) // sorguyu calistiramazsa
        {
            $outjson = array(
                "Status" => "Error",
                "Trace" => "Could not record: " . mysqli_error($db),
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
                "Status" => "Success",
                "Trace" => "Recipe is created",
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
                "Status" => "Error",
                "Trace" => "Username ".$username." already exists",
            );
        }else
        {
            $sql = "INSERT INTO `kullanici`(`username`,`password`) VALUES ('$username','$password')";
            $result = mysqli_query($db,$sql);
            if(mysqli_affected_rows($db) > 0)
            {
                $outjson = array(
                    "Status" => "Success",
                    "Trace" => "User named".$username." is created",
                );
            }
            else
            {
                $outjson = array(
                    "Status" => "Error",
                    "Trace" => "SQL query error",
                );
            }
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
                "Status" => "Error",
                "Trace" => "Could not find tarif with id ".$del_id,
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
                        "Status" => "Success",
                        "Trace" => "Recipe succesfully deleted",
                    );
                }else
                {
                    $outjson = array(
                        "Status" => "Error",
                        "Trace" => "Wrong password for user ".$username["username"],
                    );
                }
            }
            else
            {
                $outjson = array(
                    "Status" => "Error",
                    "Trace" => "Could not find user with name ".$username["username"],
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
                $outjson = array(
                    "Status" => "Success",
                    "Trace" => "Login is successful",
                );
            }
            else
            {
                $outjson = array(
                    "Status" => "Error",
                    "Trace" => "Wrong password for user ".$username,
                );
            }
        }else
        {
            $outjson = array(
                "Status" => "Error",
                "Trace" => "User named ".$username."is not found",
            );
        }

        return json_encode($outjson);
    }

    $data = file_get_contents('php://input');
    $injson = json_decode($data);

        if(!empty($injson))
        {
            if(isset($injson->register))
                if($injson->register)
                    if(isset($injson->username) && isset($injson->password))
                        echo register($injson->username,$injson->password);
                else
                    if(isset($injson->username) && isset($injson->password))
                        echo login($injson->username,$injson->password);

            elseif(isset($injson->tarif) && isset($injson->tags) && isset($injson->aciklama) && isset($injson->username))
                if(isset($injson->image))
                    echo readRecipe($injson->tarif,$injson->tags,$injson->aciklama,$injson->username,$injson->image);
                else
                    echo readRecipe($injson->tarif,$injson->tags,$injson->aciklama,$injson->username,null);
            elseif(isset($injson->delete) && isset($injson->password))
                echo delete($injson->delete,$injson->password);
            else{
                $outjson = array(
                    "Status" => "Error",
                    "Trace" => "No correct arguments are found check you json file",
                );
                echo json_encode($outjson);
            }
        }
        else
        {
            $outjson = array(
                "Status" => "Error",
                "Trace" => "Could not get json file",
            );
            echo json_encode($outjson);
        }
?>