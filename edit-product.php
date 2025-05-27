<?php
include_once "include/header.php";

if (!isset($_SESSION['user']) || !$user_obj->checkUserRole($_SESSION['user']['role'], [200, 300, 9999])) {
    $_SESSION['access_denied'] = "You do not have permission to access that page.";
    header('Location: dashboard.php');
    exit;
}

// Ensure the product ID is valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Invalid or missing ID
    echo "<div class='alert alert-danger'>Invalid product ID.</div>";
    exit;
}

$productId = (int)$_GET['id'];
$response = getProductById($pdo, $productId);


if (!$response['success']) {
    // Log the actual error (optional)
    error_log("Product fetch failed: " . $response['error']);
    
    // Show user-friendly message
    echo "<div class='alert alert-warning'>Product not found or could not be loaded.</div>";
    exit;
}

$product = $response['data'];

$genresResponse = getAllGenres($pdo);
if ($genresResponse['success']) {
    // Flatten the array to just get genre names
    $allGenres = array_column($genresResponse['data'], 'gen_name');
} else {
    $allGenres = []; // Fallback to avoid crashing
    error_log("Failed to load genres: " . $genresResponse['error']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-product'])) {
    if (updateBook($pdo, $_POST)) {
        // Redirect to same page with success flag
        header("Location: edit-product.php?id=" . $_POST['product_id'] . "&updated=1");
        exit;
    } else {
        $error = "Error updating book.";
    }
}

if (isset($_POST['add_category'])) {
    $newCategory = $_POST['new_category'] ?? '';

    if (addCategory($pdo, $newCategory)) {
        // Category added successfully, reload page to refresh dropdown
        header("Location: edit-product.php?id=$productId");
        exit;
    } else {
        // Optionally set an error message (if you want)
        $error = "Category already exists or could not be added.";
    }
}

if (isset($_POST['add_genre'])) {
    $newGenre = $_POST['new_genre'] ?? '';

    if (addGenre($pdo, $newGenre)) {
        // Genre added successfully, reload page to refresh dropdown
        header("Location: edit-product.php?id=$productId");
        exit;
    } else {
        // Optional: set error message
        $error = "Genre already exists or could not be added.";
    }
}

if (isset($_POST['deleteproduct-submit'])) {
    $deleteProductId = $_POST['delete_product_id'] ?? null;

    if ($deleteProductId && is_numeric($deleteProductId)) {
        // Call the function to delete the product from the database
        $deleteSuccess = deleteProduct($pdo, $deleteProductId);

        if ($deleteSuccess) {
            // Redirect to another page or the same page to show success
            header("Location: dashboard.php?deleted=1");
            exit;
        } else {
            $error = "There was an error deleting the product.";
        }
    }
}



?>




<ul class="nav nav-tabs" id="inventory-tabs">
    <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'dashboard.php') ? 'active' : ''; ?>" id="search-tab" href="dashboard.php">Sök</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'create-product.php') ? 'active' : ''; ?>" id="add-tab" href="create-product.php">Lägg till objekt</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'edit-database.php') ? 'active' : ''; ?>" id="edit-database-tab" href="database-edit.php">Redigera databas</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'lists.php') ? 'active' : ''; ?>" id="lists-tab" href="list.php">Listor</a>
    </li>
</ul>

<div class="container mt-5">
    <div class="row justify-content-center">
        <h2 class="mb-4"><?= isset($product) ? 'Redigera bok' : 'Lägg till ny bok' ?></h2>
        <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Produkten har uppdaterats
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Stäng"></button>
            </div>
        <?php endif; ?>
        <form action="" method="POST" class="needs-validation" novalidate>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Titel</label>
                    <input type="text" name="title" id="title" class="form-control"
                           value="<?= htmlspecialchars($product['prod_title'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="category" class="form-label">Kategori</label>
                    <div class="input-group">
                        <select name="category" id="category" class="form-select" required>
                            <?= renderCategorySelect($pdo, $product['cat_name'] ?? '') ?>
                        </select>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            Lägg till
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="author" class="form-label">Författare</label>
                    <?php
                    $authors = $product['authors'] ?? [];
                    $maxAuthors = 3;
                    for ($i = 0; $i < $maxAuthors; $i++) {
                        $value = isset($authors[$i]) ? htmlspecialchars($authors[$i]) : '';
                        $placeholder = "Författare " . ($i + 1) . ($i === 0 ? '' : ' (valfritt)');
                        $required = $i === 0 ? 'required' : '';
                        echo "<input type=\"text\" name=\"authors[]\" class=\"form-control mb-1\" placeholder=\"$placeholder\" value=\"$value\" $required>";
                    }
                    ?>
                </div>

                <div class="col-md-6">
                    <label for="genres" class="form-label">Genrer</label>
                    <div class="custom-multiselect border rounded p-3 mb-3" id="selectBox">
                        <div id="selectedGenresDisplay">
                            <?= !empty($product['genres']) ? implode(', ', $product['genres']) : 'Välj genrer' ?>
                        </div>
                        <div id="checkboxList" class="border p-2 rounded" style="display: none; max-height: 200px; overflow-y: auto;">
                            <?php foreach ($allGenres as $genre): ?>
                                <label class="form-check-label mb-2">
                                    <input 
                                        type="checkbox" 
                                        name="genres[]" 
                                        class="form-check-input" 
                                        value="<?= htmlspecialchars($genre) ?>" 
                                        <?= in_array($genre, $product['genres']) ? 'checked' : '' ?>
                                    >
                                    <?= htmlspecialchars($genre) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addGenreModal">
                        <i class="bi bi-plus-circle"></i> Lägg till genre
                    </button>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="shelf" class="form-label">Hyllnummer</label>
                    <input type="text" name="shelf" id="shelf" class="form-control"
                           value="<?= htmlspecialchars($product['shelf_nr'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="price" class="form-label">Pris (€)</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control"
                           value="<?= htmlspecialchars($product['prod_price'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="year" class="form-label">År</label>
                    <input type="number" name="year" id="year" class="form-control"
                           value="<?= htmlspecialchars($product['prod_year'] ?? '') ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="prod_info" class="form-label">Produktinfo</label>
                    <textarea name="prod_info" id="prod_info" class="form-control" rows="1"><?= htmlspecialchars($product['prod_info'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label for="prod_code" class="form-label">Produktkod</label>
                    <input type="text" name="prod_code" id="prod_code" class="form-control"
                           value="<?= htmlspecialchars($product['prod_code'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="condition" class="form-label">Skick</label>
                <select name="condition" id="condition" class="form-select" required>
                    <?php
                    $conditions = ['Nyskick', 'Bra', 'Okej', 'Begagnad', 'Skadad'];
                    $originalConditions = ['New', 'Good', 'Fair', 'Used', 'Damaged'];
                    foreach ($originalConditions as $index => $cond) {
                        $selected = (isset($product['cond_class']) && $product['cond_class'] === $cond) ? 'selected' : '';
                        echo "<option value=\"$cond\" $selected>{$conditions[$index]}</option>";
                    }
                    ?>
                </select>
            </div>

            <?php if (isset($product['prod_id'])): ?>
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['prod_id']) ?>">
            <?php endif; ?>

            <button type="submit" class="btn btn-primary" name="<?= isset($product) ? 'update-product' : 'add-product' ?>">
                <?= isset($product) ? 'Uppdatera bok' : 'Lägg till bok' ?>
            </button>
        </form>

        <div class="container mt-3">
            <?php if (isset($productFeedback)) : ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($productFeedback); ?>
                </div>
            <?php endif; ?>

            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                Radera produkt
            </button>
        </div>
    </div>
</div>

<!-- Genre Modal -->
<div class="modal fade" id="addGenreModal" tabindex="-1" aria-labelledby="addGenreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGenreModalLabel">Lägg till genre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Stäng"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="new_genre" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Stäng</button>
                    <button type="submit" name="add_genre" class="btn btn-primary">Lägg till</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Kategori Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Lägg till kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Stäng"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="new_category" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Stäng</button>
                    <button type="submit" name="add_category" class="btn btn-primary">Lägg till</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Delete Product Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this product?</p>
          <input type="hidden" name="delete_product_id" value="<?= $productId ?>">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="deleteproduct-submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>