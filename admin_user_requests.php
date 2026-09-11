<?php
include 'config.php';

// 1. Security Check (Admin Only)
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// 2. Handle Accept / Reject Logic
if (isset($_GET['action']) && isset($_GET['id'])) {
    $app_id = $_GET['id'];
    $status = $_GET['action']; // 'Accepted' or 'Rejected'

    // Update Status in Database
    $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $app_id);

    if ($stmt->execute()) {
        // Refresh page to show new status
        header("Location: admin_user_requests.php?msg=updated");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* --- GLOBAL STYLES (Matches other Admin pages) --- */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f1f1f1;
            color: #444;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* --- HEADER --- */
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

        /* --- MAIN CONTAINER --- */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            background: white; /* No container padding, table fills it */
            border: 1px solid #ddd;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            flex: 1;
        }
        
        .page-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            background: white;
        }
        h1 { margin: 0; font-size: 24px; font-weight: 400; color: #23282d; }

        /* --- TABLE STYLES --- */
        .wp-list-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        .wp-list-table thead th {
            text-align: left;
            padding: 15px;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 1px solid #e1e1e1;
            color: #32373c;
            background: #f8f9fa;
        }

        .wp-list-table tbody td {
            padding: 15px;
            font-size: 14px;
            border-bottom: 1px solid #e5e5e5;
            vertical-align: middle;
            color: #555;
        }
        
        .wp-list-table tbody tr:hover { background-color: #fcfcfc; }

        /* --- BADGES --- */
        .badge {
            padding: 5px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase;
        }
        .status-pending { background-color: #f39c12; color: white; }
        .status-accepted { background-color: #27ae60; color: white; }
        .status-rejected { background-color: #c0392b; color: white; }

        /* --- LINKS & BUTTONS --- */
        .resume-link {
            text-decoration: none; color: #0073aa; font-weight: 500;
        }
        .resume-link:hover { text-decoration: underline; color: #005177; }

        .btn-action {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 3px;
            color: white;
            text-decoration: none;
            font-size: 12px;
            margin-right: 5px;
            transition: opacity 0.2s;
        }
        .btn-accept { background-color: #27ae60; }
        .btn-reject { background-color: #d9534f; }
        .btn-action:hover { opacity: 0.8; }

        /* --- FOOTER --- */
        .footer {
            background: #23282d;
            color: #999;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            margin-top: auto;
        }
        .footer p { margin: 0; }

    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo"><i class="fas fa-tachometer-alt"></i> Dashboard</div>
        <div class="menu">
            <a href="admin_manage_jobs.php">All Jobs</a>
            <a href="admin_user_requests.php" style="color:white; font-weight:bold;">User Requests</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        
        <div class="page-header">
            <h1>Manage Applications</h1>
        </div>

        <table class="wp-list-table">
            <thead>
                <tr>
                    <th>Candidate Name</th>
                    <th>Job Title</th>
                    <th>Applied Date</th>
                    <th>Resume</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch Applications with User and Job Details
                $sql = "SELECT applications.id AS app_id, applications.status, applications.applied_at,
                               users.name, users.resume,
                               jobs.title 
                        FROM applications
                        JOIN users ON applications.user_id = users.id
                        JOIN jobs ON applications.job_id = jobs.id
                        ORDER BY applications.applied_at DESC";

                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        
                        $status = !empty($row['status']) ? $row['status'] : 'Pending';
                        $status_class = "status-" . strtolower($status);
                        $date = date("M d, Y", strtotime($row['applied_at']));
                ?>
                    <tr>
                        <td style="font-weight:bold; color:#0073aa;">
                            <?php echo htmlspecialchars($row['name']); ?>
                        </td>

                        <td><?php echo htmlspecialchars($row['title']); ?></td>

                        <td style="color:#777;"><?php echo $date; ?></td>

                        <td>
                            <?php if(!empty($row['resume'])): ?>
                                <a href="uploads/<?php echo $row['resume']; ?>" target="_blank" class="resume-link">
                                    <i class="fas fa-file-pdf"></i> View CV
                                </a>
                            <?php else: ?>
                                <span style="color:#ccc;">No File</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <span class="badge <?php echo $status_class; ?>"><?php echo $status; ?></span>
                        </td>

                        <td>
                            <?php if($status == 'Pending'): ?>
                                <a href="admin_user_requests.php?action=Accepted&id=<?php echo $row['app_id']; ?>" 
                                   class="btn-action btn-accept" title="Accept"
                                   onclick="return confirm('Accept this candidate?');">
                                   <i class="fas fa-check"></i> Accept
                                </a>
                                <a href="admin_user_requests.php?action=Rejected&id=<?php echo $row['app_id']; ?>" 
                                   class="btn-action btn-reject" title="Reject"
                                   onclick="return confirm('Reject this candidate?');">
                                   <i class="fas fa-times"></i> Reject
                                </a>
                            <?php else: ?>
                                <span style="color:#aaa; font-size:12px;">
                                    <i class="fas fa-lock"></i> Locked
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center; padding:30px;'>No new applications found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Job Portal Admin Panel. All rights reserved.</p>
    </div>

</body>
</html>