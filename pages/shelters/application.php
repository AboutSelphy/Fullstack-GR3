<?php
//config for global constants
require_once("../../config.php");

require_once('../../script/globals.php');
require_once('../../script/loginGate.php');
require_once('../../script/getUserIDWithCookieID.php');

include("./../../script/input_validation.php");

// if ($role !== 'unset') {
//     if ($role === 'shelter') {
//         header("Location: " . ROOT_PATH . "pages/sh_dashboard.php");
//         exit();
//     }
//     if ($role === 'admin') {
//         header("Location: " . ROOT_PATH . "pages/dashboard.php");
//         exit();
//     }
// }

$loc = "./";

if ($role !== 'unset') {
    if ($role === 'shelter') {
        header("Location: " . ROOT_PATH . "pages/sh_dashboard.php");
        exit();
    }
}

$cookieID = $_COOKIE['sessionID'];
try {
    $stmt = $db->prepare("SELECT users.id
                            FROM `login`
                            INNER JOIN `users`
                            ON login.userID = users.id
                            WHERE login.sessionID = :cookieID");
    $stmt->bindParam(':cookieID', $cookieID);
    $stmt->execute();
    $userData = $stmt->fetch();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Preparing validation/error messages
$error = false;
$zip = $city = $country = $zipError = $cityError = $countryError = "";

// Clean, validate & store data from input form into variable
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $zip = clean($_POST['zip']);
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

    $data = [
        'zip' => $zip,
        'city' => $city,
        'country' => $country
    ];

    // Fetch already exisiting addresses from the table `zip`
    try {
        $addressList = $db->prepare("SELECT * FROM `zip` WHERE zip= :zip");
        $addressList->bindParam(':zip', $zip, PDO::PARAM_INT);
        $addressList->execute();
        $addresses = $addressList->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Checks if the submitted address is already existing, else it gets pushed into the table
    if (is_array($addresses) && count($addresses)) {
        echo "Address exists already!";
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO `zip`(`zip`, `city`, `country`) VALUES (:zip, :city, :country)");
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // The boolean shelterRequest gets changed to true
    try {
        $request = $db->prepare("UPDATE `users`
                            SET `shelterRequest`=1 WHERE id = $userData[id]");
        $request->execute();

        echo "<h3>Shelter registration request sent, pls wait for confirmation</h3>";
        header("refresh:2;url=" . ROOT_PATH . "pages/login/login.php");


    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
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
    <title>Shelter Application</title>

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
                <label for="zip" class="form-label">ZIP:</label>
                <input type="number" min="1" class="form-control" name="zip" id="zip" value="<?= $zip ?? "" ?>">
                <span><?= $zipError ?></span>
            </div>

            <div class="form-group">
                <label for="city" class="form-label">City:</label>
                <input type="text" class="form-control" id="city" name="city" value="<?= $city ?? "" ?>">
                <span><?= $cityError ?></span>
            </div>

            <div class="form-group">
                <label for="country" class="form-label">Country:</label>
                <input type="text" class="form-control" id="country" name="country" value="<?= $country ?? "" ?>">
                <span><?= $countryError ?></span>
            </div>

            <!--
            <div class="form-group">
                <label class="form-check-label" for="profit">Non-Profit:</label>
                <input class="form-check-input" type="checkbox" value="0" id="profit" name="profit">
            </div>

            <div class="form-group">
                <label for="philosophy">Philosophy:</label>
                <textarea name="philosophy" id="philosophy" class="form-control"><?= $philosophy ?? "" ?></textarea>
                <span><?= $philosophyError ?></span>
            </div>
            -->

            <button type="submit" value="Submit" class="btn btn-default">Submit</button>

        </form>
    </div>
    <?php require_once("./../../components/footer.php") ?>
</body>

</html>