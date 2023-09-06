<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Welcome</title>
    <link rel="stylesheet" href="public/css/admin-style.css">
</head>
<body>

<header>
<!-- Site logo -->
    <div class="logo">
        <img src="/Blog/public/images/job.png" alt="Your Logo">
    </div>
    <div class="admin-nav">
        <a href="../index.php" class="view-site-button">View Site</a>
        <div class="admin-profile">
            <img src="/Blog/public/images/avatar.png" alt="Admin Profile"> <!-- Replace with the path to your dummy avatar -->
            <div class="dropdown">
                <button class="profile-dropdown-button"><?php echo $adminName; ?></button>
                <div class="dropdown-content">
                    <a href="#">Profile</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>

    
</body>
</html>

