<?php
include_once 'includes/header.php'

/*
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$books = getBooks($conn, $category, $search);
*/
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bookshop</title>
    <link rel="stylesheet" href="index_style.css">
</head>
<body>

    <div class="shop-header">
        <h1>📚 Our Book Collection</h1>

        <form method="GET" class="filter-form">
            <input type="text" name="search" placeholder="Search title or author..." value="<?= htmlspecialchars($search) ?>">
            <select name="category">
                <option value="">All Categories</option>
                <option value="Fiction" <?= $category === 'Fiction' ? 'selected' : '' ?>>Fiction</option>
                <option value="Non-fiction" <?= $category === 'Non-fiction' ? 'selected' : '' ?>>Non-fiction</option>
                <option value="Sci-Fi" <?= $category === 'Sci-Fi' ? 'selected' : '' ?>>Sci-Fi</option>
                <option value="Fantasy" <?= $category === 'Fantasy' ? 'selected' : '' ?>>Fantasy</option>
            </select>
            <button type="submit">🔍 Search</button>
        </form>
    </div>

    <div class="book-grid">
        <?php if (count($books) > 0): ?>
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="Cover for <?= htmlspecialchars($book['title']) ?>">
                    <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
                    <div class="book-author">by <?= htmlspecialchars($book['author']) ?></div>
                    <div class="book-price">$<?= number_format($book['price'], 2) ?></div>
                    <div class="book-category"><?= htmlspecialchars($book['category']) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No books found matching your filters.</p>
        <?php endif; ?>
    </div>

</body>
</html>
