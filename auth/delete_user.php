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

// Check if the user ID is provided in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    // Handle the case where no user ID is provided
    // You can redirect to the manage_users.php page or display an error message
    header("Location: manage_users.php");
    exit();
}

// Fetch user data based on the provided user ID
$query = "SELECT id, username, email FROM users WHERE id = :user_id";
$statement = $conn->prepare($query);
$statement->bindParam(':user_id', $user_id);
$statement->execute();
$user_data = $statement->fetch(PDO::FETCH_ASSOC);

// Check if the user with the provided ID exists
if (!$user_data) {
    // Handle the case where the user does not exist
    // You can redirect to the manage_users.php page or display an error message
    header("Location: manage_users.php");
    exit();
}

// Include the admin header and navigation
require '../admin_header.php'; // Adjust the path as needed
require '../dash_nav.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
    <link rel="stylesheet" href="../public/css/admin-style.css">
</head>
<body>
    <div class="content">
        <h2>Delete User</h2>
        <p>Are you sure you want to delete the user "<?php echo $user_data['username']; ?>"?</p>
        <form action="process_delete_user.php" method="POST"> <!-- Assuming you have a process_delete_user.php for handling user deletion -->
            <input type="hidden" name="user_id" value="<?php echo $user_data['id']; ?>">
            <input type="submit" value="Yes, Delete User">
        </form>
        <a href="manage_users.php">Cancel</a> <!-- Link to cancel the deletion and return to the manage_users.php page -->
    </div>
</body>
</html>
