<?php
include_once "include/header.php";


if(!$user_obj->checkLoginStatus($_SESSION['user'] ['id'])){
	header("Location: login.php");
	exit;
}
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$books = getBooks($pdo, $category, $search, "dashboard");
$bookResult = $books['success'] ? $books['data'] : [];

$catResult = getAllCategories($pdo);
$allCategories = $catResult['success'] ? $catResult['data'] : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['current_status'])) {
    $productId = (int) $_POST['product_id'];
    $newStatus = $_POST['current_status'] == 1 ? 0 : 1;

    // Anropa funktionen för att uppdatera statusen
    $result = updateProductStatus($pdo, $productId, $newStatus);
    echo "<p>{$result['message']}</p>";
}
?>
<ul class="nav nav-tabs" id="inventory-tabs">
                <li class="nav-item">
                    <a class="nav-link active" id="search-tab"  href="dashboard.php">Sök</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="add-tab"  href="create-product.php">Lägg till objekt</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="edit-database-tab"  href="#edit-database">Redigera
                        databas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="lists-tab"  href="#lists">Listor</a>
                </li>
            </ul>

    <?php if(isset($_GET['deletedproduct'])): ?>
    <div class="user-feedback bg-success  text-white m-4"><p class="text-center m-0">Product deleted successfully</p></div>
    <?php endif ?>
 <div class="container mt-5">
    <!-- Filter Form Above the Table -->
    <form method="GET" action="" class="mb-4">
        <div class="row">
            <!-- Search Bar -->
            <div class="col-md-6 mb-3">
                <input type="text" name="search" class="form-control" placeholder="Search Products/author/genre" value="<?= htmlspecialchars($search ?? '') ?>">
            </div>

            <!-- Category Filter -->
            <div class="col-md-6 mb-3">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach ($allCategories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['cat_name']) ?>" <?= isset($category) && $category === $cat['cat_name'] ? 'selected' : '' ?>>
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

    <!-- Product Table -->
    <div class="table-responsive">
        <table class="table table-hover" id="inventory-table">
            <thead class="table-light">
                <tr>
                    <th>Id</th>
                    <th>Titel</th>
                    <th>Författare</th>
                    <th>Kategori</th>
                    <th>Pris</th>
                    <th>År</th>
                    <th>Status</th>
                    <th>Åtgärder</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($books['success']) && !empty($books['data'])): ?>
                    <?php foreach ($books['data'] as $book): ?>
                        <tr onclick="window.location='edit-product.php?id=<?= $book['prod_id'] ?>';" style="cursor:pointer;">
                            <td><?= htmlspecialchars($book['prod_id']) ?></td>
                            <td><?= htmlspecialchars($book['prod_title']) ?></td>
                            <td><?= htmlspecialchars($book['author_name']) ?></td>
                            <td><?= htmlspecialchars($book['cat_name']) ?></td>
                            <td>$<?= number_format($book['prod_price'], 2) ?></td>
                            <td><?= htmlspecialchars($book['prod_year']) ?></td>
                            <td>
                                <span class="card-status">
                                    <?= $book['prod_status'] == 1 ? '<p class="text-success">Tillgänglig</p>' : '<p class="text-danger">Såld</p>' ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" onClick="event.stopPropagation();">
                                    <input type="hidden" name="product_id" value="<?= $book['prod_id'] ?>">
                                    <input type="hidden" name="current_status" value="<?= $book['prod_status'] ?>">
                                    <button type="submit" class="btn btn-primary">Växla</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">
                            <div class="alert alert-warning text-center mb-0">No products found based on your filters.</div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
