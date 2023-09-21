<?php
require '../db.php'; 
//require '../admin_header.php'; // Adjust the path as needed
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

// Now include admin_header.php
require '../admin_header.php'; // Adjust the path as needed
require '../dash_nav.php';
?>
    <div class="content">
    <div class="post-form">
    <h2>Create a New Post</h2>
    <!-- Your form for creating new posts here -->
    <form action="../create_post.php" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" required>
        
        <label for="content">Content:</label>
        <textarea name="content" rows="4" required></textarea>
        
        <label for="image">Image:</label>
        <input type="file" name="image">

        <!-- Add a dropdown for selecting the category -->
        <label for="category">Category:</label>
            <select name="category" required>
                <option value="" disabled selected>Select Category</option> <!-- Default option -->
                <?php
                // Fetch categories from the database and populate the dropdown
                $query = "SELECT * FROM categories";
                $statement = $conn->prepare($query);
                $statement->execute();
                $categories = $statement->fetchAll(PDO::FETCH_ASSOC);

                foreach ($categories as $category) {
                    echo "<option value='{$category['id']}'>{$category['category_name']}</option>";
                }
                ?>
            </select>
        
        <button type="submit">Create Post</button>
    </form>
</div>

    </div>