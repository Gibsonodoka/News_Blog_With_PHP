<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category']; // Assuming 'category' is the name of your select element

    // Handle image upload
    $targetDir = "uploads/";
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

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
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
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = basename($_FILES["image"]["name"]);
            
            // Fetch the selected category name based on its ID
            $query = "SELECT category_name FROM categories WHERE id = :category_id";
            $categoryStatement = $conn->prepare($query);
            $categoryStatement->bindParam(':category_id', $category_id);
            $categoryStatement->execute();
            $category = $categoryStatement->fetchColumn();

            // Insert the post with the category name
            $query = "INSERT INTO posts (title, content, image, category) VALUES (:title, :content, :image, :category)";
            $statement = $conn->prepare($query);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':content', $content);
            $statement->bindValue(':image', $image);
            $statement->bindValue(':category', $category);

            if ($statement->execute()) {
                header("Location: auth/dashboard.php"); // Redirect back to the main page after adding the post
            } else {
                echo "Error adding post.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
