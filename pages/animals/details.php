<?php
require_once("./../../script/db_connection.php");

//USER ID FOR ANIMAL ADOPTION : CUT & PASTE
$cookieID = $_COOKIE['sessionID'];
try {
    $stmt = $db->prepare("SELECT users.* FROM `login` INNER JOIN `users` ON login.userID = users.id WHERE login.sessionID = :cookieID");
    $stmt->bindParam(':cookieID', $cookieID);
    $stmt->execute();
    $userData = $stmt->fetch();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); // This will display the error message
}
// END OF GETTING USER ID

// Get ID from URL (linked in Details button) and select according data
$id = $_GET["id"];
$stmt = $db->prepare("SELECT * FROM `animals` WHERE id = $id");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$row = $result[0];

// Getting ZIP (foreign key) options
$stmt = $db->prepare("SELECT * FROM `shelters` WHERE id = $row[fk_shelter]");
$stmt->execute();
$result_shelters = $stmt->fetchAll(PDO::FETCH_ASSOC);
$row_shelters = $result_shelters[0];

$details = "
    <div>
        <h1>$row[name]</h1>
    </div>
    <div>
        <div>
            <img src='../../../resources/img/animals/$row[image]' alt='$row[name]'>
        </div>
        <div>
            <h3>$row[description]</h3>
            <hr>
            <h4>$row[age] years</h4>
            <h4>$row[species]</h4>
            <h4>$row[gender] </h4>
            <h4>$row[vaccination] </h4>
            <h4>$row[status] </h4>
            <a href='./../shelters//details.php?id=$row_shelters[id]'><p>$row_shelters[shelter_name]</p></a>
        </div>
    </div>
";

// DESIGN OF THE ADOPTION CRUD --> NEEDS TO BE TRANSFERED TO THE ANIMALS DETAIL PAGE
$adoption = "
    <form action='' method='post'>
        <input class='btn' type='submit' value='Adopt' name='adopt'>
    </form>
";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = date("d.m.Y");

    $data = [
        'date' => $date,
        'fk_user' => $userData['id'],
        'fk_animal' => $id
    ];

    try {
        // Inserting new entry into adoptions table
        $stmt = $db->prepare("INSERT INTO `adoptions`(`date`, `fk_user`, `fk_animal`) VALUES (:date,:fk_user,:fk_animal)");
        $stmt->execute($data);
        // Changing the status of animal to "pending" until it gets accepted by shelter
        $stmt = $db->prepare("UPDATE `animals` SET `status`='pending' WHERE id = $id");
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
// END OF ADOPTION FUNCTIONALITY

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
        <?= $adoption ?>
    </div>

    <!-- // BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>