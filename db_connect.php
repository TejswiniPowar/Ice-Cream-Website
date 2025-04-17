<?php
$host = 'localhost';
$db = 'temp_ice';
$user = 'root';
$pass = ''; // Update if your MySQL has a password

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
