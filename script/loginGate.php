<?php
require_once(__DIR__. '/db_connection.php');
require_once(__DIR__. '/globals.php');
require_once(__DIR__.  '/isLoggedIn.php');


//check if client has entry in db table login
    
    $role = isLoggedIn($db)[1];
    echo ' <br>--loginGATE -- ' . 'role: ' .$role ;

    if($role === 'error'){
        header("Location: ".BASE_DIR."pages/login/");
        exit();
    }

