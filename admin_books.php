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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="content-wrapper">
        <div class="container">
            <h1 class="mt-5 font-weight-bold">Admin Panel - Books</h1>
            <form method="POST" action="">
                <button type="submit" name="logout" class="btn btn-danger">Logout</button>
            </form>

            <!-- Display the error message if it exists -->
            <?php if (isset($error)) : ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>

            <h2 class="mt-5">Books List</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <form method="POST" action="">
                                <td><?php echo $row['id']; ?></td>
                                <td><input type="text" name="title" value="<?php echo $row['title']; ?>" required></td>
                                <td><input type="text" name="author" value="<?php echo $row['author']; ?>" required></td>
                                <td>$ <input type="text" name="price" value="<?php echo $row['price']; ?>" required></td>
                                <td>
                                    <input type="hidden" name="book_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="modify_book" class="btn btn-primary">Modify</button>
                                    <button type="submit" name="delete_book" class="btn btn-danger">Delete</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h2 class="mt-5">Add New Book</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="author">Author:</label>
                    <input type="text" name="author" id="author" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" name="price" id="price" class="form-control" required>
                </div>

                <button type="submit" name="add_book" class="btn btn-success">Add Book</button>
            </form>

            <!-- Button to go back to admin.php -->
            <form method="POST" action="admin.php">
                <button type="submit" name="back_admin" class="btn btn-secondary my-3">Back to Admin Panel</button>
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
