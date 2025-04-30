<?php
require "include/header-visitor.php";

$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$books = getBooks($pdo, $category, $search, "index");
$bookResult = $books['success'] ? $books['data'] : [];

$catResult = getAllCategories($pdo);
$allCategories = $catResult['success'] ? $catResult['data'] : [];
?>


    <div class="hero-container position-relative">
            <img src="hero.webp" alt="Karis Antikvariat" class="hero-image w-100">
            <div class="container">
                <div class="hero-content position-absolute">
                    <div class="hero-text-container p-5 rounded text-center">
                        <h1>Välkommen till Karis Antikvariat</h1>
                        <p class="lead">Din lokala antikvariat med ett brett utbud av böcker, musik och samlarobjekt</p>
                        <a href="#browse" class="btn btn-primary btn-lg mt-3">Bläddra i vårt sortiment</a>
                    </div>
                </div>
            </div>
        </div>

<div id="homepage" class="container my-4">
        <section id="about" class="my-5">
                    <div class="row">
                        <div class="col-lg-6">
                            <h2>Om vår butik</h2>
                            <p>Karis Antikvariat har ett mycket brett utbud av böcker, men vi har specialiserat oss på finlandssvenska författare, lokalhistoria och sjöfart.</p>
                            <p>Vi har dessutom barn- och ungdomsböcker, serietidningar, seriealbum, DVD-filmer, CD- och vinylskivor samt samlarobjekt.</p>
                            <p>Välkommen att besöka oss och upptäck vårt unika utbud!</p>
                        </div>
                        <div class="col-lg-6">
                            <!-- Image Carousel -->
                            <div id="storeCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#storeCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#storeCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                    <button type="button" data-bs-target="#storeCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                    <button type="button" data-bs-target="#storeCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
                                </div>
                                <div class="carousel-inner rounded">
                                    <div class="carousel-item active">
                                        <img src="img/bild1.webp" class="d-block w-100" alt="Karis Antikvariat butiksbild 1">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="img/bild2.webp" class="d-block w-100" alt="Karis Antikvariat butiksbild 2">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="img/bild3.webp" class="d-block w-100" alt="Karis Antikvariat butiksbild 3">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="img/bild4.webp" class="d-block w-100" alt="Karis Antikvariat butiksbild 4">
                                    </div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#storeCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Föregående</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#storeCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Nästa</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>



        <!-- Search and Filter Section -->
    <section class="container mt-5">
    <h2 id="browse">Sök i vårt sortiment</h2>
        <form method="GET" action="index.php" class="p-4">
            <div class="row align-items-end g-3">
                <!-- Search -->
                <div class="col-md-6">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Search products, authors, or genres" value="<?= htmlspecialchars($search) ?>">
                </div>

                <!-- Category Filter -->
                <div  id="products" class="col-md-4">
                    <label for="category" class="form-label">Category</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach ($allCategories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['cat_name']) ?>" <?= $category === $cat['cat_name'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['cat_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Button -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                </div>
            </div>
        </form>
    </section>

    <!-- Product Cards Section -->
    <section class="container mt-5">
        <div class="row">
        <table class="table table-hover" id="inventory-table">
                <thead class="table-light">
                    <tr>
                        <th>Titel</th>
                        <th>Författare</th>
                        <th>Kategori</th>
                        <th>Genre</th>
                        <th>Bok skick</th>
                        <th>Pris</th>
                    </tr>
                </thead>
                <tbody>
            <?php if (!empty($bookResult)): ?>
                <?php foreach ($bookResult as $book): ?>
                    <tr onclick="window.location='product-detail.php?id=<?= $book['prod_id'] ?>';" style="cursor:pointer;">
                                <td><?= htmlspecialchars($book['prod_title']) ?></td>
                                <td><?= htmlspecialchars($book['author_name']) ?></td>
                                <td><?= htmlspecialchars($book['cat_name']) ?></td>
                                <td><?= htmlspecialchars($book['genre_name']) ?></td>
                                <td><?= htmlspecialchars($book['cond_class']) ?></td>
                                <td>€<?= number_format($book['prod_price'], 2) ?></td>
                            </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center p-4">
                        <strong>No products found</strong><br>
                        Try adjusting your search or filter options.
                    </div>
                </div>
            <?php endif; ?>
        </tbody>
    </table>
        </div>
    </section>






    <section class="container my-5">
    <h2 class="mb-4">Rariteter</h2>
    <div class="row g-4">
        <?php
            $category = 'Rariteter';
            $books = getProductsByCategory($category, $pdo);

            if (!empty($books)) {
                foreach ($books as $book) {
        ?>
        <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 position-relative">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0"><?= htmlspecialchars($book['prod_title']) ?></h5>
                    </div>
                <a href="product-detail.php?id=<?=($book['prod_id']) ?>" class="text-decoration-none text-dark">
                    <div class="card-body">
                        <p class="mb-1"><strong>Authors:</strong> <?= htmlspecialchars($book['authors']) ?></p>
                        <p class="mb-1"><strong>Category:</strong> <?= htmlspecialchars($book['prod_category']) ?></p>
                        <p class="mb-1"><strong>Genre:</strong> <?= htmlspecialchars($book['genre_name']) ?></p>
                        <p class="mb-1"><strong>Condition:</strong> <?= htmlspecialchars($book['prod_condition']) ?></p>
                        <p class="fw-bold text-success mt-2">€<?= number_format($book['prod_price'], 2) ?></p>
                    </div>
                </a>
                </div>

        </div>
        <?php
                }
            } else {
                echo "<p class='col-12'>No books found in the 'Rariteter' category.</p>";
            }
        ?>
    </div>
</section>







</div>
    </body>
    </html>
<?php require_once "include/footer.php"?>