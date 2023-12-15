<?php
require_once(__DIR__. '/db_connection.php');
require_once(__DIR__. '/globals.php');
require_once(__DIR__.  '/isLoggedIn.php');


//check if client has entry in db table login
    
    $role = isLoggedIn($db)[1];
    // echo ' <br>--loginGATE -- ' . 'role: ' .$role ;
    // echo 'FILENAME: ' . basename($_SERVER["SCRIPT_FILENAME"]);

    if($role === 'unset'){
        header("Location: ".BASE_DIR."pages/login/login.php");
        exit();
    }

