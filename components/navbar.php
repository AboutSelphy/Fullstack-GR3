<?php

if (count($_COOKIE) > 0 && isset($_COOKIE['sessionID'])) {
  $cookieID = $_COOKIE['sessionID'];

  $headerUserName = '';
  try {
    $db = new PDO('mysql:host=localhost;dbname=animalshelter', 'root', '');
    // echo 'DB - Connected ✅🖥 ';

  } catch (PDOException $e) {
    // attempt to retry the connection after some timeout for example
    // echo $e . 'DB - ERROR ✕ 🖥';

  }

  try {
    $stmt = $db->prepare("SELECT users.* FROM `login` INNER JOIN `users` ON login.userID = users.id WHERE login.sessionID = :cookieID");
    $stmt->bindParam(':cookieID', $cookieID);
    $stmt->execute();
    $navbarData = $stmt->fetch();
    // var_dump($navbarData);
    if (is_array($navbarData) && count($navbarData) > 0) {
      $headerUserName = $navbarData['first_name'];
      $headerUserIMG = $navbarData['profile'];
      $headerUserRole = $navbarData['status'];
      $headerShelterRequest = $navbarData['shelterRequest'];
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); // This will display the error message
  }
}
$destination = 'users';

?>

<nav class="navbar navbar-expand-lg bg-body-tertiary px-5">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= ROOT_PATH ?>index.php">
      <img src="<?= ROOT_PATH ?>resources/img/website/logo.png" class="rounded-circle mainlogo" alt="" />
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link text-uppercase font-weight-bold active" aria-current="page" href="<?= ROOT_PATH ?>pages/animals/animals.php">Animals</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-uppercase font-weight-bold active" aria-current="page" href="<?= ROOT_PATH ?>pages/shelters/shelters.php">Shelters</a>
        </li>
        <!-- is shelter -->
        <?php if (isset($headerUserRole) && $headerUserRole === 'shelter' && $headerShelterRequest === 1) : ?>
          <a class="nav-link text-uppercase font-weight-bold active" aria-current="page" href="<?= ROOT_PATH ?>pages/shelters/create.php">Open Shelter</a>
          <li class="nav-item dropdown">
            <a class="nav-link text-uppercase font-weight-bold dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Actions
            </a>
            <ul class="dropdown-menu">
              <!-- <li><a class="dropdown-item" href="<?= ROOT_PATH ?>pages/shelters/create.php">Create Shelter</a></li> -->
              <li><a class="dropdown-item" href="<?= ROOT_PATH ?>pages/animals/create.php">Create Animals</a></li>
            </ul>
          </li>
          <!-- is admin -->
        <?php elseif (isset($headerUserRole) &&  $headerUserRole === 'admin') : ?>
          <li class="nav-item dropdown">
            <a class="nav-link text-uppercase font-weight-bold dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Actions
            </a>
            <ul class="dropdown-menu">
              <!-- <li><a class="dropdown-item" href="<?= ROOT_PATH ?>pages/shelters/create.php">Create Shelter</a></li> -->
              <!-- <li><a class="dropdown-item" href="<?= ROOT_PATH ?>pages/animals/create.php">Create Animals</a></li> -->
              <li><a class="dropdown-item" href="<?= ROOT_PATH ?>pages/users/register.php">Register User</a></li>
              <!-- <li><a class="dropdown-item" href="<?= ROOT_PATH ?>pages/animals/create.php">Create Animals</a></li> -->
              <!-- <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="<?= ROOT_PATH ?>pages/login/logout.php">Something else here</a></li> -->
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link text-uppercase font-weight-bold active" aria-current="page" href="<?= ROOT_PATH ?>pages/dashboard.php#requests">Shelter Requests</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-uppercase font-weight-bold active" aria-current="page" href="<?= ROOT_PATH ?>pages/dashboard.php">Dashboard</a>
          </li>

        <?php else : ?>

        <?php endif; ?>
      </ul>
      <ul class="navbar-nav  mb-2 mb-lg-0 display-flex align-items-center">
        <li class="nav-item">
          <?php if (isset($headerUserRole) && $headerUserRole !== 'unset') : ?>
            <?php
            if (isset($headerUserRole) && $headerUserRole !== 'unset') {


              // if ($headerUserRole === 'shelter') {
              //   $destination = 'shelters';
              // }
            }
            // $headerUserIMG = '../resources/img/'
            ?>
            <a class="nav-link text-uppercase font-weight-bold role" aria-current="page" href="<?= ROOT_PATH ?>pages/login/login.php">
              <img src="<?= ROOT_PATH ?>resources/img/<?= $destination . "/" . $headerUserIMG  ?>" class="rounded-circle me-2" alt="" style="width: 40px; height: 40px" />
              <?= "<b>" .  strtoupper($headerUserRole) . " </b>-Profile: " . $headerUserName ?>
            </a>
          <?php else : ?>
          <?php endif; ?>
        </li>
        <li class="nav-item">
          <?php if (isset($headerUserRole) && $headerUserRole !== 'unset') : ?>
            <a class="nav-link text-uppercase font-weight-bold" aria-current="page" href="<?= ROOT_PATH ?>pages/login/logout.php">Logout</a>
          <?php else : ?>
            <a class="nav-link text-uppercase font-weight-bold" aria-current="page" href="<?= ROOT_PATH ?>pages/login/login.php">Login</a>
          <?php endif; ?>

        </li>
        <li class="nav-item">
          <?php if (isset($headerUserRole) && $headerUserRole !== 'unset') : ?>
          <?php else : ?>
            <a class="nav-link text-uppercase font-weight-bold" aria-current="page" href="<?= ROOT_PATH ?>pages/users/register.php">Register</a>
          <?php endif; ?>
        </li>
      </ul>
    </div>
  </div>
</nav>