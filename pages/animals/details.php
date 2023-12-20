<?php
require_once("./../../script/db_connection.php");
require_once("../../config.php");

 //USER ID FOR ANIMAL ADOPTION
$cookieID = $_COOKIE['sessionID'];
try {
    $stmt = $db->prepare("SELECT users.* FROM `login` INNER JOIN `users` ON login.userID = users.id WHERE login.sessionID = :cookieID");
    $stmt->bindParam(':cookieID', $cookieID);
    $stmt->execute();
    $userData = $stmt->fetch();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); // This will display the error message
}

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




// ======================================================
// SWEET ALERT BEGINN
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adopt'])) {
    // Perform any processing or database operations here
    
    // Show SweetAlert on form submission
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'The Internet?',
                text: 'That thing is still around?',
                icon: 'question'
            });
        });
    </script>";
}
// ======================================================

// Adoption Crud
if ($row['status'] == "available") {
    $adoption = "
    <form action='' method='post'>
        <input class='btn btn-cta' type='submit' value='Adopt' name='adopt'>
    </form>";
} else {
    $adoption = "
        <h4>$row[name] found already somebody looking out for him :)</h4>
    ";
}


$details = "
    <section class='detailpage d-flex align-items-center justify-content-center '>
        <div class='container'>
            <div class='card'>
                <div class='card-body img-card'>
                    <div class='row justify-content-center align-items-center'>
                        <div class='col-12 col-lg-5 col-md-6 col-sm-12 '>
                            <img src='../../resources/img/animals/$row[image]' alt='$row[name]'>
                        </div>
                        <div class='col-12 col-lg-7 col-md-6 col-sm-12'>
                                    <h3 class='card-title'>$row[name]</h3>
                                    <h4 class='card-subtitle mb-2'>Description: $row[description]</h4>
                                    <hr>
                                    <h4 class='card-subtitle mb-2'>Age: $row[age] years</h4>
                                    <h4 class='card-subtitle mb-2'>Species: $row[species]</h4>
                                    <h4 class='card-subtitle mb-2'>Gender: $row[gender]</h4>
                                    <h4 class='card-subtitle mb-2'>Vaccinated: $row[vaccination]</h4>
                            <div class=' mt-3'>
                                    <hr>                            
                                    <h3 class='card-title'>Shelter: <a href='./../shelters/details.php?id=$row_shelters[id]'> $row_shelters[shelter_name] </a></h3>
                                    <div class='adoption-section'>$adoption</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
";





if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = date("Y-m-d");

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
        echo "Thanks for your request to adopt a furry friend!";
        header("refresh: 3; url=animals.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
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
    
        <div class="text-center">
         <?= $details ?>
        </div>    
        <?php require_once("./../../components/footer.php") ?>
    <!-- // BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>