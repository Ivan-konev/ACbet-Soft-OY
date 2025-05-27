<?php
	
	function cleanInput($stringToClean){
	$semiCleanString = htmlspecialchars($stringToClean);
	$cleanString = strip_tags($semiCleanString);
	return $cleanString;
}


function getBooks(
    PDO $pdo,
    string $category = '',
    string $search = '',
    string $year_from = '',
    string $source = 'index', // moved 'source' to match your parameter order
    string $year_to = '',
    string $price_min = '',
    string $price_max = '',
    string $status = '',
    string $sort = 'p.prod_title',
    string $order = 'ASC'
): array {
    try {
        $query = "
            SELECT
                p.prod_id,
                p.prod_title,
                p.prod_info,
                p.prod_price,
                p.prod_code,
                p.img_name,
                p.prod_year,
                c.cat_name,
                k.cond_class,
                p.prod_status,
                GROUP_CONCAT(DISTINCT CONCAT(a.auth_fname, ' ', a.auth_lnmae) ORDER BY a.auth_fname ASC) AS author_names,
                GROUP_CONCAT(DISTINCT g.gen_name ORDER BY g.gen_name ASC) AS genre_names
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

        // Basic filters
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
            array_push($params, $like, $like, $like, $like, $like);
        }
		
		if ($source === 'index') {
        $query .= " AND p.prod_status = 1";
		}
	
		//bullshit check
        if ($source === 'List') {
			if (!empty($year_from)) {
				$query .= " AND p.prod_year >= ?";
				$params[] = $year_from;
			}

			if (!empty($year_to)) {
				$query .= " AND p.prod_year <= ?";
				$params[] = $year_to;
			}

			if (!empty($price_min)) {
				$query .= " AND p.prod_price >= ?";
				$params[] = $price_min;
			}

			if (!empty($price_max)) {
				$query .= " AND p.prod_price <= ?";
				$params[] = $price_max;
			}

			if ($status !== '') {
				$query .= " AND p.prod_status = ?";
				$params[] = $status;
			}
		}
		
        // Sorting
        $sortMap = [
			'prod_title' => 'p.prod_title',
			'author_name' => 'author_names',
			'cat_name' => 'c.cat_name',
			'genre_name' => 'genre_names',
			'cond_class' => 'k.cond_class',
			'prod_price' => 'p.prod_price'
		];

		$sortColumn = $sortMap[$sort] ?? 'p.prod_title'; // Fallback to default
		$order = strtoupper($order);
		if (!in_array($order, ['ASC', 'DESC'])) {
			$order = 'ASC';
		}
		$query .= " GROUP BY p.prod_id ORDER BY $sortColumn $order";

        $stmt = $pdo->prepare($query);
        foreach ($params as $i => $param) {
            $stmt->bindValue($i + 1, $param);
        }

        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $books
            ? ['success' => true, 'data' => $books]
            : ['success' => false, 'error' => 'No books found.'];

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

function getProductsByCategory($category, $pdo, string $site = '') {
    // Define the query with placeholders
    $query = "
        SELECT 
            p.prod_id,
            p.prod_title,
            p.prod_price,
            p.prod_info,
            p.prod_code,
            p.prod_year,
            c.cat_name AS prod_category,
            k.cond_class AS prod_condition,
            s.shelf_nr AS prod_shelf,
            GROUP_CONCAT(DISTINCT CONCAT(a.auth_fname, ' ', a.auth_lnmae) ORDER BY a.auth_fname ASC SEPARATOR ', ') AS authors,
            GROUP_CONCAT(DISTINCT g.gen_name ORDER BY g.gen_name ASC SEPARATOR ', ') AS genres
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
    ";

    // Add the prod_status condition if the site is 'index'
    if ($site === 'index') {
        $query .= " AND p.prod_status = 1";
    }

    // Group by product to avoid duplicate rows due to joins
    $query .= " GROUP BY p.prod_id;";

    // Prepare the query
    $stmt = $pdo->prepare($query);

    // Bind the category parameter
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);

    // Execute the query
    if ($stmt->execute()) {
        // Fetch and return the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results ? $results : []; // Return an empty array if no results found
    } else {
        // Log the error and return an empty array
        $errorInfo = $stmt->errorInfo();
        error_log("Error in query: " . $errorInfo[2]);
        return [];
    }
}






function renderCategorySelect(PDO $pdo, $category = ''): string {
    $result = getAllCategories($pdo);

    if (!$result['success']) {
        return '<option value="">Error loading categories</option>';
    }

    $categories = $result['data'];
    $html = '<option value="">--  --</option>';

    foreach ($categories as $cat) {
        $selected = ($cat['cat_name'] === $category) ? 'selected' : '';
        $html .= "<option value=\"" . htmlspecialchars($cat['cat_name']) . "\" $selected>" . htmlspecialchars($cat['cat_name']) . "</option>";
    }

    return $html;
}

function updateCategoryName(PDO $pdo, int $categoryId, string $newName): bool {
    try {
        $stmt = $pdo->prepare("UPDATE tab_cat SET cat_name = ? WHERE cat_id = ?");
        return $stmt->execute([$newName, $categoryId]);
    } catch (Exception $e) {
        error_log("Update Category Error: " . $e->getMessage());
        return false;
    }
}

function renderCategoryTableRows(PDO $pdo): string {
    $result = getAllCategories($pdo);

    if (!$result['success']) {
        return '<tr><td colspan="3">Error loading categories: ' . htmlspecialchars($result['error']) . '</td></tr>';
    }

    $categories = $result['data'];
    $html = '';

    foreach ($categories as $cat) {
		 $html .= '<tr>';
		$html .= '<td>' . htmlspecialchars($cat['cat_id']) . '</td>';
		$html .= '<td>' . htmlspecialchars($cat['cat_name']) . '</td>';
		$html .= '<td>';
		$html .= '<button type="button" class="btn btn-sm btn-secondary me-1" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-category-id="' . htmlspecialchars($cat['cat_id']) . '" data-category-name="' . htmlspecialchars($cat['cat_name']) . '">Edit</button>';
		$html .= '<button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteCategoryModal" data-category-id="' . htmlspecialchars($cat['cat_id']) . '" data-category-name="' . htmlspecialchars($cat['cat_name']) . '">Delete</button>';
		$html .= '</td>';
		$html .= '</tr>';
    }

    return $html;
}

function deleteCategoryById(PDO $pdo, int $categoryId): bool {
    try {
        // OPTIONAL: delete related products first if FK constraints exist
        $stmt = $pdo->prepare("DELETE FROM products WHERE prod_cat_fk = ?");
        $stmt->execute([$categoryId]);

        $stmt = $pdo->prepare("DELETE FROM tab_cat WHERE cat_id = ?");
        return $stmt->execute([$categoryId]);
    } catch (Exception $e) {
        error_log("Delete category error: " . $e->getMessage());
        return false;
    }
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

function renderGenreSelect(PDO $pdo, array|string $selectedGenres = []): string {
    // Fetch genres from the database
    $result = getAllGenres($pdo);

    if (!$result['success']) {
        return '<p>Error loading genres.</p>';
    }

    $selectedGenres = (array) $selectedGenres;

    // Start building the HTML for the custom multi-select
    $html = '<div class="mb-3">';
    $html .= '<label for="genres" class="form-label">Genres</label>';
    $html .= '<div class="custom-multiselect">';
    $html .= '<div class="select-box form-control" id="selectBox" aria-label="Select genres">Select genres</div>';
    $html .= '<div class="checkbox-list" id="checkboxList" style="display: none;">';

    // Loop through genres and create checkboxes
    foreach ($result['data'] as $genre) {
        $value = htmlspecialchars($genre['gen_name']);
        $checked = in_array($genre['gen_name'], $selectedGenres) ? 'checked' : '';  // Pre-select the genres
        $html .= "<label class='form-check-label'>
                    <input type='checkbox' class='form-check-input' value='$value' $checked> $value
                  </label><br>";
    }

    $html .= '</div>'; // Close the checkbox-list div
    $html .= '</div>'; // Close the custom-multiselect div

    // Hidden input to store selected genres (these will be sent to the server on form submission)
    $html .= '<input type="hidden" name="genres" id="selectedGenres" value="' . implode(',', $selectedGenres) . '">';
    $html .= '</div>'; // Close the mb-3 div

    return $html;
}

function updateGenreName(PDO $pdo, int $genreId, string $newName): bool {
    try {
        $stmt = $pdo->prepare("UPDATE tab_genger SET gen_name = ? WHERE gen_id = ?");
        return $stmt->execute([$newName, $genreId]);
    } catch (Exception $e) {
        error_log("Update Genre Error: " . $e->getMessage());
        return false;
    }
}

function renderGenreTableRows(PDO $pdo): string {
    $result = getAllGenres($pdo);

    if (!$result['success']) {
        return '<tr><td colspan="3">Error loading genres: ' . htmlspecialchars($result['error']) . '</td></tr>';
    }

    $genres = $result['data'];
    $html = '';

    foreach ($genres as $genre) {
       $html .= '<tr>';
		$html .= '<td>' . htmlspecialchars($genre['gen_id']) . '</td>';
		$html .= '<td>' . htmlspecialchars($genre['gen_name']) . '</td>';
		$html .= '<td>';
		$html .= '<button type="button" class="btn btn-sm btn-secondary me-1" data-bs-toggle="modal" data-bs-target="#editGenreModal" data-genre-id="' . htmlspecialchars($genre['gen_id']) . '" data-genre-name="' . htmlspecialchars($genre['gen_name']) . '">Edit</button>';
		$html .= '<button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-genre-id="' . htmlspecialchars($genre['gen_id']) . '" data-genre-name="' . htmlspecialchars($genre['gen_name']) . '">Delete</button>';
		$html .= '</td>';
		$html .= '</tr>';
    }

    return $html;
}

function deleteGenreById(PDO $pdo, int $genreId): bool {
    try {
        // OPTIONAL: delete related rows in linking tables first if necessary
        $stmt = $pdo->prepare("DELETE FROM `tab-prod-genre` WHERE genre_fk = ?");
        $stmt->execute([$genreId]);

        $stmt = $pdo->prepare("DELETE FROM tab_genger WHERE gen_id = ?");
        return $stmt->execute([$genreId]);
    } catch (Exception $e) {
        error_log("Delete genre error: " . $e->getMessage());
        return false;
    }
}

function getProductById(PDO $pdo, int $productId): array {
    try {
        // Fetch the base product info
        $stmt = $pdo->prepare("
                SELECT 
                p.prod_id,
                p.prod_title,
                p.prod_price,
                p.prod_info,
                p.prod_code,
                p.Img_name,
                p.prod_year,
                p.prod_cat_fk,      -- ADD THIS
                c.cat_name,
                k.cond_class,
                s.shelf_nr
            FROM products p
            JOIN tab_cat c ON p.prod_cat_fk = c.cat_id
            JOIN tab_kond k ON p.prod_cond_fk = k.cond_id
            JOIN tab_shelf s ON p.prod_shelf_fk = s.shelf_id
            WHERE p.prod_id = ?

        ");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            return ['success' => false, 'error' => 'Product not found'];
        }

        // Fetch authors
        $authorStmt = $pdo->prepare("
            SELECT CONCAT(a.auth_fname, ' ', a.auth_lnmae) AS full_name
            FROM `table-prod-author` pa
            JOIN tab_athor a ON pa.auth_fk = a.auth_id
            WHERE pa.prod_fk = ?
        ");
        $authorStmt->execute([$productId]);
        $authors = $authorStmt->fetchAll(PDO::FETCH_COLUMN);

        // Fetch genres
        $genreStmt = $pdo->prepare("
            SELECT g.gen_name
            FROM `tab-prod-genre` pg
            JOIN tab_genger g ON pg.genre_fk = g.gen_id
            WHERE pg.prod_fk = ?
        ");
        $genreStmt->execute([$productId]);
        $genres = $genreStmt->fetchAll(PDO::FETCH_COLUMN);

        // Add authors and genres to the product array
        $product['authors'] = $authors;
        $product['genres'] = $genres;

        return ['success' => true, 'data' => $product];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
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
        $shelfNr = trim($data['shelf']);
        $price = (float)$data['price'];
        $year = (int)$data['year'];
        $prodInfo = trim($data['prod_info']);
        $prodCode = trim($data['prod_code']);
        $conditionClass = trim($data['condition']);
        $authorNames = $data['authors'] ?? [];
        $genreNames = $data['genres'] ?? [];

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

        // --- Authors ---
        $stmt = $pdo->prepare("DELETE FROM `table-prod-author` WHERE prod_fk = ?");
        $stmt->execute([$productId]);

        foreach ($authorNames as $authorName) {
            $authorName = trim($authorName);
            if ($authorName === '') continue;

            [$firstName, $lastName] = explode(' ', $authorName, 2) + ['', ''];

            $stmt = $pdo->prepare("SELECT auth_id FROM tab_athor WHERE auth_fname = ? AND auth_lnmae = ?");
            $stmt->execute([$firstName, $lastName]);
            $author = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$author) {
                $stmt = $pdo->prepare("INSERT INTO tab_athor (auth_fname, auth_lnmae) VALUES (?, ?)");
                $stmt->execute([$firstName, $lastName]);
                $authorId = $pdo->lastInsertId();
            } else {
                $authorId = $author['auth_id'];
            }

            $stmt = $pdo->prepare("INSERT INTO `table-prod-author` (auth_fk, prod_fk) VALUES (?, ?)");
            $stmt->execute([$authorId, $productId]);
        }

        // --- Genres ---
        $stmt = $pdo->prepare("DELETE FROM `tab-prod-genre` WHERE prod_fk = ?");
        $stmt->execute([$productId]);

        foreach ($genreNames as $genreName) {
            $genreName = trim($genreName);
            if ($genreName === '') continue;

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

            $stmt = $pdo->prepare("INSERT INTO `tab-prod-genre` (genre_fk, prod_fk) VALUES (?, ?)");
            $stmt->execute([$genreId, $productId]);
        }

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



function insertBook(PDO $pdo, array $data, array $file): bool
{
    try {
        $pdo->beginTransaction();

        $title = trim($data['title']);
        $categoryName = trim($data['category']);
        $shelfNr = (int)$data['shelf'];
        $price = (float)$data['price'];
        $year = (int)$data['year'];
        $prodInfo = trim($data['prod_info']);
        $prodCode = trim($data['prod_code']);
        $conditionClass = trim($data['condition']);

        // === HANDLE FILE UPLOAD ===
        $uploadDir = 'images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if ($file['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('img_', true) . '.' . strtolower($extension);
            $uploadPath = $uploadDir . $newFileName;

            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                throw new Exception("Failed to move uploaded file.");
            }
            $prodImg = $newFileName;
        } else {
            throw new Exception("Image upload failed with error code: " . $file['error']);
        }

        // === Shelf
        $stmt = $pdo->prepare("SELECT shelf_id FROM tab_shelf WHERE shelf_nr = ?");
        $stmt->execute([$shelfNr]);
        $shelf = $stmt->fetch();
        $shelfId = $shelf ? $shelf['shelf_id'] : ($pdo->prepare("INSERT INTO tab_shelf (shelf_nr) VALUES (?)")->execute([$shelfNr]) ? $pdo->lastInsertId() : null);

        // === Category
        $stmt = $pdo->prepare("SELECT cat_id FROM tab_cat WHERE cat_name = ?");
        $stmt->execute([$categoryName]);
        $category = $stmt->fetch();
        if (!$category) throw new Exception("Category not found.");
        $categoryId = $category['cat_id'];

        // === Condition
        $stmt = $pdo->prepare("SELECT cond_id FROM tab_kond WHERE cond_class = ?");
        $stmt->execute([$conditionClass]);
        $condition = $stmt->fetch();
        $conditionId = $condition ? $condition['cond_id'] : ($pdo->prepare("INSERT INTO tab_kond (cond_class) VALUES (?)")->execute([$conditionClass]) ? $pdo->lastInsertId() : null);

        // === Check for existing product
        $stmt = $pdo->prepare("SELECT prod_id FROM products WHERE prod_title = ? AND prod_cat_fk = ? AND prod_cond_fk = ? AND prod_shelf_fk = ? AND prod_year = ?");
        $stmt->execute([$title, $categoryId, $conditionId, $shelfId, $year]);
        if ($stmt->fetch()) throw new Exception("This product already exists.");

        // === Insert product
        $stmt = $pdo->prepare("INSERT INTO products (prod_title, prod_cat_fk, prod_cond_fk, prod_shelf_fk, prod_price, prod_info, prod_code, Img_name, prod_year, prod_status)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->execute([$title, $categoryId, $conditionId, $shelfId, $price, $prodInfo, $prodCode, $prodImg, $year]);
        $productId = $pdo->lastInsertId();

        // === Authors
        $authors = $data['authors'] ?? [];
        foreach ($authors as $authorFullName) {
            $authorFullName = trim($authorFullName);
            if (!$authorFullName) continue;
            [$firstName, $lastName] = explode(' ', $authorFullName, 2) + ['', ''];

            $stmt = $pdo->prepare("SELECT auth_id FROM tab_athor WHERE auth_fname = ? AND auth_lnmae = ?");
            $stmt->execute([$firstName, $lastName]);
            $author = $stmt->fetch();

            $authorId = $author ? $author['auth_id'] : (
                $pdo->prepare("INSERT INTO tab_athor (auth_fname, auth_lnmae) VALUES (?, ?)")->execute([$firstName, $lastName]) ? $pdo->lastInsertId() : null
            );

            $stmt = $pdo->prepare("INSERT INTO `table-prod-author` (auth_fk, prod_fk) VALUES (?, ?)");
            $stmt->execute([$authorId, $productId]);
        }

        // === Genres
        $genres = $data['genres'] ?? [];
        foreach ($genres as $genreName) {
            $genreName = trim($genreName);
            if (!$genreName) continue;

            $stmt = $pdo->prepare("SELECT gen_id FROM tab_genger WHERE gen_name = ?");
            $stmt->execute([$genreName]);
            $genre = $stmt->fetch();

            $genreId = $genre ? $genre['gen_id'] : (
                $pdo->prepare("INSERT INTO tab_genger (gen_name) VALUES (?)")->execute([$genreName]) ? $pdo->lastInsertId() : null
            );

            $stmt = $pdo->prepare("INSERT INTO `tab-prod-genre` (genre_fk, prod_fk) VALUES (?, ?)");
            $stmt->execute([$genreId, $productId]);
        }

        $pdo->commit();
        return true;

    } catch (Exception $e) {
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

        // === Get image filename before deleting the product ===
        $stmt = $pdo->prepare("SELECT Img_name FROM products WHERE prod_id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();

        if ($product && !empty($product['Img_name'])) {
            $imgPath = 'images/' . $product['Img_name'];
            if (file_exists($imgPath)) {
                unlink($imgPath); // Delete the file from the server
            }
        }

        // === Delete connections to authors and genres ===
        $stmt = $pdo->prepare("DELETE FROM `table-prod-author` WHERE prod_fk = ?");
        $stmt->execute([$productId]);

        $stmt = $pdo->prepare("DELETE FROM `tab-prod-genre` WHERE prod_fk = ?");
        $stmt->execute([$productId]);

        // === Delete the product itself ===
        $stmt = $pdo->prepare("DELETE FROM products WHERE prod_id = ?");
        $stmt->execute([$productId]);

        $pdo->commit();
        return [
            'success' => true,
            'message' => "Product, image, and connections deleted successfully."
        ];
    } catch (Exception $e) {
        $pdo->rollBack();
        return [
            'success' => false,
            'message' => "Failed to delete product: " . $e->getMessage()
        ];
    }
}


function bulkUpdateBooks(PDO $pdo, string $selected_ids, ?string $status, ?string $price, ?string $year): void {
    $ids = explode(',', $selected_ids);

    foreach ($ids as $id) {
        $fields = [];
        $params = [];

        if ($status !== null && $status !== '') {
            $fields[] = "prod_status = ?";
            $params[] = $status;
        }
        if ($price !== null && $price !== '') {
            $fields[] = "prod_price = ?";
            $params[] = $price;
        }
        if ($year !== null && $year !== '') {
            $fields[] = "prod_year = ?";
            $params[] = $year;
        }

        if (!empty($fields)) {
            $sql = "UPDATE products SET " . implode(', ', $fields) . " WHERE prod_id = ?";
            $params[] = $id;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }
    }
}

function exportBooksAsCSV(PDO $pdo, $category = '', $search = '', $year_from = '', $year_to = '', $price_min = '', $price_max = '', $status = ''): void {
    if (headers_sent()) {
        echo "Headers already sent. CSV export failed.";
        return;
    }

    $sort  = 'p.prod_title';
    $order = 'ASC';

    $result = getBooks(
    $pdo,
    $category,
    $search,
    $year_from,
    'List', // this is the source parameter
    $year_to,
    $price_min,
    $price_max,
    $status,
    $sort,
    $order
);

    if (!$result['success']) {
        echo "No data to export.";
        return;
    }

    $books = $result['data'];

	ob_clean(); // clean any previous output
	ob_start(); // start buffering

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="books_export.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Title', 'Author(s)', 'Category', 'Price', 'Year', 'Status']);

    foreach ($books as $book) {
        fputcsv($output, [
            $book['prod_id'],
            $book['prod_title'],
            $book['author_names'],
            $book['cat_name'],
            number_format($book['prod_price'], 2, '.', ''),
            $book['prod_year'],
            $book['prod_status'] == 1 ? 'Tillgänglig' : 'Såld'
        ]);
    }

    fclose($output);
	
	
	ob_end_flush(); // send the buffer to the browser
	
    exit;
}