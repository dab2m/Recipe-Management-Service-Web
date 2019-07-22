<?php
    include 'foto.php';
    header("Content-type: application/json");
    $outjson = array(
        "isset" => isset($_FILES),
        "content" => var_dump($_FILES),
    );

    echo json_encode($outjson);
    
    //$url = uploadCloud($image);

    /*if(!empty($url))
    {
        $outjson = array(
            "Status" => "Success",
            "Trace" => "Image successfully uploaded to Cloudinary",
            "Image URL" => $url,
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
    }*/

?>