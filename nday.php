<?php
include "db.php";
$days = "SELECT * FROM 'n_gun'";
$result = mysqli_query($db, $days);
echo "Hello form nday.php";
if(mysqli_affected_rows($db) > 0)
    while($row = mysqli_fetch_assoc($result))
    {
        echo $row['gun'];
        echo $row['username'];
        $mysql = "DELETE FROM `tarif` WHERE `creation_date` < DATE_SUB(NOW(), INTERVAL " . $row['gun'] . " DAY) AND username=" . $row['username'];
        mysqli_query($db, $mysql);
    }
?>