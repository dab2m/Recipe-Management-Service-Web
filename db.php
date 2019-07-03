<?php
    //$url = parse_url(getenv("mysql://be8001746b3ab5:25d67e82@us-cdbr-iron-east-02.cleardb.net/heroku_1f4b01bd817d3c2?reconnect=true"));

    $server = "us-cdbr-iron-east-02.cleardb.net";
    $username = "be8001746b3ab5";
    $password = "25d67e82";
    $conn = "heroku_1f4b01bd817d3c2";

    //$db = mysqli_connect("127.0.0.1","root","","yemek_tarifi"); //girdigi link - db username - db password - db name 
    $db = mysqli_connect($server,$username,$password,$conn);

    if (!$db) {
        echo "<script>alert('There is something wrong with DB connection...');</script>";
        die("Connection failed: " . mysqli_connect_error());
    }
      
  
?>