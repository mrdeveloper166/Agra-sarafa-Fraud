<?php
session_start();
include 'db_connect.php';

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if(isset($_POST['id'])) {
    $id = $_POST['id'];

    // Delete the record from the database
    $stmt = $pdo->prepare("DELETE FROM members WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: dashboard.php"); // Redirect back to the dashboard
    exit();
}
?> 