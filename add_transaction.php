<?php
include 'header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $date = $_POST['date'];
    $description = trim($_POST['description']);
    $user_id = $_SESSION['user_id'];

    if (empty($amount) || empty($type) || empty($category) || empty($date)) {
        $error = "Please fill in all required fields.";
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $error = "Amount must be a positive number.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, amount, type, category, date, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $amount, $type, $category, $date, $description]);
            $success = "Transaction added successfully!";
        } catch (PDOException $e) {
            $error = "Error adding transaction: " . $e->getMessage();
        }
    }
}

$categories = [
    'Food' => 'fas fa-utensils',
    'Shopping' => 'fas fa-shopping-cart',
    'Travel' => 'fas fa-plane',
    'Entertainment' => 'fas fa-film',
    'Bills' => 'fas fa-file-invoice-dollar',
    'Health' => 'fas fa-heartbeat',
    'Salary' => 'fas fa-money-bill-wave',
    'Other' => 'fas fa-box'
];
?>

<div class="container animate-fade-in" style="max-width: 600px;">
    <div class="card">
        <h2 style="margin-bottom: 1.5rem;"><i class="fas fa-plus-circle"></i> Add Transaction</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="add_transaction.php" method="POST">
            <div class="form-group">
                <label for="amount">Amount (₹)</label>
                <input type="number" step="0.01" id="amount" name="amount" placeholder="0.00" required>
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type" required>
                    <option value="expense">Expense</option>
                    <option value="income">Income</option>
                </select>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <?php foreach ($categories as $cat => $icon): ?>
                        <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description (Optional)</label>
                <textarea id="description" name="description" rows="3" placeholder="What was this for?"></textarea>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn">Add Transaction</button>
                <a href="dashboard.php" class="btn btn-secondary" style="text-decoration: none; text-align: center;">Back</a>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
