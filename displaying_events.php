<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Events</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
   <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="#">EveQuest</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon">&#9776;</span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="displaying_events.php">Events</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login_signup.html">Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            More
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#">Blog</a>
            <a class="dropdown-item" href="#">FAQs</a>
          </div>
        </li>
      </ul>
      <!-- Search Bar -->
      <form class="form-inline d-flex ms-3">
        <input class="form-control me-2" type="search" placeholder="Search Events..." aria-label="Search">
        <button class="btn btn-outline-light" type="submit">Search</button>
      </form>
    </div>
  </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Approved Events</h1>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($row['event_image'] ?: 'placeholder.jpg'); ?>" class="card-img-top" alt="Event Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['event_name']); ?></h5>
                                <p class="card-text">
                                    <strong>Date:</strong> <?php echo htmlspecialchars($row['event_date']); ?><br>
                                    <strong>Location:</strong> <?php echo htmlspecialchars($row['event_location']); ?><br>
                                    <strong>Category:</strong> <?php echo htmlspecialchars($row['event_category']); ?>
                                </p>
                                <a href="event_details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No approved events available.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
