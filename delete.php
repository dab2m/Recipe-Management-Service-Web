<?php 
    session_start();
    if(!isset($_SESSION['username']))
        header("location:anasayfa.php");
	include 'db.php';
	$sql="DELETE FROM tarif WHERE id='".$_GET['del_id']."'";
	mysqli_query($db, $sql);
	include 'db.php';
	//$sql="DELETE FROM tag t INNER JOIN tarif_tag tt ON t.tag_id=tt.tag_id WHERE tt.tarif_id='".$_GET['del_id']."'";
	$sql="DELETE FROM tag WHERE tag_id = (SELECT tag_id FROM tarif_tag WHERE tarif_id='".$_GET['del_id']."')";
	mysqli_query($db, $sql);
	include 'db.php';
	$sql="DELETE FROM tarif_tag WHERE tarif_id='".$_GET['del_id']."'";
	mysqli_query($db, $sql);
	header("location:tariflerim.php");
	?>