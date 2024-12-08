<?php
// Database connection
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $role = $_POST['role'];
  $username = $_POST['txt'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $password = password_hash($_POST['pswd'], PASSWORD_DEFAULT);

  // Insert data into the database
  $sql = "INSERT INTO users (role, username, email, phone, password) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $role, $username, $email, $phone, $password);

  if ($stmt->execute()) {
    // Signup success, trigger the Bootstrap modal
    $showSuccessModal = true;
  } else {
    // Error handling
    echo "<script>
                alert('Something went wrong. Please try again.');
                window.location.href = 'login_signup.html';
              </script>";
  }

  $stmt->close();
}
$conn->close();
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<body>
  <!-- Your page content here -->

  <!-- Bootstrap Modal for Success Message -->
  <!-- Bootstrap Modal for Success Message -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="successModalLabel">Congrats!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Congrats! You are now one of EveQuest Family. Please login to access your account. 😎
        </div>
        <div class="modal-footer">
          <!-- Link without data-bs-dismiss, add JavaScript for redirect -->
          <a href="javascript:void(0);" id="goToLoginBtn" class="btn btn-primary">Go to Login</a>
        </div>
      </div>
    </div>
  </div>



  <!-- Bootstrap JS -->
  <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Trigger Modal Script -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Trigger the modal if the signup is successful
    <?php if (isset($showSuccessModal) && $showSuccessModal): ?>
      var successModal = new bootstrap.Modal(document.getElementById('successModal'));
      successModal.show();
    <?php endif; ?>

    // Redirect when clicking the "Go to Login" button
    document.getElementById('goToLoginBtn').addEventListener('click', function() {
      // Close the modal first
      var modal = bootstrap.Modal.getInstance(document.getElementById('successModal'));
      modal.hide();

      // Redirect to the login page after closing the modal
      window.location.href = 'login_signup.html';
    });
  });
</script>

</body>