<?php
include 'config.php';

// --- SEARCH & FILTER LOGIC ---
$search_query = "";
$location_query = "";

$sql = "SELECT jobs.*, employers.company_name 
        FROM jobs 
        JOIN employers ON jobs.employer_id = employers.id 
        WHERE 1";

// 1. Keyword Search
if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $k = $_GET['keyword'];
    $sql .= " AND (jobs.title LIKE '%$k%' OR employers.company_name LIKE '%$k%')";
    $search_query = $k;
}

// 2. Location Search
if (isset($_GET['location']) && !empty($_GET['location'])) {
    $l = $_GET['location'];
    $sql .= " AND jobs.location LIKE '%$l%'";
    $location_query = $l;
}

// 3. Job Type Filter
if (isset($_GET['type']) && !empty($_GET['type'])) {
    $types = $_GET['type'];
    $type_list = implode("','", $types);
    $sql .= " AND jobs.job_type IN ('$type_list')";
}

$sql .= " ORDER BY jobs.posted_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find Your Dream Job - JobPortal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* --- GLOBAL RESET --- */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; margin: 0; padding: 0; color: #1c1e21; display: flex; flex-direction: column; min-height: 100vh; }
        * { box-sizing: border-box; }

        /* --- NAVBAR --- */
        .navbar { background: #fff; padding: 15px 5%; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 1000; }
        .logo { font-size: 24px; font-weight: 800; color: #2c3e50; }
        .logo span { color: #3498db; }
        
        .menu a { text-decoration: none; color: #555; margin-left: 25px; font-weight: 600; font-size: 15px; transition: color 0.3s; }
        .menu a:hover { color: #3498db; }
        .menu .active { color: #3498db; }
        
        .btn-login { border: 2px solid #3498db; padding: 8px 20px; border-radius: 50px; color: #3498db !important; }
        .btn-login:hover { background: #3498db; color: white !important; }

        /* --- HERO SEARCH SECTION --- */
        .hero-section { background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%); padding: 50px 20px; text-align: center; color: white; margin-bottom: 40px; }
        .hero-title { font-size: 32px; margin-bottom: 10px; }

        .search-container { background: white; padding: 10px; border-radius: 8px; max-width: 900px; margin: 0 auto; display: flex; gap: 10px; box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
        .input-group { flex: 1; position: relative; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; }
        .form-control { width: 100%; padding: 15px 15px 15px 45px; border: 1px solid #ddd; border-radius: 6px; font-size: 15px; outline: none; }
        .btn-search { background: #27ae60; color: white; border: none; padding: 0 40px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 16px; }

        /* --- FILTERS --- */
        .filters-bar { max-width: 1000px; margin: 0 auto 30px; text-align: center; }
        .filter-label { display: inline-block; background: #fff; padding: 8px 15px; border-radius: 20px; margin: 5px; font-size: 14px; color: #555; cursor: pointer; border: 1px solid #ddd; transition: all 0.2s; }
        .filter-label:hover { border-color: #3498db; color: #3498db; }
        .filter-label input { display: none; }
        .filter-label input:checked + span { color: #3498db; font-weight: bold; }

        /* --- GRID CONTAINER --- */
        .main-content { flex: 1; } /* Pushes footer to the bottom */
        .container { max-width: 1200px; margin: 0 auto 60px; padding: 0 20px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 25px; }

        /* --- JOB CARD --- */
        .job-card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); border: 1px solid #eee; transition: transform 0.2s, box-shadow 0.2s; display: flex; flex-direction: column; justify-content: space-between; height: 100%; }
        .job-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); border-color: #b3d7ff; }
        .job-top { display: flex; gap: 15px; align-items: flex-start; margin-bottom: 15px; }
        .company-icon { width: 50px; height: 50px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #3498db; flex-shrink: 0; }
        .job-info h3 { margin: 0 0 5px; font-size: 18px; color: #2c3e50; line-height: 1.3; }
        .job-info h3 a { text-decoration: none; color: inherit; }
        .job-info h3 a:hover { color: #3498db; }
        .company-name { font-size: 14px; color: #777; font-weight: 600; margin-bottom: 5px; display: block; }
        .job-details { font-size: 13px; color: #666; display: flex; flex-wrap: wrap; gap: 10px; margin-top: 5px; }
        .job-details span { display: flex; align-items: center; gap: 5px; }
        .job-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f0f0f0; padding-top: 15px; margin-top: 10px; }

        /* Badges & Buttons */
        .badge { padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .type-full-time { background: #e3fcef; color: #00a86b; }
        .type-part-time { background: #fff8e1; color: #f39c12; }
        .type-freelance { background: #e6f7ff; color: #007bff; }
        .type-internship { background: #f3e5f5; color: #9c27b0; }
        .type-temporary { background: #ffebee; color: #e74c3c; }
        .posted-date { font-size: 13px; color: #888; display: flex; align-items: center; gap: 5px; }
        .posted-date i { color: #aaa; }
        .btn-view { background: #fff; color: #3498db; border: 1px solid #3498db; padding: 6px 15px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 13px; transition: all 0.2s; }
        .btn-view:hover { background: #3498db; color: white; }

        /* --- FOOTER --- */
        .footer { background: #2c3e50; color: #ecf0f1; padding: 50px 20px 20px; margin-top: auto; }
        .footer-content { max-width: 1200px; margin: 0 auto; display: flex; flex-wrap: wrap; justify-content: space-between; gap: 40px; }
        .footer-section { flex: 1; min-width: 250px; }
        .footer-section h3 { color: #3498db; margin-bottom: 20px; font-size: 18px; text-transform: uppercase; letter-spacing: 1px; }
        .footer-section p { font-size: 14px; line-height: 1.8; color: #bdc3c7; margin-bottom: 15px; }
        .footer-section ul { list-style: none; padding: 0; margin: 0; }
        .footer-section ul li { margin-bottom: 12px; }
        .footer-section ul li a { color: #bdc3c7; text-decoration: none; font-size: 14px; transition: color 0.3s; }
        .footer-section ul li a:hover { color: #3498db; padding-left: 5px; }
        .footer-section i { margin-right: 10px; color: #3498db; width: 15px; text-align: center; }
        
        .footer-bottom { text-align: center; padding-top: 25px; margin-top: 40px; border-top: 1px solid #34495e; font-size: 13px; color: #95a5a6; }

        /* Responsive */
        @media (max-width: 768px) {
            .container { grid-template-columns: 1fr; }
            .search-container { flex-direction: column; }
            .footer-content { flex-direction: column; }
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">Job<span>Portal</span></div>
        <div class="menu">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="index.php" class="active">Jobs</a>
                <a href="profile.php">My Profile</a>
                <a href="my_applications.php">Applications</a>
                <a href="logout.php" class="btn-login" style="margin-left:15px; border:1px solid #ddd; color:#555 !important;">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn-login">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="main-content">
        <div class="hero-section">
            <h1 class="hero-title">Find Your Next Job</h1>
            <form method="GET" action="index.php" class="search-container">
                <div class="input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" name="keyword" class="form-control" placeholder="Job title or keywords" value="<?php echo htmlspecialchars($search_query); ?>">
                </div>
                <div class="input-group">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="location" class="form-control" placeholder="City" value="<?php echo htmlspecialchars($location_query); ?>">
                </div>
                <button type="submit" class="btn-search">Search</button>
            </form>
        </div>

        <form method="GET" action="index.php" class="filters-bar">
            <input type="hidden" name="keyword" value="<?php echo htmlspecialchars($search_query); ?>">
            <input type="hidden" name="location" value="<?php echo htmlspecialchars($location_query); ?>">

            <?php 
            $filters = ['Full Time', 'Part Time', 'Freelance', 'Internship', 'Temporary'];
            foreach($filters as $f) {
                $checked = (isset($_GET['type']) && in_array($f, $_GET['type'])) ? 'checked' : '';
                echo "<label class='filter-label'>
                        <input type='checkbox' name='type[]' value='$f' onchange='this.form.submit()' $checked>
                        <span>$f</span>
                      </label>";
            }
            ?>
        </form>

        <div class="container">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): 
                    
                    if (isset($row['job_type'])) { $type = $row['job_type']; } 
                    elseif (isset($row['type'])) { $type = $row['type']; } 
                    else { $type = "Full Time"; }
                    
                    $job_id = isset($row['id']) ? $row['id'] : '';
                    $job_title = isset($row['title']) ? $row['title'] : 'Job Title';
                    $company_name = isset($row['company_name']) ? $row['company_name'] : 'Company';
                    $job_location = isset($row['location']) ? $row['location'] : 'Location';
                    $job_category = isset($row['category']) ? $row['category'] : 'Category';
                    
                    $badge_class = "type-full-time"; 
                    if(stripos($type, 'Part') !== false) $badge_class = "type-part-time";
                    if(stripos($type, 'Free') !== false) $badge_class = "type-freelance";
                    if(stripos($type, 'Intern') !== false) $badge_class = "type-internship";
                    if(stripos($type, 'Temp') !== false) $badge_class = "type-temporary";

                    $upload_date = isset($row['posted_at']) ? date("M d, Y", strtotime($row['posted_at'])) : "Recent";
                ?>
                    <div class="job-card">
                        <div class="job-top">
                            <div class="company-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="job-info">
                                <h3><a href="job_details.php?id=<?php echo htmlspecialchars($job_id); ?>"><?php echo htmlspecialchars($job_title); ?></a></h3>
                                <span class="company-name"><?php echo htmlspecialchars($company_name); ?></span>
                                
                                <div class="job-details">
                                    <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job_location); ?></span>
                                    <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($job_category); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="job-footer">
                            <div style="display:flex; flex-direction:column; gap:5px;">
                                <span class="badge <?php echo $badge_class; ?>" style="width:fit-content;"><?php echo htmlspecialchars($type); ?></span>
                                
                                <span class="posted-date">
                                    <i class="far fa-calendar-alt"></i> Posted: <?php echo $upload_date; ?>
                                </span>
                            </div>
                            <a href="job_details.php?id=<?php echo htmlspecialchars($job_id); ?>" class="btn-view">Details</a>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: span 2; text-align: center; padding: 50px; color: #777;">
                    <h3>No jobs found</h3>
                    <p>Try adjusting your search.</p>
                </div>
            <?php endif; ?>
        </div>
    </div> <footer class="footer">
        <div class="footer-content">
            
            <div class="footer-section">
                <h3>About Us</h3>
                <p>JobPortal is a modern online recruitment platform designed to bridge the gap between talented job seekers and top-tier employers. Built during an internship program, our goal is to streamline the hiring process with smart, efficient digital tools.</p>
            </div>

            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php"><i class="fas fa-angle-right"></i> Home / Search Jobs</a></li>
                    <li><a href="login.php"><i class="fas fa-angle-right"></i> Job Seeker Login</a></li>
                    <li><a href="register.php"><i class="fas fa-angle-right"></i> Create an Account</a></li>
                    <li><a href="admin_login.php"><i class="fas fa-angle-right"></i> Employer / Admin Panel</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Contact Us</h3>
                <p><i class="fas fa-map-marker-alt"></i> Amplebrain Technologies, Rajkot, Gujarat</p>
                <p><i class="fas fa-envelope"></i> support@amplebrain.com</p>
                <p><i class="fas fa-phone-alt"></i> +91 98765 43210</p>
                <p><i class="fas fa-globe"></i> www.amplebrain.com</p>
            </div>

        </div>
        
        <div class="footer-bottom">
            &copy; <?php echo date("Y"); ?> JobPortal System. All Rights Reserved. Designed as part of the BCA Sem VI Project.
        </div>
    </footer>

</body>
</html>