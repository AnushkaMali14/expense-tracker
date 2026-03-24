<?php
require_once 'config.php';

// Clear session variables
$_SESSION = array();

// Destroy session
session_destroy();

// Redirect to login
header("Location: index.php");
exit();
?>
