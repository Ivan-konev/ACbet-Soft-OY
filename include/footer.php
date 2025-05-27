<?php
include_once "class-user.php";
include_once "config.php";
include_once "functions.php";

// Bestämmer vilket språk som ska användas
$lang = $_GET['lang'] ?? 'sv';
$lang = ($lang === 'fi') ? 'fi' : 'sv';

// Inkluderar rätt språkfil för footer-text
include_once __DIR__ . "/../lang/footer-$lang.php"; // laddar in footer_text-arrayen
?>

<!-- Footer for Public Pages -->
<footer class="footer text-white py-4 mt-5">
    <div class="container-fluid p-5 " style="background-color:#308c54;">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0 ">
<<<<<<< HEAD
                <h5><?php echo $footer_text['contact']; ?></h5>
                <address class="mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i> <?php echo $footer_text['address']; ?><br>
                    <i class="fas fa-phone-alt me-2"></i> <?php echo $footer_text['phone']; ?><br>
                    <i class="fas fa-envelope me-2"></i> <?php echo $footer_text['email']; ?><br>
                    <a href="https://www.facebook.com/antikvariatkaris" class="text-white me-3" target="_blank">
                        <i class="fab fa-facebook-f fa-lg me-2"></i><?php echo $footer_text['facebook']; ?>
=======
                <h5>Karis Antikvariat</h5>
                <address class="mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i> Köpmansgatan 12, 10300 Karis<br>
                    <i class="fas fa-phone-alt me-2"></i> 040-8719706<br>
                    <i class="fas fa-envelope me-2"></i> karisantikvariat@gmail.com<br>
                    <a href="https://www.facebook.com/antikvariatkaris" class="text-white me-3" target="_blank">
                        <i class="fab fa-facebook-f fa-lg me-2"></i>@antikvariatkaris
>>>>>>> 9eaa33085df2e65624a0cf33ee42933732fbc200
                    </a><br>
                    <i class="fas fa-building me-2"></i> FO: 3302825-3
                </address>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
<<<<<<< HEAD
                <h5><?php echo $footer_text['opening_hours']; ?></h5>
                <ul class="list-unstyled">
                    <li><?php echo $footer_text['tue_fri']; ?></li>
                    <li><?php echo $footer_text['sat']; ?></li>
                    <li><?php echo $footer_text['sun_mon']; ?></li>
                </ul>
                <h5 class="mt-3"><?php echo $footer_text['delivery']; ?></h5>
                <p><?php echo $footer_text['delivery_text']; ?></p>
=======
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
>>>>>>> 9eaa33085df2e65624a0cf33ee42933732fbc200
            </div>
        </div>
        <hr class="my-3 bg-light">
        <div class="text-center">
<<<<<<< HEAD
            <p class="mb-0"><?php echo $footer_text['rights']; ?></p>
=======
            <p class="mb-0">&copy; 2025 Axxell. Alla rättigheter förbehållna.</p>
>>>>>>> 9eaa33085df2e65624a0cf33ee42933732fbc200
        </div>
    </div>
</footer>

<<<<<<< HEAD
=======
<div class="col-md-3 col-lg-3 col-xl-3 mx-auto mb-4">
      
      </div>
>>>>>>> 9eaa33085df2e65624a0cf33ee42933732fbc200
