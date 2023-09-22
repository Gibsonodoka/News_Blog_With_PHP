<?php
require '../db.php'; // Include your database connection file
session_start();

// Check if the user is logged in and has the admin role on the server-side
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

// Fetch the admin's name from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT username FROM users WHERE id = :user_id"; // Use the correct column name
$statement = $conn->prepare($query);
$statement->bindParam(':user_id', $user_id);
$statement->execute();
$adminName = $statement->fetchColumn();

// Include the admin header and navigation
require '../admin_header.php'; // Adjust the path as needed
require '../dash_nav.php';

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Perform form validation and data sanitization here

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST["role"];
    $status = $_POST["status"];

    // Insert the new user into the database
    $insertQuery = "INSERT INTO users (username, email, password, role, status, created_at) VALUES (:username, :email, :password, :role, :status, NOW())";
    $insertStatement = $conn->prepare($insertQuery);
    $insertStatement->bindParam(':username', $username);
    $insertStatement->bindParam(':email', $email);
    $insertStatement->bindParam(':password', $password);
    $insertStatement->bindParam(':role', $role);
    $insertStatement->bindParam(':status', $status);

    if ($insertStatement->execute()) {
        // User added successfully, you can redirect or display a success message
        header("Location: manage_users.php");
        exit();
    } else {
        // Handle the case where user insertion fails, e.g., display an error message
        $errorMessage = "User creation failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <!--<link rel="stylesheet" href="../public/css/admin-style.css">
        Include Bootstrap CSS and JS
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
-->
</head>
<body>
<div class="content">
    <h2>Add User</h2>
    <form method="POST" action="add_user.php">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputEmail">Email</label>
                <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email" required>
            </div>
            <div class="form-group col-md-6">
                <label for="inputPassword">Password</label>
                <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password" required>
            </div>
        </div>
        <div class="form-group">
            <label for="inputUsername">Username</label>
            <input type="text" class="form-control" id="inputUsername" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <label for="inputRole">Role</label>
            <select id="inputRole" class="form-control" name="role" required>
                <option selected>Choose...</option>
                <!-- Add your role options here, e.g., Admin, User -->
                <option>Admin</option>
                <option>User</option>
            </select>
        </div>
        <div class="form-group">
            <label for="inputStatus">Status</label>
            <select id="inputStatus" class="form-control" name="status" required>
                <option selected>Choose...</option>
                <!-- Add your status options here, e.g., Active, Inactive -->
                <option>Active</option>
                <option>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary add-user-button">Add User</button>
    </form>
</div>

</body>
</html>
