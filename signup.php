<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password (empty)
$dbname = "user_management"; // Your target database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Extract data from form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['txt'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['pswd'], PASSWORD_DEFAULT);
    $role = $_POST['role'];}



// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO users (username, email, phone, password, role) 
                        VALUES (?, ?, ?, ?, ?)");

// Bind the parameters (since you have 5 values to insert)
$stmt->bind_param("ssiss", $username, $email, $phone, $password, $role);

// Execute the prepared statement
if ($stmt->execute()) {
    echo "Record inserted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement
$stmt->close();
$conn->close();
?> 