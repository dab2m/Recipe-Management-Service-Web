<?php
    include 'foto.php';
    header("Content-type: application/json");
    
    $image = file_get_contents('php://input');

    $outjson = array(
        "Image" => $image,
        "var_dump" => var_dump($image),
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