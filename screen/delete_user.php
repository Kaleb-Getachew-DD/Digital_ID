<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $user_name = $_POST['user_name'];
    $position = $_POST['position'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE users SET 
        full_name = ?, 
        user_name = ?, 
        position = ?, 
        status = ? 
        WHERE id = ?");

    $stmt->execute([$full_name, $user_name, $position, $status, $id]);
    
    header("Location: users.php");
    exit();
}
?>