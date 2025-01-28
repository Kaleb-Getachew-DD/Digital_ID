<?php
// Start the session
session_start();

include 'db_connection.php';

$user_name = $_SESSION['user_name'];
$stmt = $conn->prepare("UPDATE user SET status = '0' WHERE user_name = ?");
$stmt->bind_param("s", $user_name);
$stmt->execute();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit();
?>
