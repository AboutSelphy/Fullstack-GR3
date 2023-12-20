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
    <div class='col col-sm-12 col-md-4 col-lg-4 col-xl-3 col-xxl-3'>
        <div class='card p-1 h-100'>
            <img class='card-img-top img-fluid mt-2 px-2 h-100' src='./../../resources/img/animals/$row[image]' alt='$row[name]'>
            <div class='card-body '>
                <h3 class='card-title'>$row[name]</h3>
                <p class='card-text'>Age: $row[age] years</p>
                <p class='card-text'>Species: $row[species]</p>
                <div class='mt-2'>
                    <a href='./details.php?id=$row[id]' class='btn btn-default'>Details</a>
                </div>
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
    <section class="overviewgrid">
        <div class="container text-center">
            <div class="row g-2  my-4 justify-content-start">
                <?= $animals ?>
            </div>
        </div>
    </section>
    <?php require_once("./../../components/footer.php") ?>
    <!-- // BOOTSTRAP -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script> -->
</body>

</html>