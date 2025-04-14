<?php
include_once "class-user.php";
include_once "config.php";
include_once "functions.php";

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Karis Antikvariat</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script defer src="js/script.js"></script>
</head>
<body >
<nav id="Navbar" class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <div class="container">
  <div class="row align-items-center justify-content-start" style="height: 70px;"> <!-- höjd på navbar -->

    <!-- Karis Antikvariat -->
    <div class="col-auto d-flex align-items-center">
      <h3 class="mb-0">
        <a class="nav-link" href="index.php"><strong>Karis Antikvariat</strong></a>
      </h3>
    </div>

    <!-- Detailed Search -->
    <div class="col-auto d-flex align-items-center">
      <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'searchpage.php' ? 'active' : ''; ?>" href="searchpage.php">
      <strong>Searchpage</strong>
      </a>
    </div>

    <!-- About Us -->
    <div class="col-auto d-flex align-items-center">
      <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about-us.php' ? 'active' : ''; ?>" href="about-us.php">
        <strong>About Us</strong>
      </a>
    </div>

  </div>
</div>
      <?php?>
    </div>
  </div>
</nav>