<?php
session_start();

// Check if the admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: admin_login.php");
    exit;
}

// Connect to the database and retrieve the purchases
include 'database.php';

$sql = "SELECT purchases.id, purchases.user_id, users.username, books.title, books.author, purchases.price FROM purchases JOIN users ON purchases.user_id = users.id JOIN books ON purchases.book_id = books.id";
$result = mysqli_query($conn, $sql);

// Handle purchase deletion
if (isset($_POST['delete'])) {
    $purchaseId = $_POST['purchase_id'];

    // Delete the purchase from the database
    $deleteSql = "DELETE FROM purchases WHERE id = '$purchaseId'";
    mysqli_query($conn, $deleteSql);

    // Redirect back to the admin page
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5 font-weight-bold">Admin Panel</h1>
        <form method="POST" action="">
            <button type="submit" name="logout" class="btn btn-warning">Logout</button>
        </form>

        <h2 class="mt-5">Admin Dashboard</h2>
        <form method="GET" action="admin_books.php">
            <button type="submit" class="btn btn-secondary">Manage Books</button>
        </form>
        <br>
    
        <h2 class="mt-5">Purchases</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Book Title</th>
                    <th>Book Author</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['author']; ?></td>
                        <td>$<?php echo $row['price']; ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="purchase_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
