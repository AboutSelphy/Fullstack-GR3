<?php
//config for global constants
require_once("../config.php");

require_once('../script/globals.php');
require_once('../script/loginGate.php');
require_once('../script/getUserIDWithCookieID.php');

$loc = "./";

if ($role !== 'unset') {
  if ($role === 'shelter') {
    header("Location: {$loc}sh_dashboard.php");
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
  // echo "Error: " . $e->getMessage(); // This will display the error message
}
// echo '<pre>';
// var_dump($userData);
// echo '</pre>';
// die();
// and somewhere later:
if (count($userData) > 0) {
} else {
  echo 'is no user';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashbaord</title>

  <!-- // BOOTSTRAP -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

  <!-- // OWN STYLING -->
  <link rel="stylesheet" href="../resources/style/global.css">

  <!-- Icons FA -->
  <script src="https://kit.fontawesome.com/96fa54072b.js" crossorigin="anonymous"></script>
</head>

<body>
  <?php require_once("../components/navbar.php") ?>
  <div class="wrap_all">
    <main>
      <section class="pb-4">
        <div class="border rounded-5">

          <section class="w-100 px-4 py-5 gradient-custom-2" style="border-radius: .5rem .5rem 0 0;">
            <style>
              .gradient-custom-2 {
                /* fallback for old browsers */
                background: #fbc2eb;

                /* Chrome 10-25, Safari 5.1-6 */
                background: -webkit-linear-gradient(to right, rgba(251, 194, 235, 1), rgba(166, 193, 238, 1));

                /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
                background: linear-gradient(to bottom, #e1e1e1, #A6A272, #6A7324);
              }
            </style>
            <div class="row d-flex justify-content-center">
              <div class="col col-lg-9 col-xl-8">
                <div class="card">
                  <div class="rounded-top text-white d-flex flex-row" style="background-color: #000; height:200px;">
                    <div class="ms-4 mt-5 d-flex flex-column" style="width: 300px;">
                      <img src="../resources/img/users/<?= $userData['profile'] ?>" alt="Generic placeholder image" class="img-fluid img-thumbnail mt-4 mb-2" style="width: 150px; min-height: 150px; object-fit: cover; z-index: 1">
                      <a style="display:block; width: 100%; z-index: 1; font-size: 12px; letter-spacing: 1px;" href="./users/edit.php?id=<?= $userData['id'] ?>" type="button" class="btn btn-outline-dark" data-mdb-ripple-color="dark" style="z-index: 1;">
                        Edit profile
                      </a>
                    </div>
                    <div class="ms-3" style="margin-top: 130px;">
                      <h5> <?= $userData['first_name'] . " " .  $userData['last_name'] ?></h5>
                      <p><b>Role:</b> <?= $userData['status'] ?></p>
                      <!-- <p><b>Adress:</b> <?= $userData['address'] ?></p> -->
                    </div>
                  </div>
                  <div class="p-4 text-black" style="background-color: #f8f9fa;">
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
                      <div class="p-4" style="background-color: #f8f9fa;">
                        <p class="font-italic mb-1"><b>Email: </b><?= $userData['email'] ?></p>
                        <p class="font-italic mb-1"><b>Address: </b><?= $userData['address'] ?></p>
                        <p class="font-italic mb-0"><b>Shelter: </b><?= $userData['shelter_name'] ?></p>
                        <p class="font-italic mb-0"><b>ZIP: </b><?= $userData['zip'] ?></p>
                      </div>
                      <a style="color:red;" href="./users/delete.php?id=<?= getUserIDWithCookieID($db) ?>">Delete Account</a>
                      <a href="./login/logout.php">logout</a>
                    </div>
                  </div>
                </div>
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