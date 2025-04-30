<?php
include_once "class-user.php";
include_once "config.php";
include_once "functions.php";
?>

<!-- Footer for Public Pages -->
<footer class="footer text-white py-4 mt-5">
    <div class="container-fluid p-5 " style="background-color:#308c54;">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0 ">
                <h5>Karis Antikvariat</h5>
                <address class="mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i> Köpmansgatan 12, 10300 Karis<br>
                    <i class="fas fa-phone-alt me-2"></i> 040-8719706<br>
                    <i class="fas fa-envelope me-2"></i> karisantikvariat@gmail.com<br>
                    <a href="https://www.facebook.com/antikvariatkaris" class="text-white me-3" target="_blank">
                        <i class="fab fa-facebook-f fa-lg me-2"></i>@antikvariatkaris
                    </a><br>
                    <i class="fas fa-building me-2"></i> FO: 3302825-3
                </address>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <h5>Öppettider</h5>
                <ul class="list-unstyled">
                    <li>Tisdag - Fredag: 10:00 - 17:00</li>
                    <li>Lördag: 10:00 - 15:00</li>
                    <li>Söndag - Måndag: Stängt</li>
                </ul>
                <h5 class="mt-3">Leverans</h5>
                <p>Vi levererar via Posti enligt deras prislistor. Vi levererar även utomlands.</p>
            </div>
            <div class="col-md-4 ">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
      <li class="nav-item ">
         <a  class="nav-link d-flex justify-content-center <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"  href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link d-flex justify-content-center <?php echo basename($_SERVER['PHP_SELF']) == 'about-us.php' ? 'active' : ''; ?>" href="index.php#about">Om oss</a>
        </li>
      </ul>
            </div>
        </div>
        <hr class="my-3 bg-light">
        <div class="text-center">
            <p class="mb-0">&copy; 2025 Axxell. Alla rättigheter förbehållna.</p>
        </div>
    </div>
</footer>

<div class="col-md-3 col-lg-3 col-xl-3 mx-auto mb-4">
      
      </div>