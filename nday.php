<?php
include "db.php";
$days = "SELECT * FROM 'n_gun'";
$result = mysqli_query($db, $days);
echo "Hello form nday.php";
echo $row['gun'];
echo $row['username'];
if(mysqli_affected_rows($db) > 0)
    while($row = mysqli_fetch_assoc($result))
    {
        $mysql = "DELETE FROM `tarif` WHERE `creation_date` < DATE_SUB(NOW(), INTERVAL " . $row['gun'] . " DAY) AND username=" . $row['username'];
        mysqli_query($db, $mysql);
    }
?>