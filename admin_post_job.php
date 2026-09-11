<?php
include 'config.php';

// 1. Security Check (Admin Only)
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$error_msg = "";
$success_msg = "";

// 2. Handle Form Submission
if(isset($_POST['submit_job'])) {
    $title = $_POST['title'];
    $location = $_POST['location'];
    $type = $_POST['job_type'];
    $category = $_POST['category'];
    $desc = $_POST['description'];
    
    // Default Admin Employer ID
    $emp_id = 1; 

    // SQL Query (Removed 'app_url')
    $sql = "INSERT INTO jobs (title, location, job_type, category, description, employer_id, posted_at) 
            VALUES ('$title', '$location', '$type', '$category', '$desc', '$emp_id', NOW())";

    if($conn->query($sql)) {
        echo "<script>alert('✅ Job Posted Successfully!'); window.location='admin_manage_jobs.php';</script>";
    } else {
        $error_msg = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Job</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0; padding: 0; color: #333;
        }

        /* --- Main Container --- */
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        h1 { margin-top: 0; font-weight: normal; font-size: 32px; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 30px; }

        /* --- Account Info Bar --- */
        .account-info {
            display: flex; justify-content: space-between;
            padding-bottom: 30px; margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        .account-label { font-weight: bold; width: 25%; }
        .account-details { width: 75%; }
        .account-details a { text-decoration: none; color: #2980b9; font-weight: bold; }
        .account-details i { color: #2980b9; margin-left: 5px; }

        /* --- Form Rows (Horizontal Layout) --- */
        .form-row {
            display: flex;
            margin-bottom: 25px;
            align-items: flex-start;
        }
        
        .form-label {
            width: 25%;
            padding-top: 10px;
            font-size: 16px;
            color: #555;
        }

        .form-input {
            width: 75%;
        }

        /* --- Inputs Styling --- */
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #fcfcfc;
        }
        input[type="text"]:focus, select:focus, textarea:focus {
            border-color: #3498db;
            outline: none;
            background-color: #fff;
        }

        /* --- Fake Rich Text Editor Styling --- */
        .editor-container {
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #fcfcfc;
        }
        .editor-toolbar {
            background: #e8e8e8;
            background: linear-gradient(to bottom, #f5f5f5, #e8e8e8);
            padding: 8px;
            border-bottom: 1px solid #ccc;
            display: flex; gap: 10px;
        }
        .editor-toolbar i {
            color: #555; cursor: pointer; padding: 4px;
        }
        .editor-toolbar i:hover { color: #000; }
        
        textarea {
            width: 100%;
            height: 200px;
            border: none;
            padding: 15px;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
            box-sizing: border-box;
            background: white;
        }

        /* --- Submit Button --- */
        .btn-submit {
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }
        .btn-submit:hover { background-color: #1a252f; }
        
        /* Message Boxes */
        .msg-box { padding: 15px; margin-bottom: 20px; border-radius: 5px; font-weight: bold; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

       .navbar {
            background: #23282d;
            padding: 10px 20px;
            color: #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .navbar a { color: #eee; text-decoration: none; margin-left: 15px; font-size: 13px; transition: color 0.3s; }
        .navbar a:hover { color: #0073aa; }
        .navbar .logo { font-weight: bold; font-size: 16px; display: flex; align-items: center; gap: 8px; }
        /* --- FOOTER --- */
        .footer {
            background: #23282d;
            color: #999;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            margin-top: 40px;
        }
        .footer p { margin: 0; }
        
        .error-box { background: #fbeaea; border-left: 4px solid #dc3232; padding: 10px; margin-bottom: 20px; color: #dc3232; }
    </style>
</head>
<body>
<div class="navbar">
        <div class="logo"><i class="fas fa-tachometer-alt"></i> Dashboard</div>
        <div class="menu">
            <a href="admin_manage_jobs.php">All Jobs</a>
            <a href="admin_user_requests.php">User Requests</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <h1>Submit Job</h1>
        
        <?php if(!empty($error_msg)) { echo "<div class='msg-box error'>$error_msg</div>"; } ?>

        <div class="account-info">
            <div class="account-label">Your account</div>
            <div class="account-details">
                You are currently signed in as <strong>admin</strong>. 
                <a href="logout.php"><i class="fas fa-key"></i> Sign out</a>
            </div>
        </div>

        <form method="post">
            
            <div class="form-row">
                <label class="form-label">Job title</label>
                <div class="form-input">
                    <input type="text" name="title" required>
                </div>
            </div>

            <div class="form-row">
                <label class="form-label">Job location</label>
                <div class="form-input">
                    <input type="text" name="location" placeholder='e.g. "London, UK", "New York", "Anywhere"' required>
                </div>
            </div>

            <div class="form-row">
                <label class="form-label">Job type</label>
                <div class="form-input">
                    <select name="job_type">
                        <option value="Freelance">Freelance</option>
                        <option value="Full Time">Full Time</option>
                        <option value="Part Time">Part Time</option>
                        <option value="Internship">Internship</option>
                        <option value="Temporary">Temporary</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <label class="form-label">Job category</label>
                <div class="form-input">
                    <select name="category">
                        <option value="Information Technology">Information Technology</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Design">Design</option>
                        <option value="Finance">Finance</option>
                        <option value="Education">Education</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <label class="form-label">Description</label>
                <div class="form-input">
                    <div class="editor-container">
                        <div class="editor-toolbar">
                            <i class="fas fa-bold"></i>
                            <i class="fas fa-italic"></i>
                            <i class="fas fa-list-ul"></i>
                            <i class="fas fa-link"></i>
                            <i class="fas fa-undo"></i>
                            <i class="fas fa-redo"></i>
                            <span style="font-size:10px; font-weight:bold; color:blue; margin-left:auto;">HTML</span>
                        </div>
                        <textarea name="description" required></textarea>
                        <div style="background:#f1f1f1; padding:5px; font-size:12px; color:#888; border-top:1px solid #ddd;">
                            Path: p
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <label class="form-label"></label> <div class="form-input">
                    <button type="submit" name="submit_job" class="btn-submit">Submit Job</button>
                </div>
            </div>

        </form>
    </div>
 <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Job Portal Admin Panel. All rights reserved.</p>
        <p>Version 1.0 | Developed for Project</p>
    </div>
</body>
</html>