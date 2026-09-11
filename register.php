<?php
include 'config.php';

$msg = "";
$msg_type = "";

// Handle Registration Logic
if (isset($_POST['register'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password']; 
    $phone = $conn->real_escape_string($_POST['phone']);
    $skills = $conn->real_escape_string($_POST['skills']);
    $experience = $conn->real_escape_string($_POST['experience']);
    $resume = "";

    // 1. Check if email exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    
    if ($check->num_rows > 0) {
        $msg = "❌ This email is already registered.";
        $msg_type = "error";
    } else {
        // 2. Handle Resume Upload (With Debugging)
        if (!empty($_FILES['resume']['name'])) {
            $resume_name = time() . "_" . $_FILES['resume']['name'];
            $target = "uploads/" . $resume_name;
            
            // Auto-create folder if it doesn't exist
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }

            if (move_uploaded_file($_FILES['resume']['tmp_name'], $target)) {
                $resume = $resume_name; // Success
            } else {
                $msg = "❌ Error: Failed to upload file. Check folder permissions.";
                $msg_type = "error";
            }
        }

        // Only proceed if no upload error
        if (empty($msg)) {
            // 3. Insert into Database
            $sql = "INSERT INTO users (name, email, password, phone, skills, experience, resume, created_at) 
                    VALUES ('$name', '$email', '$password', '$phone', '$skills', '$experience', '$resume', NOW())";
            
            if ($conn->query($sql)) {
                $msg = "✅ Account created successfully! <a href='login.php' style='color:#155724; font-weight:bold;'>Login Now</a>";
                $msg_type = "success";
            } else {
                $msg = "❌ Database Error: " . $conn->error;
                $msg_type = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* --- GLOBAL STYLES --- */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; color: #333; }
        
        /* --- NAVBAR --- */
        .navbar { background: #fff; padding: 15px 5%; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .logo { font-size: 24px; font-weight: 800; color: #2c3e50; text-decoration: none; }
        .logo span { color: #3498db; }
        .nav-link { text-decoration: none; color: #555; font-weight: 600; }

        /* --- CONTAINER --- */
        .register-wrapper {
            min-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .register-card {
            background: white;
            width: 100%;
            max-width: 500px;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        h2 { margin-top: 0; color: #2c3e50; margin-bottom: 25px; font-size: 24px; }

        /* --- FORM ELEMENTS --- */
        .form-group { margin-bottom: 18px; }
        
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 700;
            color: #444; 
            font-size: 14px;
        }

        .form-control {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;
            box-sizing: border-box; background-color: #fff;
        }
        .form-control:focus { border-color: #28a745; outline: none; }

        /* --- BUTTON --- */
        .btn-register {
            background-color: #28a745; color: white; border: none; width: 100%; padding: 15px;
            border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px; transition: background 0.2s;
        }
        .btn-register:hover { background-color: #218838; }

        /* --- FILE INPUT --- */
        input[type="file"] { padding: 10px; background: #f9f9f9; border: 1px solid #ddd; width: 100%; border-radius: 4px; }

        /* --- ALERTS --- */
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        .footer-link { text-align: center; margin-top: 20px; font-size: 14px; }
        .footer-link a { color: #28a745; text-decoration: none; font-weight: bold; }

    </style>
</head>
<body>

    <div class="navbar">
        <a href="index.php" class="logo">Job<span>Portal</span></a>
        <a href="login.php" class="nav-link">Login</a>
    </div>

    <div class="register-wrapper">
        <div class="register-card">
            <h2>Create an Account</h2>

            <?php if(!empty($msg)): ?>
                <div class="alert <?php echo $msg_type; ?>"><?php echo $msg; ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Key Skills</label>
                    <input type="text" name="skills" class="form-control" placeholder="e.g. PHP, Java">
                </div>

                <div class="form-group">
                    <label>Experience</label>
                    <select name="experience" class="form-control">
                        <option value="Fresher">Fresher</option>
                        <option value="1 Year">1 Year</option>
                        <option value="2 Years">2 Years</option>
                        <option value="3+ Years">3+ Years</option>
                        <option value="5+ Years">5+ Years</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Upload Resume (PDF)</label>
                    <input type="file" name="resume" accept=".pdf,.doc,.docx" required>
                </div>

                <button type="submit" name="register" class="btn-register">Register Now</button>

            </form>

            <div class="footer-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>

        </div>
    </div>

</body>
</html>