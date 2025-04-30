<?php
	
	function cleanInput($stringToClean){
	$semiCleanString = htmlspecialchars($stringToClean);
	$cleanString = strip_tags($semiCleanString);
	return $cleanString;
}


function getBooks(PDO $pdo, string $category = '', string $search = '', $site): array {
    try {
        $query = "
            SELECT 
                p.prod_id,
                p.prod_title,
                p.prod_info,
                p.prod_price,
                p.prod_code,
                p.prod_year,
                c.cat_name,
                k.cond_class,
                p.prod_status,
                CONCAT(a.auth_fname, ' ', a.auth_lnmae) AS author_name,
                g.gen_name AS genre_name
            FROM products p
            LEFT JOIN tab_cat c ON p.prod_cat_fk = c.cat_id
            LEFT JOIN `table-prod-author` pa ON p.prod_id = pa.prod_fk
            LEFT JOIN tab_athor a ON pa.auth_fk = a.auth_id
            LEFT JOIN `tab-prod-genre` pg ON p.prod_id = pg.prod_fk
            LEFT JOIN tab_genger g ON pg.genre_fk = g.gen_id
            LEFT JOIN tab_kond k ON p.prod_cond_fk = k.cond_id
            WHERE 1=1
        ";

        $params = [];

        // Visa bara status 1 om vi är på index-sidan
        if ($site === 'index') {
            $query .= " AND p.prod_status = 1";
        }

        if (!empty($category)) {
            $query .= " AND c.cat_name = ?";
            $params[] = $category;
        }

        if (!empty($search)) {
            $query .= " AND (
                p.prod_title LIKE ? 
                OR p.prod_info LIKE ? 
                OR a.auth_fname LIKE ?
                OR a.auth_lnmae LIKE ?
                OR g.gen_name LIKE ?
            )";
            $like = "%$search%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $query .= " GROUP BY p.prod_id ORDER BY p.prod_title ASC";

        $stmt = $pdo->prepare($query);

        foreach ($params as $index => $param) {
            $stmt->bindValue($index + 1, $param);
        }

        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($books) {
            return ['success' => true, 'data' => $books];
        } else {
            return ['success' => false, 'error' => 'No books found.'];
        }

    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

function getStatusText(int $status): string {
    return match($status) {
        1 => 'Tillgänglig',
        0 => 'Såld',
        default => 'Okänd'
    };
}

function updateProductStatus(PDO $pdo, int $productId, int $newStatus): array {
    try {
        $query = "UPDATE products SET prod_status = :status WHERE prod_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':status', $newStatus, PDO::PARAM_INT);
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        Header('Location: '.$_SERVER['PHP_SELF']);
        

        if ($stmt->rowCount()) {
            return ['success' => true, 'message' => 'Status updated successfully.'];
        } else {
            return ['success' => false, 'message' => 'No changes made or invalid product ID.'];
        }
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
    exit();
}

function getAllCategories(PDO $pdo): array {
    try {
        $stmt = $pdo->query("SELECT cat_id, cat_name FROM tab_cat ORDER BY cat_name ASC");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['success' => true, 'data' => $categories];
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

function getProductsByCategory($category, $pdo) {
    
    // Define the query with placeholders
    $query = "
            SELECT 
                p.prod_title,
                p.prod_price,
                p.prod_info,
                p.prod_code,
                p.prod_year,
                c.cat_name AS prod_category,
                k.cond_class AS prod_condition,
                s.shelf_nr AS prod_shelf,
                GROUP_CONCAT(CONCAT(a.auth_fname, ' ', a.auth_lnmae) SEPARATOR ', ') AS authors,
                g.gen_name AS genre_name
            FROM 
                products p
            JOIN 
                tab_cat c ON p.prod_cat_fk = c.cat_id
            JOIN 
                tab_kond k ON p.prod_cond_fk = k.cond_id
            JOIN 
                tab_shelf s ON p.prod_shelf_fk = s.shelf_id
            LEFT JOIN 
                `table-prod-author` tp ON p.prod_id = tp.prod_fk
            LEFT JOIN 
                tab_athor a ON tp.auth_fk = a.auth_id
            LEFT JOIN 
                `tab-prod-genre` pg ON p.prod_id = pg.prod_fk
            LEFT JOIN 
                tab_genger g ON pg.genre_fk = g.gen_id
            WHERE 
                c.cat_name = :category
            GROUP BY 
                p.prod_id;
";


    // Prepare the query
    $stmt = $pdo->prepare($query);

    // Bind the category parameter
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Fetch and return the results
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function renderCategorySelect(PDO $pdo, $category = ''): string {
    $result = getAllCategories($pdo);

    if (!$result['success']) {
        return '<option value="">Error loading categories</option>';
    }

    $categories = $result['data'];
    $html = '<option value="">-- Select Category --</option>';

    foreach ($categories as $cat) {
        $selected = ($cat['cat_name'] === $category) ? 'selected' : '';
        $html .= "<option value=\"" . htmlspecialchars($cat['cat_name']) . "\" $selected>" . htmlspecialchars($cat['cat_name']) . "</option>";
    }

    return $html;
}

function getAllGenres(PDO $pdo): array {
    try {
        $stmt = $pdo->query("SELECT gen_id, gen_name FROM tab_genger ORDER BY gen_name ASC");
        $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['success' => true, 'data' => $genres];
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

function renderGenreSelect(PDO $pdo, $genre = ''): string {
    $result = getAllGenres($pdo);

    if (!$result['success']) {
        return '<option value="">Error loading genres</option>';
    }

    $genres = $result['data'];
    $html = '<option value="">-- Select Genre --</option>';

    foreach ($genres as $g) {
        $selected = ($g['gen_name'] === $genre) ? 'selected' : '';
        $html .= "<option value=\"" . htmlspecialchars($g['gen_name']) . "\" $selected>" . htmlspecialchars($g['gen_name']) . "</option>";
    }

    return $html;
}

function getProductById(PDO $pdo, int $prod_id): array {
    $query = "
        SELECT 
            p.prod_id,
            p.prod_title,
            p.prod_info,
            p.prod_price,
            p.prod_year,
            p.prod_code,
            c.cat_name,
            s.shelf_nr,
            k.cond_class,
            CONCAT(a.auth_fname, ' ', a.auth_lnmae) AS author_name,
            g.gen_name
        FROM products p
        LEFT JOIN tab_cat c ON p.prod_cat_fk = c.cat_id
        LEFT JOIN tab_shelf s ON p.prod_shelf_fk = s.shelf_id
        LEFT JOIN tab_kond k ON p.prod_cond_fk = k.cond_id
        LEFT JOIN `table-prod-author` tpa ON p.prod_id = tpa.prod_fk
        LEFT JOIN tab_athor a ON tpa.auth_fk = a.auth_id
        LEFT JOIN `tab-prod-genre` tpg ON p.prod_id = tpg.prod_fk
        LEFT JOIN tab_genger g ON tpg.genre_fk = g.gen_id
        WHERE p.prod_id = :id
    ";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $prod_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            return ['success' => true, 'data' => $product];
        } else {
            return ['success' => false, 'error' => 'Product not found.'];
        }
    } catch (PDOException $e) {
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}
function updateBook(PDO $pdo, array $data): bool
{
    try {
        $pdo->beginTransaction();

        // Extract and sanitize inputs
        $productId = (int)$data['product_id'];
        $title = trim($data['title']);
        $categoryName = trim($data['category']);
        $authorName = trim($data['author']);
        $genreName = trim($data['genre']);
        $shelfNr = trim($data['shelf']);
        $price = (float)$data['price'];
        $year = (int)$data['year'];
        $prodInfo = trim($data['prod_info']);
        $prodCode = trim($data['prod_code']);
        $conditionClass = trim($data['condition']);

        // Shelf
        $stmt = $pdo->prepare("SELECT shelf_id FROM tab_shelf WHERE shelf_nr = ?");
        $stmt->execute([$shelfNr]);
        $shelf = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$shelf) {
            $stmt = $pdo->prepare("INSERT INTO tab_shelf (shelf_nr) VALUES (?)");
            $stmt->execute([$shelfNr]);
            $shelfId = $pdo->lastInsertId();
        } else {
            $shelfId = $shelf['shelf_id'];
        }

        // Category
        $stmt = $pdo->prepare("SELECT cat_id FROM tab_cat WHERE cat_name = ?");
        $stmt->execute([$categoryName]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$category) {
            throw new Exception("Category not found.");
        }
        $categoryId = $category['cat_id'];

        // Condition
        $stmt = $pdo->prepare("SELECT cond_id FROM tab_kond WHERE cond_class = ?");
        $stmt->execute([$conditionClass]);
        $condition = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$condition) {
            $stmt = $pdo->prepare("INSERT INTO tab_kond (cond_class) VALUES (?)");
            $stmt->execute([$conditionClass]);
            $conditionId = $pdo->lastInsertId();
        } else {
            $conditionId = $condition['cond_id'];
        }

        // Update the product
        $stmt = $pdo->prepare("
            UPDATE products
            SET prod_title = ?, 
                prod_cat_fk = ?, 
                prod_cond_fk = ?, 
                prod_shelf_fk = ?, 
                prod_price = ?, 
                prod_info = ?, 
                prod_code = ?, 
                prod_year = ?
            WHERE prod_id = ?
        ");
        $stmt->execute([
            $title,
            $categoryId,
            $conditionId,
            $shelfId,
            $price,
            $prodInfo,
            $prodCode,
            $year,
            $productId
        ]);

        // Author
        [$authorFirstName, $authorLastName] = explode(' ', $authorName, 2) + ['', ''];
        $stmt = $pdo->prepare("SELECT auth_id FROM tab_athor WHERE auth_fname = ? AND auth_lnmae = ?");
        $stmt->execute([$authorFirstName, $authorLastName]);
        $author = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$author) {
            $stmt = $pdo->prepare("INSERT INTO tab_athor (auth_fname, auth_lnmae) VALUES (?, ?)");
            $stmt->execute([$authorFirstName, $authorLastName]);
            $authorId = $pdo->lastInsertId();
        } else {
            $authorId = $author['auth_id'];
        }

        // Update author link
        $stmt = $pdo->prepare("DELETE FROM `table-prod-author` WHERE prod_fk = ?");
        $stmt->execute([$productId]);

        $stmt = $pdo->prepare("INSERT INTO `table-prod-author` (auth_fk, prod_fk) VALUES (?, ?)");
        $stmt->execute([$authorId, $productId]);

        // Genre
        $stmt = $pdo->prepare("SELECT gen_id FROM tab_genger WHERE gen_name = ?");
        $stmt->execute([$genreName]);
        $genre = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$genre) {
            $stmt = $pdo->prepare("INSERT INTO tab_genger (gen_name) VALUES (?)");
            $stmt->execute([$genreName]);
            $genreId = $pdo->lastInsertId();
        } else {
            $genreId = $genre['gen_id'];
        }

        // Update genre link
        $stmt = $pdo->prepare("DELETE FROM `tab-prod-genre` WHERE prod_fk = ?");
        $stmt->execute([$productId]);

        $stmt = $pdo->prepare("INSERT INTO `tab-prod-genre` (genre_fk, prod_fk) VALUES (?, ?)");
        $stmt->execute([$genreId, $productId]);

        $pdo->commit();
        return true;

    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Update Book Error: " . $e->getMessage());
        return false;
    }
}

function addCategory(PDO $pdo, string $newCategory): bool
{
    try {
        $newCategory = trim($newCategory);
        if (empty($newCategory)) {
            return false;
        }

        // Check if category already exists
        $stmt = $pdo->prepare("SELECT cat_id FROM tab_cat WHERE cat_name = ?");
        $stmt->execute([$newCategory]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Category already exists
            return false;
        }

        // Insert new category
        $insert = $pdo->prepare("INSERT INTO tab_cat (cat_name) VALUES (?)");
        $insert->execute([$newCategory]);
        return true;

    } catch (Exception $e) {
        error_log("Add Category Error: " . $e->getMessage());
        return false;
    }
}

function addGenre(PDO $pdo, string $newGenre): bool
{
    try {
        $newGenre = trim($newGenre);
        if (empty($newGenre)) {
            return false;
        }

        // Check if genre already exists
        $stmt = $pdo->prepare("SELECT gen_id FROM tab_genger WHERE gen_name = ?");
        $stmt->execute([$newGenre]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Genre already exists
            return false;
        }

        // Insert new genre
        $insert = $pdo->prepare("INSERT INTO tab_genger (gen_name) VALUES (?)");
        $insert->execute([$newGenre]);
        return true;

    } catch (Exception $e) {
        error_log("Add Genre Error: " . $e->getMessage());
        return false;
    }
}



function insertBook(PDO $pdo, array $data): bool
{
    try {
        // Start the transaction
        $pdo->beginTransaction();

        // Collect and sanitize input data
        $title = trim($data['title']);
        $categoryName = trim($data['category']);
        $authorName = trim($data['author']);
        $genreName = trim($data['genre']);
        $shelfNr = (int)$data['shelf'];
        $price = (float)$data['price'];
        $year = (int)$data['year'];
		$prodInfo = trim($data['prod_info']);
		$prodCode = trim($data['prod_code']);
        $conditionClass = trim($data['condition']);

        // Get or insert shelf
        $stmt = $pdo->prepare("SELECT shelf_id FROM tab_shelf WHERE shelf_nr = ?");
        $stmt->execute([$shelfNr]);
        $shelf = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$shelf) {
            $stmt = $pdo->prepare("INSERT INTO tab_shelf (shelf_nr) VALUES (?)");
            $stmt->execute([$shelfNr]);
            $shelfId = $pdo->lastInsertId();
        } else {
            $shelfId = $shelf['shelf_id'];
        }

        // Get category ID
        $stmt = $pdo->prepare("SELECT cat_id FROM tab_cat WHERE cat_name = ?");
        $stmt->execute([$categoryName]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$category) {
            throw new Exception("Category not found.");
        }
        $categoryId = $category['cat_id'];

        // Get or create condition ID (checking if condition exists)
        $stmt = $pdo->prepare("SELECT cond_id FROM tab_kond WHERE cond_class = ?");
        $stmt->execute([$conditionClass]);
        $condition = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$condition) {
            // If condition doesn't exist, insert it
            $stmt = $pdo->prepare("INSERT INTO tab_kond (cond_class) VALUES (?)");
            $stmt->execute([$conditionClass]);
            $conditionId = $pdo->lastInsertId();
        } else {
            $conditionId = $condition['cond_id'];
        }
		
		$stmt = $pdo->prepare("
			SELECT prod_id FROM products 
			WHERE prod_title = ? 
			  AND prod_cat_fk = ? 
			  AND prod_cond_fk = ? 
			  AND prod_shelf_fk = ? 
			  AND prod_year = ?
		");
		$stmt->execute([$title, $categoryId, $conditionId, $shelfId, $year]);
		$existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($existingProduct) {
			throw new Exception("This product already exists."); // Or you can return false if you prefer
		}

        // Insert the book into products table
       $stmt = $pdo->prepare("
		INSERT INTO products (prod_title, prod_cat_fk, prod_cond_fk, prod_shelf_fk, prod_price, prod_info, prod_code, prod_year, prod_status)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
		");
		$stmt->execute([$title, $categoryId, $conditionId, $shelfId, $price, $prodInfo, $prodCode, $year]);
        $productId = $pdo->lastInsertId();

        // Handle author
        [$authorFirstName, $authorLastName] = explode(' ', $authorName, 2) + ['', ''];
        $stmt = $pdo->prepare("SELECT auth_id FROM tab_athor WHERE auth_fname = ? AND auth_lnmae = ?");
        $stmt->execute([$authorFirstName, $authorLastName]);
        $author = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$author) {
            $stmt = $pdo->prepare("INSERT INTO tab_athor (auth_fname, auth_lnmae) VALUES (?, ?)");
            $stmt->execute([$authorFirstName, $authorLastName]);
            $authorId = $pdo->lastInsertId();
        } else {
            $authorId = $author['auth_id'];
        }

        // Link author to product
        $stmt = $pdo->prepare("INSERT INTO `table-prod-author` (auth_fk, prod_fk) VALUES (?, ?)");
        $stmt->execute([$authorId, $productId]);

        // Handle genre
        $stmt = $pdo->prepare("SELECT gen_id FROM tab_genger WHERE gen_name = ?");
        $stmt->execute([$genreName]);
        $genre = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$genre) {
            $stmt = $pdo->prepare("INSERT INTO tab_genger (gen_name) VALUES (?)");
            $stmt->execute([$genreName]);
            $genreId = $pdo->lastInsertId();
        } else {
            $genreId = $genre['gen_id'];
        }

        // Link genre to product
        $stmt = $pdo->prepare("INSERT INTO `tab-prod-genre` (genre_fk, prod_fk) VALUES (?, ?)");
        $stmt->execute([$genreId, $productId]);

        // Commit the transaction
        $pdo->commit();
        return true;

    } catch (Exception $e) {
        // Rollback on error
        $pdo->rollBack();
        error_log("Insert Book Error: " . $e->getMessage());
        return false;
    }
}

function getOrCreateCondition(PDO $pdo, string $condition): int
{
    try {
        // Check if the condition already exists
        $stmt = $pdo->prepare("SELECT cond_id FROM tab_kond WHERE cond_class = :condition");
        $stmt->execute(['condition' => $condition]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            return (int)$existing['cond_id']; // Condition found, return existing ID
        } else {
            // Condition not found, insert new one
            $insert = $pdo->prepare("INSERT INTO tab_kond (cond_class) VALUES (:condition)");
            $insert->execute(['condition' => $condition]);
            return (int)$pdo->lastInsertId(); // Return the newly inserted ID
        }
    } catch (Exception $e) {
        throw new Exception('Error checking or inserting condition: ' . $e->getMessage());
    }
}
function deleteProduct($pdo, $productId) {
    try {
        $pdo->beginTransaction();
        
        // First, delete connections to authors
        $stmt = $pdo->prepare("DELETE FROM `table-prod-author` WHERE `prod_fk` = ?");
        $stmt->execute([$productId]);
        
        // Then, delete connections to genres
        $stmt = $pdo->prepare("DELETE FROM `tab-prod-genre` WHERE `prod_fk` = ?");
        $stmt->execute([$productId]);
        
        // Finally, delete the product itself
        $stmt = $pdo->prepare("DELETE FROM `products` WHERE `prod_id` = ?");
        $stmt->execute([$productId]);
        
        $pdo->commit();
        return [
            'success' => true,
            'message' => "Product and its connections deleted successfully."
        ];
    } catch (Exception $e) {
        $pdo->rollBack();
        return [
            'success' => false,
            'message' => "Failed to delete product: " . $e->getMessage()
        ];
    }
}