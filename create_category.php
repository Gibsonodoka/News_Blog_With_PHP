<?php
require 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = $_POST['category_name'];
    $language = $_POST['language'];
    $slug = $_POST['slug'];
    $keywords = $_POST['keywords'];
    $description = $_POST['description'];

    // Validate the category name (you can add more validation)
    if (empty($category_name)) {
        echo "Category name cannot be empty.";
    } else {
        // Insert the new category into the 'categories' table
        $query = "INSERT INTO categories (category_name, language, slug, keywords, description) VALUES (:category_name, :language, :slug, :keywords, :description)";
        $statement = $conn->prepare($query);
        $statement->bindParam(':category_name', $category_name);
        $statement->bindParam(':language', $language);
        $statement->bindParam(':slug', $slug);
        $statement->bindParam(':keywords', $keywords);
        $statement->bindParam(':description', $description);

        if ($statement->execute()) {
            // Redirect back to category.php
            header("Location: auth/category.php");
            exit();
        } else {
            echo "Error creating category.";
        }
    }
}
?>
