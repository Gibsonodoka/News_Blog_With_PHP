<?php
require '../db.php'; 
//require '../admin_header.php'; // Adjust the path as needed
session_start();

// Check if the user is logged in and has the admin role on the server-side
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

// Fetch values from the database
$query = "SELECT COUNT(*) FROM posts"; // Total Post
$statement = $conn->prepare($query);
$statement->execute();
$totalPost = $statement->fetchColumn();


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
<!--Start of HTML Document-->
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../public/css/admin-style.css">
</head>
<body>
    <!--Start of column-->
    <div class="content">

        <div class="column total-post">
            <h3><strong><?php echo $totalPost; ?></strong></h3>
            <p>Total Post</p>
        </div>
        <div class="column pending-post">
            <h3>25</h3>
            <p>Pending Post</p>
        </div>
        <div class="column draft">
            <h3>10</h3>
            <p>Draft</p>
        </div>
        <div class="column scheduled-post">
            <h3>15</h3>
            <p>Scheduled Post</p>
        </div>
        <!-- End of Column-->
        
    </div>
</body>
</html>
