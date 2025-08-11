<?php
// session_start();
require_once("require/database_connection.php");
require_once("admin_file.php");

// Show success message once
if (isset($_SESSION['feedback_success'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['feedback_success']) . '</div>';
    unset($_SESSION['feedback_success']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Feedback</title>
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">Feedback</h2>
    <form method="post" action="../feedback_send.php">
      <input type="hidden" name="blog_post_id" value="123" />

      <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" name="name" id="name" class="form-control" placeholder="Your Name" required />
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Your Email" required />
        </div>
      <?php endif; ?>

      <div class="mb-3">
        <label for="feedback" class="form-label">Feedback</label>
        <textarea name="feedback" id="feedback" class="form-control" rows="4" placeholder="Your Message" required></textarea>
      </div>

      <button type="submit" class="btn btn-warning">Submit</button>
    </form>
  </div>
</body>
</html>
