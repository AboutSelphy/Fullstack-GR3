<?php
require_once('defineUserStatus.php');

function isLoggedIn($db){
      //check if loggedIn
      //get client cookie
    if(count($_COOKIE) > 0){

        if(isset($_COOKIE['sessionID'])){
            $cookieID = $_COOKIE['sessionID'];

            $stmt = $db->prepare("SELECT sessionID, `status` FROM `login` INNER JOIN users ON login.userID = users.id WHERE sessionID = :cookieID");
            $stmt->bindParam(':cookieID', $cookieID);
            $stmt->execute();
            $loginData = $stmt->fetchAll();
    
            // echo '<pre>';
            // var_dump($loginData);
            // echo '</pre>';
            // and somewhere later:
            if(count($loginData) > 0 ){
                echo 'is logged In 😎';
                echo $loginData[0]['status'];
                //defineUserStatus
                defineUserStatus($loginData[0]['status']);

                return true;
                
            }else{
                echo 'is no user';
            }
        } else {
            echo 'no cookie set';
        }

    } else {
        echo 'no cookies available';
    }
    return false;

}