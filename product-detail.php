<?php
// product_detail.php
include_once "include/header-visitor.php";  // Make sure this returns a PDO connection like $pdo
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $result = getProductById($pdo, (int)$_GET['id']);

    if ($result['success']) {
        $product = $result['data'];
    } else {
        echo "<div class='alert alert-danger mt-5 text-center'>" . htmlspecialchars($result['error']) . "</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-warning mt-5 text-center'>Invalid or missing product ID.</div>";
    exit;
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="path_to_images/<?= htmlspecialchars($product['prod_id']) ?>.jpg" class="img-fluid" alt="Product Image">
        </div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($product['prod_title']) ?></h2>
            <p><strong>Category:</strong> <?= htmlspecialchars($product['cat_name']) ?></p>
            <p><strong>Condition:</strong> <?= htmlspecialchars($product['cond_class']) ?></p>
            <p><strong>Price:</strong> $<?= htmlspecialchars($product['prod_price']) ?></p>
            <p><strong>Year:</strong> <?= htmlspecialchars($product['prod_year']) ?></p>
            <p><strong>Shelf:</strong> <?= htmlspecialchars($product['shelf_nr']) ?></p>
            <p><?= nl2br(htmlspecialchars($product['prod_info'])) ?></p>
            <a href="index.php" class="btn btn-secondary">Back to Shop</a>
        </div>
    </div>
</div>

</body>
</html>
<?php
require 'include/footer.php';
?>