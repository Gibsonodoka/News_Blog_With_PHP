<!-- posts.php -->
<?php
require '../admin_header.php';
require '../db.php';
?>

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

<?php require '../footer.php'; ?>
