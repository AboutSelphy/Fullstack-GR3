<?php
//config for global constants
require_once("../../config.php");
// db connection
require_once("./../../script/db_connection.php");
// get fileupload functionality
include("./../../script/file_upload.php");
// get inputValidtio  functionality
include("./../../script/input_validation.php");
// checks if user is loggedIn and seesion cookie is set
require_once('./../../script/isLoggedIn.php');

//check if client has entry in db table login
$role = isLoggedIn($db)[1];

// if $role is set redirect to loginpage that handles the redirect to right dashboard
if ($role !== 'unset' && $role === 'admin') {
} elseif ($role !== 'unset') {
    header("Location: " . ROOT_PATH . "pages/login/login.php");
    exit();
}

// Getting ZIP (foreign key) options
$locations = "";
$stmt = $db->prepare("SELECT * FROM `zip`");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    $locations .= "<option value='{$row['id']}'>{$row['city']}</option>";
}

// Getting Shelters (foreign key) options
// $shelters = "";
// $stmt = $db->prepare("SELECT * FROM `shelters`");
// $stmt->execute();
// $responseShelters = $stmt->fetchAll(PDO::FETCH_ASSOC);

// foreach ($responseShelters as $row) {
//     $shelters .= "<option value='{$row['id']}'>{$row['shelter_name']}</option>";
// }

// Preparing validation/error messages
$error = false;

$first_nameError = $last_nameError = $addressError = $emailError = $passwordError = $zipError =  "";

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
    // $shelter = $_POST['shelter'] != 0 ? $_POST["shelter"] : 0;

    $image = fileUpload($_FILES["image"], "user");

    //VAL first $first_name
    if (validate($first_name, $first_nameError, 255)[0] == true) {
        $error = true;
        $first_nameError = validate($first_name, $first_nameError, 255)[1];
    }
    //VAL last $last_name
    if (validate($last_name, $last_nameError, 255)[0] == true) {
        $error = true;
        $last_nameError = validate($last_name, $last_nameError, 255)[1];
    }
    //VAL $address
    if (validate($address, $addressError, 255)[0] == true) {
        $error = true;
        $addressError = validate($address, $addressError, 255)[1];
    }
    //VAL $email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter a valid Email adress";
    } else {
        if ($role === 'unset') { // if no role is set
            $stmt = $db->prepare("SELECT email FROM `users` WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $emailResult = $stmt->fetch();

            if (is_array($emailResult) && count($emailResult) != 0) {
                $errorStatus = true;
                $emailError = "Provided Email is already in use";
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

    //VAL shelter
    // if ($shelter == "0") {
    //     $error = true;
    //     $shelterError = "Please select one option!";
    // }

    $data = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'address' => $address,
        'email' => $email,
        'password' => hash('sha256', $password),
        'profile' => $image[0],
        'zip' => $zip,
        // 'shelter' => $shelter,
        // 'status' => $accountType
    ];

    // Prepare and execute SQL insertion
    if ($error === false) {
        try {
            $sql = "INSERT INTO `users`(`first_name`, `last_name`, `address`, `email`, `password`,`profile`,`fk_zip`) VALUES (:first_name, :last_name, :address, :email, :password, :profile, :zip)";
            $stmt = $db->prepare($sql);
            $stmt->execute($data);

            echo 'successfully registered :)';
            header("refresh:2;url=" . ROOT_PATH . "pages/login/login.php");
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

    <div class="container p-4">
        <div class="row justify-content-center mt-1">

            <div class="col-md-8">
                <form method="post" enctype="multipart/form-data">
                    <div class="card-group mb-0">
                        <div class="card p-4">
                            <div class="card-body">
                                <h1 class="text-center">Sign up</h1>
                                <p class="text-muted">Create your account</p>
                                <div class="input-group mb-3">
                                    <label for="first_name" class="d-flex align-items-center border border-2 rounded-start p-2" style><span class="input-group-addon"><i class="fa fa-user"></i></span></label>
                                    <input autocomplete="on" type="text" name="first_name" id="first_name" class="form-control" value="<?= $first_name ?? "" ?>" placeholder="First name">
                                </div>
                                <p style="color:red;"><?= $first_nameError ?></p>

                                <div class="input-group mb-3">
                                    <label for="last_name" class="d-flex align-items-center border border-2 rounded-start p-2" style><span class="input-group-addon"><i class="fa fa-user"></i></span></label>
                                    <input autocomplete="on" type="text" name="last_name" id="last_name" class="form-control" value="<?= $last_name ?? "" ?>" placeholder="Last name">
                                </div>
                                <p style="color:red;"><?= $last_nameError ?></p>

                                <div class="input-group mb-3">
                                    <label for="address" class="d-flex align-items-center border border-2 rounded-start p-2" style><span class="input-group-addon"><i class="fa fa-city"></i></span></label>
                                    <input autocomplete="on" type="text" name="address" id="address" class="form-control" value="<?= $address ?? "" ?>" placeholder="Address">
                                </div>
                                <p style="color:red;"><?= $addressError ?></p>

                                <div class="input-group mb-3">
                                    <label for="email" class="d-flex align-items-center border border-2 rounded-start p-2" style><span class="input-group-addon"><i class="fa fa-envelope"></i></span></label>
                                    <input autocomplete="on" type="email" name="email" id="email" class="form-control" value="<?= $email ?? "" ?>" placeholder="Email">
                                </div>
                                <p style="color:red;"><?= $emailError ?></p>

                                <div class="input-group mb-3">
                                    <label for="password" class="d-flex align-items-center border border-2 rounded-start p-2" style><span class="input-group-addon"><i class="fa fa-lock"></i></span></label>
                                    <input autocomplete="on" type="password" name="password" id="password" class="form-control" value="<?= $password ?? "" ?>" placeholder="Password">
                                </div>
                                <p style="color:red;"><?= $passwordError ?></p>


                                <div class="input-group mb-3">
                                    <label for="zip" class="d-flex align-items-center border border-2 rounded-start p-2" style><span class="input-group-addon"><i class="fa fa-location"></i></span></label>
                                    <select name="zip" id="zip" class="form-control">
                                        <option value="0">Please choose...</option>
                                        <?= $locations ?>
                                    </select>
                                </div>
                                <p style="color:red;"><?= $zipError ?></p>


                                <div class="input-group mb-3">
                                    <label for="image" class="d-flex align-items-center border border-2 rounded-start p-2" style><span class="input-group-addon"><i class="fa fa-image"></i></span></label>
                                    <input type="file" name="image" id="image" class="form-control">
                                </div>



                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" value="Submit" class="btn btn-default">Create account</button>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card text-white py-5 d-md-down-none" style="width:44%; background: #98A633">
                            <div class="card-body text-center">
                                <div>
                                    <h2>Sign in</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    <a type="button" href="<?= ROOT_PATH ?>pages/login/login.php" class="btn btn-cta active mt-3">Login Now!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>



    </div>
    <div style="position: fixed; width: 100%; bottom: 0">
        <?php require_once("../../components/footer.php") ?>

    </div>
</body>

</html>