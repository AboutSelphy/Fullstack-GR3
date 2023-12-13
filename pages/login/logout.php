<?php
require_once('../../script/db_connection.php');
require_once('./script/unsetSessionCookie.php');
include_once('../../script/loginGate.php');

//unset our beautiful cookie :/
unsetSessionCookie($db);

header("refresh:3;url=../../index.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>you are logged out :)</p>
    
</body>
</html>
