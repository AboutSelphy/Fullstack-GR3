<?php
require_once('../../script/globals.php');
require_once('./script/setSessionCookie.php');
require_once('../../script/db_connection.php');
require_once('../../script/isLoggedIn.php');



//start session
session_start();
$sessionID = session_id();

//is loggedIN?

isLoggedIn($db);



if(isset($GLOBALS['role'])){

    if ($GLOBALS['role'] === 'user') {
        header("Location: ".BASE_DIR."pages/user_dashboard.php");
        exit();
    }elseif($GLOBALS['role'] === 'admin'){
        header("Location: ".BASE_DIR."pages/dashboard.php");
        exit();
    
    }elseif( $GLOBALS['role'] === 'shelter'){
        header("Location: ".BASE_DIR."pages/sh_dashboard.php");
        exit();
    }
}



if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //`? get userID
    // has account and logins successfully
    if(true){
        //? $_POST['mail'] 
        $email = 'mani@gmail.com';
        setSessionCookie($sessionID,$db,$email);

        // header('location: ../login/');

        

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="#" method="post">
        <input type="submit" value="Small login">
    </form>
    
</body>
</html>
