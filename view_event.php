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

// Fetch the logged-in user's email and ID (you should have stored the user ID in the session upon login)
$user_email = $_SESSION['user_email'];

// Get the user's ID from the database
$user_id_query = $conn->prepare("SELECT id FROM users WHERE email = ?");
$user_id_query->bind_param("s", $user_email);
$user_id_query->execute();
$user_id_result = $user_id_query->get_result();
$user_data = $user_id_result->fetch_assoc();
$user_id = $user_data['id'];
$user_id_query->close();


// Fetch user's events (both created and subscribed)
$sql_user_events = "
    SELECT e.*, 'Created' AS event_type
    FROM events e 
    WHERE e.created_by = ? AND e.status = 'Approved'
    UNION
    SELECT e.*, 'Subscribed' AS event_type
    FROM events e
    JOIN subscriptions s ON e.id = s.event_id
    WHERE s.user_id = ? AND e.status = 'Approved'
";
$stmt_user_events = $conn->prepare($sql_user_events);
$stmt_user_events->bind_param("ii", $user_id, $user_id);
$stmt_user_events->execute();
$user_events_result = $stmt_user_events->get_result();
$stmt_user_events->close();


// Fetch approved events created by other users
$sql_others = "SELECT * FROM events WHERE created_by != ? AND status = 'Approved'";
$stmt_others = $conn->prepare($sql_others);
$stmt_others->bind_param("s", $user_email);
$stmt_others->execute();
$other_events_result = $stmt_others->get_result();
$stmt_others->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Events</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                <li class="nav-item"><a class="nav-link" href="profile.html">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
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
                <button class="nav-link active" id="user-events-tab" data-bs-toggle="tab" data-bs-target="#userEvents"
                    type="button" role="tab" aria-controls="userEvents" aria-selected="true">Your Events</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="other-events-tab" data-bs-toggle="tab" data-bs-target="#otherUsersEvents"
                    type="button" role="tab" aria-controls="otherUsersEvents" aria-selected="false">Events by Other
                    Users</button>
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
                                    <img src="<?php echo htmlspecialchars($row['event_image']); ?>" class="card-img-top"
                                        alt="Event Image">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['event_name']); ?></h5>
                                        <p class="card-text">Date: <?php echo htmlspecialchars($row['event_date']); ?></p>
                                        <p class="card-text">Location: <?php echo htmlspecialchars($row['event_location']); ?>
                                        </p>
                                        <a href="event_details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View
                                            Details</a>

                                        <?php if ($row['event_type'] == 'Subscribed'): ?>
                                            <span class="badge bg-secondary">Subscribed</span>
                                        <?php else: ?>
                                            <button class="btn btn-success btn-sm subscribe-btn"
                                                data-event-id="<?php echo $row['id']; ?>">Subscribe</button>
                                        <?php endif; ?>
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
                <!-- Filter Button with Icon -->
                <div>
                    <div class="filter-container">
                        <button id="filterBtnCategory" class="filter-btn">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                    <!-- Modal for Date Filtering -->
                    <div id="dateFilterModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2>Filter Events by Date</h2>
                            <form method="GET" action="">
                                <label for="start_date">Start Date:</label>
                                <input type="date" id="start_date" name="start_date">

                                <label for="end_date">End Date:</label>
                                <input type="date" id="end_date" name="end_date">

                                <button type="submit">Apply Filter</button>
                            </form>
                        </div>
                    </div>

                </div>

                <h3 class="section-heading">Events by Other Users</h3>
                <div class="row mt-3">
                    <?php if ($other_events_result->num_rows > 0): ?>
                        <?php while ($row = $other_events_result->fetch_assoc()): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm event-card">
                                    <img src="<?php echo htmlspecialchars($row['event_image']); ?>" class="card-img-top"
                                        alt="Event Image">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['event_name']); ?></h5>
                                        <p class="card-text">Date: <?php echo htmlspecialchars($row['event_date']); ?></p>
                                        <p class="card-text">Location: <?php echo htmlspecialchars($row['event_location']); ?>
                                        </p>
                                        <a href="event_details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View
                                            Details</a>
                                        <button class="btn btn-success subscribe-btn"
                                            data-event-id="<?php echo $row['id']; ?>">Subscribe</button>
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

    <script>
        // Use jQuery for simplicity
        $(document).ready(function () {
            // Handle the Subscribe button click
            $('.subscribe-btn').on('click', function () {
                var eventId = $(this).data('event-id'); // Get the event ID from the button's data attribute

                // Show a loading message or disable the button to indicate it's being processed
                var button = $(this);
                button.prop('disabled', true); // Disable the button to prevent multiple clicks
                button.text('Subscribing...'); // Change button text

                $.ajax({
                    url: 'subscribe.php', // The script that will handle the subscription
                    type: 'POST',
                    data: {
                        event_id: eventId // Send the event ID to the server
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data.message) {
                            // Successfully subscribed, show the success message
                            alert(data.message); // Show success alert
                            button.text('Subscribed'); // Update button text to "Subscribed"
                            button.prop('disabled', true); // Disable the button to prevent further clicks
                            location.reload(); // Reload the page to reflect the subscription
                        } else if (data.error) {
                            // Error occurred, show the error message
                            alert(data.error); // Show error alert
                            button.text('Subscribe'); // Reset the button text
                            button.prop('disabled', false); // Enable the button again
                        }
                    },
                    error: function () {
                        alert('An error occurred while subscribing to the event.');
                        button.text('Subscribe'); // Reset the button text
                        button.prop('disabled', false); // Enable the button again
                    }
                });
            });
        });
    </script>






</body>

</html>