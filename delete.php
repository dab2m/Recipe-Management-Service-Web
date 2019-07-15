<?php 

	include 'autoload.php';
	include 'db.php';

	session_start();
    if(!isset($_SESSION['username']))
        header("location:anasayfa.php");

	\Cloudinary::config(array(
		"cloud_name" => "dewae3den",
		"api_key" => "464216752894627",
		"api_secret" => "nZRgekbC44lEsk88nDzYAlxV0RA",
		"secure" => true
	));

	//$sql = "SELECT fotograf FROM tarif WHERE id='".$_GET['del_id']."'";
	//$result = mysqli_query($db,$sql);
	//$photoURL = mysqli_fetch_assoc($result);
	//$photo = substr($photoURL['fotograf'] , strripos($photoURL['fotograf'] ,'/')+1);
	$sql="DELETE FROM tarif WHERE id='".$_GET['del_id']."'";
	mysqli_query($db, $sql);
	$sql="DELETE FROM tag t INNER JOIN tarif_tag tt ON t.tag_id=tt.tag_id WHERE tt.tarif_id='".$_GET['del_id']."'";
	$sql="DELETE tag.* FROM tag INNER JOIN  tarif_tag ON tarif_tag.tag_id = tag.tag_id WHERE (tarif_id='".$_GET['del_id']."')";
	mysqli_query($db, $sql);
	$sql="DELETE FROM tarif_tag WHERE tarif_id='".$_GET['del_id']."'";
	mysqli_query($db, $sql);
	//\Cloudinary\Uploader::destroy($photo);
	header("location:tariflerim.php");
	?>