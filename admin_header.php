<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Welcome</title>
    <link rel="stylesheet" href="public/css/admin-style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script> 
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
     
</head>
<body>

<header>
    <div class="admin-logo">
        <img src="/Blog/public/images/job.png" alt="Your Logo">
    </div>
    <div class="admin-navigation">
        <a href="../index.php" class="admin-view-site-button">View Site</a>
        <div class="admin-profile">
            <img src="/Blog/public/images/avatar.png" alt="Admin Profile"> <!-- Replace with the path to your dummy avatar -->
            <div class="admin-dropdown">
                <button class="admin-profile-dropdown-button"><?php echo $adminName; ?></button>
                <div class="admin-dropdown-content">
                    <a href="#">Profile</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>

    
</body>
</html>

