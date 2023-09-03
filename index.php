<?php
require 'db.php';
require 'header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Handle image upload
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = basename($_FILES["image"]["name"]);
            $query = "INSERT INTO posts (title, content, image) VALUES (:title, :content, :image)";
            $statement = $conn->prepare($query);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':content', $content);
            $statement->bindValue(':image', $image);

            if ($statement->execute()) {
                header("Location: index.php"); // Redirect back to main page after adding post
            } else {
                echo "Error adding post.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Fetch existing posts
$query = "SELECT * FROM posts ORDER BY created_at DESC";
$statement = $conn->prepare($query);
$statement->execute();
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
    

    
    <main>
        <div class="post-form">
            <h2>Create a New Post</h2>
            <form action="index.php" method="post" enctype="multipart/form-data">
                <label for="title">Title:</label>
                <input type="text" name="title" required>
                
                <label for="content">Content:</label>
                <textarea name="content" rows="4" required></textarea>
                
                <label for="image">Image:</label>
                <input type="file" name="image">
                
                <button type="submit">Create Post</button>
            </form>
        </div>
        <div class="row">
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <h2><?php echo $post['title']; ?></h2>
                    <?php if (!empty($post['image'])): ?>
                        <img src="uploads/<?php echo $post['image']; ?>" alt="Post Image">
                    <?php endif; ?>
                    <p><?php echo implode(' ', array_slice(explode(' ', $post['content']), 0, 20)); ?>...</p> <!-- Display first 20 words -->

                    <!-- Place the date and time to the right -->
                    <div class="post-info">
                        <p><strong><?php echo date('M j, Y | H:i', strtotime($post['created_at'])); ?></strong></p> <!-- Format the date -->
                        <form action="single_post.php" method="get">
                            <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                            <button type="submit">Read More</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>


       

    </main>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Simple Blog</p>
    </footer>
</body>
</html>