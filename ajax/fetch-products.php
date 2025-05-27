<?php
require_once "../include/functions.php";
require_once "../include/class-user.php";
require_once "../include/config.php";

header('Content-Type: text/html; charset=utf-8');

$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'prod_title';
$order = $_GET['order'] ?? 'asc';

$allowedSortColumns = ['prod_title', 'author_name', 'cat_name', 'genre_name', 'cond_class', 'prod_price'];
$allowedOrderDirections = ['asc', 'desc'];

if (!in_array($sort, $allowedSortColumns)) $sort = 'prod_title';
if (!in_array($order, $allowedOrderDirections)) $order = 'asc';

$books = getBooks($pdo, $category, $search, '', 'index', '', '', '', '', $sort, strtoupper($order));
$bookResult = $books['success'] ? $books['data'] : [];

if (!empty($bookResult)):
    foreach ($bookResult as $book): ?>
        <tr onclick="window.location='product-detail.php?id=<?= $book['prod_id'] ?>';" style="cursor:pointer;">
            <td><?= htmlspecialchars($book['prod_title']) ?></td>
            <td><?= htmlspecialchars($book['author_names']) ?></td>
            <td><?= htmlspecialchars($book['cat_name']) ?></td>
            <td><?= htmlspecialchars($book['genre_names']) ?></td>
            <td><?= htmlspecialchars($book['cond_class']) ?></td>
            <td>€<?= number_format($book['prod_price'], 2) ?></td>
        </tr>
    <?php endforeach;
else: ?>
    <tr><td colspan="6" class="text-center"><strong>No products found</strong></td></tr>
<?php endif; ?>