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

include 'database.php';

// Fetch all books from the database
$sql = "SELECT * FROM books";
$result = mysqli_query($conn, $sql);

// Handle book modification
if (isset($_POST['modify_book'])) {
    $bookId = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];

    // Validate the price
    if (!is_numeric($price)) {
        $error = "Please enter a valid numeric value for the price.";
    } else {
        // Update the book details in the database
        $updateSql = "UPDATE books SET title = '$title', author = '$author', price = '$price' WHERE id = '$bookId'";
        mysqli_query($conn, $updateSql);

        // Redirect back to admin_books.php
        header("Location: admin_books.php");
        exit;
    }
}

// Handle book deletion
if (isset($_POST['delete_book'])) {
    $bookId = $_POST['book_id'];

    // Delete the book from the database
    $deleteSql = "DELETE FROM books WHERE id = '$bookId'";
    mysqli_query($conn, $deleteSql);

    // Redirect back to admin_books.php
    header("Location: admin_books.php");
    exit;
}

// Handle book addition
if (isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];

    // Validate the price
    if (!is_numeric($price)) {
        $error = "Please enter a valid numeric value for the price.";
    } else {
        // Insert the new book into the database
        $insertSql = "INSERT INTO books (title, author, price) VALUES ('$title', '$author', '$price')";
        mysqli_query($conn, $insertSql);

        // Redirect back to admin_books.php
        header("Location: admin_books.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Books</title>
</head>
<body>
    <h1>Admin Panel - Books</h1>
    <form method="POST" action="">
        <input type="submit" name="logout" value="Logout">
    </form>

    <!-- Display the error message if it exists -->
    <?php if (isset($error)) : ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>

    <h2>Books List</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <form method="POST" action="">
                    <td><input type="text" name="title" value="<?php echo $row['title']; ?>" required></td>
                    <td><input type="text" name="author" value="<?php echo $row['author']; ?>" required></td>
                    <td><input type="text" name="price" value="<?php echo $row['price']; ?>" required></td>
                    <td>
                        <input type="hidden" name="book_id" value="<?php echo $row['id']; ?>">
                        <input type="submit" name="modify_book" value="Modify">
                        <input type="submit" name="delete_book" value="Delete">
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <h2>Add New Book</h2>
    <form method="POST" action="">
        <label>Title:</label>
        <input type="text" name="title" required><br>

        <label>Author:</label>
        <input type="text" name="author" required><br>

        <label>Price:</label>
        <input type="text" name="price" required><br>

        <input type="submit" name="add_book" value="Add Book">
    </form>

    <!-- Button to go back to admin.php -->
    <form method="POST" action="admin.php">
        <input type="submit" name="back_admin" value="Back to Admin Panel">
    </form>
</body>
</html>
