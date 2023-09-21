<?php
// Include your database connection file (e.g., db.php)
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user registration data from POST
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username']; // Add username field

    // Validate input data (add more validation as needed)
    if (empty($name) || empty($email) || empty($password) || empty($username)) {
        // Handle validation errors (e.g., redirect to registration page with an error message)
        header("Location: register.php?error=emptyfields");
        exit();
    }

    // Check if the email is already in use (you should have a unique constraint on the email field)
    $query = "SELECT id FROM users WHERE email = :email";
    $statement = $conn->prepare($query);
    $statement->bindParam(':email', $email);
    $statement->execute();

    if ($statement->rowCount() > 0) {
        // Handle email already in use error (e.g., redirect to registration page with an error message)
        header("Location: register.php?error=emailtaken");
        exit();
    }

    // Hash the password (you can use password_hash)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $query = "INSERT INTO users (name, email, username, password) VALUES (:name, :email, :username, :password)";
    $statement = $conn->prepare($query);
    $statement->bindParam(':name', $name);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':username', $username);
    $statement->bindParam(':password', $hashedPassword);

    if ($statement->execute()) {
        // Registration successful, redirect to a success page or login page
        header("Location: login.php?registration=success");
        exit();
    } else {
        // Handle database error (e.g., redirect to registration page with an error message)
        header("Location: register.php?error=sqlerror");
        exit();
    }
} else {
    // Redirect unauthorized access
    header("Location: register.php");
    exit();
}
