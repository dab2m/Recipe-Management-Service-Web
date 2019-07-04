<?php 
    session_start();
    if(!isset($_SESSION['username']))
        header("location:anasayfa.php");
	include 'db.php';
	$sql="DELETE FROM tarif WHERE id='".$_GET['del_id']."'";
	mysqli_query($db, $sql);
	include 'db.php';
	$sql="DELETE FROM tarif_tag WHERE tarif_id='".$_GET['del_id']."'";
	mysqli_query($db, $sql);
	include 'db.php';
	$sql="DELETE FROM tag WHERE SELECT tag_id FROM tarif_tag WHERE tarif_id='".$_GET['del_id']."'";
	mysqli_query($db, $sql);
	header("location:anasayfa.php");
	?>