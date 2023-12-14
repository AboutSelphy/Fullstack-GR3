<?php

require_once("./../../script/db_connection.php");
// include("./../../script/loginGate.php");

$userDeletedLoginTable = false;
$userDeletedUsersTable = false;

// if($role = 'shelter'){
//     header('Location: ../login/login.php ');
// }


//users/delete.php?id=1
//GET

if(isset($_GET['id'])){
    $deleteID = $_GET['id'];
    
    try {
        $stmt = $db->prepare("SELECT users.* FROM `login` INNER JOIN `users` ON login.userID = users.id WHERE login.sessionID = :cookieID");
        $stmt->bindParam(':cookieID', $cookieID);
        $stmt->execute();
        $userData = $stmt->fetch();
        // var_dump($userData);
        if(is_array($userData) && count($userData) > 0){
            return $userData['id'];
        }
    
        return 'error';
    
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // This will display the error message
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
        <h1>User not deleted ❌</h1>
    <?php endif; ?>
</body>
</html>