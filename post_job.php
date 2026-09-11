<?php
include 'config.php';

if (!isset($_SESSION['emp_id'])) {
    header("Location: emp_login.php");
    exit();
}

if(isset($_POST['submit'])) {
    $title = $_POST['title'];
    $salary = $_POST['salary'];
    $location = $_POST['location'];
    $emp_id = $_SESSION['emp_id'];

    $sql = "INSERT INTO jobs (employer_id, title, salary, location) VALUES ('$emp_id', '$title', '$salary', '$location')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Job Posted Successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<h2>Post a New Job</h2>
<form method="post">
    Job Title: <input type="text" name="title" required><br>
    Salary: <input type="text" name="salary" required><br>
    Location: <input type="text" name="location" required><br>
    <button type="submit" name="submit">Post Job</button>
</form>
<br>
<a href="view_applicants.php">View Applicants</a>