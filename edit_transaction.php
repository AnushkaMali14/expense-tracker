<?php
include 'header.php';

$id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$id) {
    redirect('history.php');
}

// Fetch transaction details
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$transaction = $stmt->fetch();

if (!$transaction) {
    redirect('history.php');
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $date = $_POST['date'];
    $description = trim($_POST['description']);

    if (empty($amount) || empty($type) || empty($category) || empty($date)) {
        $error = "Please fill in all required fields.";
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $error = "Amount must be a positive number.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE transactions SET amount = ?, type = ?, category = ?, date = ?, description = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$amount, $type, $category, $date, $description, $id, $user_id]);
            $success = "Transaction updated successfully! <a href='history.php'>View History</a>";
            
            // Refetch updated data
            $stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ?");
            $stmt->execute([$id]);
            $transaction = $stmt->fetch();
        } catch (PDOException $e) {
            $error = "Error updating transaction: " . $e->getMessage();
        }
    }
}

$categories = ['Food', 'Shopping', 'Travel', 'Entertainment', 'Bills', 'Health', 'Salary', 'Other'];
?>

<div class="container animate-fade-in" style="max-width: 600px;">
    <div class="card">
        <h2 style="margin-bottom: 1.5rem;"><i class="fas fa-edit"></i> Edit Transaction</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="edit_transaction.php?id=<?php echo $id; ?>" method="POST">
            <div class="form-group">
                <label for="amount">Amount (₹)</label>
                <input type="number" step="0.01" id="amount" name="amount" value="<?php echo $transaction['amount']; ?>" required>
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type" required>
                    <option value="expense" <?php echo ($transaction['type'] == 'expense') ? 'selected' : ''; ?>>Expense</option>
                    <option value="income" <?php echo ($transaction['type'] == 'income') ? 'selected' : ''; ?>>Income</option>
                </select>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat; ?>" <?php echo ($transaction['category'] == $cat) ? 'selected' : ''; ?>><?php echo $cat; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" value="<?php echo $transaction['date']; ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($transaction['description']); ?></textarea>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn">Update Transaction</button>
                <a href="history.php" class="btn btn-secondary" style="text-decoration: none; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
