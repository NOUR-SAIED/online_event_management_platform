<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login page if not authorized
    exit();
}

// Check if the request is a POST and if the user_id parameter is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    // Database connection
    $servername = "localhost";
    $username = "root"; // Default XAMPP username (change if needed)
    $password = ""; // Default XAMPP password (change if needed)
    $dbname = "user_management"; // Your database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get and sanitize the user_id
    $user_id = $conn->real_escape_string($_POST['user_id']);

    // Prepare and execute the delete query
    $sql = "DELETE FROM users WHERE id = '$user_id'";
    if ($conn->query($sql) === TRUE) {
        // Redirect back to the admin dashboard after successful deletion
        header("Location: admin.php?message=User deleted successfully");
        exit();
    } else {
        // Handle errors
        echo "Error deleting user: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    // If no user_id is provided or if the request is not a POST
    header("Location: admin.php?error=Invalid request");
    exit();
}
?>
