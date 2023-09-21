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

// Include the admin header and navigation
require '../admin_header.php'; // Adjust the path as needed
require '../dash_nav.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="../public/css/admin-style.css">
</head>
<body>
    <div class="content">
        <h2>Manage Users</h2>
        <a href="add_user.php" class="add-user-button">Add User</a> <!-- Link to add a new user -->
   

    <!-- Display user data in a table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Date Registered</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch and display user data here
            $query = "SELECT id, username, email, role, status, created_at FROM users";
            $statement = $conn->prepare($query);
            $statement->execute();
            $user_data = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($user_data as $user) {
                echo "<tr>";
                echo "<td>{$user['id']}</td>";
                echo "<td>{$user['username']}</td>";
                echo "<td>{$user['email']}</td>";
                echo "<td>{$user['role']}</td>";
                echo "<td>{$user['status']}</td>";
                echo "<td>{$user['created_at']}</td>";
                echo "<td>
                        <a href='edit_user.php?id={$user['id']}' class='edit-user-button'>Edit User</a>|
                        <a href='delete_user.php?id={$user['id']}' class='delete-user-button'>Delete User</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Add pagination here if you have many users -->

    <!-- Add search and filter options if needed -->

    <!-- Add JavaScript for any client-side functionality -->

</body>
</html>
