<?php
// delete-product.php

if (!isset($_SESSION['user']) || !$user_obj->checkUserRole($_SESSION['user']['role'], [200, 300, 9999])) {
    $_SESSION['access_denied'] = "Åtkomst förbjuden till sida";
    header('Location: dashboard.php');
    exit;
}

include_once "include/header.php";

if (isset($_GET['uid']) && is_numeric($_GET['uid'])) {
    $productId = (int)$_GET['uid'];

    if (isset($_POST['confirm']) && $_POST['confirm'] === "delete") {
        $result = deleteProduct($pdo, $productId);

        if ($result['success']) {
            header("Location: dashboard.php?deletedproduct=1");
            exit;
        } else {
            $productFeedback = $result['message'];
        }
    }
} else {
    echo "<div class='alert alert-danger'>Felaktigt produkt ID.</div>";
    exit;
}
?>
