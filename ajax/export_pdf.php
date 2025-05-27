<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;


include_once 'header.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read POST filter values safely
    $category = $_POST['category'] ?? '';
    $search = $_POST['search'] ?? '';
    $year_from = $_POST['year_from'] ?? '';
    $year_to = $_POST['year_to'] ?? '';
    $price_min = $_POST['price_min'] ?? '';
    $price_max = $_POST['price_max'] ?? '';
    $status = $_POST['status'] ?? '';

    // Query your database with these filters to get $bookResult
    // Example (pseudo code):
    // $bookResult = queryBooks($category, $search, $year_from, $year_to, $price_min, $price_max, $status);

    // For example purposes, use dummy data:
    $bookResult = [
        ['prod_title' => 'Book A', 'author_names' => 'Author 1', 'prod_year' => '2020', 'prod_price' => '100'],
        ['prod_title' => 'Book B', 'author_names' => 'Author 2', 'prod_year' => '2019', 'prod_price' => '150'],
    ];

    // Generate HTML for PDF content
    $html = '<h1>Exported Books</h1><ul>';
    foreach ($bookResult as $book) {
        $html .= '<li>' . htmlspecialchars($book['prod_title']) . ' - ' 
                      . htmlspecialchars($book['author_names']) . ' - ' 
                      . htmlspecialchars($book['prod_year']) . ' - ' 
                      . htmlspecialchars($book['prod_price']) . ' kr</li>';
    }
    $html .= '</ul>';

    // Initialize Dompdf and load HTML
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);

    // (Optional) Setup paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the PDF
    $dompdf->render();

    // Output PDF as string
    $pdfOutput = $dompdf->output();

    // Send PDF headers and output to browser for download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="exported_books.pdf"');
    header('Content-Length: ' . strlen($pdfOutput));
    echo $pdfOutput;
    exit;
}
?>