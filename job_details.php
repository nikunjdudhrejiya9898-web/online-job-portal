<?php
include 'config.php';

// 1. Check if ID exists
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$job_id = $_GET['id'];
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$msg = "";

// 2. Fetch Job Details
$sql = "SELECT jobs.*, employers.company_name, employers.email as emp_email, employers.city 
        FROM jobs 
        JOIN employers ON jobs.employer_id = employers.id 
        WHERE jobs.id = '$job_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Job not found."; exit();
}

$job = $result->fetch_assoc();

// 3. Handle "Apply Now" Click
if (isset($_POST['apply_now'])) {
    if (!isset($_SESSION['user_id'])) {
        // Redirect to login if not signed in
        header("Location: login.php");
        exit();
    } else {
        // Check if already applied
        $check = $conn->query("SELECT * FROM applications WHERE user_id='$user_id' AND job_id='$job_id'");
        if ($check->num_rows > 0) {
            $msg = "You have already applied for this job.";
        } else {
            // Insert Application
            $insert = "INSERT INTO applications (user_id, job_id, status, applied_at) VALUES ('$user_id', '$job_id', 'Pending', NOW())";
            if ($conn->query($insert)) {
                $msg = "✅ Application submitted successfully!";
            } else {
                $msg = "Error: " . $conn->error;
            }
        }
    }
}

// 4. Check status for button (Applied or Not)
$has_applied = false;
if ($user_id > 0) {
    $check_status = $conn->query("SELECT * FROM applications WHERE user_id='$user_id' AND job_id='$job_id'");
    if ($check_status->num_rows > 0) {
        $has_applied = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($job['title']); ?> - Job Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; color: #333; }

        /* Navbar */
        .navbar { background: #fff; padding: 15px 40px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 24px; font-weight: bold; color: #2c3e50; }
        .menu a { text-decoration: none; color: #666; margin-left: 20px; font-weight: 500; }

        /* Page Header (Blue Background) */
        .page-header {
            background: #2c3e50;
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        .job-title { font-size: 36px; font-weight: bold; margin: 0; }
        .company-name { font-size: 18px; color: #bdc3c7; margin-top: 10px; }
        .meta-row { margin-top: 20px; font-size: 14px; }
        .meta-row span { margin: 0 15px; color: #ecf0f1; }
        .meta-row i { color: #3498db; margin-right: 5px; }

        /* Main Content Grid */
        .container {
            max-width: 1000px;
            margin: -30px auto 50px; /* Overlaps header slightly */
            display: grid;
            grid-template-columns: 2fr 1fr; /* Left: Content, Right: Sidebar */
            gap: 30px;
            padding: 0 20px;
        }

        /* Left Side: Description */
        .content-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .section-title { font-size: 20px; font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px; color: #2c3e50; }
        .job-description { font-size: 16px; line-height: 1.8; color: #555; }
        
        /* Right Side: Sidebar */
        .sidebar-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
            height: fit-content;
        }
        
        .btn-apply {
            display: block;
            width: 100%;
            background-color: #27ae60;
            color: white;
            padding: 15px 0;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-apply:hover { background-color: #219150; }
        
        .btn-disabled {
            background-color: #95a5a6; cursor: not-allowed;
        }

        .summary-item {
            display: flex; justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            color: #555;
        }
        .summary-item:last-child { border-bottom: none; }
        .summary-label { font-weight: bold; color: #333; }

        /* Message Alert */
        .alert {
            padding: 15px; margin-bottom: 20px; border-radius: 5px; font-weight: bold; text-align: center;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">JobPortal</div>
        <div class="menu">
            <a href="index.php">Find Jobs</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="my_applications.php">My Applications</a>
                <a href="logout.php">Sign out</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="page-header">
        <h1 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h1>
        <div class="company-name"><?php echo htmlspecialchars($job['company_name']); ?></div>
        
        <div class="meta-row">
            <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?></span>
            <span><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($job['job_type']); ?></span>
            <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($job['category']); ?></span>
            <span><i class="far fa-clock"></i> Posted <?php echo date("M d, Y", strtotime($job['posted_at'])); ?></span>
        </div>
    </div>

    <div class="container">
        
        <div class="content-box">
            <?php if($msg): ?>
                <div class="alert alert-success"><?php echo $msg; ?></div>
            <?php endif; ?>

            <h3 class="section-title">Job Description</h3>
            <div class="job-description">
                <?php echo nl2br($job['description']); ?>
            </div>
        </div>

        <div class="sidebar-card">
            <h3 style="margin-top:0;">Job Overview</h3>
            
            <div class="summary-item">
                <span class="summary-label">Posted date:</span>
                <span><?php echo date("F j, Y", strtotime($job['posted_at'])); ?></span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Location:</span>
                <span><?php echo htmlspecialchars($job['location']); ?></span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Job Type:</span>
                <span><?php echo htmlspecialchars($job['job_type']); ?></span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Category:</span>
                <span><?php echo htmlspecialchars($job['category']); ?></span>
            </div>

            <br><br>

            <form method="post">
                <?php if($has_applied): ?>
                    <button type="button" class="btn-apply btn-disabled" disabled>Applied ✓</button>
                    <p style="font-size:12px; color:#777; margin-top:10px;">You have already applied for this job.</p>
                <?php else: ?>
                    <button type="submit" name="apply_now" class="btn-apply">Apply Now</button>
                    <p style="font-size:12px; color:#777; margin-top:10px;">Clicking apply will send your profile to the employer.</p>
                <?php endif; ?>
            </form>

        </div>

    </div>

</body>
</html>