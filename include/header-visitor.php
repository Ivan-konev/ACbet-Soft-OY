<?php
include_once "class-user.php";
include_once "config.php";
include_once "functions.php";
<<<<<<< HEAD

$lang = $_GET['lang'] ?? 'sv'; // Bestämmer vilket språk som används
$lang = ($lang === 'fi') ? 'fi' : 'sv'; // Om språket är fi, sätt till fi, annars sv

include_once __DIR__ . "/../lang/header-$lang.php"; // Laddar in header-texten baserat på språket
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>"> <!-- Sätt språket för hela dokumentet -->
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $header_text['title']; ?></title>
=======
?>
<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Karis Antikvariat</title>
>>>>>>> 9eaa33085df2e65624a0cf33ee42933732fbc200

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style rel="stylesheet" href="include/main.css"></style>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="include/main.css">
<<<<<<< HEAD
  <style> 
    .navbar {
        background-color: #308c54;
    }

    .nav-link,
    .navbar-brand {
        color: white !important;
        transition: background-color 0.3s, color 0.3s;
    }

    .nav-link:hover,
    .navbar-brand:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 0.375rem;
    }

    .nav-link.active,
    .navbar-brand.active {
=======
    <style> 
    .navbar {
        background-color: #308c54;
      }

      .nav-link,
      .navbar-brand {
        color: white !important;
        transition: background-color 0.3s, color 0.3s;
      }

      .nav-link:hover,
      .navbar-brand:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 0.375rem;
      }

      .nav-link.active,
      .navbar-brand.active {
>>>>>>> 9eaa33085df2e65624a0cf33ee42933732fbc200
        font-weight: bold;
        color: #ffffff !important;
        border-radius: 0.375rem;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
<<<<<<< HEAD
    }

    .btn-outline-light.active {
=======
      }

      .btn-outline-light.active {
>>>>>>> 9eaa33085df2e65624a0cf33ee42933732fbc200
        background-color: white;
        color: #308c54;
        border-color: white;
        font-weight: bold;
<<<<<<< HEAD
    }

    .btn-outline-light:hover {
        background-color: rgba(255, 255, 255, 0.3);
        color: white;
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 30 30'%3e%3cpath stroke='white' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav id="Navbar" class="navbar navbar-expand-lg px-5 py-3">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <!-- Brand alltid till vänster -->
    <a class="navbar-brand <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php?lang=<?=$lang?>">
      <i class="fas fa-book-open me-2"></i><?php echo $header_text['brand']; ?>
=======
      }

      .btn-outline-light:hover {
        background-color: rgba(255, 255, 255, 0.3);
        color: white;
      }
      .navbar-toggler-icon {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 30 30'%3e%3cpath stroke='white' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}
</style>
</head>
<body>

  <!-- Navbar -->
  <nav id="Navbar" class="navbar navbar-expand-lg px-5 py-3">
  <div class="container-fluid d-flex justify-content-between align-items-center">

    <!-- Brand alltid till vänster -->
    <a class="navbar-brand <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">
      <i class="fas fa-book-open me-2"></i>Karis Antikvariat
>>>>>>> 9eaa33085df2e65624a0cf33ee42933732fbc200
    </a>

    <!-- Hamburgermeny till höger -->
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Innehåll -->
    <div class="collapse navbar-collapse justify-content-between" id="navbarContent">

      <!-- Vänster nav-länkar -->
      <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item">
<<<<<<< HEAD
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about-us.php' ? 'active' : ''; ?>" href="index.php?lang=<?=$lang?>#about"><?php echo $header_text['about_us']; ?></a>
=======
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about-us.php' ? 'active' : ''; ?>" href="index.php#about">Om oss</a>
>>>>>>> 9eaa33085df2e65624a0cf33ee42933732fbc200
        </li>
      </ul>

      <!-- Höger språkknappar -->
      <div class="d-flex ms-auto">
<<<<<<< HEAD
        <a href="?lang=sv" class="btn btn-sm btn-outline-light me-2 <?php echo (empty($_GET['lang']) || $_GET['lang'] == 'sv') ? 'active' : ''; ?>"><?php echo $header_text['lang_sv']; ?></a>
        <a href="?lang=fi" class="btn btn-sm btn-outline-light <?php echo (isset($_GET['lang']) && $_GET['lang'] == 'fi') ? 'active' : ''; ?>"><?php echo $header_text['lang_fi']; ?></a>
=======
        <a href="?lang=sv" class="btn btn-sm btn-outline-light me-2 <?php echo (isset($_GET['lang']) && $_GET['lang'] == 'sv') ? 'active' : ''; ?>">SV</a>
        <a href="?lang=fi" class="btn btn-sm btn-outline-light <?php echo (isset($_GET['lang']) && $_GET['lang'] == 'fi') ? 'active' : ''; ?>">FI</a>
>>>>>>> 9eaa33085df2e65624a0cf33ee42933732fbc200
      </div>

    </div>
  </div>
</nav>
