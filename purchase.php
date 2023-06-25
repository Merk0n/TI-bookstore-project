<?php
session_start();
include 'database.php';

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Handle the purchase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['purchase'])) {
    $userId = $_SESSION['user_id'];

    // Fetch books in the user's cart
    $sql = "SELECT book_id, price FROM orders WHERE user_id = '$userId'";
    $result = mysqli_query($conn, $sql);

    // Insert the purchase details into the database
    while ($row = mysqli_fetch_assoc($result)) {
        $bookId = $row['book_id'];
        $price = $row['price'];

        $sql = "INSERT INTO purchases (user_id, book_id, price) VALUES ('$userId', '$bookId', '$price')";
        mysqli_query($conn, $sql);
    }

    // Clear the user's cart
    $sql = "DELETE FROM orders WHERE user_id = '$userId'";
    mysqli_query($conn, $sql);

    header("Location: dashboard.php");
    exit;
}
?>
