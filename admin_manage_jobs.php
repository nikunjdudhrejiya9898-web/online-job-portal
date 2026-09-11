<?php
include 'config.php';

// 1. Security Check
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// 2. Handle Delete Logic
if(isset($_GET['del'])) {
    $id = $_GET['del'];
    $conn->query("DELETE FROM jobs WHERE id='$id'");
    header("Location: admin_manage_jobs.php?msg=deleted");
}

// 3. Search Logic
$search_query = "";
$sql = "SELECT jobs.*, employers.company_name 
        FROM jobs 
        JOIN employers ON jobs.employer_id = employers.id ";

if(isset($_GET['s']) && !empty($_GET['s'])) {
    $search = $conn->real_escape_string($_GET['s']);
    $sql .= " WHERE jobs.title LIKE '%$search%' OR employers.company_name LIKE '%$search%' ";
    $search_query = $_GET['s'];
}

$sql .= " ORDER BY jobs.posted_at DESC";
$result = $conn->query($sql);
$total_jobs = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Jobs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* --- WordPress Style Reset --- */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            background-color: #f1f1f1;
            color: #444;
            margin: 0;
        }

        /* --- Navbar (Keep your existing nav) --- */
        .navbar { background: #23282d; padding: 10px 20px; color: #eee; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color: #eee; text-decoration: none; margin-left: 15px; font-size: 13px; }
        .navbar .logo { font-weight: bold; font-size: 16px; }

        /* --- Main Content Wrapper --- */
        .wrap { margin: 20px auto; max-width: 1200px; padding: 0 20px; }

        /* --- Page Header --- */
        .page-header { display: flex; align-items: center; margin-bottom: 20px; }
        .page-title { font-size: 23px; font-weight: 400; margin: 0; padding: 9px 0 4px 0; line-height: 1.3; }
        
        .btn-add-new {
            display: inline-block;
            text-decoration: none;
            font-size: 13px;
            line-height: 26px;
            height: 28px;
            margin-left: 10px;
            padding: 0 10px;
            cursor: pointer;
            border-width: 1px;
            border-style: solid;
            border-radius: 3px;
            white-space: nowrap;
            box-sizing: border-box;
            background: #f7f7f7;
            border-color: #ccc;
            color: #0073aa;
            font-weight: 600;
        }
        .btn-add-new:hover { background: #fbfbfb; border-color: #999; color: #00a0d2; }

        /* --- Filters Bar --- */
        .filter-bar { margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; }
        .status-links a { text-decoration: none; color: #0073aa; }
        .status-links a.current { color: #000; font-weight: 600; }
        .status-links span { color: #ddd; margin: 0 2px; }

        .search-box input { padding: 5px; border: 1px solid #ddd; border-radius: 3px; }
        .search-box button { padding: 5px 10px; background: #f7f7f7; border: 1px solid #ccc; border-radius: 3px; cursor: pointer; }

        /* --- The Table --- */
        .wp-list-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #e5e5e5;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }

        .wp-list-table thead th, .wp-list-table tbody td {
            text-align: left;
            padding: 8px 10px;
            font-size: 13px;
            line-height: 1.5em;
            vertical-align: top;
        }

        .wp-list-table thead th {
            border-bottom: 1px solid #e1e1e1;
            font-weight: 400;
            color: #32373c;
        }
        
        .wp-list-table tbody tr { background-color: #fff; }
        .wp-list-table tbody tr:nth-child(odd) { background-color: #f9f9f9; }
        .wp-list-table tbody tr:hover { background-color: #fcfcfc; }
        .wp-list-table td { border-bottom: 1px solid #e5e5e5; color: #555; }

        /* --- Column Styling --- */
        .column-type { width: 100px; }
        .column-title { width: 30%; }
        .column-actions { width: 100px; text-align: right !important; }

        .job-title {
            color: #0073aa;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: block;
            margin-bottom: 4px;
        }
        .job-title:hover { color: #00a0d2; }
        .company-name { font-size: 12px; color: #a0a5aa; }

        /* --- Badges (Job Type) --- */
        .badge {
            display: inline-block;
            padding: 4px 8px;
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            border-radius: 3px;
            text-align: center;
            min-width: 60px;
            text-transform: capitalize;
        }
        .full-time { background-color: #7ad03a; } /* Green */
        .part-time { background-color: #ffba00; } /* Orange */
        .freelance { background-color: #5bc0de; } /* Blue */
        .internship { background-color: #9933cc; } /* Purple */
        .temporary { background-color: #d9534f; } /* Red */
        .general    { background-color: #777; }    /* Grey */

        /* --- Action Icons --- */
        .actions-row a {
            text-decoration: none;
            margin: 0 5px;
            color: #a0a5aa;
            font-size: 14px;
            transition: color 0.2s;
        }
        .actions-row a:hover { color: #00a0d2; }
        .actions-row a.delete-icon:hover { color: #dc3232; }

    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo"><i class="fas fa-tachometer-alt"></i> Dashboard</div>
        <div class="menu">
    <a href="admin_manage_jobs.php">Jobs</a>
    <a href="admin_manage_users.php">Users</a>  <a href="admin_user_requests.php">Requests</a>
    <a href="logout.php">Logout</a>
</div>
        </div>
        
    </div>

    <div class="wrap">
        
        <div class="page-header">
            <h1 class="page-title">Jobs</h1>
            <a href="admin_post_job.php" class="btn-add-new">Add New</a>
        </div>

        <div class="filter-bar">
            <div class="status-links">
                <a href="admin_manage_jobs.php" class="current">All <span class="count">(<?php echo $total_jobs; ?>)</span></a>
                <span>|</span>
                <a href="#">Published <span class="count">(<?php echo $total_jobs; ?>)</span></a>
            </div>

            <form method="GET" class="search-box">
                <input type="text" name="s" placeholder="Search Jobs" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search Jobs</button>
            </form>
        </div>

        <table class="wp-list-table">
            <thead>
                <tr>
                    <th style="width: 30px;"><input type="checkbox"></th> <th class="column-type">Type</th>
                    <th class="column-title">Position</th>
                    <th>Location</th>
                    <th>Posted</th>
                    <th>Categories</th>
                    <th class="column-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        
                        // Badge Logic
                        $type = strtolower($row['job_type']);
                        $class = 'general'; // default
                        
                        if(strpos($type, 'full') !== false) $class = 'full-time';
                        if(strpos($type, 'part') !== false) $class = 'part-time';
                        if(strpos($type, 'free') !== false) $class = 'freelance';
                        if(strpos($type, 'intern') !== false) $class = 'internship';
                        if(strpos($type, 'temp') !== false) $class = 'temporary';

                        // Date Format
                        $date = date("M d, Y", strtotime($row['posted_at']));
                ?>
                    <tr>
                        <td><input type="checkbox"></td>

                        <td>
                            <span class="badge <?php echo $class; ?>"><?php echo $row['job_type']; ?></span>
                        </td>

                        <td>
                            <a href="job_details.php?id=<?php echo $row['id']; ?>" class="job-title">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </a>
                            <span class="company-name"><?php echo htmlspecialchars($row['company_name']); ?></span>
                        </td>

                        <td><?php echo htmlspecialchars($row['location']); ?></td>

                        <td>
                            <?php echo $date; ?><br>
                            <span style="color:#aaa; font-size:11px;">by admin</span>
                        </td>

                        <td style="color:#0073aa;"><?php echo htmlspecialchars($row['category']); ?></td>

                        <td class="column-actions">
                            <div class="actions-row">
                                <a href="job_details.php?id=<?php echo $row['id']; ?>" title="View"><i class="fas fa-eye"></i></a>
                                <a href="#" title="Edit" onclick="alert('Edit feature coming soon!');"><i class="fas fa-pen"></i></a>
                                <a href="admin_manage_jobs.php?del=<?php echo $row['id']; ?>" title="Delete" class="delete-icon" onclick="return confirm('Delete this job?');"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center; padding:20px;'>No jobs found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div style="margin-top: 10px; font-size: 13px; color: #666; text-align: right;">
            <?php echo $total_jobs; ?> items
        </div>

    </div>

</body>
</html>