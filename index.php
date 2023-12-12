<?php
require_once("./script/db_connection.php");

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
    <link rel="stylesheet" href="./resources/style/global.css">

    <!-- Icons FA -->
    <script src="https://kit.fontawesome.com/96fa54072b.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php require_once("./components/navbar.php") ?>

   
    <!-- MOSTAFA WILL ENTER OUR LANDING PAGE HERE -->
    <div class="container">
        <header>
        <h1>Find Your New Best Friend</h1>
        <p>Adopt, Don't Shop!</p>
    </header>
    </div>

    <section>
        <div class="container">
            <div class="feature">
                <h2>Explore Our Adoptable Pets</h2>
                <p>Discover a variety of lovable pets waiting for a forever home.</p>
            </div>

            <div class="feature">
                <h2>Adoption Made Easy</h2>
                <p>Apply for adoption online and make a difference in a pet's life.</p>
            </div>

            <div class="feature">
                <h2>Connect with Us</h2>
                <p>Join our community, and let's make a positive impact together.</p>

                 <!-- Image -->
                 <img src="path/to/your/image.jpg" alt="Animal Shelter Image" style="width: 100%; max-width: 600px; margin-top: 20px;">
                
            </div>
        </div>
    </section>

    <footer>
        <p>&copy right: 2023 Animal Shelter || Contact us at info@animalshelter.com</p>
    </footer>



    <!-- // BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>