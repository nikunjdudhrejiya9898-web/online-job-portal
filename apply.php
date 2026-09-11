<?php
include 'config.php';

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Check if job ID is present
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $user_id = $_SESSION['user_id'];

    // 3. Check if ALREADY applied
    $check = "SELECT * FROM applications WHERE job_id='$job_id' AND user_id='$user_id'";
    $result = $conn->query($check);

    if ($result->num_rows == 0) {
        // 4. Insert Application
        $sql = "INSERT INTO applications (job_id, user_id) VALUES ('$job_id', '$user_id')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('Application Submitted Successfully!');
                    window.location.href='my_applications.php';
                  </script>";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "<script>
                alert('You have already applied for this job.');
                window.location.href='job_details.php?id=$job_id';
              </script>";
    }
} else {
    header("Location: index.php");
}
?>