<?php
include 'header.php';

$user_id = $_SESSION['user_id'];

// 1. Category-wise Expenses (Pie Chart)
$stmt = $pdo->prepare("SELECT category, SUM(amount) as total FROM transactions WHERE user_id = ? AND type = 'expense' GROUP BY category");
$stmt->execute([$user_id]);
$category_data = $stmt->fetchAll();

$cat_labels = [];
$cat_values = [];
foreach ($category_data as $row) {
    $cat_labels[] = $row['category'];
    $cat_values[] = $row['total'];
}

// 2. Monthly Expenses (Bar Chart) - Last 6 months
$stmt = $pdo->prepare("SELECT DATE_FORMAT(date, '%b %Y') as month, SUM(amount) as total 
                        FROM transactions 
                        WHERE user_id = ? AND type = 'expense' 
                        GROUP BY month 
                        ORDER BY date ASC 
                        LIMIT 6");
$stmt->execute([$user_id]);
$monthly_data = $stmt->fetchAll();

$month_labels = [];
$month_values = [];
foreach ($monthly_data as $row) {
    $month_labels[] = $row['month'];
    $month_values[] = $row['total'];
}
?>

<div class="dashboard-header animate-fade-in">
    <h1>Reports & Analytics</h1>
</div>

<div class="stats-grid animate-fade-in">
    <div class="card">
        <h3>Category Distribution</h3>
        <p class="text-muted" style="font-size: 0.875rem; margin-bottom: 1rem;">Where your money goes (Expenses)</p>
        <div style="height: 300px;">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
    
    <div class="card">
        <h3>Monthly Trends</h3>
        <p class="text-muted" style="font-size: 0.875rem; margin-bottom: 1rem;">Expense trends over time</p>
        <div style="height: 300px;">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Category Pie Chart
    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($cat_labels); ?>,
            datasets: [{
                data: <?php echo json_encode($cat_values); ?>,
                backgroundColor: [
                    '#6366f1', '#10b981', '#f43f5e', '#f59e0b', '#8b5cf6', '#06b6d4', '#ec4899', '#64748b'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Monthly Bar Chart
    const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctxMonthly, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($month_labels); ?>,
            datasets: [{
                label: 'Expenses (₹)',
                data: <?php echo json_encode($month_values); ?>,
                backgroundColor: '#6366f1',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });
</script>

<?php include 'footer.php'; ?>
