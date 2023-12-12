<?php
require_once('globals.php');


function defineUserStatus($userStatus) {
    if(isset($userStatus) && !empty($userStatus) && $userStatus !== ""){

        $GLOBALS['role'] = $userStatus;

        
       
    }

}