<?php
include 'config.php';

// 1. If already logged in, redirect to Home
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error_msg = "";

// 2. Handle Login Logic
if (isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Query to find user
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify Password (Assuming plain text for now based on your setup)
        // If you use hashing, change this to: if(password_verify($password, $user['password']))
        if ($password == $user['password']) {
            // Login Success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            
            header("Location: index.php"); // Redirect to Home
            exit();
        } else {
            $error_msg = "❌ Incorrect password.";
        }
    } else {
        $error_msg = "❌ No account found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - JobPortal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* --- GLOBAL STYLES --- */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; margin: 0; padding: 0; color: #333; }
        * { box-sizing: border-box; }

        /* --- NAVBAR --- */
        .navbar { background: #fff; padding: 15px 5%; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .logo { font-size: 24px; font-weight: 800; color: #2c3e50; text-decoration: none; }
        .logo span { color: #3498db; }
        .nav-link { text-decoration: none; color: #555; font-weight: 600; font-size: 14px; }
        .nav-link:hover { color: #3498db; }

        /* --- LOGIN CONTAINER --- */
        .login-wrapper {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: white;
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
        }

        .login-header { margin-bottom: 30px; }
        .login-header h2 { margin: 0; color: #2c3e50; font-size: 28px; }
        .login-header p { color: #7f8c8d; font-size: 14px; margin-top: 5px; }

        /* --- FORM STYLES --- */
        .form-group { margin-bottom: 20px; text-align: left; }
        .label { font-size: 14px; font-weight: 600; color: #555; margin-bottom: 5px; display: block; }

        .input-group { position: relative; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; }
        
        .form-control {
            width: 100%;
            padding: 12px 15px 12px 40px; /* Space for icon */
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: 0.3s;
        }
        .form-control:focus { border-color: #3498db; outline: none; box-shadow: 0 0 5px rgba(52, 152, 219, 0.2); }

        .btn-login {
            background-color: #3498db;
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }
        .btn-login:hover { background-color: #2980b9; }

        /* --- LINKS --- */
        .footer-links { margin-top: 20px; font-size: 14px; color: #666; }
        .footer-links a { color: #3498db; text-decoration: none; font-weight: 600; }
        .footer-links a:hover { text-decoration: underline; }

        .admin-link { display: block; margin-top: 15px; font-size: 12px; color: #999; }

        /* --- ALERT BOX --- */
        .alert {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 20px;
            border: 1px solid #ffcdd2;
            display: flex; align-items: center; gap: 8px;
        }

    </style>
</head>
<body>

    <div class="navbar">
        <a href="index.php" class="logo">Job<span>Portal</span></a>
        <a href="register.php" class="nav-link">Create Account</a>
    </div>

    <div class="login-wrapper">
        <div class="login-card">
            
            <div class="login-header">
                <h2>Welcome Back</h2>
                <p>Please sign in to your account</p>
            </div>

            <?php if(!empty($error_msg)): ?>
                <div class="alert">
                    <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                
                <div class="form-group">
                    <label class="label">Email Address</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                </div>

                <button type="submit" name="login" class="btn-login">Sign In</button>

            </form>

            <div class="footer-links">
                Don't have an account? <a href="register.php">Register Here</a>
            </div>

            <a href="admin_login.php" class="admin-link">Are you an Admin? Login here</a>

        </div>
    </div>

</body>
</html>