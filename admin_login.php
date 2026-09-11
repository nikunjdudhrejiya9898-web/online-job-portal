<?php
include 'config.php';

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if admin exists
    $sql = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['admin_id'] = $row['id']; // Set Admin Session
        header("Location: admin_manage_jobs.php"); // Redirect to Dashboard
    } else {
        echo "<script>alert('Invalid Admin Credentials');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color:#2c3e50; display:flex; justify-content:center; align-items:center; height:100vh;">

    <div class="form-container" style="width: 350px;">
        <h2 style="text-align:center;">Admin Panel</h2>
        <form method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn-submit" style="background-color:#34495e;">Login</button>
        </form>
        <p style="text-align:center; margin-top:10px;"><a href="index.php">Back to Website</a></p>
    </div>

</body>
</html>