<?php
require_once("require/database_connection.php");
                // require_once("admin_file.php");

session_start();
                $isUserLoggedIn = isset($_SESSION['users']);
                $isAdminLoggedIn = isset($_SESSION['admin']);

                // print_r($_SESSION);
                ?>
                <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm mb-4">
        <div class="container-fluid">
            <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- RIGHT Dropdown for user -->
            <div class="dropdown ms-auto">
                <?php if ($isUserLoggedIn): ?>
                    <!-- User Dropdown -->
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo htmlspecialchars($_SESSION['users']['user_image']); ?>" alt="User Image" width="32" height="32" class="rounded-circle me-2">
                        <strong><?php echo htmlspecialchars($_SESSION['users']['first_name'] . ' ' . $_SESSION['users']['last_name']); ?></strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="../edit_profile.php"><i class="bi bi-person-fill"></i> Edit Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-chat-dots-fill"></i> Messages</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear-fill"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                <?php elseif ($isAdminLoggedIn): ?>
                    <!-- Admin Dropdown -->
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownAdmin" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../images/1 (1).jpg" alt="Admin" width="32" height="32" class="rounded-circle me-2">
                        <strong>Admin</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownAdmin">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person-fill"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear-fill"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
<?php




// Handle AJAX POST actions before any HTML output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? '';
    $userId = $_POST['user_id'] ?? '';

    if (!is_numeric($userId)) {
        echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
        exit;
    }

    if ($action === 'activate_user') {
        $query = "UPDATE user SET is_active = 1 WHERE user_id = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Activation failed']);
        }
        exit;
    } elseif ($action === 'delete_user') {
        $query = "DELETE FROM user WHERE user_id = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Deletion failed']);
        }
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
    }
}

// Fetch inactive users
$sql = "SELECT user_id, first_name, last_name, email, is_active FROM user WHERE is_active = 0";
$result = mysqli_query($connection, $sql);
if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inactive Users</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .container {
      max-width: 1200px;
    }
    .status-badge {
      font-size: 0.75rem;
      padding: 5px 10px;
      border-radius: 12px;
      font-weight: 500;
      display: inline-block;
      min-width: 70px;
      text-align: center;
    }
    .inactive { background-color: #f8d7da; color: #721c24; }
    .action-btn {
      padding: 5px 10px;
      font-size: 0.8rem;
      border-radius: 4px;
      border: none;
      cursor: pointer;
      transition: all 0.3s;
    }
    .btn-activate {
      background-color: #28a745;
      color: white;
    }
    .btn-delete {
      background-color: #dc3545;
      color: white;
    }
  </style>
</head>
<body>
          <?php
function userFilterButtons() {
    ?>
    <div class="mb-5 mt-5 mx-5">
        <a href="show_user_data.php" class="btn btn-primary me-2">All Users</a>
        <a href="active_user.php" class="btn btn-success me-2">Active Users</a>
        <a href="inActive_user.php" class="btn btn-danger me-2">Inactive Users</a>
        <a href="../form.php" class="btn btn-warning me-2">Add User</a>
        <a href="admin_file.php" class="btn btn-light  me-2">Go back Admin Pannel</a>
    </div>
    <?php
}
?>
<?php userFilterButtons(); ?>

<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-dark text-white">
      <h4 class="mb-0"><i class="fas fa-user-slash me-2"></i>Inactive Users</h4>

    </div>
    <div class="card-body">
      <table id="userTable" class="table table-hover">
        <thead class="thead-dark">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr id="user-<?php echo $row['user_id']; ?>">
              <td><?php echo htmlspecialchars($row['user_id']); ?></td>
              <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><span class="status-badge inactive">Inactive</span></td>
              <td>
                <button class="action-btn btn-activate" onclick="activateUser(<?php echo $row['user_id']; ?>)">
                  <i class="fas fa-power-off me-1"></i>Activate
                </button>
                <button class="action-btn btn-delete" onclick="deleteUser(<?php echo $row['user_id']; ?>)">
                  <i class="fas fa-trash me-1"></i>Delete
                </button>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function () {
    $('#userTable').DataTable({
      responsive: true,
      dom: '<"top"lf>rt<"bottom"ip><"clear">',
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
  });

  function activateUser(userId) {
    $.ajax({
      url: '', // Same file
      method: 'POST',
      dataType: 'json',
      data: {
        action: 'activate_user',
        user_id: userId
      },
      success: function(response) {
        if (response.success) {
          Swal.fire({
            icon: 'success',
            title: 'User Activated',
            text: 'The user has been activated successfully',
            timer: 1500,
            showConfirmButton: false
          }).then(() => {
            $('#user-' + userId).fadeOut(300, function() {
              $(this).remove();
              $('#userTable').DataTable().draw();
            });
          });
        } else {
          Swal.fire('Error', response.message || 'Failed to activate user', 'error');
        }
      },
      error: function(xhr) {
        Swal.fire('Error', 'Failed to connect to server', 'error');
        console.log(xhr.responseText);
      }
    });
  }

  function deleteUser(userId) {
    Swal.fire({
      title: 'Confirm Deletion',
      text: 'Are you sure you want to permanently delete this user?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: 'Delete',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '', // Same file
          method: 'POST',
          dataType: 'json',
          data: {
            action: 'delete_user',
            user_id: userId
          },
          success: function(response) {
            if (response.success) {
              Swal.fire({
                icon: 'success',
                title: 'User Deleted',
                text: 'The user has been deleted successfully',
                timer: 1500,
                showConfirmButton: false
              }).then(() => {
                $('#user-' + userId).fadeOut(300, function() {
                  $(this).remove();
                  $('#userTable').DataTable().draw();
                });
              });
            } else {
              Swal.fire('Error', response.message || 'Failed to delete user', 'error');
            }
          },
          error: function(xhr) {
            Swal.fire('Error', 'Failed to connect to server', 'error');
            console.log(xhr.responseText);
          }
        });
      }
    });
  }
</script>

</body>
</html>
