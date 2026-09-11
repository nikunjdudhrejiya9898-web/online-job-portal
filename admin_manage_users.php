<?php
include 'config.php';

// 1. Security Check
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// 2. Handle Delete User
if(isset($_GET['del'])) {
    $id = $_GET['del'];
    $conn->query("DELETE FROM users WHERE id='$id'");
    header("Location: admin_manage_users.php?msg=deleted");
}

// 3. Search & Fetch Users
$search_query = "";
$sql = "SELECT * FROM users WHERE 1";

if(isset($_GET['s']) && !empty($_GET['s'])) {
    $search = $conn->real_escape_string($_GET['s']);
    $sql .= " AND (name LIKE '%$search%' OR email LIKE '%$search%' OR skills LIKE '%$search%')";
    $search_query = $_GET['s'];
}

$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);
$total_users = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* --- WordPress Style Reset --- */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f1f1f1;
            color: #444;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* --- Header --- */
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

        /* --- Container --- */
        .wrap { margin: 20px auto; max-width: 1200px; padding: 0 20px; flex: 1; }

        .page-header { display: flex; align-items: center; margin-bottom: 20px; justify-content: space-between; }
        .page-title { font-size: 23px; font-weight: 400; margin: 0; color: #23282d; }

        /* --- Filter Bar --- */
        .filter-bar { margin-bottom: 15px; display: flex; justify-content: flex-end; }
        .search-box input { padding: 6px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; }
        .search-box button { padding: 6px 12px; background: #f7f7f7; border: 1px solid #ccc; border-radius: 3px; cursor: pointer; color: #555; }
        .search-box button:hover { background: #eee; }

        /* --- Table --- */
        .wp-list-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #e5e5e5;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }

        .wp-list-table thead th {
            text-align: left;
            padding: 10px;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 1px solid #e1e1e1;
            background: #f8f9fa;
            color: #32373c;
        }

        .wp-list-table tbody td {
            padding: 10px;
            font-size: 14px;
            border-bottom: 1px solid #e5e5e5;
            color: #555;
            vertical-align: top;
        }

        .wp-list-table tbody tr:hover { background-color: #fcfcfc; }

        /* --- User Info Styling --- */
        .user-name { font-weight: bold; color: #0073aa; font-size: 15px; }
        .user-email { display: block; font-size: 13px; color: #666; margin-top: 2px; }
        
        .badge {
            background: #eee; padding: 3px 8px; border-radius: 3px; font-size: 12px; color: #555; display: inline-block; margin-top: 2px;
        }

        .resume-link {
            text-decoration: none; color: #27ae60; font-weight: bold; font-size: 13px;
        }
        .resume-link i { margin-right: 4px; }
        
        .delete-btn {
            color: #a00; text-decoration: none; font-size: 13px;
        }
        .delete-btn:hover { color: #dc3232; text-decoration: underline; }

        /* --- Footer --- */
        .footer {
            background: #23282d;
            color: #999;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            margin-top: 40px;
        }
        .footer p { margin: 0; }

    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo"><i class="fas fa-tachometer-alt"></i> Dashboard</div>
        <div class="menu">
            <a href="admin_manage_jobs.php">Jobs</a>
            <a href="admin_manage_users.php" style="color:white; font-weight:bold;">Users</a> <a href="admin_user_requests.php">Requests</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="wrap">
        
        <div class="page-header">
            <h1 class="page-title">Registered Users <span style="font-size:14px; color:#777;">(<?php echo $total_users; ?>)</span></h1>
            
            <form method="GET" class="filter-bar search-box">
                <input type="text" name="s" placeholder="Search by name or email" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <table class="wp-list-table">
            <thead>
                <tr>
                    <th width="25%">User Profile</th>
                    <th width="15%">Phone</th>
                    <th width="25%">Details (Skills & Exp)</th>
                    <th width="15%">Resume</th>
                    <th width="10%">Joined</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $date = date("M d, Y", strtotime($row['created_at']));
                ?>
                    <tr>
                        <td>
                            <div class="user-name"><?php echo htmlspecialchars($row['name']); ?></div>
                            <span class="user-email"><?php echo htmlspecialchars($row['email']); ?></span>
                        </td>

                        <td><?php echo !empty($row['phone']) ? htmlspecialchars($row['phone']) : '<span style="color:#ccc;">--</span>'; ?></td>

                        <td>
                            <?php if(!empty($row['experience'])): ?>
                                <span class="badge"><?php echo htmlspecialchars($row['experience']); ?></span><br>
                            <?php endif; ?>
                            <span style="font-size:12px; color:#777;"><?php echo htmlspecialchars($row['skills']); ?></span>
                        </td>

                        <td>
                            <?php if(!empty($row['resume'])): ?>
                                <a href="uploads/<?php echo $row['resume']; ?>" target="_blank" class="resume-link">
                                    <i class="fas fa-file-download"></i> Download
                                </a>
                            <?php else: ?>
                                <span style="color:#ccc;">No Resume</span>
                            <?php endif; ?>
                        </td>

                        <td style="color:#777; font-size:13px;"><?php echo $date; ?></td>

                        <td>
                            <a href="admin_manage_users.php?del=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user? This cannot be undone.');">
                                <i class="fas fa-trash-alt"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center; padding:30px; color:#777;'>No users found matching your search.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Job Portal Admin Panel.</p>
    </div>

</body>
</html>