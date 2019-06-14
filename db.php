<?php
    $db = mysqli_connect("localhost","root","","yemek_tarifi"); //girdigi link - db username - db password - db name
    
    if (!$db) {
        echo "<script>alert('There is something wrong with DB connection...');</script>";
        die("Connection failed: " . mysqli_connect_error());
    }
      
  
?>