<?php
include 'config.php';

// 1. Check Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";
$msg_type = "";

// 2. Handle Profile Update
if (isset($_POST['update_profile'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $skills = $conn->real_escape_string($_POST['skills']);
    $bio = $conn->real_escape_string($_POST['bio']); // New Field

    // Handle Resume Upload
    if (!empty($_FILES['resume']['name'])) {
        $resume_name = time() . "_" . $_FILES['resume']['name'];
        $resume_tmp = $_FILES['resume']['tmp_name'];
        $target = "uploads/" . $resume_name;
        
        if (move_uploaded_file($resume_tmp, $target)) {
            $sql = "UPDATE users SET name='$name', phone='$phone', skills='$skills', bio='$bio', resume='$resume_name' WHERE id='$user_id'";
        } else {
            $msg = "❌ Failed to upload resume.";
            $msg_type = "error";
        }
    } else {
        $sql = "UPDATE users SET name='$name', phone='$phone', skills='$skills', bio='$bio' WHERE id='$user_id'";
    }

    if (empty($msg)) {
        if ($conn->query($sql)) {
            $msg = "✅ Profile updated successfully!";
            $msg_type = "success";
        } else {
            $msg = "❌ Error: " . $conn->error;
            $msg_type = "error";
        }
    }
}

// 3. Fetch User Data
$result = $conn->query("SELECT * FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* --- GLOBAL STYLE --- */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; margin: 0; padding: 0; color: #1c1e21; }
        * { box-sizing: border-box; }

        /* --- NAVBAR --- */
        .navbar { background: #fff; padding: 15px 5%; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 1000; }
        .logo { font-size: 24px; font-weight: 800; color: #2c3e50; }
        .logo span { color: #3498db; }
        .menu a { text-decoration: none; color: #555; margin-left: 25px; font-weight: 600; font-size: 15px; transition: color 0.3s; }
        .menu a:hover { color: #3498db; }
        .menu .active { color: #3498db; }
        .btn-logout { border: 1px solid #ddd; padding: 6px 15px; border-radius: 50px; color: #555 !important; }
        .btn-logout:hover { background: #f8f9fa; color: #333 !important; }

        /* --- MAIN LAYOUT --- */
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 300px 1fr; /* Sidebar + Content */
            gap: 30px;
        }

        /* --- SIDEBAR CARD --- */
        .profile-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            border: 1px solid #eee;
            height: fit-content;
        }
        .avatar {
            width: 100px; height: 100px; background: #e3f2fd; color: #3498db;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 40px; margin: 0 auto 15px;
        }
        .user-name { font-size: 20px; font-weight: bold; margin-bottom: 5px; color: #2c3e50; }
        .user-email { font-size: 14px; color: #777; margin-bottom: 20px; }
        .join-date { font-size: 12px; color: #aaa; border-top: 1px solid #eee; padding-top: 15px; }

        /* --- FORM CARD --- */
        .edit-form-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            border: 1px solid #eee;
        }
        .card-header { font-size: 22px; font-weight: bold; margin-bottom: 25px; color: #2c3e50; border-bottom: 1px solid #eee; padding-bottom: 15px; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #444; font-size: 14px; }
        input[type="text"], input[type="email"], textarea {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: #fcfcfc;
        }
        input:focus, textarea:focus { border-color: #3498db; outline: none; background: white; }
        
        textarea { resize: vertical; height: 100px; }

        /* File Upload */
        .file-upload {
            border: 2px dashed #ddd; padding: 20px; text-align: center; border-radius: 6px; cursor: pointer; transition: 0.2s;
        }
        .file-upload:hover { border-color: #3498db; background: #f0f8ff; }
        .current-resume { margin-top: 10px; font-size: 13px; }
        .current-resume a { color: #3498db; text-decoration: none; font-weight: bold; }

        /* Button */
        .btn-save {
            background: #3498db; color: white; border: none; padding: 12px 30px; font-size: 16px;
            border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.2s; display: block; width: 100%;
        }
        .btn-save:hover { background: #2980b9; }

        /* Alerts */
        .alert { padding: 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* Mobile */
        @media (max-width: 768px) {
            .container { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
        }

    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">Job<span>Portal</span></div>
        <div class="menu">
            <a href="index.php">Jobs</a>
            <a href="profile.php" class="active">My Profile</a>
            <a href="my_applications.php">Applications</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </div>

    <div class="container">
        
        <div class="profile-card">
            <div class="avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
            <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
            
            <div class="join-date">
                <i class="far fa-clock"></i> Member since <?php echo date("M Y", strtotime($user['created_at'])); ?>
            </div>
        </div>

        <div class="edit-form-card">
            <div class="card-header">Edit Profile</div>

            <?php if(!empty($msg)): ?>
                <div class="alert <?php echo $msg_type; ?>"><?php echo $msg; ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?php echo isset($user['phone']) ? htmlspecialchars($user['phone']) : ''; ?>" placeholder="+91 99999 99999">
                    </div>
                </div>

                <div class="form-group">
                    <label>Email Address (Read-only)</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="background:#f2f2f2; color:#777; cursor:not-allowed;">
                </div>

                <div class="form-group">
                    <label>Professional Bio (Short Summary)</label>
                    <textarea name="bio" placeholder="Briefly describe your experience..."><?php echo isset($user['bio']) ? htmlspecialchars($user['bio']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label>Key Skills (Comma separated)</label>
                    <input type="text" name="skills" value="<?php echo isset($user['skills']) ? htmlspecialchars($user['skills']) : ''; ?>" placeholder="e.g. PHP, Java, Python, Communication">
                </div>

                <div class="form-group">
                    <label>Resume / CV</label>
                    <div class="file-upload" onclick="document.getElementById('resume_input').click();">
                        <i class="fas fa-cloud-upload-alt" style="font-size:24px; color:#3498db; margin-bottom:10px;"></i><br>
                        Click to upload new resume (PDF/DOC)
                        <input type="file" name="resume" id="resume_input" style="display:none;">
                    </div>

                    <?php if(!empty($user['resume'])): ?>
                        <div class="current-resume">
                            <i class="fas fa-file-alt"></i> Current File: 
                            <a href="uploads/<?php echo $user['resume']; ?>" target="_blank">Download Resume</a>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" name="update_profile" class="btn-save">Save Changes</button>

            </form>
        </div>

    </div>

</body>
</html>