<?php
session_start();
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

// Extract login data from the form
$email = $_POST['email'];    // Extracting email
$password = $_POST['pswd'];  // Extracting password


  

// Prepare a statement to check if the user exists
 $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
 $stmt->bind_param("s", $email);
    
// Execute the query
$stmt->execute();
    
// Get the result
$result = $stmt->get_result();

 if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $row['password'])) {
        if ($row['role'] == 'admin') {
            header("Location: admin.html");  // Redirect to admin dashboard
        } else {
            header("Location: view_event.html");   // Redirect to user dashboard
        }
    } else {
         echo "Invalid password";
     }
 } else {
    echo "No account found with that email";
 }

// Close the statement and the connection
$stmt->close();
$conn->close();

?>
