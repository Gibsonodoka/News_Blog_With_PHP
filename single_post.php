<?php
require 'db.php';
require 'header.php'; // Include your header here

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "SELECT * FROM posts WHERE id = :id";
    $statement = $conn->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $post = $statement->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $post['title']; ?> - Simple Blog</title>
</head>
<body>
    <main>
        <div class="post">
            <h2><?php echo $post['title']; ?></h2>
            <?php if (!empty($post['image'])): ?>
                <img src="uploads/<?php echo $post['image']; ?>" alt="Post Image">
            <?php endif; ?>
            <p><?php echo $post['content']; ?></p>
            <p><?php echo $post['created_at']; ?></p>
        </div>
    </main>
    <?php require 'footer.php';?>
</body>
</html>
