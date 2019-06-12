<?php
    include 'db.php';
    if(isset($_POST['username']) && isset($_POST['password']) ) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $sql = "SELECT * FROM `kullanici` WHERE `username` = '$username'";
        mysqli_query($db, $sql);
        if(mysqli_affected_rows($db) > 0) {
            echo "<script> alert('$username kullanici adi mevcut...'); window.location.href='login.php'; </script>";
        }
        else {
            $sql = "INSERT INTO `kullanici` (`username`,`password`) VALUES ('$username', '$password')";    
            if(mysqli_query($db, $sql))
            {          
                echo "<script> alert('Kaydiniz olusturulmustur...'); window.location.href='login.php'; </script>";
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                header("location:anasayfa.php");
            }
            else 
                echo "<script> alert('Kaydiniz olusturulamadi...'); window.location.href='login.php'; </script>";
        }
    }

?>