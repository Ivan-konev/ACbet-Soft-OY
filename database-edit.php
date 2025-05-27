<?php
include_once "include/header.php";

if (!isset($_SESSION['user']) || !$user_obj->checkUserRole($_SESSION['user']['role'], [200, 300, 9999])) {
    $_SESSION['access_denied'] = "You do not have permission to access that page.";
    header('Location: dashboard.php');
    exit;
}

if (isset($_POST['delete_category_id'])) {
    $categoryId = (int)$_POST['delete_category_id'];
    
    if (deleteCategoryById($pdo, $categoryId)) {
        header("Location: " . $_SERVER['REQUEST_URI']); // refresh
        exit;
    } else {
        $deleteError = "Could not delete category.";
    }
}

if (isset($_POST['delete_genre_id'])) {
    $genreId = (int)$_POST['delete_genre_id'];
    
    if (deleteGenreById($pdo, $genreId)) {
        header("Location: " . $_SERVER['REQUEST_URI']); // refresh to show updated list
        exit;
    } else {
        $deleteGenreError = "Could not delete genre.";
    }
}

if (isset($_POST['update_genre_id']) && isset($_POST['update_genre_name'])) {
    $genreId = (int)$_POST['update_genre_id'];
    $newName = trim($_POST['update_genre_name']);
    
    if ($newName !== '') {
        if (updateGenreName($pdo, $genreId, $newName)) {
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            $updateGenreError = "Failed to update genre.";
        }
    } else {
        $updateGenreError = "Genre name cannot be empty.";
    }
}

if (isset($_POST['new_category'])) {
    $newCategory = $_POST['new_category'];
    if (addCategory($pdo, $newCategory)) {
        header("Location: " . $_SERVER['REQUEST_URI']); // refresh page
        exit;
    } else {
        $categoryAddError = "Could not add category (already exists or invalid).";
    }
}

if (isset($_POST['new_genre'])) {
    $newGenre = $_POST['new_genre'];
    if (addGenre($pdo, $newGenre)) {
        header("Location: " . $_SERVER['REQUEST_URI']); // refresh page
        exit;
    } else {
        $genreAddError = "Could not add genre (already exists or invalid).";
    }
}


if (isset($_POST['update_category_id']) && isset($_POST['update_category_name'])) {
    $categoryId = (int)$_POST['update_category_id'];
    $newName = trim($_POST['update_category_name']);
    
    if ($newName !== '') {
        if (updateCategoryName($pdo, $categoryId, $newName)) {
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            $updateCategoryError = "Failed to update category.";
        }
    } else {
        $updateCategoryError = "Category name cannot be empty.";
    }
}
?>
<body>
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
			
 <div class="py-3" id="edit-database">
                    <!-- Categories Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Kategorier</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex mb-3 justify-content-start">
                                <form method="POST" class="d-flex mb-3 w-100">
								<input type="text" class="form-control me-2" name="new_category" placeholder="Ny kategori" required>
								<button type="submit" class="btn btn-primary">Lägg till</button>
							</form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Kategorinamn</th>
                                            <th width="150px">Åtgärder</th>
                                        </tr>
                                    </thead>
                                    <tbody id="categories-list">
                                       <?= renderCategoryTableRows($pdo) ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- Genres Section -->
                    <div class="card mb-4">
						<div class="card-header bg-light">
							<h5 class="mb-0">Genrer</h5>
						</div>
						<div class="card-body">
							<div class="d-flex mb-3 justify-content-start">
								<form method="POST" class="d-flex mb-3 w-100">
									<input type="text" class="form-control me-2" name="new_genre" placeholder="Ny genre" required>
									<button type="submit" class="btn btn-primary">Lägg till</button>
								</form>
							</div>
						
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Genrenamn</th>
                                            <th width="150px">Åtgärder</th>
                                        </tr>
                                    </thead>
                                    <tbody id="genres-list">
                                        <?= renderGenreTableRows($pdo) ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
</div>



<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteModalLabel">Bekräfta borttagning</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Stäng"></button>
        </div>
        <div class="modal-body">
          Är du säker på att du vill ta bort genren <strong id="genre-name"></strong>?
          <input type="hidden" name="delete_genre_id" id="delete-genre-id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
          <button type="submit" class="btn btn-danger">Ta bort</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editGenreModal" tabindex="-1" aria-labelledby="editGenreModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="editGenreModalLabel">Redigera genre</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Stäng"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="update_genre_id" id="update-genre-id">
          <div class="mb-3">
            <label for="update-genre-name" class="form-label">Genrenamn</label>
            <input type="text" name="update_genre_name" id="update-genre-name" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
          <button type="submit" class="btn btn-primary">Spara ändringar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="editCategoryModalLabel">Redigera kategori</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Stäng"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="update_category_id" id="update-category-id">
          <div class="mb-3">
            <label for="update-category-name" class="form-label">Kategorinamn</label>
            <input type="text" name="update_category_name" id="update-category-name" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
          <button type="submit" class="btn btn-primary">Spara ändringar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="confirmDeleteCategoryModal" tabindex="-1" aria-labelledby="confirmDeleteCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteCategoryModalLabel">Ta bort kategori</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Stäng"></button>
        </div>
        <div class="modal-body">
          <p>Är du säker på att du vill ta bort kategorin <strong id="delete-category-name"></strong>?</p>
          <input type="hidden" name="delete_category_id" id="delete-category-id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
          <button type="submit" class="btn btn-danger">Ta bort</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.getElementById('editGenreModal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var genreId = button.getAttribute('data-genre-id');
    var genreName = button.getAttribute('data-genre-name');

    var inputId = this.querySelector('#update-genre-id');
    var inputName = this.querySelector('#update-genre-name');

    inputId.value = genreId;
    inputName.value = genreName;
});

document.getElementById('confirmDeleteModal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; // Button that triggered the modal
    var genreId = button.getAttribute('data-genre-id');
    var genreName = button.getAttribute('data-genre-name');

    // Update modal content
    var inputId = this.querySelector('#delete-genre-id');
    var nameText = this.querySelector('#genre-name');

    inputId.value = genreId;
    nameText.textContent = genreName;
});

document.getElementById('editCategoryModal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var categoryId = button.getAttribute('data-category-id');
    var categoryName = button.getAttribute('data-category-name');

    var inputId = this.querySelector('#update-category-id');
    var inputName = this.querySelector('#update-category-name');

    inputId.value = categoryId;
    inputName.value = categoryName;
});

document.getElementById('confirmDeleteCategoryModal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var categoryId = button.getAttribute('data-category-id');
    var categoryName = button.getAttribute('data-category-name');

    var inputId = this.querySelector('#delete-category-id');
    var nameSpan = this.querySelector('#delete-category-name');

    inputId.value = categoryId;
    nameSpan.textContent = categoryName;
});
</script>

</body>