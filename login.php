<?php
session_start();

// Database connection parameters
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

// Check if the form was submitted using POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data to avoid potential issues
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['pswd']) ? $_POST['pswd'] : ''; // No need to trim password

    // Check if the email and password are provided
    if (!empty($email) && !empty($password)) {

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
                // Store user information in session variables
                $_SESSION['user_email'] = $row['email']; // Store email
                $_SESSION['user_role'] = $row['role'];   // Store role

                // Redirect based on user role
                if ($row['role'] == 'admin') {
                    header("Location: admin.php");  // Redirect to admin dashboard
                    exit(); // Make sure to stop further script execution
                } else {
                    header("Location: view_event.php");   // Redirect to user dashboard
                    exit(); // Make sure to stop further script execution
                }
            } else {
                echo "Invalid password";
            }
        } else {
            echo "No account found with that email";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Please enter both email and password.";
    }
}

// Close the connection
$conn->close();
?>
