<?php
require '../db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Perform the deletion
    $query = "DELETE FROM posts WHERE id = :post_id";
    $statement = $conn->prepare($query);
    $statement->bindParam(':post_id', $post_id);

    if ($statement->execute()) {
        // Deletion was successful, you can redirect back to the posts page
        header("Location: posts.php");
        exit();
    } else {
        echo "Error deleting post.";
    }
} else {
    // Handle invalid request or missing post ID
    echo "Invalid request or missing post ID.";
}
?>
