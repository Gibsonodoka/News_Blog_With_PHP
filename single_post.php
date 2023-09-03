<?php
require 'db.php';

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
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h1>Simple Blog</h1>
    </header>
    
    <main>
        <div class="post">
            <h2><?php echo $post['title']; ?></h2>
            <p><?php echo $post['content']; ?></p>
            <p><?php echo $post['created_at']; ?></p>
        </div>
    </main>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Simple Blog</p>
    </footer>
</body>
</html>
