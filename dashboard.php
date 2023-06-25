<?php
session_start();
include 'database.php';

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Fetch available books from the database
$sql = "SELECT * FROM books";
$result = mysqli_query($conn, $sql);

// Handle adding books to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $bookId = $_POST['book_id'];

    // Retrieve book details
    $sql = "SELECT * FROM books WHERE id = '$bookId'";
    $bookResult = mysqli_query($conn, $sql);
    $book = mysqli_fetch_assoc($bookResult);

    // Store the book in the user's cart
    $userId = $_SESSION['user_id'];
    $price = $book['price'];

    $sql = "INSERT INTO orders (user_id, book_id, price) VALUES ('$userId', '$bookId', '$price')";
    mysqli_query($conn, $sql);
}

// Fetch user information
$userId = $_SESSION['user_id'];
$sql2 = "SELECT username FROM users WHERE id = '$userId'";
$result2 = mysqli_query($conn, $sql2);
$user = mysqli_fetch_assoc($result2);

// Fetch purchases made by the user
$sql3 = "SELECT books.title, books.author, purchases.price FROM purchases JOIN books ON purchases.book_id = books.id WHERE purchases.user_id = '$userId'";
$result3 = mysqli_query($conn, $sql3);

// Calculate the total price of all purchases
$totalPrice = 0;
$result4 = mysqli_query($conn, $sql3);
while ($row = mysqli_fetch_assoc($result4)) {
    $totalPrice += $row['price'];
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Fetch the number of books in the user's cart
$cartCount = 0;
$userId = $_SESSION['user_id'];
$sql = "SELECT COUNT(*) AS count FROM orders WHERE user_id = '$userId'";
$countResult = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($countResult);
if ($row) {
    $cartCount = $row['count'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Bookstore - Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5 font-weight-bold">Online Bookstore - Dashboard</h1>
        <h3>Welcome, <?php echo $user['username']; ?>!</h2>
        <form method="POST" action="">
            <button type="submit" name="logout" class="btn btn-warning">Logout</button>
        </form>

        <h2 class="mt-5">Available Books</h2>
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
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['author']; ?></td>
                        <td>$<?php echo $row['price']; ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="book_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="cart.php" class="btn btn-secondary">View Cart (<?php echo $cartCount; ?>)</a>

        <h2 class="mt-5">Your Purchases</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result3)): ?>
                    <tr>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['author']; ?></td>
                        <td>$<?php echo $row['price']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <h3>Total Price: $<?php echo $totalPrice; ?></h3>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>