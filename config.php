<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "job_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// FIX: Only start the session if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>