<?php
include 'header.php';

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Fetch current user data
$stmt = $pdo->prepare("SELECT username, email, budget_limit FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $username = trim($_POST['username']);
        $budget_limit = $_POST['budget_limit'];

        if (empty($username)) {
            $error = "Username cannot be empty.";
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE users SET username = ?, budget_limit = ? WHERE id = ?");
                $stmt->execute([$username, $budget_limit, $user_id]);
                $_SESSION['username'] = $username;
                $success = "Profile updated successfully!";
                $user['username'] = $username;
                $user['budget_limit'] = $budget_limit;
            } catch (PDOException $e) {
                $error = "Update failed: " . $e->getMessage();
            }
        }
    } elseif (isset($_POST['change_password'])) {
        $old_pass = $_POST['old_password'];
        $new_pass = $_POST['new_password'];
        $confirm_pass = $_POST['confirm_password'];

        // Verify old password
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $stored_pass = $stmt->fetchColumn();

        if (!password_verify($old_pass, $stored_pass)) {
            $error = "Current password is incorrect.";
        } elseif ($new_pass !== $confirm_pass) {
            $error = "New passwords do not match.";
        } elseif (strlen($new_pass) < 6) {
            $error = "New password must be at least 6 characters.";
        } else {
            try {
                $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed, $user_id]);
                $success = "Password changed successfully!";
            } catch (PDOException $e) {
                $error = "Password update failed.";
            }
        }
    }
}
?>

<div class="dashboard-header animate-fade-in">
    <h1>Profile & Settings</h1>
</div>

<?php if ($success): ?>
    <div class="alert alert-success animate-fade-in"><?php echo $success; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error animate-fade-in"><?php echo $error; ?></div>
<?php endif; ?>

<div class="stats-grid animate-fade-in">
    <!-- Profile Info -->
    <div class="card">
        <h3><i class="fas fa-user-cog"></i> Profile Information</h3>
        <form action="profile.php" method="POST" style="margin-top: 1rem;">
            <div class="form-group">
                <label>Email Address (Cannot be changed)</label>
                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="opacity: 0.7;">
            </div>
            <div class="form-group">
                <label for="username">Display Name</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="budget_limit">Monthly Budget Limit (₹)</label>
                <input type="number" step="0.01" id="budget_limit" name="budget_limit" value="<?php echo $user['budget_limit']; ?>">
                <small class="text-muted">Set to 0 to disable warnings.</small>
            </div>
            <button type="submit" name="update_profile" class="btn">Update Profile</button>
        </form>
    </div>

    <!-- Change Password -->
    <div class="card">
        <h3><i class="fas fa-key"></i> Change Password</h3>
        <form action="profile.php" method="POST" style="margin-top: 1rem;">
            <div class="form-group">
                <label for="old_password">Current Password</label>
                <input type="password" id="old_password" name="old_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" name="change_password" class="btn btn-secondary">Change Password</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
