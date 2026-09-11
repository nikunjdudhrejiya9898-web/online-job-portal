<?php
include 'config.php';

echo "<h2>Database Repair Tool</h2>";

// 1. Check if 'applications' table exists
$checkTable = $conn->query("SHOW TABLES LIKE 'applications'");

if ($checkTable->num_rows == 0) {
    // Table is missing, create it
    $sql = "CREATE TABLE applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        job_id INT,
        user_id INT,
        status VARCHAR(50) DEFAULT 'Pending',
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if ($conn->query($sql)) {
        echo "<p style='color:green'>✅ Created 'applications' table.</p>";
    } else {
        echo "<p style='color:red'>❌ Failed to create table: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color:blue'>ℹ️ 'applications' table exists.</p>";
}

// 2. Check if 'status' column exists
$checkColumn = $conn->query("SHOW COLUMNS FROM applications LIKE 'status'");

if ($checkColumn->num_rows == 0) {
    // Column is missing, add it
    $sql = "ALTER TABLE applications ADD COLUMN status VARCHAR(50) DEFAULT 'Pending'";
    if ($conn->query($sql)) {
        echo "<p style='color:green'>✅ Successfully added 'status' column!</p>";
    } else {
        echo "<p style='color:red'>❌ Failed to add column: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color:green'>✅ The 'status' column already exists!</p>";
}

echo "<hr>";
echo "<h3>Current Columns in 'applications' table:</h3>";
$result = $conn->query("SHOW COLUMNS FROM applications");
if ($result) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . $row['Field'] . "</li>";
    }
    echo "</ul>";
}

echo "<br><a href='my_applications.php'>Go back to My Applications</a>";
?>