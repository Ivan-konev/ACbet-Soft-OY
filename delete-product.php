<?php
<<<<<<< HEAD
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
=======
include_once "include/header.php";

if (isset($_GET['uid'])) {
    $productId = (int)$_GET['uid'];
}

if (isset($_POST['confirm'])) {
    if ($_POST['confirm'] === "delete") {
>>>>>>> 9eaa33085df2e65624a0cf33ee42933732fbc200
        $result = deleteProduct($pdo, $productId);

        if ($result['success']) {
            header("Location: dashboard.php?deletedproduct=1");
            exit;
        } else {
            $productFeedback = $result['message'];
        }
    }
<<<<<<< HEAD
} else {
    echo "<div class='alert alert-danger'>Felaktigt produkt ID.</div>";
    exit;
}
?>
=======

    if ($_POST['confirm'] === "back") {
        header("Location: edit-product.php?id={$productId}");
        exit;
    }
}
?>

<div class="container">
    <h1>Delete Product</h1>

    <?php if (isset($productFeedback)) : ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($productFeedback); ?>
        </div>
    <?php endif; ?>

    <p>Are you sure you want to permanently delete this product?</p>

    <form method="post">
        <button type="submit" name="confirm" value="delete" class="btn btn-danger">Delete Product</button>
        <button type="submit" name="confirm" value="back" class="btn btn-secondary">Cancel</button>
    </form>
</div>
>>>>>>> 9eaa33085df2e65624a0cf33ee42933732fbc200
