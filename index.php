<?php
require "include/header-visitor.php";


$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$books = getBooks($pdo, $category, $search);
$bookResult = $books['success'] ? $books['data'] : [];

$catResult = getAllCategories($pdo);
$allCategories = $catResult['success'] ? $catResult['data'] : [];

?>



<!-- Search and Filter Section -->
<div class="container mt-4">
    <form method="GET" action="index.php">
        <div class="row">
            <!-- Search Bar -->
            <div class="col-md-6 mb-3">
                <input type="text" name="search" class="form-control" placeholder="Search Products" value="<?= htmlspecialchars($search) ?>">
            </div>

            <!-- Category Filter -->
            <div class="col-md-6 mb-3">
               <select name="category" class="form-select">
					<option value="">All Categories</option>
					<?php foreach ($allCategories as $cat): ?>
						<option value="<?= htmlspecialchars($cat['cat_name']) ?>" <?= $category === $cat['cat_name'] ? 'selected' : '' ?>>
							<?= htmlspecialchars($cat['cat_name']) ?>
						</option>
					<?php endforeach; ?>
				</select>
            </div>

            <!-- Submit Button -->
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        </div>
    </form>
</div>

<!-- Product Cards Section -->
<div class="container mt-5">
    <div class="row">
        <?php if (!empty($books['success']) && !empty($books['data'])): ?>
            <?php foreach ($books['data'] as $book): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="path_to_images/<?= htmlspecialchars($book['prod_code']) ?>.jpg" class="card-img-top" alt="<?= htmlspecialchars($book['prod_title']) ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($book['prod_title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($book['prod_info']) ?></p>
                            <p class="card-price">$<?= number_format($book['prod_price'], 2) ?></p>
                            <p class="card-year">Year: <?= htmlspecialchars($book['prod_year']) ?></p>
                            <p class="card-cat">category <?= htmlspecialchars($book['cat_name']) ?></p>
                            <a href="product-detail.php?id=<?= $book['prod_id'] ?>" class="btn btn-primary mt-auto">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning">No products found based on your filters.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
require 'include/footer.php';
?>