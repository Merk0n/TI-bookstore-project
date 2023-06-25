<?php
session_start();

// Check if the admin is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit;
}

// Handle login form submission
if (isset($_POST['login'])) {
    $adminUsername = $_POST['username'];
    $adminPassword = $_POST['password'];

    // Check if the admin credentials are valid
    if ($adminUsername === 'admin' && $adminPassword === 'admin') {
        $_SESSION['admin_id'] = 1; // Set a flag or ID to indicate admin is logged in
        header("Location: admin.php");
        exit;
    } else {
        $loginError = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
    <h1>Admin Login</h1>
    <?php if (isset($loginError)) { ?>
        <p><?php echo $loginError; ?></p>
    <?php } ?>
    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" name="login" value="Login">
    </form>

    <form method="GET" action="index.php">
        <input type="submit" value="Back to Main Page">
    </form>
</body>
</html>
