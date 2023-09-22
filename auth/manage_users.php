<?php
require '../db.php'; 
session_start();

// Check if the user is logged in and has the admin role on the server-side
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

// Fetch the admin's name from the database
require '../db.php'; // Include your database connection file
$user_id = $_SESSION['user_id'];
$query = "SELECT username FROM users WHERE id = :user_id"; // Use the correct column name
$statement = $conn->prepare($query);
$statement->bindParam(':user_id', $user_id);
$statement->execute();
$adminName = $statement->fetchColumn();

// Include the admin header and navigation
require '../admin_header.php'; // Adjust the path as needed
require '../dash_nav.php';

// Pagination variables
$usersPerPage = 10; // Adjust as needed
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Get the current page from the URL

// Calculate the OFFSET for SQL query
$offset = ($page - 1) * $usersPerPage;

// Fetch and display the list of users with pagination
$query = "SELECT id, username, email, role, status, created_at FROM users LIMIT :limit OFFSET :offset";
$statement = $conn->prepare($query);
$statement->bindValue(':limit', $usersPerPage, PDO::PARAM_INT);
$statement->bindValue(':offset', $offset, PDO::PARAM_INT);
$statement->execute();
$user_data = $statement->fetchAll(PDO::FETCH_ASSOC);

// Calculate the total number of users
$totalUsersQuery = "SELECT COUNT(*) FROM users";
$totalUsersStatement = $conn->query($totalUsersQuery);
$totalUsers = $totalUsersStatement->fetchColumn();

// Calculate the total number of pages
$totalPages = ceil($totalUsers / $usersPerPage);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="../public/css/admin-style.css">
    <!-- Include Bootstrap CSS and JS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="content">
        <h2>Manage Users</h2>
        <a href="add_user.php" class="add-user-button">Add User</a> <!-- Link to add a new user -->
   
        <!-- Display user data in a table -->
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Date Registered</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($user_data as $user) {
                    echo "<tr>";
                    echo "<td>{$user['id']}</td>";
                    echo "<td>{$user['username']}</td>";
                    echo "<td>{$user['email']}</td>";
                    echo "<td>{$user['role']}</td>";
                    echo "<td>{$user['status']}</td>";
                    echo "<td>{$user['created_at']}</td>";
                    echo "<td>
                            <a href='edit_user.php?id={$user['id']}' class='edit-user-button'>Edit User</a> |  
                            <a href='#' class='delete-user-button' data-toggle='modal' data-target='#confirmDeleteModal' data-username='{$user['username']}'>Delete User</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination links -->
        <div class="pagination">
            <?php
            // Output pagination links
            for ($i = 1; $i <= $totalPages; $i++) {
                $activeClass = ($i == $page) ? 'active' : '';
                echo "<a href='manage_users.php?page={$i}' class='pagination-link {$activeClass}'>{$i}</a>";
            }
            ?>
        </div>

        <!-- Modal for confirming user deletion -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete user: <span id="deleteUserName"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-danger" id="confirmDeleteButton">Yes</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript to handle the Delete User link click -->
        <script>
            $(".delete-user-button").click(function () {
                var userName = $(this).data("username");
                $("#deleteUserName").text(userName);
            });
        </script>
    </div>
</body>
</html>
