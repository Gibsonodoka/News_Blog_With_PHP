<?php
require '../db.php'; 
session_start();

// Check if the user is logged in and has the admin role on the server-side
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the values from the form
    $post_id = $_POST['post_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category'];

    // Handle image upload
    $targetDir = "../uploads/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is an actual image or a fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 700000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Delete the old image file
        $query = "SELECT image FROM posts WHERE id = :post_id";
        $statement = $conn->prepare($query);
        $statement->bindParam(':post_id', $post_id);
        $statement->execute();
        $oldImage = $statement->fetchColumn();
        if ($oldImage) {
            unlink("../uploads/$oldImage");
        }

        // Upload the new image
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // Update the post data
            $query = "UPDATE posts SET title = :title, content = :content, image = :image, category = :category WHERE id = :post_id";
            $statement = $conn->prepare($query);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':content', $content);
            $statement->bindValue(':image', basename($_FILES["image"]["name"]));
            $statement->bindValue(':category', $category_id);
            $statement->bindValue(':post_id', $post_id);

            if ($statement->execute()) {
                header("Location: posts.php"); // Redirect back to the posts page after updating the post
            } else {
                echo "Error updating post.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
