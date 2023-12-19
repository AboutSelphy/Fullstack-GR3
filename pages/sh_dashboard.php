<?php
//config for global constants
require_once("../config.php");

require_once('../script/globals.php');
require_once('../script/loginGate.php');

include('../script/grammarCheck.php');
include('../script/accept_request.php');

$loc = "./";

if ($role !== 'unset') {
    if ($role !== 'shelter') {
        header("Location: " . ROOT_PATH . "pages/login/login.php");
        exit();
    }
}

$cookieID = $_COOKIE['sessionID'];
try {
    $stmt = $db->prepare("SELECT users.* , zip.*, shelters.*
                            FROM `login`
                            INNER JOIN `users`
                            ON login.userID = users.id
                            INNER JOIN `zip`
                            ON users.fk_zip = zip.id
                            INNER JOIN `shelters`
                            ON users.fk_shelter = shelters.id
                            WHERE login.sessionID = :cookieID");
    $stmt->bindParam(':cookieID', $cookieID);
    $stmt->execute();
    $userData = $stmt->fetch();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); // This will display the error message
}

if (count($userData) > 0) {
} else {
    echo 'is no user';
}



if (isset($_GET['accepted'])) {
    accept($_GET, $db, "adopted");
}

try {
    $stmt = $db->prepare("SELECT * FROM `animals` WHERE `fk_shelter` = $userData[fk_shelter]");
    $stmt->execute();
    $animals = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Get number for the profile animal counter
$animalNumber = count($animals);
$animalCount = grammarCheck($animalNumber, "Animal");

$animalList = "";
if ($animalNumber > 0) {
    foreach ($animals as $animal) {
        // Check the status and store the value with its color into a variable
        if ($animal['status'] == "available") {
            $status = "
                <span class='badge rounded-pill text-bg-success d-inline'>$animal[status]</span>
            ";
            $accept = "";
        } elseif ($animal['status'] == "pending") {
            $status = "
                <span class='badge rounded-pill text-bg-danger d-inline'>$animal[status]</span>
            ";
            $accept = "
                <span class='d-inline'><i class='fa-solid fa-check'></i></span>
            ";
        } else {
            $status = "
                <span class='badge rounded-pill text-bg-secondary d-inline'>$animal[status]</span>
            ";
            $accept = "";
        }
        // Check vaccination and store the value as an icon into a variable
        if ($animal['vaccination'] == "yes") {
            $vaccination = "
                <i class='fa-solid fa-check success'></i>
            ";
        } else {
            $vaccination = "
                <i class='fa-solid fa-xmark danger'></i>
            ";
        }

        $years = grammarCheck($animal['age'], 'year');

        // Create table content of animals belonging to a the corresponding shelter dashboard
        $animalList .= "
            <tr>
                <td class='ps-5'>
                    <div class='d-flex align-items-center'>
                        <img
                        src='../resources/img/animals/$animal[image]'
                        alt='$animal[name]'
                        class='tablePic rounded-circle'
                        />
                        <div class='ms-3'>
                            <p class='fw-bold mb-1'>$animal[name]</p>
                            <p class='text-muted mb-0'>$animal[age] $years</p>
                        </div>
                    </div>
                </td>
                <td class='text-center'>
                    <p class='fw-normal mb-1'>$animal[species]</p>
                </td>
                <td class='text-center'>
                    <p class='fw-normal mb-1'>$animal[gender]</p>
                </td>
                <td class='text-center'>
                    $status
                </td>
                <td class='text-center'>$vaccination</td>
                <td class='actions text-center'>
                    <a class='px-1' href='animals/edit.php?id=$animal[id]'><i class='fa-sharp fa-solid fa-pen-nib'></i></a>
                    <a class='px-1' href='animals/delete.php?id=$animal[id]'><i class='fa-regular fa-trash-can'></i></a>
                    <a class='px-1' href='./sh_dashboard.php?accepted=$animal[id]'>$accept</a>                
                </td>
            </tr>
        ";
    }
}


?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelter Dashbaord</title>

    <!-- // BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- // OWN STYLING -->
    <link rel="stylesheet" href="../resources/style/global.css">
    <link rel="stylesheet" href="../resources/style/dashboards/main.css">

    <!-- Icons FA -->
    <script src="https://kit.fontawesome.com/96fa54072b.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php require_once("../components/navbar.php") ?>
    <div class="wrap_all">
        <main>
            <section class="pb-4">
                <div class="border rounded-5">
                    <section class="w-100 px-4 py-5 gradient-custom-2">
                        <div class="row d-flex justify-content-center py-4">
                            <div class="col col-lg-9 col-xl-8">
                                <div class="card">
                                    <div class="content rounded-top text-white d-flex flex-row">
                                        <div class="header ms-4 mt-5 d-flex flex-column">
                                            <img src="../resources/img/users/<?= $userData['profile'] ?>" alt="Generic placeholder image" class="img-fluid img-thumbnail mt-4 mb-2">
                                            <div class="d-flex">
                                                <a href="./users/edit.php?id=<?= $userData['id'] ?>" type="button" class="btn btn-default" data-mdb-ripple-color="dark">
                                                    Edit profile
                                                </a>
                                                <a href="./shelters/edit.php?id=<?= $userData['fk_shelter'] ?>" type="button" class="btn btn-default ms-4" data-mdb-ripple-color="dark">
                                                    Edit shelter
                                                </a>
                                            </div>
                                        </div>
                                        <div class="profile ms-3">
                                            <h5> <?= $userData['first_name'] . " " .  $userData['last_name'] ?></h5>
                                            <p><b>Role:</b> <?= $userData['status'] ?></p>
                                            <!-- <p><b>Adress:</b> <?= $userData['address'] ?></p> -->
                                        </div>
                                    </div>
                                    <div class="socials p-4 text-black">
                                        <div class="d-flex justify-content-end text-center py-1">
                                            <div>
                                                <p class="mb-1 h5"><?= $animalNumber ?></p>
                                                <p class="small text-muted mb-0"><?= $animalCount ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-4 text-black">
                                        <div class="mb-5">
                                            <p class="lead fw-normal mb-1">About</p>
                                            <div class="infoblock p-4">
                                                <p class="font-italic mb-1"><b>Email: </b><?= $userData['email'] ?></p>
                                                <p class="font-italic mb-1"><b>Address: </b><?= $userData['address'] ?></p>
                                                <p class="font-italic mb-0"><b>Shelter: </b><?= $userData['shelter_name'] ?></p>
                                                <p class="font-italic mb-0"><b>ZIP: </b><?= $userData['zip'] ?></p>
                                            </div>
                                            <a href="./login/logout.php">logout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center py-4">
                            <div class="col col-lg-9 col-xl-8">
                                <table class="table align-middle mb-0 bg-white">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-5">Animal</th>
                                            <th class="text-center">Species</th>
                                            <th class="text-center">Gender</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Vacc.</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?= $animalList ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </section>
        </main>
        <?php require_once('../components/footer.php') ?>
    </div>

</body>

</html>