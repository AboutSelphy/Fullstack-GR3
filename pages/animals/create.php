<?php
require_once("./../../script/db_connection.php");

// File Upload
include("./../../script/file_upload.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $name = $_POST['name'];          
    $age = $_POST['age'];            
    $species = $_POST['species'];    
    $gender = $_POST['gender'];    
    $age = $_POST['age'];    
    $fk_shelter = $_POST['fk_shelter'];    
    $vaccination = $_POST['vaccination'];    

    // File Upload
    $image = fileUpload($_FILES["image"], "animal");
    if ($image === false) {
        // Handle file upload error
        echo "Error uploading the image.";
        exit;
    }  
    $status = $_POST['status'];    
    $description = $_POST['description'];    

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

    <!-- MOSTAFA WILL ENTER OUR LANDING PAGE HERE -->
<div class="container">
    <form method="post"  enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" name="age" id="age" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="species">Species:</label>
            <input type="text" name="species" id="species" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="gender">Gender:</label>
            <input type="text" name="gender" id="gender" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="fk_shelter">Shelter ID:</label>
            <input type="number" name="fk_shelter" id="fk_shelter" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="vaccination">Vaccination:</label>
            <select name="vaccination" id="vaccination" class="form-control" required>
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
                        <option value="Adopted">Adopted</option>
                        <option value="Available">Available</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
        </div>

    <button type="submit" value="Submit" class="btn btn-default">Submit</button>

    </form>
</div>
    <!-- // BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>