<?php
//config for global constants
require_once("../../config.php");

require_once("./../../script/db_connection.php");
include("./../../script/loginGate.php");
include("./../../script/file_upload.php");
include("./../../script/input_validation.php");


// edit access (USER / ADMIN)

$loc = "../";

if($role !== 'unset'){
    if ($role === 'shelter') {
        header("Location: " . BASE_DIR . "pages/sh_dashboard.php");
        exit();
    }
}


// Getting current User

    $first_name = "";
    $last_name = "";
    $email = "";
    $address = "";
    $password = "";
    $accountType = "";
    $image = "";

    $zip = 0;
    $shelter = 0;



    $cookieID = $_COOKIE['sessionID'];

    try {
        $stmt = $db->prepare("SELECT users.* FROM `login` INNER JOIN `users` ON login.userID = users.id WHERE login.sessionID = :cookieID");
        $stmt->bindParam(':cookieID', $cookieID);
        $stmt->execute();
        $userData = $stmt->fetch();

        $first_name = $userData['first_name'];
        $last_name = $userData['last_name'];
        $email = $userData['email'];
        $address = $userData['address'];
        // $accountType = $userData['status'];
        $image = $userData['profile'];
    
        $zip = $userData['fk_zip'] != 0 ? $userData['fk_zip'] : 0;
        $shelter = $userData['fk_shelter'] != 0 ? $userData['fk_shelter'] : 0;






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


// Getting ZIP (foreign key) options
$locations = "";
$stmt = $db->prepare("SELECT * FROM `zip`");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    $locations .= "<option value='{$row['id']}' ".($userData['fk_zip'] == $row['id'] ? 'selected' : '' )." >{$row['city']}</option>";
}

// Getting Shelters (foreign key) options
$shelters = "";
$stmt = $db->prepare("SELECT * FROM `shelters`");
$stmt->execute();
$responseShelters = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($responseShelters as $row) {
    $shelters .= "<option value='{$row['id']}' ".($userData['fk_shelter'] == $row['id'] ? 'selected' : '' )." >{$row['shelter_name']}</option>";
}

// Preparing validation/error messages
$error = false;

$first_nameError = $last_nameError = $addressError = $emailError = $passwordError = $zipError = $shelterError =  "";




// Clean, validate & store data from input form into variable
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // echo '<pre>';
    // var_dump($_POST);
    // echo '</pre>';

    $first_name = clean($_POST['first_name']);
    $last_name = clean($_POST['last_name']);
    $email = clean($_POST['email']);
    $address = clean($_POST['address']);
    $password = clean($_POST['password']);
    // $accountType = clean($_POST['accountType']);

    $zip = $_POST['zip'] != 0 ? $_POST["zip"] : 0;
    $shelter = $_POST['shelter'] != 0 ? $_POST["shelter"] : 0;

    $image = fileUpload($_FILES["image"], "user");

    //VAL first name
    if (validate($first_name, $first_nameError, 255)[0] == true) {
        $error = true;
        $first_nameError = validate($first_name, $first_nameError, 255)[1];
    }
    //VAL last name
    if (validate($last_name, $last_nameError, 255)[0] == true) {
        $error = true;
        $last_nameError = validate($last_name, $last_nameError, 255)[1];
    }
    //VAL adress
    if (validate($address, $addressError, 255)[0] == true) {
        $error = true;
        $addressError = validate($address, $addressError, 255)[1];
    }
    //VAL $email
    if(!filter_var($email,FILTER_VALIDATE_EMAIL))
    {
        $error = true;
        $emailError = "Please enter a valid Email adress";
    }
    else
    {
        if($role === 'unset'){ // if no role is set
            $stmt = $db->prepare("SELECT email FROM `users` WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $emailResult = $stmt->fetch();

                if (is_array($emailResult) && count($emailResult) != 0 ){
                    $errorStatus = true ;
                    $emailError = "Provided Email is already in use" ;
                }
            }
    }
    //VAL password
    if (validate($password, $passwordError, 255)[0] == true) {
        $error = true;
        $passwordError = validate($password, $passwordError, 255)[1];
    }

    //VAL zip
    if ($zip == "0") {
        $error = true;
        $zipError = "Please select one option!";
    }

    //VAL zip
    if ($shelter == "0") {
        $error = true;
        $shelterError = "Please select one option!";
    }

    // if (strlen($description) > 500) {
    //     $error = true;
    //     $descriptionError = "Your input must not have more than 500 characters!";
    // }

    $data = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'address' => $address,
        'email' => $email,
        'password' => hash('sha256',$password),
        'profile' => $image[0],
        'zip' => $zip,
        'shelter' => $shelter,
        'cookieID' => $cookieID,
        // 'status' => $accountType
    ];

    // Prepare and execute SQL insertion
    if($error === false){
        // echo '<pre>';
        // var_dump($data);
        // echo '</pre>';
        if($_FILES["image"]["error"] == 0){
            if( $userData['profile'] != "default.jpg" ){
                unlink("../../resources/img/users/$image" );
            }

            $sql = "UPDATE `users`
                    INNER JOIN `login` ON login.userID = users.id
                    SET first_name = :first_name,
                        last_name = :last_name,
                        address =  :address,
                        email =  :email,
                        password =  :password,
                        profile =  :profile,
                        fk_zip =  :zip,
                        fk_shelter = :shelter
                    WHERE login.sessionID = :cookieID;";
        }else{
            $sql = "UPDATE `users`
                        INNER JOIN `login` ON login.userID = users.id
                        SET first_name = :first_name,
                            last_name = :last_name,
                            address =  :address,
                            email =  :email,
                            password =  :password,
                            profile =  :profile,
                            fk_zip =  :zip,
                            fk_shelter = :shelter
                        WHERE login.sessionID = :cookieID;";
        }
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($data);
            
            header("refresh:0;url=" . BASE_DIR . "pages/login/login.php");
            echo 'db entry updated';
        
        } catch (PDOException $e) {
            // echo "Error: " . $e->getMessage(); 
        }
    }

}

// Close database connection
$db = NULL;

?>

<!DOCTYPE html>
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
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input autocomplete="on" type="text" name="first_name" id="first_name" class="form-control" value="<?= $first_name ?? "" ?>">
                <span style="color:red;"><?= $first_nameError ?></span>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input autocomplete="on" type="text" name="last_name" id="last_name" class="form-control" value="<?= $last_name ?? "" ?>">
                <span style="color:red;"><?= $last_nameError ?></span>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input autocomplete="on" type="text" name="address" id="address" class="form-control" value="<?= $address ?? "" ?>">
                <span style="color:red;"><?= $addressError ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input autocomplete="on" type="email" name="email" id="email" class="form-control" value="<?= $email ?? "" ?>">
                <span style="color:red;"><?= $emailError ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input autocomplete="on" type="password" name="password" id="password" class="form-control" >
                <span style="color:red;"><?= $passwordError ?></span>
            </div>
            <div class="form-group">
                <label for="zip">ZIP:</label>
                <select name="zip" id="zip" class="form-control">
                    <option value="0">Please choose...</option>
                    <?= $locations ?>
                </select>
                <span style="color:red;"><?= $zipError ?></span>
            </div>

            <div class="form-group">
                <label for="shelter">Shelter:</label>
                <select name="shelter" id="shelter" class="form-control">
                    <option value="0">Please choose...</option>
                    <?= $shelters ?>
                </select>
                <span style="color:red;"><?= $shelterError ?></span>
            </div>

            <div class="form-group">
                <label for="image">ProfilePicture:</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>

            <button type="submit" value="Submit" class="btn btn-default">Update Account</button>

        </form>
    </div>
    <!-- // BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>