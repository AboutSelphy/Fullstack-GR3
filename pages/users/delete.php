<?php
//config for global constants
require_once("../../config.php");

require_once("./../../script/db_connection.php");
include("./../../script/loginGate.php");

$userDeleted = false;

$loc = "../";

if($role !== 'unset'){
    if ($role === 'shelter') {
        header("Location: " . BASE_DIR . "pages/sh_dashboard.php");
        exit();
    }
}


if(isset($_GET['id'])){
    $deleteID = $_GET['id'];

    try {

        $stmt = $db->prepare("DELETE FROM users WHERE id = :deleteID");
        $stmt->bindParam(':deleteID', $deleteID);
        $stmt->execute();
        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            if(isset($_COOKIE['sessionID'])){
                $cookieID = $_COOKIE['sessionID'];
                
                //destroy cookie
                setcookie('sessionID', '', time() - 3600, '/');
            }
            echo "Deletion successful. $rowCount row(s) deleted.";
            $userDeleted = true;

            header("refresh:1;url=" . BASE_DIR . "pages/login/login.php");
        } else {
            // echo "No records deleted. Possibly no matching userID found.";
        }
    
    
    } catch (PDOException $e) {
        // echo "Error: " . $e->getMessage(); // This will display the error message
    }
}



//destroy cookie

//DB delete users entry

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if ($userDeleted === true): ?>
        <h1>User deleted ✅</h1>
    <?php else: ?>
        <h1>Something went wrong :/ ❌</h1>
    <?php endif; ?>
</body>
</html>