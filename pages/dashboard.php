<?php
require_once('../script/globals.php');
require_once('../script/loginGate.php');


$loc = "./";

    if($role !== 'unset'){
        if ($role !== 'admin') {
            header("Location: {$loc}login/login.php");
            exit();
        }
    }

?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>ADMIN DASHBOARD</h1>
    <a href="./login/logout.php">LOGOUT</a>

</body>
</html>