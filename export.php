<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$user_id = $_SESSION['user_id'];

// Fetch all transactions for this user
$stmt = $pdo->prepare("SELECT date, amount, type, category, description FROM transactions WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();

// Set headers to force download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=expense_report_' . date('Y-m-d') . '.csv');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, array('Date', 'Amount (INR)', 'Type', 'Category', 'Description'));

// Loop over the rows, outputting them
foreach ($transactions as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>
