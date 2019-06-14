<?php
    $db = mysqli_connect("us-cdbr-iron-east-02.cleardb.net","b9adbef1407bd0","ed176cca","yemek_tarifi"); //girdigi link - db username - db password - db name
    
    if (!$db) {
        echo "<script>alert('There is something wrong with DB connection...');</script>";
        die("Connection failed: " . mysqli_connect_error());
    }
      
  
?>