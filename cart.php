<?php
session_start();
include 'database.php';

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Handle book deletion
if (isset($_POST['delete'])) {
    $userId = $_SESSION['user_id'];
    $bookId = $_POST['book_id'];

    // Delete the book from the user's cart
    $sql = "DELETE FROM orders WHERE user_id = '$userId' AND book_id = '$bookId'";
    if (mysqli_query($conn, $sql)) {
        header("Location: cart.php");
        exit;
    } else {
        echo "Error deleting book: " . mysqli_error($conn);
    }
}

// Fetch books in the user's cart
$userId = $_SESSION['user_id'];
$sql = "SELECT books.title, books.author, orders.price, orders.book_id FROM orders JOIN books ON orders.book_id = books.id WHERE orders.user_id = '$userId'";
$result = mysqli_query($conn, $sql);

// Calculate the total price
$totalPrice = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $totalPrice += $row['price'];
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Bookstore - Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="content-wrapper">
        <div class="container">
            <h1 class="mt-5 font-weight-bold">Online Bookstore - Cart</h1>

            <h2 class="mt-5">Selected Books</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php mysqli_data_seek($result, 0); // Reset the result pointer ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['author']; ?></td>
                            <td>$<?php echo $row['price']; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-2">
                    <form method="POST" action="purchase.php">
                        <button type="submit" name="purchase" class="btn btn-success">Purchase</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <h3>Total Price: $<?php echo $totalPrice; ?></h3>
                </div>
            </div>

            <form method="POST" action="dashboard.php">
                <button type="submit" name="back_to_dashboard" class="btn btn-secondary mt-5">Back to Dashboard</button>
            </form>

            <form method="POST" action="">
                <button type="submit" name="logout" class="btn btn-warning my-3">Logout</button>
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