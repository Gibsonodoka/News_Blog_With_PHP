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

// Delete category if a delete request is sent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $category_id = $_POST['delete_category']; // This assumes you have an input with the name 'delete_category' in your form

    // Perform the DELETE operation using the primary key 'id'
    $deleteQuery = "DELETE FROM categories WHERE id = :category_id";
    $deleteStatement = $conn->prepare($deleteQuery);
    $deleteStatement->bindParam(':category_id', $category_id);

    if ($deleteStatement->execute()) {
        echo "Category deleted successfully.";
        // Redirect or refresh the page as needed
    } else {
        echo "Error deleting category.";
    }
}

?>
<div class="content">

    <div class="create-category">
        <h3>Create New Category</h3>
        <form action="../create_category.php" method="post">
            <label for="category_name">Category Name:</label>
            <input type="text" name="category_name" required>

            <label for="language">Language:</label>
                <select name="language">
                    <option value="English">English</option>
                    <option value="Spanish">Spanish</option>
                    <option value="French">French</option>
                    <option value="Arabic">Arabic</option>
                </select>


            <label for="slug">Slug:</label>
            <input type="text" name="slug">

            <label for="keywords">Keywords:</label>
            <input type="text" name="keywords">

            <label for="description">Description:</label>
            <textarea name="description" rows="4"></textarea>

            <button type="submit">Create Category</button>
        </form>
    </div>

    <!-- Display the list of categories -->
    <div class="category-list">
        <h3>Categories List</h3> 
        <table>
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Language</th>
                    <th>Slug</th>
                    <th>Keywords</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo $category['category_name']; ?></td>
                        <td><?php echo $category['language']; ?></td>
                        <td><?php echo $category['slug']; ?></td>
                        <td><?php echo $category['keywords']; ?></td>
                        <td><?php echo $category['description']; ?></td>
                        <td>
                            <form action="category.php" method="post">
                                <input type="hidden" name="delete_category" value="<?php echo $category['id']; ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
