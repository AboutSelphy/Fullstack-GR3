<?php
//config for global constants
require_once("../config.php");

require_once('../script/globals.php');
require_once('../script/loginGate.php');


$loc = "./";

    if($role !== 'unset'){
        if ($role !== 'admin') {
            header("Location: " . ROOT_PATH . "pages/login/login.php");
            exit();
        }
    }

?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashbaord</title>

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
                                            <a href="./users/edit.php?id=<?= $userData['id'] ?>" type="button" class="btn btn-outline-dark" data-mdb-ripple-color="dark">
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
                                                <p class="mb-1 h5">253</p>
                                                <p class="small text-muted mb-0">Photos</p>
                                            </div>
                                            <div class="px-3">
                                                <p class="mb-1 h5">1026</p>
                                                <p class="small text-muted mb-0">Followers</p>
                                            </div>
                                            <div>
                                                <p class="mb-1 h5">478</p>
                                                <p class="small text-muted mb-0">Following</p>
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
                                            <th>Animal</th>
                                            <th>Info</th>
                                            <th>Status</th>
                                            <th>Vaccination</th>
                                            <th>Actions</th>
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