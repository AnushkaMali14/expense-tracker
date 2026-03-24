<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Ensure user owns the transaction
        $stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
        
        header("Location: history.php?msg=deleted");
    } catch (PDOException $e) {
        die("Error deleting transaction: " . $e->getMessage());
    }
} else {
    redirect('history.php');
}
?>
