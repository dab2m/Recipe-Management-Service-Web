<?php
session_start();
if (!isset($_SESSION['username']))
	header("location:anasayfa.php");

//$sql="DELETE FROM `tarif` WHERE `timestamp` &gt; DATE_SUB(NOW(), INTERVAL 10 MINUTE)";
//$date = date("m-d-Y", strtotime('-3 day'));
//$sql="DELETE FROM `tarif` WHERE date < '".$date."'";
include 'db.php';
/*$user_name = $_SESSION['username'];
if (isset($_POST['days'])) {
	$key = $_POST['days'];
	echo "days:"+$days;
	$sql = "SELECT gün FROM `n_gun` WHERE username='$user_name'";
	mysqli_query($db, $sql);
	if (mysqli_affected_rows($db) > 0) {
		$newsql = "UPDATE `n_gun` SET gün='$key' WHERE username='$user_name'";
		mysqli_query($db, $newsql);
	} else {
		$newsql = "INSERT INTO `n_gun` (username,gun) VALUES('$user_name','$key') ";
		mysqli_query($db, $newsql);
	}

	$mysql = "DELETE FROM `tarif` WHERE `creation_date` < " . strtotime('-$key month');
	mysqli_query($db, $mysql);
	header("location:tariflerim.php");
}*/

$user_name = $_SESSION['username'];
if (isset($_POST['days'])) {
	$key = $_POST['days'];
	//echo "<script> hello+console.log('$key') </script>";
	$sql = "SELECT gun FROM `n_gun` WHERE username='$user_name'";
	mysqli_query($db, $sql);
	if (mysqli_affected_rows($db) > 0) {
		$newsql = "UPDATE `n_gun` SET gun='$key' WHERE username='$user_name'";
		mysqli_query($db, $newsql);
	} else {
		$newsql = "INSERT INTO `n_gun` (username,gun) VALUES('$user_name','$key') ";
		mysqli_query($db, $newsql);
	}

	//$mysql = "DELETE FROM `tarif` WHERE `creation_date` < " . strtotime('-$key month');
	$mysql = "DELETE FROM `tarif` WHERE `creation_date` < DATE_SUB(NOW(), INTERVAL $key DAY) AND username='$user_name'";
	mysqli_query($db, $mysql);
}
