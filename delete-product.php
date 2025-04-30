<?php
include_once "include/header.php";

if (isset($_GET['uid'])) {
    $productId = (int)$_GET['uid'];
}

if (isset($_POST['confirm'])) {
    if ($_POST['confirm'] === "delete") {
        $result = deleteProduct($pdo, $productId);

        if ($result['success']) {
            header("Location: dashboard.php?deletedproduct=1");
            exit;
        } else {
            $productFeedback = $result['message'];
        }
    }

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