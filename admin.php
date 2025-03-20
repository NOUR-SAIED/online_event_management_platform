<?php
session_start(); // Start the session

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login page if not authorized
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_style.css">
</head>

<body>
    <!-- Navigation Bar -->
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
                <li class="nav-item"><a class="nav-link" href="admin_profile.html">Profile</a></li>
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
        <h2 class="text-center section-heading">Admin Dashboard</h2>

        <!-- Tabs for User and Event Management -->
        <ul class="nav nav-tabs justify-content-center mt-4" id="adminTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="user-management-tab" data-bs-toggle="tab"
                    data-bs-target="#userManagement" type="button" role="tab" aria-controls="userManagement"
                    aria-selected="true">User Management</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="event-management-tab" data-bs-toggle="tab"
                    data-bs-target="#eventManagement" type="button" role="tab" aria-controls="eventManagement"
                    aria-selected="false">Event Management</button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-4" id="adminTabContent">
            <!-- User Management Tab -->
            <div class="tab-pane fade show active" id="userManagement" role="tabpanel"
                aria-labelledby="user-management-tab">
                <h3 class="text-center">User Management</h3>
                <div class="table-responsive mt-3">
                    <table class="table table-dark table-hover text-center">
                        <thead>
                            <tr>
                                <th scope="col">User ID</th>
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Include the database connection
                            include('db_connection.php');

                            // Fetch non-admin users from the database
                            $sql = "SELECT id, username, email, status FROM users WHERE role != 'admin'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['username'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td><span class='badge bg-success'>" . $row['status'] . "</span></td>";
                                    echo "<td>
                                         <form action='delete_user.php' method='post' style='display:inline;'>
                                            <button class='btn btn-danger btn-sm'>Block</button>
                                            <button class='btn btn-secondary btn-sm'>Unblock</button>
                                            <button class='btn btn-danger btn-sm'>Delete</button>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No users found</td></tr>";
                            }

                            // Close connection
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Event Management Tab -->
            <div class="tab-pane fade" id="eventManagement" role="tabpanel" aria-labelledby="event-management-tab">
                <h3 class="text-center">Event Management</h3>
                <div class="table-responsive mt-3">
                    <table class="table table-dark table-hover text-center">
                        <thead>
                            <tr>
                                <th scope="col">Event ID</th>
                                <th scope="col">Event Name</th>
                                <th scope="col">Created By</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Include the database connection
                            include('db_connection.php');

                            // Fetch events from the database
                            $sql = "SELECT id, event_name, created_by, event_date, status FROM events WHERE status = 'Pending'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['event_name'] . "</td>";
                                    echo "<td>" . $row['created_by'] . "</td>";
                                    echo "<td>" . $row['event_date'] . "</td>";
                                    echo "<td><span class='badge bg-warning'>" . $row['status'] . "</span></td>";
                                    echo "<td>
                                            <form action='update_event.php' method='post' style='display:inline;'>
                                                <input type='hidden' name='event_id' value='" . $row['id'] . "'>
                                                <button type='submit' name='action' value='approve' class='btn btn-success btn-sm'>Approve</button>
                                                <button type='submit' name='action' value='reject' class='btn btn-danger btn-sm'>Reject</button>
                                            </form>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No events found</td></tr>";
                            }

                            // Close connection
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
