<?php
session_start();
include 'database.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user from database
    $sql = "SELECT id FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $loginError = "Invalid username or password";
    }
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Insert new user into database
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if (mysqli_query($conn, $sql)) {
        $registrationSuccess = "Registration successful. Please log in.";
    } else {
        $registrationError = "Error registering the user.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Bookstore - Login/Register</title>
</head>
<body>
    <h1>Online Bookstore</h1>
    <h2>Login</h2>
    <?php if (isset($loginError)) echo $loginError; ?>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" name="login" value="Login">
    </form>

    <h2>Register</h2>
    <?php if (isset($registrationError)) echo $registrationError; ?>
    <?php if (isset($registrationSuccess)) echo $registrationSuccess; ?>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" name="register" value="Register">
    </form>

    <form method="POST" action="admin_login.php">
        <input type="submit" value="Admin Panel">
</body>
</html>
