<?php
include 'db_connect.php'; // Include the database connection

// Example user data
$username = 'admin';
$password = 'admin123'; // Plain text password

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert the user into the database
$stmt = $pdo->prepare("INSERT INTO admin (username, password) VALUES (:username, :password)");
$stmt->execute(['username' => $username, 'password' => $hashedPassword]);

echo "User created successfully!";
?>