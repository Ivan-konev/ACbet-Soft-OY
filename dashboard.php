<?php
include_once "include/header.php";

if(!$user_obj->checkLoginStatus($_SESSION['user'] ['id'])){
	header("Location: login.php");
	exit;
}
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$books = getBooks(
    $pdo, 
    $category, 
    $search, 
    '',          // year_from
    'dashboard', // page or context
    '',          // year_to
    '',          // price_min
    '',          // price_max
    '',          // status
    'prod_title', // sort
    'ASC'        // order
);  
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

if (isset($_SESSION['access_denied'])) {
    $message = $_SESSION['access_denied'];
    unset($_SESSION['access_denied']); // clear it so it only shows once
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            // assuming you're using Bootstrap modal or similar
            let modal = new bootstrap.Modal(document.getElementById('accessDeniedModal'));
            modal.show();
        });
    </script>";
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
                    <a class="nav-link" id="edit-database-tab"  href="database-edit.php">Redigera
                        databas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="lists-tab"  href="list.php">Listor</a>
                </li>
            </ul>

            <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
        <div class="alert alert-success"><p class="text-center m-0">Bok updaterad</p></div>
             <?php endif; ?>

    <?php if(isset($_GET['deleted'])): ?>
    <div class="alert alert-success"><p class="text-center m-0">Produkt raderad</p></div>
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
                    <option value="">Alla Kategorier</option>
                    <?php foreach ($allCategories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['cat_name']) ?>" <?= isset($category) && $category === $cat['cat_name'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['cat_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Filtrera böcker</button>
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
                            <td><?= htmlspecialchars($book['author_names']) ?></td>
                            <td><?= htmlspecialchars($book['cat_name']) ?></td>
                            <td>$<?= number_format($book['prod_price'], 2) ?></td>
                            <td><?= htmlspecialchars($book['prod_year']) ?></td>
                            <td>
                                <span class="card-status">
                                    <?= $book['prod_status'] == 1 ? '<p class="text-success">Tillgänglig</p>' : '<p class="text-danger">Såld</p>' ?>
                                </span>
                            </td>
                            <td>
                               <form method="POST" onClick="event.stopPropagation();" style="display: inline;">
									<input type="hidden" name="product_id" value="<?= $book['prod_id'] ?>">
									<input type="hidden" name="current_status" value="<?= $book['prod_status'] ?>">

									<label class="switch">
										<input type="checkbox" name="toggle_status" onchange="this.form.submit()" <?= $book['prod_status'] ? 'checked' : '' ?>>
										<span class="slider round"></span>
									</label>
								</form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">
                            <div class="alert alert-warning text-center mb-0">Inga produkter baserad på filtering</div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="accessDeniedModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Åtkomst förbjuden</h5>
      </div>
      <div class="modal-body">
        <?= htmlspecialchars($message ?? '') ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #f44336;
  transition: .4s;
  border-radius: 24px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #4caf50;
}

input:checked + .slider:before {
  transform: translateX(26px);
}
</style>
