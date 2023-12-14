<?php

function getUserIDWithCookieID($db){
    $cookieID = $_COOKIE['sessionID'];

    try {
        $stmt = $db->prepare("SELECT users.* FROM `login` INNER JOIN `users` ON login.userID = users.id WHERE login.sessionID = :cookieID");
        $stmt->bindParam(':cookieID', $cookieID);
        $stmt->execute();
        $userData = $stmt->fetch();
        // var_dump($userData);
        if(is_array($userData) && count($userData) > 0){
            return $userData['id'];
        }

        return 'error';




    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // This will display the error message
    }
}