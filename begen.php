<?php

    session_start();
    if (!isset($_SESSION['username']))
        header("location:login.php");
    include 'db.php';  // db scriptini bu scripte ekliyor
    
   
    if(isset($_GET['tarif_id_like'])) /* Begen butonuna basildiysa */
    {
        $sql = "INSERT INTO `begeni` (`tarif_id`,`kullanici_id`) VALUES (".$_GET['tarif_id_like'].",".$_SESSION['user_id'].")";    
        $res = mysqli_query($db, $sql);
        if($res)
        {
            echo "<script> alert('Begenildi...'); </script>";
        }
        if(isset($_GET['anasayfa']))
            header("location:anasayfa.php");
        else
            header("location:tektarif.php?tarif_id=".$_GET['tarif_id_like']);
        
    }
    if(isset($_GET['tarif_id_dislike'])) /* Begeniyi kaldirma butonuna basildiysa */
    {
        $sql = "DELETE FROM `begeni` WHERE `kullanici_id` = ".$_SESSION['user_id'];
        $res = mysqli_query($db, $sql);
        if($res)
        {
            echo "<script> alert('Begeni geri cekildi...'); </script>";
        }
        if(isset($_GET['anasayfa']))
            header("location:anasayfa.php");
        else
            header("location:tektarif.php?tarif_id=".$_GET['tarif_id_dislike']);
        
    }
    
?>