<?php
require_once "../include/functions.php";
require_once "../include/class-user.php";
require_once "../include/config.php"; 

// Get the parameters from the AJAX request
$sort = $_GET['sort'] ?? 'p.prod_title';  // Default sort by product title
$order = $_GET['order'] ?? 'ASC';         // Default order ascending
$category = $_GET['category'] ?? '';      // Optional category filter
$search = $_GET['search'] ?? '';          // Optional search term
$site = $_GET['site'] ?? '';              // Optional site status (e.g., 'index')

// Call the getBooks function with dynamic parameters
$response = getBooks($pdo, $category, $search, $site, $sort, $order);

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($response);