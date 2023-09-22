<!-- posts.php -->
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

// Fetch categories from the database
$query = "SELECT * FROM categories"; // Replace 'categories' with your table name
$categoriesStatement = $conn->prepare($query);
$categoriesStatement->execute();
$categories = $categoriesStatement->fetchAll();

// Now include admin_header.php
require '../admin_header.php'; // Adjust the path as needed
require '../dash_nav.php';

// Pagination variables
$postsPerPage = 10; // Adjust as needed
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Get the current page from the URL

// Calculate the OFFSET for SQL query
$offset = ($page - 1) * $postsPerPage;

// Fetch and display the list of posts with pagination
$query = "SELECT * FROM posts LIMIT :limit OFFSET :offset";
$statement = $conn->prepare($query);
$statement->bindValue(':limit', $postsPerPage, PDO::PARAM_INT);
$statement->bindValue(':offset', $offset, PDO::PARAM_INT);
$statement->execute();
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);

// Calculate the total number of posts
$totalPostsQuery = "SELECT COUNT(*) FROM posts";
$totalPostsStatement = $conn->query($totalPostsQuery);
$totalPosts = $totalPostsStatement->fetchColumn();

// Calculate the total number of pages
$totalPages = ceil($totalPosts / $postsPerPage);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
    <link rel="stylesheet" href="../public/css/admin-style.css">
</head>
<body>
    


<div class="content">
    <h2>Posts</h2>

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($posts as $post) {
                echo "<tr>";
                echo "<td><img src='../uploads/{$post['image']}' alt='Thumbnail' width='100' height='100'></td>";
                echo "<td>{$post['title']}</td>";
                echo "<td>";
                echo "<a href='javascript:void(0);' onclick='editPost({$post['id']})' class='edit-post-button'>Edit</a>";
                echo "<a href='delete_post.php?id={$post['id']}' class='delete-post-button'>Delete</a>";
                echo "</td>";
            }
            ?>
        </tbody>
    </table>

    <!-- Pagination links -->
    <div class="pagination">
        <?php
        for ($i = 1; $i <= $totalPages; $i++) {
            $isActive = $i == $page ? 'active' : '';
            echo "<a href='post.php?page=$i' class='pagination-link $isActive'>$i</a>";
        }
        ?>
    </div>
</div>
<script src="../public/js/admin_script.js"></script>
</body>
</html>
<?php require '../footer.php'; ?>
