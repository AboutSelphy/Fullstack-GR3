<?php
require_once('globals.php');


function defineUserStatus($userStatus) {
    if(isset($userStatus) && !empty($userStatus) && $userStatus !== ""){

        setcookie('role',$userStatus,time() + 60*60*24);

        
       
    }

}