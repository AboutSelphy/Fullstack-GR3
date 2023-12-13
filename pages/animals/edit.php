<?php
require_once("./../../script/db_connection.php");
// test
// File Upload
include("./../../script/file_upload.php");

include("./../../script/input_validation.php");
// Getting Shelter (foreign key) options
$shelter = "";
$stmt = $db->prepare("SELECT * FROM `shelters`");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    $shelter .= "<option value='{$row['id']}'>{$row['shelter_name']}</option>";
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


if (isset($_GET['id'])) {
    $animalid = $_GET['id'];

    // Prepare a SELECT query to fetch data for the selected animal
    $selectSql = "SELECT * FROM animals WHERE id = :id";
    $selectStmt = $db->prepare($selectSql);
    $selectStmt->bindParam(':id', $animalid, PDO::PARAM_INT);  // Fix the typo here
    $selectStmt->execute();

    // Fetch the data
    $animalData = $selectStmt->fetch(PDO::FETCH_ASSOC);
    // Populate variables with fetched data
    $name = $animalData['name'];
    $age = $animalData['age'];
    $species = $animalData['species'];
    $gender = $animalData['gender'];
    $fk_shelter = $animalData['fk_shelter'];
    $vaccination = $animalData['vaccination'];
    $image = $animalData['image'];
    $status = $animalData['status'];
    $description = $animalData['description'];
} else {
    // Handle the case when no ID is provided
    echo "No ID provided for fetching data.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $age = $_POST['age'] != 0 ? $_POST["age"] : 1; 
// File Upload
// Check if a new image was uploaded
if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
    // File Upload
    $uploadedImage = fileUpload($_FILES["image"], "animal");
    if ($uploadedImage === false) {
        // Handle file upload error
        echo "Error uploading the image.";
        exit;
    }
    $imageToUpdate = $uploadedImage[0];
} else {
    // No new image uploaded, use the existing image or 'default.jpg'
    $imageToUpdate = $image ?: 'default.jpg';
}


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


// Prepare the SQL statement with named parameters for update
$sql = "UPDATE animals SET 
        `name` = :name,
        `age` = :age,
        `species` = :species,
        `gender` = :gender,
        `fk_shelter` = :fk_shelter,
        `vaccination` = :vaccination,
        `image` = :image,
        `status` = :status,
        `description` = :description
        WHERE `id` = :id";
$stmt = $db->prepare($sql);


// Bind the parameters
$stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
$stmt->bindParam(':age', $_POST['age'], PDO::PARAM_INT);
$stmt->bindParam(':species', $_POST['species'], PDO::PARAM_STR);
$stmt->bindParam(':gender', $_POST['gender'], PDO::PARAM_STR);
$stmt->bindParam(':fk_shelter', $_POST['fk_shelter'], PDO::PARAM_INT);
$stmt->bindParam(':vaccination', $_POST['vaccination'], PDO::PARAM_STR);
$stmt->bindParam(':image', $imageToUpdate, PDO::PARAM_STR);
$stmt->bindParam(':status', $_POST['status'], PDO::PARAM_STR);
$stmt->bindParam(':description', $_POST['description'], PDO::PARAM_STR);
$stmt->bindParam(':id', $animalid, PDO::PARAM_INT);

// Execute the update statement
// Execute the statement
if ($stmt->execute()) {
    echo "Record updated successfully.";
} else {
    // Handle the error
    echo "Error update record: " . $stmt->errorInfo()[2];
}
}
// Close the database connection (if needed)
$conn = null;
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
    <form method="post"  enctype="multipart/form-data">
        
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control"  value="<?php echo $name; ?>">
        </div>

        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" name="age" id="age" min="1" class="form-control" value="<?php echo $age; ?>">
        </div>

        <div class="form-group">
            <label for="species">Species:</label>
            <input type="text" name="species" id="species" class="form-control"  value="<?php echo $species; ?>">
        </div>

        <div class="form-group">
            <label for="gender">Gender:</label>
            <select name="gender" id="gender" class="form-control" required>
                <option value="<?php echo $gender; ?>"><?php echo $gender; ?></option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <div class="form-group">
            <label for="fk_shelter" >Shelter:</label>
            <select name="fk_shelter" id="fk_shelter" class="form-control" required>
            <option value="<?php echo $fk_shelter; ?>" ><?php echo $fk_shelter; ?></option>
            <?= $shelter ?>
            </select>
        </div>

        <div class="form-group">
            <label for="vaccination">Vaccination:</label>
            <select name="vaccination" id="vaccination" class="form-control" required>
                <option value="<?php echo $vaccination; ?>"><?php echo $vaccination; ?></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" name="image" id="image" class="form-control" value="<?php echo $image; ?>">
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control">
                <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                <option value="Adopted">Adopted</option>
                <option value="Available">Available</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" class="form-control" value="<?php echo $description; ?>"><?php echo $description; ?></textarea>
        </div>

    <button type="submit" value="Submit" class="btn btn-cta">Update</button>

    </form>
</div>
    <!-- // BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>