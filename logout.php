<?php
    
    if(!session_id())
        session_start();
    session_destroy();
    
    echo "<script> alert('Cikis yapildi...'); window.location.href='login.php'; </script>";
?>