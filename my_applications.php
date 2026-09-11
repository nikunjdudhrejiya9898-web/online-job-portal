<?php
include 'config.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Fetch Applications
// We JOIN jobs and employers to get the Address/Location for the interview
$sql = "SELECT applications.*, jobs.title, jobs.location, employers.company_name, employers.city, employers.phone 
        FROM applications 
        JOIN jobs ON applications.job_id = jobs.id 
        JOIN employers ON jobs.employer_id = employers.id 
        WHERE applications.user_id = '$user_id' 
        ORDER BY applications.applied_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Applications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f4f7f6; margin: 0; color: #333; }

        /* Navbar */
        .navbar { background: #fff; padding: 15px 40px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 24px; font-weight: bold; color: #2c3e50; }
        .menu a { text-decoration: none; color: #666; margin-left: 20px; font-weight: 500; }
        .menu a:hover { color: #000; }

        /* Container */
        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        h1 { font-weight: normal; color: #333; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 15px; }

        /* Application Card */
        .app-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            gap: 15px;
            border-left: 5px solid #ddd; /* Default Grey Border */
        }

        /* Status-Specific Borders */
        .border-accepted { border-left-color: #27ae60; }
        .border-rejected { border-left-color: #c0392b; }
        .border-pending { border-left-color: #f39c12; }

        /* Header Row */
        .app-header { display: flex; justify-content: space-between; align-items: flex-start; }
        .job-title { font-size: 20px; font-weight: bold; color: #2c3e50; text-decoration: none; }
        .company-name { font-size: 14px; color: #7f8c8d; margin-top: 5px; font-weight: 600; }
        
        .date-badge { font-size: 12px; color: #999; background: #f9f9f9; padding: 5px 10px; border-radius: 4px; }

        /* Message Box (The Reason/Location Part) */
        .status-box {
            padding: 15px;
            border-radius: 5px;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Pending Box */
        .box-pending { background: #fff8e1; color: #8d6e63; border: 1px solid #ffe0b2; }
        
        /* Accepted Box */
        .box-accepted { background: #e8f5e9; color: #1b5e20; border: 1px solid #c8e6c9; }
        .box-accepted strong { color: #2e7d32; }

        /* Rejected Box */
        .box-rejected { background: #ffebee; color: #b71c1c; border: 1px solid #ffcdd2; }

        .btn-view {
            text-align: right; margin-top: 10px;
        }
        .btn-view a { text-decoration: none; color: #2980b9; font-weight: bold; font-size: 14px; }

    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">JobPortal</div>
        <div class="menu">
            <a href="index.php">Find Jobs</a>
            <a href="logout.php">Sign out</a>
        </div>
    </div>

    <div class="container">
        <h1>My Applications</h1>

        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                
                $status = $row['status'];
                $applied_date = date("M d, Y", strtotime($row['applied_at']));
                
                // Determine CSS Classes
                $border_class = "border-pending";
                if($status == 'Accepted') $border_class = "border-accepted";
                if($status == 'Rejected') $border_class = "border-rejected";

                // Calculate Interview Date (7 Days from Today)
                $interview_date = date("l, F j, Y", strtotime("+7 days"));
        ?>
            <div class="app-card <?php echo $border_class; ?>">
                
                <div class="app-header">
                    <div>
                        <a href="job_details.php?id=<?php echo $row['job_id']; ?>" class="job-title"><?php echo htmlspecialchars($row['title']); ?></a>
                        <div class="company-name"><i class="fas fa-building"></i> <?php echo htmlspecialchars($row['company_name']); ?></div>
                    </div>
                    <div class="date-badge">Applied: <?php echo $applied_date; ?></div>
                </div>

                <?php if($status == 'Accepted'): ?>
                    
                    <div class="status-box box-accepted">
                        <h4 style="margin:0 0 10px 0;"><i class="fas fa-check-circle"></i> Congratulations! You are Shortlisted.</h4>
                        <p style="margin:0;">
                            We are pleased to invite you for an interview.<br>
                            <strong><i class="fas fa-map-marker-alt"></i> Venue:</strong> <?php echo $row['location']; ?> (<?php echo $row['city']; ?> Office)<br>
                            <strong><i class="far fa-calendar-alt"></i> Date:</strong> Please visit within 7 days (by <?php echo $interview_date; ?>)<br>
                            <strong><i class="fas fa-phone"></i> Contact:</strong> <?php echo $row['phone'] ? $row['phone'] : 'HR Department'; ?><br>
                            <em style="font-size:12px;">Note: Please bring your CV and Original ID proof.</em>
                        </p>
                    </div>

                <?php elseif($status == 'Rejected'): ?>

                    <div class="status-box box-rejected">
                        <h4 style="margin:0 0 10px 0;"><i class="fas fa-times-circle"></i> Application Status: Not Selected</h4>
                        <p style="margin:0;">
                            <strong>Reason:</strong> After reviewing your profile, we found that your skills do not fully match our current project requirements.<br>
                            We encourage you to apply for other future openings.
                        </p>
                    </div>

                <?php else: ?>

                    <div class="status-box box-pending">
                        <strong><i class="fas fa-hourglass-half"></i> Status: Pending Review</strong><br>
                        Your application is currently being reviewed by the HR team. You will be notified once a decision is made.
                    </div>

                <?php endif; ?>

            </div>
        <?php 
            }
        } else {
            echo "<p style='text-align:center; padding:40px; color:#777;'>You haven't applied for any jobs yet.</p>";
        }
        ?>

    </div>

</body>
</html>