<?php
include 'header.php';

$user_id = $_SESSION['user_id'];

// Get unique categories for filter
$stmt = $pdo->prepare("SELECT DISTINCT category FROM transactions WHERE user_id = ?");
$stmt->execute([$user_id]);
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Handle Filters and Search
$where = ["user_id = ?"];
$params = [$user_id];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $where[] = "description LIKE ?";
    $params[] = "%" . $_GET['search'] . "%";
}

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $where[] = "category = ?";
    $params[] = $_GET['category'];
}

if (isset($_GET['type']) && !empty($_GET['type'])) {
    $where[] = "type = ?";
    $params[] = $_GET['type'];
}

if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
    $where[] = "date >= ?";
    $params[] = $_GET['date_from'];
}

if (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
    $where[] = "date <= ?";
    $params[] = $_GET['date_to'];
}

$query = "SELECT * FROM transactions WHERE " . implode(" AND ", $where) . " ORDER BY date DESC, id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$transactions = $stmt->fetchAll();
?>

<div class="dashboard-header animate-fade-in">
    <h1>Transaction History</h1>
    <div style="display: flex; gap: 0.5rem;">
        <a href="export.php" class="btn btn-secondary" style="width: auto;"><i class="fas fa-file-export"></i> Export CSV</a>
        <a href="add_transaction.php" class="btn" style="width: auto;"><i class="fas fa-plus"></i> Add New</a>
    </div>
</div>

<div class="card animate-fade-in" style="margin-bottom: 2rem;">
    <form action="history.php" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label>Search</label>
            <input type="text" name="search" placeholder="Search description..." value="<?php echo $_GET['search'] ?? ''; ?>">
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label>Category</label>
            <select name="category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $cat) ? 'selected' : ''; ?>>
                        <?php echo $cat; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label>Type</label>
            <select name="type">
                <option value="">All Types</option>
                <option value="income" <?php echo (isset($_GET['type']) && $_GET['type'] == 'income') ? 'selected' : ''; ?>>Income</option>
                <option value="expense" <?php echo (isset($_GET['type']) && $_GET['type'] == 'expense') ? 'selected' : ''; ?>>Expense</option>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label>From</label>
            <input type="date" name="date_from" value="<?php echo $_GET['date_from'] ?? ''; ?>">
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label>To</label>
            <input type="date" name="date_to" value="<?php echo $_GET['date_to'] ?? ''; ?>">
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn"><i class="fas fa-filter"></i> Filter</button>
            <a href="history.php" class="btn btn-secondary" style="text-decoration: none; text-align: center;">Reset</a>
        </div>
    </form>
</div>

<div class="card animate-fade-in">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted);">No transactions found matching your criteria.</td>
                    </tr>
                <?php else: foreach ($transactions as $row): ?>
                    <tr>
                        <td><?php echo date('d M, Y', strtotime($row['date'])); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td style="font-weight: 600;">₹<?php echo number_format($row['amount'], 2); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $row['type']; ?>">
                                <?php echo ucfirst($row['type']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-links">
                                <a href="edit_transaction.php?id=<?php echo $row['id']; ?>" class="btn btn-sm" style="background: var(--primary-color); text-decoration: none;"><i class="fas fa-edit"></i></a>
                                <a href="delete_transaction.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" style="text-decoration: none;" onclick="return confirm('Are you sure you want to delete this transaction?')"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
