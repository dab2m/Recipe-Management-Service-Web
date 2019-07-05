<?php 
    session_start();
    if(!isset($_SESSION['username']))
        header("location:anasayfa.php");
	include 'db.php';
	$sql="DELETE FROM tarif WHERE id='".$_GET['del_id']."'";
	mysqli_query($db, $sql);
	//$sql="DELETE FROM tag t INNER JOIN tarif_tag tt ON t.tag_id=tt.tag_id WHERE tt.tarif_id='".$_GET['del_id']."'";
	$sql="DELETE FROM tag INNER JOIN  tarif_tag ON tarif_tag.tag_id=tag.tag_id WHERE  tarif_id='".$_GET['del_id']."')";
	mysqli_query($db, $sql);
	$sql="DELETE FROM tarif_tag WHERE tarif_id='".$_GET['del_id']."'";
	mysqli_query($db, $sql);
	header("location:tariflerim.php");
	?>