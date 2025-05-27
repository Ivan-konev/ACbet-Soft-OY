<?php
// product_detail.php

include_once "include/header-visitor.php";  // $pdo should be available here

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $result = getProductById($pdo, (int)$_GET['id']);

    if ($result['success']) {
        $product = $result['data'];

        try {
            $categoryId = isset($product['prod_cat_fk']) ? (int)$product['prod_cat_fk'] : 0;

            if ($categoryId <= 0) {
                // No valid category, just show empty recommended products
                $similarProducts = [];
            } else {
                // Call the function normally
                $similarProducts = getRecommendedProducts($pdo, $categoryId, $product['prod_id'], 4);
            }
        } catch (PDOException $e) {
            $similarProducts = [];
            // Optionally log $e->getMessage()
        }

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
            <img src="images/<?= htmlspecialchars($product['Img_name']) ?>" class="img-fluid" alt="Product Image">
        </div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($product['prod_title']) ?></h2>
            <p><strong>Category:</strong> <?= htmlspecialchars($product['cat_name']) ?></p>
            <p><strong>Condition:</strong> <?= htmlspecialchars($product['cond_class']) ?></p>
            <p><strong>Price:</strong> €<?= htmlspecialchars($product['prod_price']) ?></p>
            <p><strong>Year:</strong> <?= htmlspecialchars($product['prod_year']) ?></p>
            <p><strong>Shelf:</strong> <?= htmlspecialchars($product['shelf_nr']) ?></p>

            <a href="index.php#products" class="btn btn-secondary">Tillbaka</a>
            <h3 id="Product-descH1">Product Description</h3>
            <h4 id="Product-desc"><?= nl2br(htmlspecialchars($product['prod_info'])) ?></h4>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h3 class="mb-4">Recommended Products</h3>
    <div class="row">
        <?php if (!empty($similarProducts)): ?>
            <?php foreach ($similarProducts as $simProd): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="images/<?= htmlspecialchars($simProd['prod_code']) ?>.jpg" class="card-img-top" alt="<?= htmlspecialchars($simProd['prod_title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($simProd['prod_title']) ?></h5>
                            <p class="card-text"><strong>Price:</strong> €<?= htmlspecialchars($simProd['prod_price']) ?></p>
                            <p class="card-text"><strong>Year:</strong> <?= htmlspecialchars($simProd['prod_year']) ?></p>
                            <a href="product-detail.php?id=<?= htmlspecialchars($simProd['prod_id']) ?>" class="btn btn-primary btn-sm">View Product</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class='col-12'>
                <div class='alert alert-info'>No recommended products found.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
function getRecommendedProducts(PDO $pdo, int $categoryId, int $excludeProductId, int $limit = 4): array {
    // Sanitize $limit (max 20 to avoid huge queries)
    $limit = ($limit > 0 && $limit <= 20) ? $limit : 4;

    // Insert $limit directly in the query
    $sql = "
        SELECT 
            p.*, 
            c.cat_name, 
            k.cond_class, 
            s.shelf_nr
        FROM products p
        JOIN tab_cat c ON p.prod_cat_fk = c.cat_id
        JOIN tab_kond k ON p.prod_cond_fk = k.cond_id
        JOIN tab_shelf s ON p.prod_shelf_fk = s.shelf_id
        WHERE p.prod_cat_fk = :categoryId
          AND p.prod_id != :excludeProductId
          AND p.prod_status = 1
        LIMIT $limit
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
    $stmt->bindValue(':excludeProductId', $excludeProductId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$categoryId = $product['prod_cat_fk'];
$excludeProductId = $product['prod_id'];

$sql = "SELECT p.*, c.cat_name, k.cond_class, s.shelf_nr
        FROM products p
        JOIN tab_cat c ON p.prod_cat_fk = c.cat_id
        JOIN tab_kond k ON p.prod_cond_fk = k.cond_id
        JOIN tab_shelf s ON p.prod_shelf_fk = s.shelf_id
        WHERE p.prod_cat_fk = ?
          AND p.prod_id != ?
          AND p.prod_status = 1
        LIMIT 4";

$stmt = $pdo->prepare($sql);
$stmt->execute([$categoryId, $excludeProductId]);
$similarProducts = $stmt->fetchAll();

if (empty($similarProducts)) {
    echo "No recommended products found in category $categoryId excluding product $excludeProductId.";
} else {
    foreach ($similarProducts as $prod) {
        echo $prod['prod_title'] . "<br>";
    }
}




?>

</body>
</html>

<style>


/* Reset and base */
body {
  background: #f5faf7; /* very light greenish-white */
  font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #264d27; /* dark green for text */
  line-height: 1.6;
  margin: 0;
  padding: 0;
}

.container {
  max-width: 960px;
  margin: 3rem auto;
  background: #ffffff;
  padding: 2rem 2.5rem;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(38, 77, 39, 0.1);
  transition: box-shadow 0.3s ease;
}

.container:hover {
  box-shadow: 0 15px 40px rgba(38, 77, 39, 0.15);
}

.row {
  display: flex;
  flex-wrap: wrap;
  gap: 2.5rem;
  align-items: start;
}

/* Image column */
.col-md-6 {
  flex: 1 1 45%;
  min-width: 280px;
}

.img-fluid {
  width: 100%;
  border-radius: 14px;
  box-shadow: 0 15px 25px rgba(38, 77, 39, 0.12);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: zoom-in;
}

.img-fluid:hover {
  transform: scale(1.05);
  box-shadow: 0 20px 40px rgba(38, 77, 39, 0.18);
}

/* Text column */
h2 {
  font-size: 2.4rem;
  font-weight: 700;
  margin-bottom: 0.75rem;
  color: #1b3318;
}

p {
  font-size: 1.1rem;
  margin: 0.35rem 0;
  color: #3b5d23;
}

p strong {
  color:rgb(0, 0, 0);
  font-weight: 600;
}

/* Buttons */
a.btn-secondary, a.btn-primary {
  display: inline-block;
  padding: 0.6rem 1.5rem;
  font-size: 1rem;
  font-weight: 600;
  border-radius: 8px;
  text-decoration: none;
  transition: background-color 0.3s ease, box-shadow 0.25s ease;
  cursor: pointer;
  user-select: none;
}

a.btn-secondary {
  background-color: #e6f0e9;
  color: #2c4b19;
  border: 2px solid transparent;
  margin-top: 1rem;
}

a.btn-secondary:hover {
  background-color: #c6dfc9;
  color: #1b3318;
  box-shadow: 0 4px 12px rgba(38, 77, 39, 0.1);
}

a.btn-primary {
  background-color: #2c4b19;
  color: white;
  border: 2px solid #2c4b19;
  margin-top: 0.75rem;
}

a.btn-primary:hover {
  background-color: #1b3318;
  border-color: #1b3318;
  box-shadow: 0 5px 20px rgba(27, 51, 24, 0.6);
}

/* Description section */
#Product-descH1 {
  margin-top: 3.5rem;
  font-weight: 700;
  font-size: 1.8rem;
  border-bottom: 3px solid #2c4b19;
  padding-bottom: 0.6rem;
  color: #1b3318;
}

#Product-desc {
  font-size: 1.125rem;
  line-height: 1.7;
  color: #496934;
  margin-top: 1rem;
  white-space: pre-line;
}

/* Recommended Products */
.container.mt-5 h3.mb-4 {
  font-weight: 700;
  font-size: 2rem;
  color: #1b3318;
  margin-bottom: 2rem;
  border-left: 6px solid #2c4b19;
  padding-left: 0.75rem;
}

.row > .col-md-3 {
  flex: 1 1 22%;
  min-width: 200px;
}

.card {
  border-radius: 12px;
  box-shadow: 0 8px 16px rgba(38, 77, 39, 0.07);
  transition: transform 0.25s ease, box-shadow 0.25s ease;
  cursor: pointer;
  background: white;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 30px rgba(38, 77, 39, 0.15);
}

.card-img-top {
  border-radius: 12px 12px 0 0;
  width: 100%;
  object-fit: cover;
  height: 180px;
  transition: transform 0.3s ease;
}

.card:hover .card-img-top {
  transform: scale(1.1);
}

.card-body {
  padding: 1rem 1rem 1.5rem;
}

.card-title {
  font-weight: 700;
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
  color: #1b3318;
}

.card-text {
  font-size: 1rem;
  margin-bottom: 0.5rem;
  color: #496934;
}

/* Responsive */
@media (max-width: 768px) {
  .row {
    flex-direction: column;
  }
  
  .col-md-6 {
    flex: 1 1 100%;
  }
  
  .row > .col-md-3 {
    flex: 1 1 100%;
  }
  
  a.btn-primary, a.btn-secondary {
    width: 100%;
    text-align: center;
  }
}

</style>
