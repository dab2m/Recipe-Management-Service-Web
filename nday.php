<?php
include "db.php";
$days = "SELECT * FROM n_gun";
$result = mysqli_query($db, $days);
if(mysqli_affected_rows($db) > 0)
  
    while($row = mysqli_fetch_assoc($result))
    {
        $mysql = "DELETE `tarif`,`begeni` FROM `tarif` INNER JOIN `begeni` ON  tarif.id= begeni.tarif_id  WHERE `creation_date` < DATE_SUB(NOW(), INTERVAL " . $row['gun'] . " DAY) AND username=\"" . $row['username'] . "\"";
        mysqli_query($db, $mysql);
    }
?>