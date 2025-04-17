
    <?php
session_start();
include '../db_connect.php';

$alert = "";
$autoSwitchToLogin = false;

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signup'])) {
        $name = $_POST['full_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check if user already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $alert = "User with this email already exists!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $password]);
            $alert = "Registration successful! Please log in.";
            $autoSwitchToLogin = true;
        }
    } elseif (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            
            $_SESSION['username'] = $user['username'];
            header("Location: ../index.php");
            exit;
        } else {
            $alert = "Invalid email or password!";
            $autoSwitchToLogin = true;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login / Sign Up</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background: #f0f8ff;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .form-container {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 300px;
        text-align: center;
    }
    h2 {
        color: #ff69b4;
        margin-bottom: 20px;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-sizing: border-box;
    }
    button {
        width: 100%;
        padding: 12px;
        background-color: #ff69b4;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s;
    }
    button:hover {
        background-color: #ff1493;
    }
    .toggle-link {
        margin-top: 15px;
        color: #007BFF;
        cursor: pointer;
        text-decoration: underline;
    }
    .form-title {
        font-size: 24px;
        margin-bottom: 10px;
    }
    </style>
</head>
<body>

<div class="form-container" id="formContainer">
    <h2 class="form-title" id="formTitle">Sign Up</h2>
    <form id="signupForm" method="POST">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="signup">Sign Up</button>
    </form>
    <div class="toggle-link" onclick="toggleForm()">Already have an account? Login</div>
</div>

<script>
    function toggleForm() {
        const formTitle = document.getElementById('formTitle');
        const form = document.getElementById('signupForm');

        if (formTitle.textContent === 'Sign Up') {
            formTitle.textContent = 'Login';
            form.innerHTML = `
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            `;
            document.querySelector('.toggle-link').textContent = "Don't have an account? Sign Up";
        } else {
            formTitle.textContent = 'Sign Up';
            form.innerHTML = `
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="signup">Sign Up</button>
            `;
            document.querySelector('.toggle-link').textContent = "Already have an account? Login";
        }
    }

    // Show alert if needed
    <?php if ($alert): ?>
        alert("<?= $alert ?>");
    <?php endif; ?>

    // Auto switch to login form if flagged by PHP
    <?php if ($autoSwitchToLogin): ?>
        toggleForm();
    <?php endif; ?>
</script>

</body>
</html>
