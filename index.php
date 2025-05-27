<?php

$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'sv';
$_SESSION['lang'] = $lang;

// Ladda header-text
if ($lang === 'fi') {
    include 'lang/header-fi.php';
    include 'lang/footer-fi.php';
} else {
    include 'lang/header-sv.php';
    include 'lang/footer-sv.php';
}
require "include/header-visitor.php";


$langCode = $_GET['lang'] ?? 'sv';
if (!in_array($langCode, ['sv', 'fi'])) $langCode = 'sv';

include_once "lang/$langCode.php";
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

// Sortering
$sort = $_GET['sort'] ?? 'prod_title';
$order = $_GET['order'] ?? 'asc';

$allowedSortColumns = ['prod_title', 'author_name', 'cat_name', 'genre_name', 'cond_class', 'prod_price'];
if (!in_array($sort, $allowedSortColumns)) {
    $sort = 'prod_title';
}

$allowedOrderDirections = ['asc', 'desc'];
if (!in_array($order, $allowedOrderDirections)) {
    $order = 'asc';
}

$books = getBooks($pdo, $category, $search, '', 'index', '', '', '', '', $sort, $order);
$bookResult = $books['success'] ? $books['data'] : [];

$catResult = getAllCategories($pdo);
$allCategories = $catResult['success'] ? $catResult['data'] : [];
?>

<div class="hero-container position-relative">
    <img src="hero.webp" alt="Karis Antikvariat" class="hero-image w-100">
    <div class="container">
        <div class="hero-content position-absolute">
            <div class="hero-text-container p-5 rounded text-center">
                <h1><?php echo $lang['welcome_text']; ?></h1>
                <p class="lead"><?php echo $lang['shop_description']; ?></p>
                <a href="#browse" class="btn btn-primary btn-lg mt-3"><?php echo $lang['browse_button']; ?></a>
            </div>
        </div>
    </div>
</div>

<div id="homepage" class="container my-4">
    <section id="about" class="my-5">
        <div class="row">
            <div class="col-lg-6">
                <h2><?php echo $lang['about_title']; ?></h2>
                <p><?php echo $lang['about_text']; ?></p>
            </div>
            <div class="col-lg-6">
                <div id="storeCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#storeCarousel" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#storeCarousel" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#storeCarousel" data-bs-slide-to="2"></button>
                        <button type="button" data-bs-target="#storeCarousel" data-bs-slide-to="3"></button>
                    </div>
                    <div class="carousel-inner rounded">
                        <div class="carousel-item active"><img src="img/bild1.webp" class="d-block w-100" alt=""></div>
                        <div class="carousel-item"><img src="img/bild2.webp" class="d-block w-100" alt=""></div>
                        <div class="carousel-item"><img src="img/bild3.webp" class="d-block w-100" alt=""></div>
                        <div class="carousel-item"><img src="img/bild4.webp" class="d-block w-100" alt=""></div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#storeCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden"><?php echo $lang['previous']; ?></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#storeCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden"><?php echo $lang['next']; ?></span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="container mt-5">
        <h2 id="browse"><?php echo $lang['search_title']; ?></h2>
        <form id="searchForm" class="p-4">
            <div class="row align-items-end g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label"><?php echo $lang['search_label']; ?></label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="<?php echo $lang['search_placeholder']; ?>" value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-4">
                    <label for="category" class="form-label"><?php echo $lang['category_label']; ?></label>
                    <select id="category" name="category" class="form-select">
                        <option value=""><?php echo $lang['all_categories']; ?></option>
                        <?php foreach ($allCategories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['cat_name']) ?>" <?= $category === $cat['cat_name'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['cat_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><?php echo $lang['search_button']; ?></button>
                </div>
            </div>
        </form>
    </section>
</div>


    <section class="container mt-5">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-hover" id="inventory-table">
                <thead class="table-light">
                    <tr>
                        <th class="sortable" data-sort="prod_title" data-order="asc"><?php echo $lang['product_title']; ?> <span class="sort-arrow">↕</span></th>
                        <th class="sortable" data-sort="author_name" data-order="asc"><?php echo $lang['author_name']; ?> <span class="sort-arrow">↕</span></th>
                        <th class="sortable" data-sort="cat_name" data-order="asc"><?php echo $lang['category']; ?> <span class="sort-arrow">↕</span></th>
                        <th class="sortable" data-sort="genre_name" data-order="asc"><?php echo $lang['genre']; ?> <span class="sort-arrow">↕</span></th>
                        <th class="sortable" data-sort="cond_class" data-order="asc"><?php echo $lang['condition']; ?> <span class="sort-arrow">↕</span></th>
                        <th class="sortable" data-sort="prod_price" data-order="asc"><?php echo $lang['price']; ?> <span class="sort-arrow">↕</span></th>
                    </tr>
                </thead>


                    <tbody id="product-table-body">
                        <?php if (!empty($bookResult)): ?>
                            <?php foreach ($bookResult as $book): ?>
                                <tr onclick="window.location='product-detail.php?id=<?= $book['prod_id'] ?>';" style="cursor:pointer;">
                                    <td><?= htmlspecialchars($book['prod_title']) ?></td>
                                    <td><?= htmlspecialchars($book['author_names']) ?></td>
                                    <td><?= htmlspecialchars($book['cat_name']) ?></td>
                                    <td><?= htmlspecialchars($book['genre_names']) ?></td>
                                    <td><?= htmlspecialchars($book['cond_class']) ?></td>
                                    <td>€<?= number_format($book['prod_price'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center"><strong><?php echo $lang['no_products_found']; ?></strong><br><?php echo $lang['try_filter']; ?></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="container my-5">
        <h2 class="mb-4"><?php echo $lang['rarities']; ?></h2>
        <div class="row g-4">
        <?php
$category = 'Rariteter';
$books = getProductsByCategory($category, $pdo, "index" );

if (!empty($books)) {
    foreach ($books as $book) {
?>
        <div class="col-md-6 col-lg-4">
            <!-- Länk till produktens detaljerad sida -->
            <a href="product-detail.php?id=<?php echo $book['prod_id']; ?>" class="text-decoration-none">

                <div class="card h-100 shadow-sm border-0 position-relative">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0"><?= htmlspecialchars($book['prod_title']) ?></h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong><?php echo $lang['author']; ?>:</strong> <?= htmlspecialchars($book['authors']) ?></p>
                        <p class="mb-1"><strong><?php echo $lang['genre']; ?>:</strong> <?= htmlspecialchars($book['genres']) ?></p>
                        <p class="mb-1"><strong><?php echo $lang['condition']; ?>:</strong> <?= htmlspecialchars($book['prod_condition']) ?></p>
                        <p class="fw-bold text-success mt-2">€<?= number_format($book['prod_price'], 2) ?></p>
                    </div>
                </div>
            </a>
        </div>
<?php
    }
} else {
    echo "<p class='col-12'>" . $lang["no_rarities_found"] . "</p>";
}
?>

        </div>
    </section>
</div>

<script>
   document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const params = new URLSearchParams(formData).toString();

    fetch('ajax/fetch-products.php?' + params)
        .then(response => response.text())
        .then(html => {
            document.querySelector('#product-table-body').innerHTML = html;
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
});

document.querySelectorAll('.sortable').forEach(th => {
    th.addEventListener('click', function (e) {
        e.preventDefault();

        const sort = this.dataset.sort;
        let order = this.dataset.order;

        // Toggle order BEFORE sending request so it's intuitive
        order = (order === 'asc') ? 'desc' : 'asc';

        const search = document.querySelector('#search')?.value || '';
        const category = document.querySelector('#category')?.value || '';

        const params = new URLSearchParams({
            sort,
            order,
            search,
            category
        });

        fetch('ajax/fetch-products.php?' + params.toString())
            .then(response => response.text())
            .then(html => {
                document.querySelector('#product-table-body').innerHTML = html;

                // Reset all arrows to neutral and orders to asc
                document.querySelectorAll('.sortable').forEach(header => {
                    const arrow = header.querySelector('.sort-arrow');
                    arrow.textContent = '↕';
                    header.dataset.order = 'asc';
                });

                // Set arrow on clicked column
                const clickedArrow = this.querySelector('.sort-arrow');
                clickedArrow.textContent = (order === 'asc') ? '▲' : '▼';

                // Update dataset order with toggled value
                this.dataset.order = order;
            })
            .catch(error => {
                console.error('Sort error:', error);
            });
    });
});
</script>

<?php require "include/footer.php"; ?>
