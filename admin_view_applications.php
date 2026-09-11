<?php
include 'config.php';
// Include Header (if you have one)
// include 'header.php';

// Security Check
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// --- LOGIC: HANDLE ACCEPT / REJECT CLICK ---
if (isset($_GET['action']) && isset($_GET['id'])) {
    $app_id = $_GET['id'];
    $status = $_GET['action']; // This gets 'Accepted' or 'Rejected' URL
    
    // Update the specific application in the database
    $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $app_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Application status updated to: $status'); window.location='admin_view_applications.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Applications</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Admin Table Styling */
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        
        /* Status Badges */
        .badge { padding: 5px 10px; border-radius: 4px; color: white; font-weight: bold; font-size: 12px; }
        .pending { background-color: #f39c12; }
        .accepted { background-color: #27ae60; }
        .rejected { background-color: #c0392b; }

        /* Action Buttons */
        .btn-action { text-decoration: none; padding: 6px 12px; color: white; border-radius: 4px; font-size: 12px; margin-right: 5px; }
        .btn-accept { background-color: #27ae60; }
        .btn-reject { background-color: #c0392b; }
        .btn-accept:hover { background-color: #219150; }
        .btn-reject:hover { background-color: #a93226; }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">Admin Panel</div>
        <div class="menu">
            <a href="admin_manage_jobs.php">Jobs</a>
            <a href="admin_view_applications.php">Applications</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Job Requests / Proposals</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Candidate</th>
                    <th>Job Title</th>
                    <th>Applied Date</th>
                    <th>Resume</th>
                    <th>Current Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch Applications + User Info + Job Info
                $sql = "SELECT applications.id AS app_id, applications.status, applications.applied_at,
                               users.name, users.resume, 
                               jobs.title 
                        FROM applications
                        JOIN users ON applications.user_id = users.id
                        JOIN jobs ON applications.job_id = jobs.id
                        ORDER BY applications.applied_at DESC";
                        
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        // Determine Badge Color
                        $status_text = $row['status'] ? $row['status'] : 'Pending';
                        $badge_class = strtolower($status_text); // 'accepted', 'rejected', 'pending'
                ?>
                    <tr>
                        <td><strong><?php echo $row['name']; ?></strong></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo date("d M Y", strtotime($row['applied_at'])); ?></td>
                        <td>
                            <?php if($row['resume']): ?>
                                <a href="uploads/<?php echo $row['resume']; ?>" target="_blank" style="color:blue;">View CV</a>
                            <?php else: ?>
                                No File
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?php echo $badge_class; ?>"><?php echo $status_text; ?></span>
                        </td>
                        <td>
                            <?php if($status_text == 'Pending'): ?>
                                <a href="admin_view_applications.php?action=Accepted&id=<?php echo $row['app_id']; ?>" 
                                   class="btn-action btn-accept" onclick="return confirm('Accept this candidate?');">
                                   Accept
                                </a>
                                <a href="admin_view_applications.php?action=Rejected&id=<?php echo $row['app_id']; ?>" 
                                   class="btn-action btn-reject" onclick="return confirm('Reject this candidate?');">
                                   Reject
                                </a>
                            <?php else: ?>
                                <span style="color:gray; font-size:12px;">Completed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>No applications found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>