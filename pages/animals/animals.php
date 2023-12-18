<?php
require_once("./../../script/db_connection.php");
require_once("../../config.php");


// Get all shelters from the corresponding table and display
$stmt = $db->prepare("SELECT * FROM `animals`");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$animals = "";

if (count($result) > 0) {
    foreach ($result as $row) {
        $animals .= "
            <div>
                <div>
                    <div>
                        <h3>$row[name]</h3>
                    </div>
                    <div>
                        <img class='img-fluid' src='../../resources/img/animals/$row[image]' alt='$row[name]'>
                    </div>   
                    <div>
                        <h5>Age: $row[age] years</h5>
                    </div>
                    <div>
                        <h5>Species: $row[species] </h5>
                    </div>
                    <div class='btn btn-default'>
                        <a href='./details.php?id=$row[id]'>Details</a>
                    </div>
                </div>
            </div>    
        ";
    }
} else {
    $animals = "<p>There are no shelters yet :(</p>";
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
            <?= $animals ?>
        </div>
    </div>
    <!-- // BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>