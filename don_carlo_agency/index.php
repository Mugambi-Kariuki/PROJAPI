<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thee GOATs Agency</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            margin-top: 30px;
        }
        .carousel-item {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 350px;
        }
        .carousel img {
            width: auto;
            max-width: 80%;
            max-height: 250px;
            object-fit: contain;
        }
        .btn-custom {
            font-size: 18px;
            padding: 10px 20px;
            margin: 10px;
            width: 200px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1 class="my-4">Welcome to Thee GOATs Agency</h1>

        <!-- Slideshow -->
        <div id="goatsCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                <div class="carousel-item active">  <!-- Ensure the first item has the active class -->
                    <img src="../don_carlo_agency/images/Messi Qatar.jpg" class="d-block" alt="Messi in Kit">
                </div>
                <div class="carousel-item">
                    <img src="../don_carlo_agency/images/Messi awards.jpg" class="d-block" alt="Messi in Suit">
                </div>
                <div class="carousel-item">
                    <img src="../don_carlo_agency/images/Munich Stadium.jpg" class="d-block" alt="Munich Stadium">
                </div>
                <div class="carousel-item">
                    <img src="../don_carlo_agency/images/kane.jpg" class="d-block" alt="Harry Kane in Suit">
                </div>
                <div class="carousel-item">
                    <img src="../don_carlo_agency/images/Henry.jpg" class="d-block" alt="Thierry Henry in Kit">
                </div>
                <div class="carousel-item">
                    <img src="../don_carlo_agency/images/Kasarani stadi.jpg" class="d-block" alt="Thierry Henry in Suit">
                </div>
                <div class="carousel-item">
                    <img src="../don_carlo_agency/images/fire ball.jpg" class="d-block" alt="Iniesta in Kit">
                </div>
                <div class="carousel-item">
                    <img src="../don_carlo_agency/images/Iniesta pitch.jpg" class="d-block" alt="Iniesta in Suit">
                </div>
                <div class="carousel-item">
                    <img src="../don_carlo_agency/images/kaka in suit.jpg" class="d-block" alt="Kaka in Kit">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#goatsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#goatsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

        <!-- Buttons -->
        <div class="mt-4">
            <a href="../don_carlo_agency/forms/User registration.php" class="btn btn-primary btn-custom">Sign Up</a>
            <a href="../don_carlo_agency/forms/user login.php" class="btn btn-success btn-custom">Login</a>
        </div>
    </div>

    <!-- Ensure Bootstrap JavaScript is loaded -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
