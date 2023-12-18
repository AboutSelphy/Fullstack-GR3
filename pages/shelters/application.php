<?php
require_once("./../../script/db_connection.php");
//config for global constants
require_once("../../config.php");
include("./../../script/loginGate.php");

if($role !== 'unset'){
    if ($role === 'shelter') {
        header("Location: " . ROOT_PATH . "pages/sh_dashboard.php");
        exit();
    }
    if ($role === 'admin') {
        header("Location: " . ROOT_PATH . "pages/dashboard.php");
        exit();
    }
}

include("./../../script/input_validation.php");

// Preparing validation/error messages
$error = false;
$name = $animals = $zip = $philosophy = "";
$nameError = $animalsError = $zipError = $philosophyError = "";

// Clean, validate & store data from input form into variable
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = clean($_POST['name']);
    $animals = clean($_POST['animals']);
    $zip = $_POST['zip'] != 0 ? $_POST['zip'] : 1;
    $image = fileUpload($_FILES['image'], "shelter");
    $philosophy = clean($_POST['philosophy']);

    if (validate($name, $nameError, 255)[0] == true) {
        $error = true;
        $nameError = validate($name, $nameError, 255)[1];
    }

    if (empty($animals)) {
        $error = true;
        $animalsError = "Please insert some data here!";
    }

    if ($zip == "0") {
        $error = true;
        $zipError = "Please select one option!";
    }

    if (strlen($philosophy) > 1000) {
        $error = true;
        $philosophyError = "Your input must not have more than 1000 characters!";
    }

    $data = [
        'name' => $name,
        'animals' => $animals,
        'image' => $image[0],
        'philosophy' => $philosophy,
        'zip' => $zip
    ];

    // Prepare and execute SQL insertion
    if (!$error) {
        $sql = "INSERT INTO `shelters`(`shelter_name`, `animals`, `image`, `philosophy`, `fk_zip`) VALUES (:name, :animals, :image, :philosophy, :zip)";
        $stmt = $db->prepare($sql);
        $stmt->execute($data);

        echo 'db entry created';
        header('Location: ./shelters.php');
    } else {
        echo 'oh no, a problem';
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

            </div>

            <div class="form-group">
                <label for="age">Animals:</label>
                <input type="number" min="1" name="animals" id="animals" class="form-control" value="<?= $animals ?? "" ?>">
                <span><?= $animalsError ?></span>
            </div>

            <div class="form-group">
                <label for="zip" class="form-label">ZIP:</label>
                <input type="number" min="1" class="form-control" id="zip">
            </div>

            <div class="form-group">
                <label for="city" class="form-label">City:</label>
                <input type="text" class="form-control" id="city">
            </div>

            <div class="form-group">
                <label for="country" class="form-label">Country:</label>
                <input type="text" class="form-control" id="country">
            </div>

            <div class="form-group">
                <label class="form-check-label" for="profit">Non-Profit:</label>
                <input class="form-check-input" type="checkbox" value="0" id="profit" name="profit">
            </div>

            <div class="form-group">
                <label for="philosophy">Philosophy:</label>
                <textarea name="philosophy" id="philosophy" class="form-control"><?= $philosophy ?? "" ?></textarea>
                <span><?= $philosophyError ?></span>
            </div>

            <button type="submit" value="Submit" class="btn btn-default">Submit</button>

        </form>
    </div>
    <!-- // BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>