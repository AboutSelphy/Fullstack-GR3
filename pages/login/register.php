<?php
require_once('../../script/globals.php');
require_once('../../script/db_connection.php');
require_once('../../script/isLoggedIn.php');


$role = isLoggedIn($db)[1];
echo ' <br>--loginGATE -- ' . 'role: ' .$role ;

if($role !== 'error'){
    header("Location: ".BASE_DIR."pages/login/login.php");
    exit();
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>REGISTER</h1>

</body>
</html>