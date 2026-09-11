<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>All Jobs - Browse Careers</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="navbar">
        <div class="logo"><h2>JobPortal</h2></div>
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="jobs.php">Browse Jobs</a>
            <a href="my_applications.php">My Applications</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="hero" style="background-color: #2c3e50;">
        <h1>All Available Positions</h1>
    </div>

    <div class="container">
        <?php
        // Fetch ALL jobs
        $sql = "SELECT jobs.*, employers.company_name 
                FROM jobs 
                JOIN employers ON jobs.employer_id = employers.id 
                ORDER BY jobs.posted_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Prepare data for JS Modal
                $id = $row['id'];
                $title = addslashes($row['title']);
                $company = addslashes($row['company_name']);
                $salary = addslashes($row['salary']);
                $location = addslashes($row['location']);
                $desc = addslashes($row['description']);
        ?>
            <div class="job-card">
                <div>
                    <div class="job-title"><?php echo $row['title']; ?></div>
                    <div class="company-name"><?php echo $row['company_name']; ?></div>
                    <div class="salary">💰 <?php echo $row['salary']; ?></div>
                    <div>📍 <?php echo $row['location']; ?></div>
                </div>
                <button class="btn-details" 
                    onclick="openModal('<?php echo $title; ?>', '<?php echo $company; ?>', '<?php echo $salary; ?>', '<?php echo $location; ?>', '<?php echo $desc; ?>', '<?php echo $id; ?>')">
                    View & Apply
                </button>
            </div>
        <?php 
            }
        } else {
            echo "<p>No jobs found.</p>";
        }
        ?>
    </div>

    <?php include 'modal_component.php'; ?> 
    <script src="script.js"></script>
</body>
</html>