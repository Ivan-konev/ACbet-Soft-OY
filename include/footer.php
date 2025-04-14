<?php
include_once "class-user.php";
include_once "config.php";
include_once "functions.php";
?>

<footer class="bg-body-tertiary text-dark pt-5 pb-4">
  <div class="container text-md-left">
    <div class="row text-md-left text-center text-md-start">
      <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mb-4">
        <h1 class="text-uppercase fw-bold text-dark">Logo</h1>
      </div>
      
      <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mb-4">
        <h5 class="text-uppercase fw-bold text-dark">Company</h5>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == '#' ? 'active' : ''; ?>" href="#"><strong>...</strong></a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == '#' ? 'active' : ''; ?>" href="#"><strong>...</strong></a>
        </li>
      </ul>
      </div>

      <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mb-4">
        <h5 class="text-uppercase fw-bold text-dark">Services</h5>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == '#' ? 'active' : ''; ?>" href="#"><strong>...</strong></a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == '#' ? 'active' : ''; ?>" href="#"><strong>...</strong></a>
        </li>
      </ul>
      </div>

      <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mb-4">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item">
         <a  class="nav-link "  href="index.php"><strong>Home</strong></a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'searchpage.php' ? 'active' : ''; ?>" href="searchpage.php"><strong>Detailed search</strong></a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about-us.php' ? 'active' : ''; ?>" href="about-us.php"><strong>About Us</strong></a>
        </li>
      </ul>
      </div>

    <div class="text-center mt-3">
      <p class="text-white">&copy; 2025 YourCompany. All Rights Reserved.</p>
    </div>
  </div>
</footer>
