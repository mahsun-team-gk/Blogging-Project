 <!-- update post status  -->
  <?php
  require_once("require/database_connection.php");

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $user_id = $_POST['user_id'] ?? '';

    if ($action === 'approval' && isset($_POST['status'])) {
      $status = $_POST['status'];
      $query = "UPDATE user SET is_approved = ? WHERE user_id = ?";
      $stmt = mysqli_prepare($connection, $query);
      mysqli_stmt_bind_param($stmt, 'si', $status, $user_id);
      if (mysqli_stmt_execute($stmt)) {
        echo "success";
      } else {
        echo "error";
      }

    } elseif ($action === 'toggle_active') {
      $getStatus = mysqli_query($connection, "SELECT is_active FROM user WHERE user_id = $user_id");
      $row = mysqli_fetch_assoc($getStatus);
      $newStatus = $row['is_active'] ? 0 : 1;

      $query = "UPDATE user SET is_active = ? WHERE user_id = ?";
      $stmt = mysqli_prepare($connection, $query);
      mysqli_stmt_bind_param($stmt, 'ii', $newStatus, $user_id);
      if (mysqli_stmt_execute($stmt)) {
        echo $newStatus;
      } else {
        echo "error";
      }
    }
  }
  <?php
 // <!-- update post status  -->
  