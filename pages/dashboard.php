<?php
//config for global constants
require_once("../config.php");

require_once('../script/db_connection.php');

require_once('../script/globals.php');
require_once('../script/loginGate.php');

include('../script/grammarCheck.php');

include('../components/listAdminUsers.php');
include('../components/listAdminShelters.php');
include('../script/accept_request.php');



$loc = "./";

    if($role !== 'unset'){
        if ($role !== 'admin') {
            header("Location: " . ROOT_PATH . "pages/login/login.php");
            exit();
        }
    }

    if(isset($_GET['accepted'])){
        $userID = $_GET['accepted'];
        // Validate and sanitize $userID here before using it in the query
    
        // accept($parameter, $db, 'accepted','shelters');
        accept($_GET, $db, 'shelter','users');
    
        try {
            $stmt = $db->prepare("DELETE FROM `login` WHERE userID = :userID");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT); // Assuming userID is an integer
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
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
//fetch Users
try {
    $stmt = $db->prepare("SELECT users.id as userID, users.status as userStatus, users.*, shelters.*, zip.* 
                            FROM `users`
                            INNER JOIN `zip`
                            ON users.fk_zip = zip.id
                            INNER JOIN `shelters`
                            ON users.fk_shelter = shelters.id
                            "
                        );
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
//fetch Animals
try {
    $stmt = $db->prepare("SELECT * FROM `animals` WHERE `fk_shelter` = $userData[fk_shelter]");
    $stmt->execute();
    $animals = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
//fetch Shelters
try {
    $stmt = $db->prepare("SELECT shelters.id as sheltersID, shelters.*, zip.* 
                            FROM `shelters`
                            INNER JOIN `zip`
                            ON shelters.fk_zip = zip.id"
                        );
    $stmt->execute();
    $shelters = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Get number for the profile animal counter
$animalNumber = count($animals);
$animalCount = grammarCheck($animalNumber, "Animal");

$animalList = "";
$usersList = "";
$sheltersList = "";

$usersNumber = count($users);
$sheltersNumber = count($shelters);

//get usersList
$usersList = listAdminUsers($users,$usersNumber);
//get sheltersList
$sheltersList = listAdminShelters($shelters, $sheltersNumber);



// echo '<pre>';
// var_dump($users);
// echo '</pre>';


if ($animalNumber > 0) {
    foreach ($animals as $animal) {
        // Check the status and store the value with its color into a variable
        if ($animal['status'] == "available") {
            $status = "
                <span class='badge rounded-pill text-bg-success d-inline'>$animal[status]</span>
            ";
        } elseif ($animal['status'] == "pending") {
            $status = "
                <span class='badge rounded-pill text-bg-danger d-inline'>$animal[status]</span>
            ";
        } else {
            $status = "
                <span class='badge rounded-pill text-bg-secondary d-inline'>$animal[status]</span>
            ";
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
                        style='object-fit: cover'
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
                <td class='text-center'>$vaccination</td>
                
                </tr>
                ";
            }
        }
        // <td class='text-center'>
        //     $status
        // </td>
        // <td class='actions text-center'>
        //     <a class='px-1' href='animals/edit.php?id=$animal[id]'><i class='fa-sharp fa-solid fa-pen-nib'></i></a>
        //     <a class='px-1' href='animals/delete.php?id=$animal[id]'><i class='fa-regular fa-trash-can'></i></a>
        // </td>


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
    <!-- Font Awesome -->
    <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    rel="stylesheet"
    />
    <!-- Google Fonts -->
    <link
    href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
    rel="stylesheet"
    />
    <!-- MDB -->
    <link
    href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css"
    rel="stylesheet"
    />
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
                                                <!-- <a href="./shelters/edit.php?id=<?= $userData['fk_shelter'] ?>" type="button" class="btn btn-default ms-4" data-mdb-ripple-color="dark">
                                                    Edit shelter
                                                </a> -->
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
                        <!-- section tables -->
                        <!-- Users -->
                        <div id="requests" class="row d-flex justify-content-center py-4 usersContainerWrap">
                        <h3 class="col col-lg-9 col-xl-8 adminCreateShelterBox">Users <a class="btn btn-cta adminCreateShelter" href="../pages/users/register.php">Register User</a></h3>
                            <div class="col col-lg-9 col-xl-8">
                                <table class="table align-middle mb-0 bg-white">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-5">User</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Address</th>
                                            <th class="text-center">ZIP</th>
                                            <th class="text-center">Country</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Actions</th>
                                            <th class="text-center">Request?</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- hit them with the list elvis -->
                                        <?= $usersList ?? '' ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Animals -->
                        <div class="row d-flex justify-content-center py-4 animalsContainerWrap">
                            <h3 class="col col-lg-9 col-xl-8">Animals</h3>
                            <div class="col col-lg-9 col-xl-8">
                                <table class="table align-middle mb-0 bg-white">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-5">Animal</th>
                                            <th class="text-center">Species</th>
                                            <th class="text-center">Gender</th>
                                            <th class="text-center">Vacc.</th>
                                            <!-- <th class="text-center">Status</th> -->
                                            <!-- <th class="text-center">Actions</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?= $animalList ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Shelters -->
                        <div class="row d-flex justify-content-center py-4 animalsContainerWrap">
                            <h3 class="col col-lg-9 col-xl-8 adminCreateShelterBox">Shelters <a class="btn btn-cta adminCreateShelter" href="../pages/shelters/create.php">Create Shelter</a></h3>
                            <div class="col col-lg-9 col-xl-8">
                                <table class="table align-middle mb-0 bg-white">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-5">Shelter</th>
                                            <th class="text-center">Capacity</th>
                                            <th class="text-center">Description</th>
                                            <th class="text-center">Zip</th>
                                            <th class="text-center">Country</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?= $sheltersList ?? '' ?>
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
<!-- MDB -->
<script
  type="text/javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js"
></script>
</body>

</html>