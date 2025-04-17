<?php

session_start();
session_unset();  // Clears all session variables
session_destroy(); // Ends the session
session_start();  // Start a new one fresh again

include '../db_connect.php';

// SIGNUP
if (isset($_POST['signup'])) {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo "Email already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);

        // Redirect to login form after successful signup
        header("Location: login.php?registered=1");
        exit;
    }
}

// LOGIN
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];

        // Redirect to dashboard or homepage
        header("Location: index.php");
        exit;
    } else {
        echo "Invalid email or password!";
    }
}
?>
