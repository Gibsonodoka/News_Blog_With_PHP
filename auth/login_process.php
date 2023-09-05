<?php
session_start();
require '../db.php'; // Connect to your database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perform user authentication
    $query = "SELECT * FROM users WHERE username = :username";
    $statement = $conn->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id']; // Store user ID in the session
        $_SESSION['role'] = $user['role']; // Store user role in the session

        header("Location: dashboard.php"); // Redirect to the dashboard
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>
