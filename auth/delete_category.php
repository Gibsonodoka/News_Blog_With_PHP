<?php
require '../db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $category_id = $_GET['id'];

    // Delete the category from the 'categories' table based on the ID
    $query = "DELETE FROM categories WHERE id = :category_id";
    $statement = $conn->prepare($query);
    $statement->bindParam(':category_id', $category_id);

    if ($statement->execute()) {
        // Category deleted successfully
        header("Location: category.php"); // Redirect back to the category page
        exit();
    } else {
        echo "Error deleting category.";
    }
} else {
    echo "Invalid request.";
}
?>
