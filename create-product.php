<?php
include_once "include/header.php";

if (!isset($_SESSION['user']) || !$user_obj->checkUserRole($_SESSION['user']['role'], [200, 300, 9999])) {
    $_SESSION['access_denied'] = "You do not have permission to access that page.";
    header('Location: dashboard.php');
    exit;
}

if (isset($_POST['add-product'])) {
    $success = insertBook($pdo, $_POST, $_FILES['Img_name']);

   if ($success) {
    echo '<div class="alert alert-success" role="alert">
            Book inserted successfully!
          </div>';
    // Optional: Redirect user
    // header("Location: success_page.php");
    // exit;
} else {
    echo '<div class="alert alert-danger" role="alert">
            Error inserting the book.
          </div>';
}
}




if (isset($_POST['add_category'])) {
    $newCategory = $_POST['new_category'] ?? '';

    if (addCategory($pdo, $newCategory)) {
        // Category added successfully, reload page to refresh dropdown
        header("Location: " . $_SERVER['PHP_SELF']);
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
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        // Optional: set error message
        $error = "Genre already exists or could not be added.";
    }
}

$genresResponse = getAllGenres($pdo);
if ($genresResponse['success']) {
    $allGenres = array_column($genresResponse['data'], 'gen_name');
} else {
    $allGenres = [];
    error_log("Failed to load genres: " . $genresResponse['error']);
}

?>

<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<ul class="nav nav-tabs" id="inventory-tabs">
    <li class="nav-item">
        <a class="nav-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>" id="search-tab" href="dashboard.php">Sök</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage === 'create-product.php' ? 'active' : '' ?>" id="add-tab" href="create-product.php">Lägg till objekt</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage === 'database-edit.php' ? 'active' : '' ?>" id="edit-database-tab" href="database-edit.php">Redigera databas</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage === 'list.php' ? 'active' : '' ?>" id="lists-tab" href="list.php">Listor</a>
    </li>
</ul>

<div class="container mt-5">
	<div class="row justify-content-center">
		<h2 class="mb-4">Add New Product</h2>
		<form action="" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
		  <div class="row mb-3">
			<div class="col-md-6">
			  <label for="title" class="form-label">Titel</label>
			  <input type="text" name="title" id="title" class="form-control" required>
			</div>

			<div class="col-md-6">
				<label for="category" class="form-label">Kategori</label>
				<div class="input-group">
					<select name="category" id="category" class="form-select" required>
						<?= renderCategorySelect($pdo, $category ?? '') ?>
					</select>
					<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
						Add
					</button>
				</div>
			</div>
		  </div>

		  <div class="row mb-3">
			<div class="col-md-6">
			  <label for="author" class="form-label">Författare</label>
			  <input type="text" name="authors[]" class="form-control mb-1" placeholder="Author 1" required>
			  <input type="text" name="authors[]" class="form-control mb-1" placeholder="Author 2 (optional)">
			  <input type="text" name="authors[]" class="form-control mb-1" placeholder="Author 3 (optional)">
			</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="genres" class="form-label">Genrer</label>
				
				<div class="custom-multiselect border rounded p-3 mb-3" id="selectBox">
					<!-- Display selected genres -->
					<div id="selectedGenresDisplay">
						<?= !empty($_POST['genres']) ? implode(', ', $_POST['genres']) : 'Select genres' ?>
					</div>

					<!-- Dropdown for genres -->
					<div id="checkboxList" class="border p-2 rounded" style="display: none; max-height: 200px; overflow-y: auto;">
						<?php foreach ($allGenres as $genre): ?>
							<label class="form-check-label mb-2">
								<input 
									type="checkbox" 
									name="genres[]" 
									class="form-check-input" 
									value="<?= htmlspecialchars($genre) ?>" 
									<?= in_array($genre, $_POST['genres'] ?? []) ? 'checked' : '' ?>
								>
								<?= htmlspecialchars($genre) ?>
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Add Genre Button -->
				<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addGenreModal">
					<i class="bi bi-plus-circle"></i> Lägg till Genre
				</button>
			</div>
		</div>

		  <div class="row mb-3">
			<div class="col-md-4">
			  <label for="shelf" class="form-label">Hyllnummer</label>
			  <input type="text" name="shelf" id="shelf" class="form-control" required>
			</div>

			<div class="col-md-4">
			  <label for="price" class="form-label">Pris (€)</label>
			  <input type="number" step="0.01" name="price" id="price" class="form-control" required>
			</div>

			<div class="col-md-4">
			  <label for="year" class="form-label">Year</label>
			  <input type="number" name="year" id="year" class="form-control" required>
			</div>
		  </div>
      <div class="row mb-3">
      <div class="col-md-6">
            <label for="prod_info" class="form-label">Produkt info</label>
            <textarea name="prod_info" id="prod_info" class="form-control" rows="1"></textarea>
        </div>

        <div class="col-md-6">
            <label for="prod_code" class="form-label">Produkt kod</label>
            <input type="text" name="prod_code" id="prod_code" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="prod_img" class="form-label">Lägg till bild</label>
            <input type="file" name="Img_name" accept="image/*">  
        </div>
      </div>
		  <div class="mb-3">
			<label for="condition" class="form-label">Kondition</label>
			<select name="condition" id="condition" class="form-select" required>
			  <option value="New">New</option>
			  <option value="Good">Good</option>
			  <option value="Fair">Fair</option>
			  <option value="Used">Used</option>
			  <option value="Damaged">Damaged</option>
			</select>
		  </div>

		  <button type="submit" class="btn btn-primary" name="add-product">Lägg till Bok</button>
		</form>
	</div>
</div>


<!-- Add Genre Modal -->
<div class="modal fade" id="addGenreModal" tabindex="-1" aria-labelledby="addGenreModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="addGenreModalLabel">Lägg till Genre</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="new_genre" class="form-label">Genre Namn</label>
            <input type="text" class="form-control" id="new_genre" name="new_genre" required>
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
          <button type="submit" name="add_genre" class="btn btn-primary">Lägg till Genre</button>
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
          <h5 class="modal-title" id="addCategoryModalLabel">Lägg till Kategori</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="newCategoryName" class="form-label">Kategori Namn</label>
            <input type="text" class="form-control" id="newCategoryName" name="new_category" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
          <button type="submit" name="add_category"  class="btn btn-primary">Spara Kategori</button>
        </div>
      </form>
    </div>
  </div>
</div>

