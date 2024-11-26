<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php"); // Redirect to login page if not authorized
    exit();
}

// Establish database connection
$conn = new mysqli('localhost', 'root', '', 'user_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user's approved events
$user_email = $_SESSION['user_email'];
$sql = "SELECT * FROM events WHERE created_by = ? AND status = 'Approved'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$user_events_result = $stmt->get_result();

// Fetch approved events by other users
$sql_others = "SELECT * FROM events WHERE created_by != ? AND status = 'Approved'";
$stmt_others = $conn->prepare($sql_others);
$stmt_others->bind_param("s", $user_email);
$stmt_others->execute();
$other_events_result = $stmt_others->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Events</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="view_event_style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">EveQuest</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">&#9776;</span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="HOME.html">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="view_event.html">Events</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">More</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Blog</a>
                        <a class="dropdown-item" href="#">FAQs</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Add a button to create a new event -->
        <div class="text-center mb-4">
        <a href="Event_booking.php" class="btn btn-success btn-lg rounded-pill shadow-lg">Create New Event</a>

        </div>

        <!-- Tabs for switching between user-created and other users' events -->
        <ul class="nav nav-tabs justify-content-center" id="eventsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="user-events-tab" data-bs-toggle="tab" data-bs-target="#userEvents" type="button" role="tab" aria-controls="userEvents" aria-selected="true">Your Events</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="other-events-tab" data-bs-toggle="tab" data-bs-target="#otherUsersEvents" type="button" role="tab" aria-controls="otherUsersEvents" aria-selected="false">Events by Other Users</button>
            </li>
        </ul>

        <!-- Tab Content for both sections -->
        <div class="tab-content mt-4" id="eventsTabContent">
            <!-- User Created Events -->
            <div class="tab-pane fade show active" id="userEvents" role="tabpanel" aria-labelledby="user-events-tab">
                <h3 class="section-heading">Your Created Events</h3>
                <div class="row mt-3">
                    <?php if ($user_events_result->num_rows > 0): ?>
                        <?php while ($row = $user_events_result->fetch_assoc()): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm event-card">
                                    <img src="event_image.jpg" class="card-img-top" alt="Event Image">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['event_name']); ?></h5>
                                        <p class="card-text">Date: <?php echo htmlspecialchars($row['event_date']); ?></p>
                                        <p class="card-text">Location: <?php echo htmlspecialchars($row['event_location']); ?></p>
                                        <a href="event_details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center">No approved events found.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Other Users' Events -->
            <div class="tab-pane fade" id="otherUsersEvents" role="tabpanel" aria-labelledby="other-events-tab">
                <h3 class="section-heading">Events by Other Users</h3>
                <div class="row mt-3">
                    <?php if ($other_events_result->num_rows > 0): ?>
                        <?php while ($row = $other_events_result->fetch_assoc()): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm event-card">
                                    <img src="event_image.jpg" class="card-img-top" alt="Event Image">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['event_name']); ?></h5>
                                        <p class="card-text">Date: <?php echo htmlspecialchars($row['event_date']); ?></p>
                                        <p class="card-text">Location: <?php echo htmlspecialchars($row['event_location']); ?></p>
                                        <a href="event_details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center">No approved events found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
