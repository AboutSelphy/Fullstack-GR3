<?php

function checkIfLoginExists($db,$userID){
    $stmt = $db->prepare("SELECT userID FROM `login` WHERE userID = :userID");
    $stmt->bindParam(':userID', $userID);
    $stmt->execute();
    $loginData = $stmt->fetchAll();

    return is_array($loginData)  && count($loginData) > 0 ? true : false;
}

function setSessionCookie($sessionID,$db,$email){
    if(count($_COOKIE) > 0) {
        //set Cookie for 24hours
        setcookie('sessionID',$sessionID,time() + 60 * 60 * 24, '/' );

        //get userID from db
        $stmt = $db->prepare("SELECT `id` FROM `users` WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $loginData = $stmt->fetch();

        if(count($loginData) > 0 ){
            $userID = $loginData['id'];
        }else{
            echo 'nothing found';
        }

        //store in DB
        $data = [
            'userID' => $userID,
            'sessionID' => $sessionID,
        ];
            
        //only store when cookie is not set in db table
        if(is_int($userID) && !checkIfLoginExists($db,$userID)){
            $sql = "INSERT INTO `login` (userID,  sessionID) VALUES (:userID, :sessionID)";
            $stmt= $db->prepare($sql);
            $stmt->execute($data);
            
            echo 'db entry created';
        }
        
        //close db connection
        $db = NULL;
        
    } else {
        echo 'no cookies allowed error';
    }
}