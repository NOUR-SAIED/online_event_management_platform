<?php
session_start(); // Start the session
// Check if the user is logged in and session variables are set

// Establish database connection
$conn = new mysqli('localhost', 'root', '', 'user_management');

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Variable to track the submission status
$showSuccessModal = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['eventTitle'], $_POST['eventDescription'], $_POST['eventDate'], $_POST['eventTime'], $_POST['eventLocation'], $_POST['eventCategory'])) {
    $event_name = $_POST['eventTitle'];
    $event_description = $_POST['eventDescription'];
    $event_date = $_POST['eventDate'];
    $event_time = $_POST['eventTime'];
    $event_location = $_POST['eventLocation'];
    $event_category = $_POST['eventCategory'];

    // User id comes from the session or form input
    $created_by = $_POST['email'];

    // Handle file upload
    if (isset($_FILES['eventImage']) && $_FILES['eventImage']['error'] == 0) {
      $targetDir = "uploads/"; // Directory to store uploaded images
      $fileName = basename($_FILES['eventImage']['name']);
      $targetFilePath = $targetDir . $fileName;

      // Check file type
      $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
      $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
      if (in_array(strtolower($fileType), $allowedTypes)) {
        // Move the file to the target directory
        if (move_uploaded_file($_FILES['eventImage']['tmp_name'], $targetFilePath)) {
          $event_image = $fileName; // Store the file name in the database
        } else {
          echo "Failed to upload the image.";
          exit;
        }
      } else {
        echo "Invalid file type.";
        exit;
      }
    } else {
      $event_image = null; // No image uploaded
    }


    // Prepare the SQL query
    $sql = "INSERT INTO events (event_name, event_description, event_date, event_time, event_location, event_category, created_by, status, event_image) 
     VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $event_name, $event_description, $event_date, $event_time, $event_location, $event_category, $created_by, $event_image);

    // Execute the query and set the modal trigger if successful
    if ($stmt->execute()) {
      $showSuccessModal = true; // Set the flag to true
    } else {
      echo "Error: " . $stmt->error;
    }

    $stmt->close();
  } else {
    echo "All fields are required.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="Event_booking_style.css">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <title>Create Event</title>
</head>

<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon">&#9776;</span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="HOME.html">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="view_event.php">Events</a></li>
        <li class="nav-item"><a class="nav-link" href="profile.html">Profile</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">More</a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#">Blog</a>
            <a class="dropdown-item" href="#">FAQs</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-4">
            <div class="parent">
              <h2 class="h4 mb-4">Create Your Own Event</h2>
            </div>

            <form action="Event_booking.php" method="POST" enctype="multipart/form-data">

              <!-- Event Title -->
              <div class="mb-3">
                <label for="eventTitle" class="form-label">Event Name</label>
                <input type="text" class="form-control" id="eventTitle" name="eventTitle" required>
              </div>
              <!-- user email -->
              <div class="mb-3">
                <label for="email" class="form-label"> Your Email</label>
                <input type="text" class="form-control" id="email" name="email" required>
              </div>
              <!-- Event Description -->
              <div class="mb-3">
                <label for="eventDescription" class="form-label">Event Description</label>
                <textarea class="form-control" id="eventDescription" name="eventDescription" rows="4"
                  required></textarea>
              </div>
              <!-- Event Date and Time -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="eventDate" class="form-label">Event Date</label>
                  <input type="date" class="form-control" id="eventDate" name="eventDate" required>
                </div>
                <div class="col-md-6">
                  <label for="eventTime" class="form-label">Event Time</label>
                  <input type="time" class="form-control" id="eventTime" name="eventTime" required>
                </div>
              </div>
              <!-- Event Location -->
              <div class="mb-3">
                <label for="eventLocation" class="form-label">Event Location</label>
                <input type="text" class="form-control" id="eventLocation" name="eventLocation" required>
              </div>
              <!-- Event Category -->
              <div class="mb-3">
                <label for="eventCategory" class="form-label">Event Category</label>
                <select class="form-select" id="eventCategory" name="eventCategory" required>
                  <option value="" disabled selected>Select a category</option>
                  <option value="music">Music</option>
                  <option value="sports">Sports</option>
                  <option value="conference">Conference</option>
                  <option value="workshop">Workshop</option>
                  <option value="art">Art</option>
                  <option value="food">Food</option>
                  <option value="technology">Technology</option>
                  <option value="health">Health</option>
                  <option value="business">Business</option>
                  <option value="education">Education</option>
                  <option value="charity">Charity</option>
                  <option value="film">Film</option>
                  <option value="literature">Literature</option>
                  <option value="networking">Networking</option>
                  <option value="festival">Festival</option>
                  <option value="gaming">Gaming</option>
                  <option value="theater">Theater</option>
                  <option value="dance">Dance</option>
                  <option value="fashion">Fashion</option>
                  <option value="travel">Travel</option>
                  <option value="science">Science</option>
                  <option value="environment">Environment</option>
                  <option value="history">History</option>
                  <option value="politics">Politics</option>

                </select>
              </div>
              <!-- Upload Event Image -->
              <div class="mb-3">
                <label for="eventImage" class="form-label">Event Image</label>
                <input type="file" class="form-control" id="eventImage" name="eventImage" accept="image/*">
              </div>




              <!-- Submit and Cancel Buttons -->
              <div class="d-flex justify-content-between">
                <!-- "Back" Button -->
                <button class="btn btn-secondary" type="button" onclick="history.back()">Back</button>
                <button class="btn btn-secondary" type="button">Cancel</button>
                <button class="btn btn-primary" type="submit">Submit</button>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Bootstrap Modal for Success Message -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="successModalLabel">Event Submission</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Event submitted successfully!
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Trigger Modal Script -->
  <script>
    <?php if ($showSuccessModal): ?>
      var successModal = new bootstrap.Modal(document.getElementById('successModal'));
      successModal.show();
    <?php endif; ?>
  </script>

</body>

</html>