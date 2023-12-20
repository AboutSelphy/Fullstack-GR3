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
// $locations = "";
// $stmt = $db->prepare("SELECT * FROM `zip`");
// $stmt->execute();
// $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// foreach ($result as $row) {
//     $locations .= "<option value='{$row['id']}'>{$row['city']}</option>";
// }

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

$first_nameError = $last_nameError = $addressError = $emailError = $passwordError = $zipError = $cityError = $countryError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Clean, validate & store data from input form into variable
    $zip = intval(clean($_POST['zip']));
    $city = clean($_POST['city']);
    $country = clean($_POST['country']);

    if (empty($zip)) {
        $error = true;
        $zipError = "Please insert some data here!";
    }

    if (validate($city, $cityError, 50)[0] == true) {
        $error = true;
        $cityError = validate($city, $cityError, 50)[1];
    }

    if (validate($country, $countryError, 50)[0] == true) {
        $error = true;
        $countryError = validate($country, $countryError, 50)[1];
    }

    $location = [
        'zip' => $zip,
        'city' => $city,
        'country' => $country
    ];

    echo '<pre style="padding: 10px; background-color: black; color: white;">';
    var_dump($location);
    echo '</pre>';

    // Fetch already exisiting addresses from the table `zip`
    try {
        $addressList = $db->prepare("SELECT * FROM `zip` WHERE zip= :zip");
        $addressList->bindParam(':zip', $zip, PDO::PARAM_INT);
        $addressList->execute();
        $addresses = $addressList->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    echo '<pre style="padding: 10px; background-color: black; color: white;">';
    var_dump($addresses);
    echo '</pre>';
    exit();

    // Checks if the submitted address is already existing, else it gets pushed into the table
    if (is_array($addresses) && count($addresses)) {
        echo "Address exists already!";
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO `zip`(`zip`, `city`, `country`) VALUES (:zip, :city, :country)");
            $stmt->execute($location);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }



    // Clean, validate & store data from input form into variable
    $first_name = clean($_POST['first_name']);
    $last_name = clean($_POST['last_name']);
    $email = clean($_POST['email']);
    $address = clean($_POST['address']);
    $password = clean($_POST['password']);
    // $accountType = clean($_POST['accountType']);

    // $zip = $_POST['zip'] != 0 ? $_POST["zip"] : 0;
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
    if (validate($email, $emailError, 255)[0] == true) {
        $error = true;
        $emailError = validate($email, $emailError, 255)[1];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = true;
            $emailError = "Please enter a valid email adress";
        }
    } else {
        if ($role === 'unset') { // if no role is set
            $stmt = $db->prepare("SELECT email FROM `users` WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $emailResult = $stmt->fetch();

            if (is_array($emailResult) && count($emailResult) != 0) {
                $errorStatus = true;
                $emailError = "Provided email is already in use";
            }
        }
    }
    //VAL password
    if (validate($password, $passwordError, 255)[0] == true) {
        $error = true;
        $passwordError = validate($password, $passwordError, 255)[1];
    }

    //VAL zip
    // if ($zip == "0") {
    //     $error = true;
    //     $zipError = "Please select one option!";
    // }

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
        // 'zip' => $zip,
        // 'shelter' => $shelter,
        // 'status' => $accountType
    ];

    // Prepare and execute SQL insertion
    if ($error === false) {
        try {
            $sql = "INSERT INTO `users`(`first_name`, `last_name`, `address`, `email`, `password`,`profile`,`fk_zip`) VALUES (:first_name, :last_name, :address, :email, :password, :profile)";
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

    <div class="container my-5">
        <h2 class="text-center uppercase mb-5">Register yourself</h2>
        <form class="row g-3" method="post" enctype="multipart/form-data">
            <div class="col-md-6 px-2">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input autocomplete="on" type="text" name="first_name" id="first_name" class="form-control" value="<?= $first_name ?? "" ?>">
                    <span class="error"><?= $first_nameError ?></span>
                </div>
            </div>
            <div class="col-md-6 px-2">
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input autocomplete="on" type="text" name="last_name" id="last_name" class="form-control" value="<?= $last_name ?? "" ?>">
                    <span class="error"><?= $last_nameError ?></span>
                </div>
            </div>
            <div class="col-md-6 px-2">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input autocomplete="on" type="email" name="email" id="email" class="form-control" value="<?= $email ?? "" ?>">
                    <span class="error"><?= $emailError ?></span>
                </div>
            </div>
            <div class="col-md-6 px-2">
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input autocomplete="on" type="password" name="password" id="password" class="form-control" value="<?= $password ?? "" ?>">
                    <span class="error"><?= $passwordError ?></span>
                </div>
            </div>
            <div class="col-md-12 px-2">
                <div class="form-group">
                    <label for="image">Profile Picture:</label>
                    <input type="file" name="image" id="image" class="form-control">
                </div>
            </div>
            <div class="col-md-12 px-2">
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input autocomplete="on" type="text" name="address" id="address" class="form-control" value="<?= $address ?? "" ?>">
                    <span class="error"><?= $addressError ?></span>
                </div>
            </div>
            <div class="col-md-4 px-2">
                <div class="form-group">
                    <label for="zip" class="form-label">ZIP:</label>
                    <input type="number" min="1" class="form-control" name="zip" id="zip" value="<?= $zip ?? "" ?>">
                    <span class="error"><?= $zipError ?></span>
                </div>
            </div>
            <div class="col-md-4 px-2">
                <div class="form-group">
                    <label for="city" class="form-label">City:</label>
                    <input type="text" class="form-control" id="city" name="city" value="<?= $city ?? "" ?>">
                    <span class="error"><?= $cityError ?></span>
                </div>
            </div>
            <div class="col-md-4 px-2">
                <div class="form-group">
                    <label for="country" class="form-label">Country:</label>
                    <input type="text" class="form-control" id="country" name="country" value="<?= $country ?? "" ?>">
                    <span class="error"><?= $countryError ?></span>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <button type="submit" value="Submit" class="btn btn-default w-25 mt-5">Create Account</button>
            </div>
        </form>
    </div>

    <?php require_once("./../../components/footer.php") ?>
</body>

</html>