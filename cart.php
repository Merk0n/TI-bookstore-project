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
</head>
<body>
    <h1>Online Bookstore - Cart</h1>

    <h2>Selected Books</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        <?php mysqli_data_seek($result, 0); // Reset the result pointer ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['author']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                        <input type="submit" name="delete" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Total Price: <?php echo $totalPrice; ?></h3>

    <form method="POST" action="purchase.php">
        <input type="submit" name="purchase" value="Purchase">
    </form>

    <form method="POST" action="dashboard.php">
        <input type="submit" name="back_to_dashboard" value="Back to Dashboard">

    <form method="POST" action="">
        <input type="submit" name="logout" value="Logout">
    </form>
    </form>
</body>
</html>
