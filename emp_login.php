<?php
include 'config.php';
if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password']; // In real app, use password_verify
    
    $sql = "SELECT * FROM employers WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['emp_id'] = $row['id'];
        header("Location: post_job.php");
    } else {
        echo "Invalid Credentials";
    }
}
?>
<form method="post">
    <h2>Employer Login</h2>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit" name="login">Login</button>
</form>