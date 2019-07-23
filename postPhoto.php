<?php
    include 'foto.php';
    header("Content-type: application/json");

    if(isset($_FILES["photo"]) && $_FILES["photo"]["name"] != ""){
        $photoname = uploadCloud($_FILES["photo"]["tmp_name"]);

    if(!empty($photoname))
    {
        $outjson = array(
            "Status" => "Success",
            "Trace" => "Image successfully uploaded to Cloudinary",
            "Image URL" => $photoname,
        );
        echo json_encode($outjson);
    }
    else
    {
        $outjson = array(
            "Status" => "Error",
            "Trace" => "Image could not be uploaded",
        );
        echo json_encode($outjson);
    }

?>