<?php



include_once "include/header.php";

if (!isset($_SESSION['user']) || !$user_obj->checkUserRole($_SESSION['user']['role'], [200, 300, 9999])) {
    $_SESSION['access_denied'] = "You do not have permission to access that page.";
    header('Location: dashboard.php');
    exit;
}

// Handle export via POST, not GET
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_csv']) && $_POST['export_csv'] == '1') {
    // Pass filter parameters explicitly from POST or fallback to GET (filters are from GET)
    $category   = $_POST['category'] ?? $_GET['category'] ?? '';
    $search     = $_POST['search']   ?? $_GET['search']   ?? '';
    $year_from  = $_POST['year_from'] ?? $_GET['year_from'] ?? '';
    $year_to    = $_POST['year_to'] ?? $_GET['year_to'] ?? '';
    $price_min  = $_POST['price_min'] ?? $_GET['price_min'] ?? '';
    $price_max  = $_POST['price_max'] ?? $_GET['price_max'] ?? '';
    $status     = $_POST['status'] ?? $_GET['status'] ?? '';
    // Make sure to pass sort/order as well if you want (here defaulted)
    exportBooksAsCSV($pdo, $category, $search, $year_from, $year_to, $price_min, $price_max, $status);
    exit;  // Important to stop script after export
}

// GET filters (for display & fetching books)
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$year_from = $_GET['year_from'] ?? '';
$year_to = $_GET['year_to'] ?? '';
$price_min = $_GET['price_min'] ?? '';
$price_max = $_GET['price_max'] ?? '';
$status = $_GET['status'] ?? '';
$sort = $_GET['sort'] ?? 'prod_title';
$order = $_GET['order'] ?? 'ASC';

// Fetch data using the correct getBooks call (with sort/order params)
$result = getBooks($pdo, $category, $search, $year_from, 'List', $year_to, $price_min, $price_max, $status, $sort, $order);
$bookResult = $result['success'] ? $result['data'] : [];

$catResult = getAllCategories($pdo);
$allCategories = $catResult['success'] ? $catResult['data'] : [];

// Handle status toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['current_status'])) {
    $productId = (int) $_POST['product_id'];
    $newStatus = $_POST['current_status'] == 1 ? 0 : 1;
    $result = updateProductStatus($pdo, $productId, $newStatus);
    echo "<p>{$result['message']}</p>";
}

// Access denied modal
if (isset($_SESSION['access_denied'])) {
    $message = $_SESSION['access_denied'];
    unset($_SESSION['access_denied']);
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            let modal = new bootstrap.Modal(document.getElementById('accessDeniedModal'));
            modal.show();
        });
    </script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selected_ids'])) {
    $selected_ids = $_POST['selected_ids'];
    $status = $_POST['status'] ?? null;
    $price = $_POST['price'] ?? null;
    $year = $_POST['year'] ?? null;

    bulkUpdateBooks($pdo, $selected_ids, $status, $price, $year);

    header('Location: list.php?updated=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_delete'])) {
    $deleteIds = explode(',', $_POST['delete_ids']);
	var_dump($deleteIds);

    // Validate and sanitize IDs
    $deleteIds = array_filter($deleteIds, fn($id) => is_numeric($id));

    $results = [];
    foreach ($deleteIds as $id) {
        $result = deleteProduct($pdo, (int)$id);  // pass $pdo and product ID
        $results[] = $result;
    }

    // Optionally, check if all deletes were successful
    $allSuccess = array_reduce($results, fn($carry, $item) => $carry && $item['success'], true);

    if ($allSuccess) {
        // redirect or show success
        header("Location: " . $_SERVER['PHP_SELF'] . "?deleted=1");
        exit;
    } else {
        // handle partial failure, maybe collect error messages
        $errors = array_filter($results, fn($res) => !$res['success']);
        // You could display $errors on the page
    }
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

<?php if ((isset($_GET['updated']) && $_GET['updated'] == 1) || isset($_GET['deleted'])): ?>
    <div id="flash-alert" class="alert alert-success text-center m-0">
        <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
            Book updated successfully.
        <?php elseif (isset($_GET['deleted'])): ?>
            Product deleted successfully.
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const alert = document.getElementById('flash-alert');
            if (!alert) return;

            // Wait 3 seconds, then fade out
            setTimeout(() => {
                alert.style.transition = 'opacity 1s ease';
                alert.style.opacity = '0';

                // After fade out, remove from DOM
                setTimeout(() => alert.remove(), 1000);
            }, 3000);
        });
    </script>
<?php endif; ?>

<div class="container my-5">
    <!-- Filters Card -->
    <div class="card mb-4">
        <div class="card-header">
            <strong>Filter Products</strong>
        </div>
        <div class="card-body">
            <form method="GET" action="">
                <div class="row g-3">
                    <!-- Search -->
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search Products/Author/Genre" value="<?= htmlspecialchars($search) ?>">
                    </div>

                    <!-- Category -->
                    <div class="col-md-4">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($allCategories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['cat_name']) ?>" <?= $category === $cat['cat_name'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['cat_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="1" <?= $status === '1' ? 'selected' : '' ?>>Tillgänglig</option>
                            <option value="0" <?= $status === '0' ? 'selected' : '' ?>>Såld</option>
                        </select>
                    </div>

                    <!-- Year Range -->
                    <div class="col-md-3">
                        <input type="number" name="year_from" class="form-control" placeholder="Year From" value="<?= htmlspecialchars($year_from) ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="year_to" class="form-control" placeholder="Year To" value="<?= htmlspecialchars($year_to) ?>">
                    </div>

                    <!-- Price Range -->
                    <div class="col-md-3">
                        <input type="number" step="0.01" name="price_min" class="form-control" placeholder="Min Price" value="<?= htmlspecialchars($price_min) ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" name="price_max" class="form-control" placeholder="Max Price" value="<?= htmlspecialchars($price_max) ?>">
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>

                        <!-- Separate form for CSV export -->
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">
							Exportera
						</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Product Table -->
    <div class="table-responsive mb-4">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>Id</th>
                    <th>Titel</th>
                    <th>Författare</th>
                    <th>Kategori</th>
                    <th>Pris</th>
                    <th>År</th>
                    <th>Status</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bookResult)): ?>
                    <?php foreach ($bookResult as $book): ?>
                        <tr data-id="<?= $book['prod_id'] ?>">
                            <td><input type="checkbox" class="select-book" name="selected_books[]" value="<?= $book['prod_id'] ?>"></td>
                            <td><?= htmlspecialchars($book['prod_id']) ?></td>
                            <td><?= htmlspecialchars($book['prod_title']) ?></td>
                            <td><?= htmlspecialchars($book['author_names']) ?></td>
                            <td><?= htmlspecialchars($book['cat_name']) ?></td>
                            <td>$<?= number_format($book['prod_price'], 2) ?></td>
                            <td><?= htmlspecialchars($book['prod_year']) ?></td>
                            <td>
                                <?= $book['prod_status'] == 1
                                    ? '<span class="badge bg-success">Tillgänglig</span>'
                                    : '<span class="badge bg-danger">Såld</span>' ?>
                            </td>
                            
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center">No books found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bulk Edit Panel -->
    <div id="bulk-edit-panel" class="card p-3 mb-3 d-none" >
        <form method="POST" action="">
            <input type="hidden" name="selected_ids" id="selected-ids">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="status" class="form-label">Ny status:</label>
                    <select name="status" class="form-select">
                        <option value="">Ingen ändring</option>
                        <option value="1">Tillgänglig</option>
                        <option value="0">Såld</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="price" class="form-label">Ny pris:</label>
                    <input type="number" name="price" step="0.01" class="form-control" placeholder="Ex. 19.99">
                </div>
                <div class="col-md-4">
                    <label for="year" class="form-label">Nytt år:</label>
                    <input type="number" name="year" class="form-control" placeholder="Ex. 2022">
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">Uppdatera valda</button>
                </div>
            </div>
        </form>
    </div>
	
	<!-- Bulk Delete Panel -->
		<div id="bulk-delete-panel" class="card p-3 mb-3 d-none">
			<form method="POST" action="">
				<input type="hidden" name="delete_ids" id="delete-ids">
				<div class="row align-items-center">
					<div class="col">
						<p class="mb-0">Är du säker på att du vill ta bort de valda produkterna?</p>
					</div>
					<div class="col-auto text-end">
						<!-- Trigger button -->
						<button type="button" class="btn btn-danger" id="confirm-delete-button" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
							Ta bort valda
						</button>
					</div>
				</div>
			</form>
		</div>
	
</div>


<div class="modal fade" id="accessDeniedModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Access Denied</h5></div>
      <div class="modal-body"><?= htmlspecialchars($message ?? '') ?></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form method="POST" action="">
				<input type="hidden" name="delete_ids" id="modal-delete-ids">
				<div class="modal-header">
					<h5 class="modal-title" id="confirmDeleteLabel">Bekräfta borttagning</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Stäng"></button>
				</div>
				<div class="modal-body">
					<p>ÄR du SÄKER på att du vill ta bort de VALDA produkterna?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
					<button type="submit" name="bulk_delete"  class="btn btn-danger">Ja, ta bort</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exportModalLabel">Exportera data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Stäng"></button>
      </div>
      <div class="modal-body">

        <!-- CSV Export Form -->
        <form method="POST" action="" id="export-csv-form" class="mb-2">
          <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
          <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
          <input type="hidden" name="year_from" value="<?= htmlspecialchars($year_from) ?>">
          <input type="hidden" name="year_to" value="<?= htmlspecialchars($year_to) ?>">
          <input type="hidden" name="price_min" value="<?= htmlspecialchars($price_min) ?>">
          <input type="hidden" name="price_max" value="<?= htmlspecialchars($price_max) ?>">
          <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
          <button type="submit" name="export_csv" value="1" class="btn btn-success w-100">Exportera som CSV</button>
        </form>

        <!-- PDF Export Form -->
        <form method="POST" action="export_pdf.php" id="export-pdf-form">
		  <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
		  <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
		  <input type="hidden" name="year_from" value="<?= htmlspecialchars($year_from) ?>">
		  <input type="hidden" name="year_to" value="<?= htmlspecialchars($year_to) ?>">
		  <input type="hidden" name="price_min" value="<?= htmlspecialchars($price_min) ?>">
		  <input type="hidden" name="price_max" value="<?= htmlspecialchars($price_max) ?>">
		  <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
		  <button type="submit" class="btn btn-danger w-100">Exportera som PDF</button>
		</form>

      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.select-book');
    const selectAll = document.getElementById('select-all');

    const bulkEditPanel = document.getElementById('bulk-edit-panel');
    const bulkDeletePanel = document.getElementById('bulk-delete-panel');

    const selectedIdsInput = document.getElementById('selected-ids');
    const deleteIdsInput = document.getElementById('delete-ids');

    function updateBulkPanels() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked);
        const selectedIds = selected.map(cb => cb.value).join(',');

        if (selected.length > 0) {
            bulkEditPanel.classList.remove('d-none');
            bulkDeletePanel.classList.remove('d-none');
            selectedIdsInput.value = selectedIds;
            deleteIdsInput.value = selectedIds;
        } else {
            bulkEditPanel.classList.add('d-none');
            bulkDeletePanel.classList.add('d-none');
            selectedIdsInput.value = '';
            deleteIdsInput.value = '';
        }
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateBulkPanels));
    selectAll.addEventListener('change', () => {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBulkPanels();
    });
});

document.getElementById('confirm-delete-button').addEventListener('click', function () {
	const selected = Array.from(document.querySelectorAll('input[name="selected_books[]"]:checked')).map(cb => cb.value);
	document.getElementById('modal-delete-ids').value = selected.join(',');
});
</script>

<script>
document.getElementById('export-pdf-form').addEventListener('submit', async function(e) {
  e.preventDefault();  // Stop normal form submit

  const form = e.target;
  const formData = new FormData(form);

  // Send AJAX request to PHP export script
  const response = await fetch(form.action, {
    method: 'POST',
    body: formData
  });

  if (!response.ok) {
  console.error("Status:", response.status);      // t.ex. 404, 500
  console.error("StatusText:", response.statusText);
  alert('Något gick fel vid exporten.');
  return;
}


  // Receive response as blob (PDF file)
  const blob = await response.blob();

  // Create a temporary URL for the PDF blob and trigger download
  const url = window.URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = 'exported_books.pdf';  // Filename for download
  document.body.appendChild(a);
  a.click();
  a.remove();
  window.URL.revokeObjectURL(url);
});
</script>

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