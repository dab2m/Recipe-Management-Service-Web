<?php
    $db = mysqli_connect("http://recipemanagementservice495.herokuapp.com","root","","yemek_tarifi"); //girdigi link - db username - db password - db name
    
    if (!$db) {
        echo "<script>alert('There is something wrong with DB connection...');</script>";
        die("Connection failed: " . mysqli_connect_error());
    }
      
  
?>