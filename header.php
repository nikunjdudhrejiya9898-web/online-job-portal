<?php
// Ensure Config is included (if not already)
if (session_status() === PHP_SESSION_NONE) {
    include 'config.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="navbar">
        <div class="logo">
            <h2><a href="index.php" style="color:white; text-decoration:none;"><i class="fas fa-briefcase"></i> JobPortal</a></h2>
        </div>
        <div class="menu">
            <a href="index.php">Home</a>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="my_applications.php">My Applications</a>
                <a href="profile.php">My Profile</a>
                <a href="logout.php" class="btn-logout">Logout</a>

            <?php elseif(isset($_SESSION['emp_id'])): ?>
                <a href="post_job.php">Post Job</a>
                <a href="view_applicants.php">View Applicants</a>
                <a href="logout.php" class="btn-logout">Logout</a>

            <?php elseif(isset($_SESSION['admin_id'])): ?>
                <a href="admin_manage_jobs.php">Admin Panel</a>
                <a href="logout.php" class="btn-logout">Logout</a>

            <?php else: ?>
                <a href="login.php">User Login</a>
                <a href="emp_login.php">Employer Login</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="content-wrapper" style="flex: 1;">