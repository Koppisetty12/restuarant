<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Nikokli Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .hero {
            background: url('https://images.unsplash.com/photo-1555992336-cbfdb0c9f56a?auto=format&fit=crop&w=1650&q=80') center/cover no-repeat;
            height: 80vh;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-shadow: 1px 1px 3px #000;
        }
        .btn-custom {
            width: 150px;
            margin: 5px;
        }
    </style>
</head>
<body>
<?php include "navbar.php"; ?>

<!-- Hero Section -->
<div class="hero text-center">
    <h1 class="display-3 fw-bold">Welcome to Nikokli</h1>
    <p class="lead">Fine Dining | Memorable Moments</p>
    <div>
        <a href="login.php" class="btn btn-light btn-lg btn-custom">Login</a>
        <a href="register.php" class="btn btn-outline-light btn-lg btn-custom">Sign Up</a>
        <a href="book_table.php" class="btn btn-warning btn-lg btn-custom">Book a Table</a>
    </div>
</div>

<!-- About Section -->
<div class="container my-5">
    <h2 class="text-center mb-4">About Nikokli</h2>
    <p class="text-center">Nikokli is your perfect place for dining with elegance and comfort. Enjoy a curated menu of seasonal dishes, expertly prepared by our chefs. Whether it's a romantic dinner or a family celebration, we welcome you!</p>
</div>

<footer class="text-center p-3 bg-dark text-white">
    &copy; <?= date("Y") ?> Nikokli Restaurant. All Rights Reserved.
</footer>
</body>
</html>
