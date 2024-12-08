<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_management"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if event ID and action are set
if (isset($_POST['event_id']) && isset($_POST['action'])) {
    $event_id = $_POST['event_id'];
    $action = $_POST['action'];

    // Update the event status based on the action
    $status = ($action == 'approve') ? 'Approved' : 'Rejected';

    // SQL query to update the event status
    $sql = "UPDATE events SET status='$status' WHERE id='$event_id'";

    if ($conn->query($sql) === TRUE) {
        if (headers_sent()) {
            echo "Headers already sent. Fix this!";
            exit;
        }
        
        header("Location: admin.php"); // Redirect back to the admin dashboard
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>