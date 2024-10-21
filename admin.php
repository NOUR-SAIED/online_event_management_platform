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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">&#9776;</span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="HOME.html">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="view_event.html">Events</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li> <!-- Updated to include Logout -->
                <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">More</a>
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
                <button class="nav-link active" id="user-management-tab" data-bs-toggle="tab" data-bs-target="#userManagement" type="button" role="tab" aria-controls="userManagement" aria-selected="true">User Management</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="event-management-tab" data-bs-toggle="tab" data-bs-target="#eventManagement" type="button" role="tab" aria-controls="eventManagement" aria-selected="false">Event Management</button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-4" id="adminTabContent">
            <!-- User Management Tab -->
            <div class="tab-pane fade show active" id="userManagement" role="tabpanel" aria-labelledby="user-management-tab">
                <h3 class="text-center">Gestion des utilisateurs</h3>
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
                            <!-- Example User Row -->
                            <tr>
                                <td>1</td>
                                <td>JohnDoe</td>
                                <td>johndoe@example.com</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>
                                    <button class="btn btn-danger btn-sm">Block</button>
                                    <button class="btn btn-secondary btn-sm">Unblock</button>
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                            <!-- Add more users dynamically here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Event Management Tab -->
            <div class="tab-pane fade" id="eventManagement" role="tabpanel" aria-labelledby="event-management-tab">
                <h3 class="text-center">Gestion des événements</h3>
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
                            <!-- Example Event Row -->
                            <tr>
                                <td>101</td>
                                <td>Music Festival</td>
                                <td>JohnDoe</td>
                                <td>12th Oct 2024</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>
                                    <button class="btn btn-success btn-sm">Validate</button>
                                    <button class="btn btn-danger btn-sm">Reject</button>
                                </td>
                            </tr>
                            <!-- Add more events dynamically here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>
