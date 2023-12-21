<?php
require_once("./../../script/db_connection.php");
//config for global constants
require_once("../../config.php");

include("./../../script/file_upload.php");
include("./../../script/input_validation.php");

include("./../../script/loginGate.php");

if ($role !== 'unset') {
    if ($role === 'user') {
        header("Location: " . ROOT_PATH . "pages/login/login.php");
        exit();
    }
}

// Getting details of shelter from the database
$id = $_GET["id"];
$stmt = $db->prepare("SELECT * FROM shelters WHERE id = $id");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$row = $result[0];

// Preparing validation/error messages
$error = false;
$nameError = $capacityError = $zipError = $descriptionError = "";

// Getting ZIP (foreign key) options
$locations = "";
$stmt = $db->prepare("SELECT * FROM `zip`");
$stmt->execute();
$result_zip = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result_zip as $row_zip) {
    if ($row['id'] == $row_zip['id']) {
        $locations .= "<option value='{$row_zip['id']}' selected>{$row_zip['city']}</option>";
    } else {
        $locations .= "<option value='{$row_zip['id']}'>{$row_zip['city']}</option>";
    }
}

// Clean, validate & store data from input form into variable
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = clean($_POST['name']);
    $capacity = clean($_POST['capacity']);
    $zip = $_POST['zip'];
    $image = fileUpload($_FILES['image'], "shelter");
    $description = clean($_POST['description']);

    if (validate($name, $nameError, 255)[0] == true) {
        $error = true;
        $nameError = validate($name, $nameError, 255)[1];
    }

    if (empty($capacity)) {
        $error = true;
        $capacityError = "Please insert some data here!";
    }

    if ($zip == "0") {
        $error = true;
        $zipError = "Please select one option!";
    }

    if (strlen($description) > 1000) {
        $error = true;
        $descriptionError = "Your input must not have more than 1000 characters!";
    }

    // Prepare and execute SQL insertion
    if (!$error) {
        $sql = "";
        $data = [
            'name' => $name,
            'capacity' => $capacity,
            'description' => $description,
            'zip' => $zip
        ];

        if ($_FILES["image"]["error"] == 0) {
            // Check if the replaced image was default.jpg (elsewise it gets unlinked)
            if ($row["image"] != "default.jpg") {
                unlink("../../resources/img/shelters/$row[image]");
            }

            // Adds the image key if changed
            $data['image'] = $image[0];


            $sql = "UPDATE `shelters` SET `shelter_name`= :name,`capacity`= :capacity,`image`= :image,`description`= :description,`fk_zip`= :zip WHERE id = {$id}";
        } else {
            $sql = "UPDATE `shelters` SET `shelter_name`= :name,`capacity`= :capacity,`description`= :description,`fk_zip`= :zip WHERE id = {$id}";
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($data);

        echo 'db entry created';
        header('Location: ../sh_dashboard.php');
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

    <section class="form d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="card">
                <div class="card-body">
                <form method="post"  enctype="multipart/form-data">
                    <h2> Register as Shelter</h2>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" class="form-control" value="<?= $row["shelter_name"] ?? "" ?>">
                        <span><?= $nameError ?></span>
                    </div>

                    <div class="form-group">
                        <label for="age">Capacity:</label>
                        <input type="number" min="1" name="capacity" id="capacity" class="form-control" value="<?= $row["capacity"] ?? "" ?>">
                        <span><?= $capacityError ?></span>
                    </div>

                    <div class="form-group">
                        <label for="zip">ZIP:</label>
                        <select name="zip" id="zip" class="form-control">
                            <option value="0">Please choose...</option>
                            <?= $locations ?>
                        </select>
                        <span><?= $zipError ?></span>
                    </div>

                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" class="form-control"><?= $row["description"] ?? "" ?></textarea>
                        <span><?= $descriptionError ?></span>
                    </div>

                    <button type="submit" value="Submit" class="btn btn-default">Submit</button>

                    </form>
                </div>
            </div>
        </div>
    </section>
<?php require_once("./../../components/footer.php") ?>
</body>

</html>