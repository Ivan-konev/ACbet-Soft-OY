<?php
include_once "include/header.php";




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

if(isset($_POST['deleteproduct-submit'])){
	header("Location: delete-product.php?uid={$productId}");
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
        <a class="nav-link <?php echo ($currentPage == 'edit-database.php') ? 'active' : ''; ?>" id="edit-database-tab" href="edit-database.php">Redigera databas</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'lists.php') ? 'active' : ''; ?>" id="lists-tab" href="lists.php">Listor</a>
    </li>
</ul>

<?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
    <div class="alert alert-success">Book updated successfully.</div>
<?php endif; ?>

<div class="container mt-5">
	<div class="row justify-content-center">
		<h2 class="mb-4"><?= isset($product) ? 'Edit Book' : 'Add New Book' ?></h2>
		<form action="" method="POST" class="needs-validation" novalidate>
		  <div class="row mb-3">
			<div class="col-md-6">
			  <label for="title" class="form-label">Title</label>
			  <input type="text" name="title" id="title" class="form-control" 
				value="<?= htmlspecialchars($product['prod_title'] ?? '') ?>" required>
			</div>

			<div class="col-md-6">
				<label for="category" class="form-label">Category</label>
				<div class="input-group">
					<select name="category" id="category" class="form-select" required>
						<?= renderCategorySelect($pdo, $product['cat_name'] ?? '') ?>
					</select>
					<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
						Add
					</button>
				</div>
			</div>
		  </div>

		  <div class="row mb-3">
			<div class="col-md-6">
			  <label for="author" class="form-label">Author</label>
			  <input type="text" name="author" id="author" class="form-control" 
			         value="<?= htmlspecialchars($product['author_name'] ?? '') ?>" required>
			</div>

			<div class="col-md-6">
				<label for="genre" class="form-label">Genre</label>
				<div class="input-group">
					<select name="genre" id="genre" class="form-select" required>
						<?= renderGenreSelect($pdo, $product['gen_name'] ?? '') ?>
					</select>
					<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addGenreModal">
						Add
					</button>
				</div>
			</div>
		  </div>

		  <div class="row mb-3">
			<div class="col-md-4">
			  <label for="shelf" class="form-label">Shelf Number</label>
			  <input type="text" name="shelf" id="shelf" class="form-control" 
			         value="<?= htmlspecialchars($product['shelf_nr'] ?? '') ?>" required>
			</div>

			<div class="col-md-4">
			  <label for="price" class="form-label">Price ($)</label>
			  <input type="number" step="0.01" name="price" id="price" class="form-control" 
			         value="<?= htmlspecialchars($product['prod_price'] ?? '') ?>" required>
			</div>

			<div class="col-md-4">
			  <label for="year" class="form-label">Year</label>
			  <input type="number" name="year" id="year" class="form-control" 
			         value="<?= htmlspecialchars($product['prod_year'] ?? '') ?>" required>
			</div>
		  </div>

		  <div class="row mb-3">
			<div class="col-md-6">
				<label for="prod_info" class="form-label">Product Info</label>
				<textarea name="prod_info" id="prod_info" class="form-control" rows="1"><?= htmlspecialchars($product['prod_info'] ?? '') ?></textarea>
			</div>

			<div class="col-md-6">
				<label for="prod_code" class="form-label">Product Code</label>
				<input type="text" name="prod_code" id="prod_code" class="form-control"
				       value="<?= htmlspecialchars($product['prod_code'] ?? '') ?>">
			</div>
		  </div>

		  <div class="mb-3">
			<label for="condition" class="form-label">Condition</label>
			<select name="condition" id="condition" class="form-select" required>
				<?php
				$conditions = ['New', 'Good', 'Fair', 'Used', 'Damaged'];
				foreach ($conditions as $cond) {
					$selected = (isset($product['cond_class']) && $product['cond_class'] === $cond) ? 'selected' : '';
					echo "<option value=\"$cond\" $selected>$cond</option>";
				}
				?>
			</select>
		  </div>

		  <!-- If editing, include hidden ID field -->
		  <?php if (isset($product['prod_id'])): ?>
			  <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['prod_id']) ?>">
		  <?php endif; ?>

		  <button type="submit" class="btn btn-primary" name="<?= isset($product) ? 'update-product' : 'add-product' ?>">
			  <?= isset($product) ? 'Update Book' : 'Add Book' ?>
		  </button>
		</form>
		<form action="" method="POST">
                <button type="submit" name="deleteproduct-submit" class="btn btn-danger">Delete product</button>
            </form>
	</div>
</div>


<!-- Add Genre Modal -->
<div class="modal fade" id="addGenreModal" tabindex="-1" aria-labelledby="addGenreModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="addGenreModalLabel">Add New Genre</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="new_genre" class="form-label">Genre Name</label>
            <input type="text" class="form-control" id="new_genre" name="new_genre" required>
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="add_genre" class="btn btn-primary">Add Genre</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="">
        <div class="modal-header">
          <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="newCategoryName" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="newCategoryName" name="new_category" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="add_category"  class="btn btn-primary">Save Category</button>
        </div>
      </form>
    </div>
  </div>
</div>