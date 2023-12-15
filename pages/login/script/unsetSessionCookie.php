<?php



//what if cookie runs out in the meantime xD


function unsetSessionCookie($db){
    //detroy db entry
    if(isset($_COOKIE['sessionID'])){
        $cookieID = $_COOKIE['sessionID'];
        
        //destroy cookie
        setcookie('sessionID', '', time() - 3600, '/');
        try {
            //code...
            $stmt = $db->prepare("DELETE FROM `login` WHERE sessionID = :cookieID");
            $stmt->bindParam(':cookieID', $cookieID);
            $stmt->execute();
            
            // echo 'deleted succesfully';
    
        } catch (PDOException $e) {
            echo 'could not be unset error ' . $e;
        }
    
        
    }
}