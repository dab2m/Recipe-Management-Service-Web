<?php
include "/app/vendor/autoload.php";  //for heroku
//include "vendor/autoload.php"; //for localhost
include "db.php";
	\Cloudinary::config(array(
		"cloud_name" => "dewae3den",
		"api_key" => "464216752894627",
		"api_secret" => "nZRgekbC44lEsk88nDzYAlxV0RA",
		"secure" => true
    ));

    function uploadCloud($tmp_name)
    {
        $uploaded = \Cloudinary\Uploader::upload($tmp_name);
        return $uploaded['secure_url'];
    }

    function removeCloud($tarif_id)
    {
        $sql = "SELECT fotograf FROM tarif WHERE id='$tarif_id'";
	    $result = mysqli_query($db,$sql);
	    $photoURL = mysqli_fetch_assoc($result);
        $photo = substr($photoURL['fotograf'] , strripos($photoURL['fotograf'] ,'/')+1);
        \Cloudinary\Uploader::destroy($photo);
    }
?>
