<?php
   require_once('../script/globals.php');
   require_once('../script/loginGate.php');
   
   if($role !== 'user'){
       header("Location: ".BASE_DIR."pages/login/");
       exit();
   }

    $cookieID = $_COOKIE['sessionID'];
    try {
        $stmt = $db->prepare("SELECT users.* FROM `login` INNER JOIN `users` ON login.userID = users.id WHERE login.sessionID = :cookieID");
        $stmt->bindParam(':cookieID', $cookieID);
        $stmt->execute();
        $userData = $stmt->fetch();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // This will display the error message
    }
    // echo '<pre>';
    // var_dump($userData);
    // echo '</pre>';
    // die();
    // and somewhere later:
    if(count($userData) > 0 ){
        
    }else{
        echo 'is no user';
    }

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME -  Profile | Mani Shelter</title>
    <link rel="stylesheet" href="../resources/style/dashboards/main.css">
    <!-- <link rel="icon" type="image/png" href="../../resources/icons/Logo.svg" sizes="32x32"> -->
</head>

<!-- d
first_name
last_name
email
password
status
address
fk_zip
fk_shelter
profile -->

<body class="page-about">
    <div class="wrap_all">
        <!-- <?php include_once('../components/header.php')?> -->
        <main>
            <div class="section hero subpage" style="background-image: url(../resources/images/<?= $userData['profile'] ?>); background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="container content">
                    <div class="sectionContainer">
                        <div class="heroContent">
                        <div style=" padding: 15px; border-radius: 25px; display: flex; flex-direction: row; align-items: center; background:#FFF2D8 !important;">
                                <h1 style="line-height: 100px;">WELCOME - <?= $userData['first_name'] . " " . $userData['last_name']  ?></h1>
                                <img style=" margin-left: 15px; height: 100px; width: 100px; border-radius: 50%; object-fit: cover;" src="../resources/images/<?= $userData['profile'] ?>" alt="<?= $userData['first_name'] ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section subpagecontent">
                <div class="container content">
                    <div class="textContainer">
                        <h2>Your Profile </h2>
                        <p>See you current Data here:</p>
                        <div class='media_meta'>
                            <div class='media_meta_sub'>
                                <span><b>First Name:</b> <?= $userData['first_name'] ?></span>
                                <span><b>Last Name:</b> <?= $userData['last_name'] ?></span>
                                <span><b>Email:</b>  <?= $userData['email'] ?></span>
                                <span><b>Adress:</b> <?= $userData['address'] ?></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <a class="editButton" href="./editUser.php?id=<?= $userData['id'] ?>">EDIT PROFILE</a>
                </div>
            </div>

        </main>
        <!-- <?php require_once('../components/footer.php')?> -->
    </div>
</body>

</html>