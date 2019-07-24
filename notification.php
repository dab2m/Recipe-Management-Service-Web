<?php
    include 'db.php';
    function send_notif($user,$tarif_id)
    {
        define('API_ACCESS_KEY', 'AIzaSyA076zh9_4yCBQJzxkJ4EsDGvdsr6hN_g4');

        $sql = "SELECT isim from tarif WHERE id='".$tarif_id."'";
        $result = mysqli_query($db,$sql);
        if(mysqli_affected_rows($db) > 0)
        {
            $tarif = mysqli_fetch_assoc($result);
            $message = array(
                "body" => $user." liked your ".$tarif." named recipe",
                "title" => "Recipe Management Service",
            );
    
            $fields = array(
                // NEED token from android
                "to" => "AAAAVLDF6QI:APA91bGqXgqqC2oDWWkGbUV_GA6YkzD9BOAysMUH3-ZaUu2JHi72duKmBjxiNuOXYQc_SCFNMX92MMm9R9ahmGPJwfXmes2bJ2PqZacn63VZdJ4-idXEeBTvt3_qz82jwXoWptBeIK00",
                "notification" => $message,
            );
    
            $headers = array(
                "Authorization: key=" . API_ACCESS_KEY,
                "Content-Type: application/json",
            );
    
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
            curl_setopt( $ch, CURLOPT_POST, true);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt( $ch, CURLOPT_SSL_VERIFPEER, false);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }
?>