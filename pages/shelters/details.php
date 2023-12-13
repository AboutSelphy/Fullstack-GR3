<?php
require_once("./../../script/db_connection.php");

// Get ID from URL (linked in Details button)
$id = 1;

$stmt = $db->prepare("SELECT * FROM `shelters` WHERE id = $id");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$row = $result[0];

$details = "
    <div class='headline'>
        <h1>$row[shelter_name]</h1>
    </div>
    <div class='content'>
        <div class='image'>
            <img src='../../../resources/img/shelters/$row[image]' alt='$row[shelter_name]'>
        </div>
        <div class='text'>
            <h3 class='desc'>$row[description]</h3>
            <hr>
            <h4>$row[capacity] animals</h4>
            <p>INSERT LOCATION FOREIGN KEY</p>
        </div>
    </div>
";

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

    <div class="container text-center">
        <?= $details ?>
    </div>

    <!-- // BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>