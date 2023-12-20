<?php
//config for global constants
require_once("../config.php");

require_once('../script/globals.php');
require_once('../script/loginGate.php');
require_once('../script/getUserIDWithCookieID.php');

include('../script/grammarCheck.php');

$loc = "./";

if ($role !== 'unset') {
    if ($role === 'shelter') {
        header("Location: " . ROOT_PATH . "pages/sh_dashboard.php");
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
                            -- ON users.fk_shelter = shelters.id
                            WHERE login.sessionID = :cookieID");
    $stmt->bindParam(':cookieID', $cookieID);
    $stmt->execute();
    $userData = $stmt->fetch();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if (count($userData) > 0) {
} else {
    echo 'is no user';
}

try {
    $stmt = $db->prepare("SELECT animals.status as adoptionStatus, adoptions.* , animals.*
                            FROM `users`
                            INNER JOIN `adoptions`
                            ON users.id = adoptions.fk_user
                            INNER JOIN `animals`
                            ON adoptions.fk_animal = animals.id
                            -- INNER JOIN `shelters`
                            -- ON animals.fk_shelter = shelters.id
                            WHERE users.id = $userData[0]");
    $stmt->execute();
    $adoptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Get number for the adopted animals
$adoptionNumber = count($adoptions);
$adoptionCount = grammarCheck($adoptionNumber, "Adoption");

$adoptionList = "";
if ($adoptionNumber > 0) {
    foreach ($adoptions as $adoption) {
        $years = grammarCheck($adoption['age'], 'year');
        // Check the status and store the value with its color into a variable
        if ($adoption['adoptionStatus'] == "pending") {
            $status = "
                <span class='badge rounded-pill text-bg-danger d-inline'>$adoption[adoptionStatus]</span>
                    ";
        } else {
            $status = "
                <span class='badge rounded-pill text-bg-success d-inline'>$adoption[adoptionStatus]</span>
                    ";
        }

        $adoptionList .= "
            <tr>
                <td class='ps-5'>
                    <div class='d-flex align-items-center'>
                        <img
                        src='../resources/img/animals/$adoption[image]'
                        alt='$adoption[name]'
                        class='tablePic rounded-circle'
                        />
                        <div class='ms-3'>
                            <p class='fw-bold mb-1'>$adoption[name]</p>
                            <p class='text-muted mb-0'>$adoption[age] $years</p>
                        </div>
                    </div>
                </td>
                <td class='text-center'>
                    <p class='fw-normal mb-1'>$adoption[species]</p>
                </td>
                <td class='text-center'>
                    <p class='fw-normal mb-1'>$adoption[gender]</p>
                </td>
                <td class='text-center'>
                    $status
                </td>
                <td class='text-center'>
                    <p class='fw-normal mb-1'>$adoption[date]</p>
                </td>
                <td class='text-center'>
                </td>
                
                </tr>
                ";
                // <p class='fw-normal mb-1'>$adoption[shelter_name]</p>
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashbaord</title>

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
                                            <a href="./users/edit.php?id=<?= $userData['id'] ?>" type="button" class="btn btn-default" data-mdb-ripple-color="dark">
                                                Edit profile
                                            </a>
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
                                                <p class="mb-1 h5"><?= $adoptionNumber ?></p>
                                                <p class="small text-muted mb-0"><?= $adoptionCount ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-4 text-black">
                                        <div class="mb-5">
                                            <p class="lead fw-normal mb-1">About</p>
                                            <div class="infoblock p-4">
                                                <p class="font-italic mb-1"><b>Email: </b><?= $userData['email'] ?></p>
                                                <p class="font-italic mb-1"><b>Address: </b><?= $userData['address'] ?></p>
                                                <p class="font-italic mb-0"><b>ZIP: </b><?= $userData['zip'] ?></p>
                                            </div>
                                            <a style="color:red;" href="./users/delete.php?id=<?= getUserIDWithCookieID($db) ?>">Delete Account</a>
                                            <a class="ms-2" href="./login/logout.php">Logout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center py-4">
                        <h3 class="col col-lg-9 col-xl-8 adminCreateShelterBox">Animals</h3>
                            <div class="col col-lg-9 col-xl-8">
                                <table class="table align-middle mb-0 bg-white">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-5">Animal</th>
                                            <th class="text-center">Species</th>
                                            <th class="text-center">Gender</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Adoption</th>
                                            <th class="text-center">Location</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?= $adoptionList ?>
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