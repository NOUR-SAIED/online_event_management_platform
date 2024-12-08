// subscribe.php
<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    echo json_encode(['error' => 'You need to be logged in to subscribe']);
    exit();
}

// Get the user's ID from the session
$user_email = $_SESSION['user_email'];
$conn = new mysqli('localhost', 'root', '', 'user_management');
$user_id_query = $conn->prepare("SELECT id FROM users WHERE email = ?");
$user_id_query->bind_param("s", $user_email);
$user_id_query->execute();
$user_id_result = $user_id_query->get_result();
$user_data = $user_id_result->fetch_assoc();
$user_id = $user_data['id'];
$user_id_query->close();

// Get the event ID from the POST request
$event_id = $_POST['event_id']; // Get it directly from the POST data

// Check if the user has already subscribed to this event
$check_query = $conn->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND event_id = ?");
$check_query->bind_param("ii", $user_id, $event_id);
$check_query->execute();
$check_result = $check_query->get_result();

if ($check_result->num_rows > 0) {
    echo json_encode(['error' => 'You are already subscribed to this event']);
} else {
    // Insert the subscription into the subscriptions table
    $insert_query = $conn->prepare("INSERT INTO subscriptions (user_id, event_id) VALUES (?, ?)");
    $insert_query->bind_param("ii", $user_id, $event_id);
    if ($insert_query->execute()) {
        echo json_encode(['message' => 'You have successfully subscribed to the event']);
    } else {
        echo json_encode(['error' => 'Failed to subscribe to the event']);
    }
}

$conn->close();
?>
