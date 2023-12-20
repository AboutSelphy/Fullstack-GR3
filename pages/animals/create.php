<?php
require_once("./../../script/db_connection.php");
//config for global constants
require_once("../../config.php");
include("./../../script/loginGate.php");


// File Upload
include("./../../script/file_upload.php");

include("./../../script/input_validation.php");
require_once("../../script/getUserIDWithCookieID.php");



if($role !== 'unset'){
    if ($role !== 'shelter') {
        header("Location: " . ROOT_PATH . "pages/login/login.php");
        exit();
    }
}

$userID = getUserIDWithCookieID($db);

// Getting Shelter (foreign key) options
$shelter = "";
$stmt = $db->prepare("SELECT shelters.id as sheltersID, shelters.*, users.id as userID FROM `users` JOIN `shelters` ON users.fk_shelter = shelters.id WHERE users.id = $userID");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    $shelter .= "<option value='{$row['sheltersID']}' selected>{$row['shelter_name']}</option>";
}

// Preparing validation/error messages
$error = false;

$name = "";
$age = "";
$species = "";
$gender = "";
$fk_shelter = "";
$vaccination = "";
$status = "";
$description = "";

$nameError = "";
$ageError = "";
$speciesError = "";
$genderError = "";
$shelterError = "";
$vaccinationError = "";
$statusError = "";
$descriptionError = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $name = clean($_POST['name']);          
    $age = $_POST['age'] != 0 ? $_POST["age"] : 1;            
    $species = clean($_POST['species']);    
    $gender = $_POST['gender'];    
    $age = clean($_POST['age']);    
    $fk_shelter = clean($_POST['fk_shelter']);    
    $vaccination = clean($_POST['vaccination']);    

    // File Upload
    $image = fileUpload($_FILES["image"], "animal");
    if ($image === false) {
        // Handle file upload error
        echo "Error uploading the image.";
        exit;
    }  

    $status = clean($_POST['status']);    
    $description = clean($_POST['description']);    

    

    if (validate($name, $nameError, 50)[0] == true) {
        $error = true;
        $nameError = validate($name, $nameError, 50)[1];
    }

    if (empty($age)) {
        $error = true;
        $ageError = "Please insert some data here!";
    }

    if (validate($species, $speciesError, 50)[0] == true) {
        $error = true;
        $speciesError = validate($species, $speciesError, 50)[1];
    }
    if (empty($gender)) {
        $error = true;
        $genderError = "Please select one option!";
    }

    if (empty($shelter)) {
        $error = true;
        $fk_shalterError = "Please select one option!";
    }    

    if (empty($vaccination)) {
        $error = true;
        $vaccinationError = "Please select one option!";
    }

    if (empty($status)) {
        $error = true;
        $statusError = "Please select one option!";
    }

    if (strlen($description) > 500) {
        $error = true;
        $descriptionError = "Your input must not have more than 500 characters!";
    }
    echo 'OLTA: ' . $fk_shelter;
    // Prepare the SQL statement with named parameters
    $sql = "INSERT INTO animals (`name`, `age`, `species`, `gender`, `fk_shelter`, `vaccination`, `image`, `status`, `description`) VALUES (:name, :age, :species, :gender, :fk_shelter, :vaccination, :image, :status, :description)";
    $stmt = $db->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':age', $age, PDO::PARAM_INT);
    $stmt->bindParam(':species', $species, PDO::PARAM_STR);
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
    $stmt->bindParam(':fk_shelter', $fk_shelter, PDO::PARAM_INT);
    $stmt->bindParam(':vaccination', $vaccination, PDO::PARAM_STR);
    $stmt->bindParam(':image', $image[0], PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Record inserted successfully.";
    } else {
        // Handle the error
        echo "Error inserting record: " . $stmt->errorInfo()[2];
    }
}

// Close the database connection (if needed)
$db = null;

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

    <!-- MOSTAFA WILL ENTER OUR LANDING PAGE HERE -->
<div class="container">
    <form method="post"  enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" min="1" name="age" id="age" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="species">Species:</label>
            <input type="text" name="species" id="species" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="gender">Gender:</label>
            <select name="gender" id="gender" class="form-control" required>
                <option value="" disabled selected hidden>Please Choose...</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <div class="form-group">
            <label for="fk_shelter" >Shelter:</label>
            <select name="fk_shelter" id="fk_shelter" class="form-control" required>
            <option value="" disabled selected hidden>Please Choose...</option>
            <?= $shelter ?>
            </select>
        </div>

        <div class="form-group">
            <label for="vaccination">Vaccination:</label>
            <select name="vaccination" id="vaccination" class="form-control" required>
                <option value="" disabled selected hidden>Please Choose...</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="" disabled selected hidden>Please Choose...</option>
                <option value="Adopted">Adopted</option>
                <option value="Available">Available</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
        </div>

    <button type="submit" value="Submit" class="btn btn-cta">Submit</button>

    </form>
</div>
    <!-- // BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>