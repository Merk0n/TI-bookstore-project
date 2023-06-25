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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="content-wrapper">
        <div class="container">
            <h1 class="my-5 font-weight-bold">Admin Login</h1>
            <?php if (isset($loginError)) { ?>
                <p><?php echo $loginError; ?></p>
            <?php } ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary mb-3">Login</button>
            </form>

            <form method="GET" action="index.php">
                <button type="submit" class="btn btn-secondary">Back to Main Page</button>
            </form>
        </div>
    </div>
    
    <footer class="footer">
        <div class="container">
            <p class="text-center m-0">&#9749; Michał Marek - Techniki Internetu(2023L)</p>
        </div>
    </footer>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>