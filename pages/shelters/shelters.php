<?php
require_once("./../../script/db_connection.php");


// Get all shelters from the corresponding table and display
$stmt = $db->prepare("SELECT * FROM `shelters`");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$shelters = "";

if (count($result) > 0) {
    foreach ($result as $row) {
        $shelters .= "
            <div class='p-4'>
                <div class='animal'>
                    <div class='name'>
                        <h3 class='title'>$row[shelter_name]</h3>
                    </div>
                    <div class='image'>
                        <img class='img-fluid' src='../../resources/img/shelters/$row[image]' alt='$row[shelter_name]'>
                    </div>   
                    <div class='information'>
                        <h5>Capacity: $row[capacity]</h5>
                    </div>
                    <div class='btn'>
                        <a href='./details.php/$row[id]'>Details</a>
                    </div>
                </div>
            </div>    
        ";
    }
} else {
    $shelters = "<p>There are no shelters yet :(</p>";
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

    <div class="container text-center">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-4 my-4">
            <?= $shelters ?>
        </div>
    </div>
    <!-- // BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>