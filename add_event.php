<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login page if not authorized
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root"; // Default XAMPP username, change if different
$password = ""; // Default XAMPP password, change if set
$dbname = "user_management"; // Use the existing database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST['event_name'];
    $created_by = $_POST['created_by'];
    $event_date = $_POST['event_date'];
    $status = $_POST['status'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO events (event_name, created_by, event_date, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $event_name, $created_by, $event_date, $status);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New event added successfully!";
        // Optionally, redirect to a page (e.g., back to the dashboard)
        // header("Location: admin_dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
