<!-- posts.php -->
<?php
require '../admin_header.php'; // Adjust the path as needed
require '../dash_nav.php';
require '../db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
    <link rel="stylesheet" href="../public/css/admin-style.css">
</head>
<body>
    


<div class="content">
    <h2>Posts</h2>

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch and display the list of posts
            $query = "SELECT * FROM posts";
            $statement = $conn->prepare($query);
            $statement->execute();
            $posts = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($posts as $post) {
                echo "<tr>";
                echo "<td><img src='uploads/{$post['image']}' alt='Thumbnail' width='100'></td>";
                echo "<td>{$post['title']}</td>";
                echo "<td>";
                echo "<a href='edit_post.php?id={$post['id']}'>Edit</a> | ";
                echo "<a href='delete_post.php?id={$post['id']}'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php require '../footer.php'; ?>
