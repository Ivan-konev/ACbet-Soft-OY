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
                <h5><?php echo $footer_text['contact']; ?></h5>
                <address class="mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i> <?php echo $footer_text['address']; ?><br>
                    <i class="fas fa-phone-alt me-2"></i> <?php echo $footer_text['phone']; ?><br>
                    <i class="fas fa-envelope me-2"></i> <?php echo $footer_text['email']; ?><br>
                    <a href="https://www.facebook.com/antikvariatkaris" class="text-white me-3" target="_blank">
                        <i class="fab fa-facebook-f fa-lg me-2"></i><?php echo $footer_text['facebook']; ?>
                    </a><br>
                    <i class="fas fa-building me-2"></i> FO: 3302825-3
                </address>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <h5><?php echo $footer_text['opening_hours']; ?></h5>
                <ul class="list-unstyled">
                    <li><?php echo $footer_text['tue_fri']; ?></li>
                    <li><?php echo $footer_text['sat']; ?></li>
                    <li><?php echo $footer_text['sun_mon']; ?></li>
                </ul>
                <h5 class="mt-3"><?php echo $footer_text['delivery']; ?></h5>
                <p><?php echo $footer_text['delivery_text']; ?></p>
            </div>
        </div>
        <hr class="my-3 bg-light">
        <div class="text-center">
            <p class="mb-0"><?php echo $footer_text['rights']; ?></p>
        </div>
    </div>
</footer>

