<?php
include 'header.php';

$user_id = $_SESSION['user_id'];

// Get totals
$stmt = $pdo->prepare("SELECT 
    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense
    FROM transactions WHERE user_id = ?");
$stmt->execute([$user_id]);
$stats = $stmt->fetch();

$total_income = $stats['total_income'] ?? 0;
$total_expense = $stats['total_expense'] ?? 0;
$balance = $total_income - $total_expense;

// Get recent transactions (last 5)
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC, id DESC LIMIT 5");
$stmt->execute([$user_id]);
$recent_transactions = $stmt->fetchAll();

// Get budget limit
$stmt = $pdo->prepare("SELECT budget_limit FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch();
$budget_limit = $user_data['budget_limit'] ?? 0;

$budget_warning = ($budget_limit > 0 && $total_expense > $budget_limit);
?>

<div class="dashboard-header animate-fade-in">
    <div>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p class="text-muted">Here's your financial overview.</p>
    </div>
    <a href="add_transaction.php" class="btn" style="width: auto; padding: 0.75rem 1.5rem;">
        <i class="fas fa-plus"></i> Add Transaction
    </a>
</div>

<?php if ($budget_warning): ?>
    <div class="alert alert-error animate-fade-in">
        <i class="fas fa-exclamation-triangle"></i> 
        <strong>Budget Warning!</strong> Your total expenses (₹<?php echo number_format($total_expense, 2); ?>) have exceeded your monthly budget limit (₹<?php echo number_format($budget_limit, 2); ?>).
    </div>
<?php endif; ?>

<div class="stats-grid animate-fade-in">
    <div class="card">
        <div class="stat-label">Total Balance</div>
        <div class="stat-value value-balance">₹<?php echo number_format($balance, 2); ?></div>
    </div>
    <div class="card">
        <div class="stat-label">Total Salary (Income)</div>
        <div class="stat-value value-income">₹<?php echo number_format($total_income, 2); ?></div>
    </div>
    <div class="card">
        <div class="stat-label">Total Expense</div>
        <div class="stat-value value-expense">₹<?php echo number_format($total_expense, 2); ?></div>
    </div>
</div>

<?php if ($budget_limit > 0): 
    $percentage = ($total_expense / $budget_limit) * 100;
    $bar_class = ($percentage > 100) ? 'warning' : (($percentage > 80) ? '' : 'success');
    $display_percentage = min(100, $percentage);
?>
<div class="card animate-fade-in" style="margin-top: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h3>Monthly Budget Progress</h3>
        <span class="text-muted" style="font-size: 0.875rem;">Limit: ₹<?php echo number_format($budget_limit, 2); ?></span>
    </div>
    
    <div class="progress-container">
        <div class="progress-bar <?php echo $bar_class; ?>" style="width: <?php echo $display_percentage; ?>%;"></div>
    </div>
    
    <div class="budget-stats">
        <span>Used: ₹<?php echo number_format($total_expense, 2); ?> (<?php echo round($percentage, 1); ?>%)</span>
        <span>Remaining: ₹<?php echo number_format(max(0, $budget_limit - $total_expense), 2); ?></span>
    </div>
</div>
<?php endif; ?>

<div class="card animate-fade-in" style="margin-top: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3>Recent Transactions</h3>
        <a href="history.php" style="font-size: 0.875rem; color: var(--primary-color); text-decoration: none;">View All</a>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recent_transactions)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted);">No transactions found. Start by adding one!</td>
                    </tr>
                <?php else: foreach ($recent_transactions as $row): ?>
                    <tr>
                        <td><?php echo date('d M, Y', strtotime($row['date'])); ?></td>
                        <td><i class="fas fa-tag"></i> <?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td style="font-weight: 600;">₹<?php echo number_format($row['amount'], 2); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $row['type']; ?>">
                                <?php echo ucfirst($row['type']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
