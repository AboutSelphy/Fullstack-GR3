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
    <link rel="stylesheet" href="./resources/style/index.css">

    <!-- Icons FA -->
    <script src="https://kit.fontawesome.com/96fa54072b.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php require_once("./components/navbar.php") ?>


    <!-- MOSTAFA WILL ENTER OUR LANDING PAGE HERE -->
    <div class="container">
        <header>
            <h1><b>Find Your New Best Friend</b></h1>
            <p>Your Perfect Pet Awaits for Adoption!</p>
        </header>
    </div>

    <section>
        <div class="container">
            <div class="feature">
                <h2>Explore Our Adoptable Pets</h2>
                <p>here is great concern about the last polar bears in the Arctic. Nobody knows whether the little ones being born now – in these weeks – have a future. The polar bears don't know where to go and are on an endless search for food . Death awaits them all if we don't help now! The pack ice is decreasing every year and the threats are increasing. If we don't act as quickly as possible, there will be no more polar bears in 80 years.</p>
                <img src="resources/img/website/P1.jpg" alt="Animal Shelter Image" width="1200"; margin-top: 20px >
            </div>

            <div class="feature">
                <h2>Adoption Made Easy</h2>
                <p>The Washington Convention on International Trade in Endangered Species of Wild Fauna and Flora (CITES), passed in 1973,regulates the sustainable, international trade in endangered animals. To date, over 170 countries have signed the agreement - an important basis for cooperation for the WWF's work worldwide.</p>
            </div>

            <div class="feature">
                <h2>Connect with Us</h2>
                <p>With your will donation you can help to save animals. Because in 20 or 30 years the animals will no longer be the same as they are today.Please contact us to help the animals.</p>

        
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