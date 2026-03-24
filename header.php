<?php
require_once 'config.php';
if (!isLoggedIn()) {
    redirect('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="glass">
        <div class="logo">
            <a href="dashboard.php" style="text-decoration: none; color: var(--primary-color); font-weight: 700; font-size: 1.5rem;">
                <i class="fas fa-wallet"></i> ExpenseTracker
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="history.php"><i class="fas fa-history"></i> History</a></li>
            <li><a href="report.php"><i class="fas fa-chart-pie"></i> Reports</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><button onclick="toggleTheme()" class="theme-toggle"><i class="fas fa-moon"></i></button></li>
            <li><a href="logout.php" style="color: var(--error-color);"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
    <main class="container">
