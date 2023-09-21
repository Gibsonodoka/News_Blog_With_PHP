<?php
require '../db.php'; 
session_start();

// Check if the user is logged in and has the admin role on the server-side
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

// Fetch the admin's name from the database
require '../db.php'; // Include your database connection file
$user_id = $_SESSION['user_id'];
$query = "SELECT username FROM users WHERE id = :user_id"; // Use the correct column name
$statement = $conn->prepare($query);
$statement->bindParam(':user_id', $user_id);
$statement->execute();
$adminName = $statement->fetchColumn();

// Check if a post ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $post_id = $_GET['id'];

    // Fetch the existing post data
    $query = "SELECT * FROM posts WHERE id = :post_id";
    $statement = $conn->prepare($query);
    $statement->bindParam(':post_id', $post_id);
    $statement->execute();
    $post = $statement->fetch(PDO::FETCH_ASSOC);

    // Check if the post exists
    if (!$post) {
        echo "Post not found.";
        exit();
    }
} else {
    echo "Invalid post ID.";
    exit();
}

// Fetch categories from the database
$query = "SELECT * FROM categories"; // Replace 'categories' with your table name
$categoriesStatement = $conn->prepare($query);
$categoriesStatement->execute();
$categories = $categoriesStatement->fetchAll();

// Now include admin_header.php
require '../admin_header.php'; // Adjust the path as needed
require '../dash_nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="../public/css/admin-style.css">
</head>
<body>
    <div class="content">
        <h2>Edit Post</h2>

        <form action="update_post.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">

            <label for="title">Title:</label>
            <input type="text" name="title" value="<?php echo $post['title']; ?>" required>

            <label for="content">Content:</label>
            <textarea name="content" rows="4" required><?php echo $post['content']; ?></textarea>

            <label for="image">Image:</label>
            <input type="file" name="image">

            <!-- Display the existing image -->
            <img src="../uploads/<?php echo $post['image']; ?>" alt="Current Image" width="100">

            <label for="category">Category:</label>
            <select name="category" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ($post['category'] == $category['id']) ? 'selected' : ''; ?>>
                        <?php echo $category['category_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Update Post</button>
        </form>
    </div>
</body>
</html>
<?php require '../footer.php'; ?>
