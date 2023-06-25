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

    // Check if username already exists in the database
    $checkSql = "SELECT id FROM users WHERE username = '$username'";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        $registrationError = "Username already exists. Please choose a different username.";
    } else {
        // Insert new user into database
        $insertSql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($conn, $insertSql)) {
            $registrationSuccess = "Registration successful. Please log in.";
        } else {
            $registrationError = "Error registering the user.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Bookstore - Login/Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="content-wrapper">
        <div class="container">
            <h1 class="my-5 font-weight-bold">Online Bookstore</h1>

            <div class="row">
                <div class="col-md-6">
                    <h2>Login</h2>
                    <form method="POST" action="">
                        <?php if (isset($loginError)): ?>
                            <div class="alert alert-danger"><?php echo $loginError; ?></div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary">Login</button>
                    </form>
                </div>

                <div class="col-md-6">
                    <h2>Register</h2>
                    <form method="POST" action="">
                        <?php if (isset($registrationError)): ?>
                            <div class="alert alert-danger"><?php echo $registrationError; ?></div>
                        <?php endif; ?>
                        <?php if (isset($registrationSuccess)): ?>
                            <div class="alert alert-success"><?php echo $registrationSuccess; ?></div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="register" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>

            <form method="POST" action="admin_login.php">
                <button type="submit" class="btn btn-secondary mt-3">Admin Panel</button>
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