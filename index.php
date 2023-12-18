<?php
require_once("config.php");
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
    <link rel="stylesheet" href="./resources/style/components/index.css">

    <!-- Icons FA -->
    <script src="https://kit.fontawesome.com/96fa54072b.js" crossorigin="anonymous"></script>

</head>

<body>
    <!-- Navbar -->
    <?php require_once("./components/navbar.php") ?>

   <!-- Hero -->
<section class="hero d-flex align-items-center vh-75">
    <div id="hero-carousel" class="carousel slide w-100 mx-auto" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active c-item">
                <img src="./resources/img/animals/default.jpg" class="d-block w-100 c-img" alt="Slide 1">
                <div class="carousel-caption top-50 translate-middle-y text-center">
                    <p class="mt-5 fs-3 text-uppercase">Discover the hidden world</p>
                    <h1 class="display-1 fw-bolder text-capitalize">The Aurora Tours</h1>
                    <button class="btn btn-cta px-4 py-2 fs-5 mt-5">Book a tour</button>
                </div>
            </div>
            <div class="carousel-item c-item">
                <img src="./resources/img/shelters/default.jpg" class="d-block w-100 c-img" alt="Slide 2">
                <div class="carousel-caption top-50 translate-middle-y text-center">
                    <p class="text-uppercase fs-3 mt-5">The season has arrived</p>
                    <p class="display-1 fw-bolder text-capitalize">3 available tours</p>
                    <button class="btn btn-default px-4 py-2 fs-5 mt-5" data-bs-toggle="modal" data-bs-target="#booking-modal">Book a tour</button>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#hero-carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#hero-carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>



<section class="about">
  <div class="container ">
    <div class="row justify-content-center align-items-center">
        <div class="col col-lg-8 col-md-6 col-sx-12">
        <h2>Who we are!</h2>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Pariatur, officiis molestiae iusto suscipit soluta delectus saepe sed minus sint incidunt labore fugit neque explicabo sit. Dolorum quo ut libero consectetur.</p>
        <hr>
        <ul class="nav justify-content-center list-unstyled d-flex">
            <li class="ms-3"><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
            <li class="ms-3"><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
            <li class="ms-3"><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
            <li class="ms-3"><a href="#"><i class="fa-brands fa-tiktok"></i></a></li>
            <li class="ms-3"><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
        </ul>
        </div>
        <div class="col col-lg-4 col-md-6 col-sx-12">
            <img src="./resources/img/website/logo.svg" alt="Logo.svg" class="card-logo">
        </div>
    </div>
  </div>
</section>

<section class="list">
<div class="container">
    <div class="row">
        <div class="four col-md-3">
            <div class="counter-box colored"> <i class="fa fa-thumbs-o-up"></i> <span class="counter">2147</span>
                <p>Happy Customers</p>
            </div>
        </div>
        <div class="four col-md-3">
            <div class="counter-box"> <i class="fa fa-group"></i> <span class="counter">3275</span>
                <p>Registered Members</p>
            </div>
        </div>
        <div class="four col-md-3">
            <div class="counter-box"> <i class="fa fa-shopping-cart"></i> <span class="counter">289</span>
                <p>Available Products</p>
            </div>
        </div>
        <div class="four col-md-3">
            <div class="counter-box"> <i class="fa fa-user"></i> <span class="counter">1563</span>
                <p>Saved Trees</p>
            </div>
        </div>
    </div>
</div>
        </section>

    <!-- Footer -->
    <?php require_once("./components/footer.php") ?> 



<script>
    // Ensure the document is fully loaded before running the script
    $(document).ready(function() {
        $('.counter').each(function () {
            var $this = $(this);
            var initialText = $this.text().trim();
            var initialValue = parseFloat(initialText);

            if (!isNaN(initialValue)) {
                $({ Counter: 0 }).animate({
                    Counter: initialValue
                }, {
                    duration: 4000,
                    easing: 'swing',
                    step: function (now) {
                        $this.text(Math.ceil(now));
                    }
                });
            } else {
                console.error('Invalid initial value for counter:', initialText);
            }
        });
    });
</script>




</body>

</html>