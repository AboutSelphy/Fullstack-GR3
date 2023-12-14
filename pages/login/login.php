<?php
require_once('../../script/globals.php');
require_once('./script/setSessionCookie.php');
require_once('../../script/db_connection.php');
require_once('../../script/isLoggedIn.php');
require_once('../../script/input_validation.php');



//start session
session_start();
$sessionID = session_id();


$loc = "../";

//is loggedIN?
$role = isLoggedIn($db)[1];

echo $role;

if($role !== 'unset'){
    if ($role === 'user') {
        header("Location: {$loc}user_dashboard.php");
        exit();
    }elseif($role === 'admin'){
        header("Location: {$loc}dashboard.php");
        exit();
    
    }elseif( $role === 'shelter'){
        header("Location: {$loc}sh_dashboard.php");
        exit();
    }
}

$emailError = $passwordError = "";

$errorStatus = false;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //`? get userID
    // has account and logins successfully
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);

        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
        {
            $errorStatus = true;
            $emailError = "Please enter a valid Email adress";
        }

        if(empty($password))
        {
            $errorStatus = true;
            $passError = "Pls enter password";
        }

        // echo $email . 'EMASD';
        if($errorStatus !== true){
            $password = hash('sha256',$password);
            $stmt = $db->prepare("SELECT password FROM `users` WHERE password = :password");
            $stmt->bindParam(':password', $password );
            $stmt->execute();
            $passwordResponse = $stmt->fetch();
    
                if (is_array($passwordResponse) && count($passwordResponse) != 0 ){
                    echo "pw found cookie set" ;
                    setSessionCookie($sessionID,$db,$email);

                    header("refresh:0;url=".$_SERVER['PHP_SELF']);
                        // if ($role === 'user') {
                        //     header("Location: ".BASE_DIR."pages/user_dashboard.php");
                        //     exit();
                        // }elseif($role === 'admin'){
                        //     header("Location: ".BASE_DIR."pages/dashboard.php");
                        //     exit();
                        
                        // }elseif( $role === 'shelter'){
                        //     header("Location: ".BASE_DIR."pages/sh_dashboard.php");
                        //     exit();
                        // }
                }
        }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Shelter</title>

    <!-- // BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- // OWN STYLING -->
    <link rel="stylesheet" href="./../../resources/style/global.css">

    <!-- Icons FA -->
    <script src="https://kit.fontawesome.com/96fa54072b.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php require_once("./../../components/navbar.php") ?>
    <div class="container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                    <label for="email">Email:</label>
                    <input autocomplete="on" type="email" name="email" id="email" class="form-control" value="<?= $email ?? "" ?>">
                    <span style="color:red;"><?= $emailError ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input autocomplete="on" type="password" name="password" id="password" class="form-control" value="<?= $password ?? "" ?>">
                <span style="color:red;"><?= $passwordError ?></span>
            </div>
            <button type="submit" value="Submit" class="btn btn-default">Login</button>
        </form>
    </div>

    <!-- // BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
</body>
</html>
