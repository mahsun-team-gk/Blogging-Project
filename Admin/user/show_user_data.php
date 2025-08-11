
<?php
require_once("require/database_connection.php");
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

// require_once("admin_file.php");

// --- START: Handle AJAX requests for approval and active toggle ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'], $_POST['user_id'])) {
        $userId = intval($_POST['user_id']);

        if ($_POST['action'] === 'approval' && isset($_POST['status'])) {
            $status = $_POST['status'];
            $allowed = ['approved', 'pending', 'rejected'];
            if (!in_array($status, $allowed)) {
                echo 'error';
                exit;
            }

            $stmt = mysqli_prepare($connection, "UPDATE user SET is_approved = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "si", $status, $userId);
            $res = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            echo $res ? 'success' : 'error';
            exit;
        }

        if ($_POST['action'] === 'toggle_active') {
            $query = "SELECT is_active FROM user WHERE user_id = ?";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $currentStatus);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            if ($currentStatus === null) {
                echo 'error';
                exit;
            }

            $newStatus = $currentStatus ? 0 : 1;
            $updateStmt = mysqli_prepare($connection, "UPDATE user SET is_active = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($updateStmt, "ii", $newStatus, $userId);
            $success = mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);

            echo $success ? $newStatus : 'error';
            exit;
        }
    }
}
// --- END: Handle AJAX requests ---

// --- START: Fetch users for display ---
$sql = "SELECT user_id, role_id, first_name, last_name, email, address, is_approved, is_active FROM user";
$result = mysqli_query($connection, $sql);
// --- END: Fetch users ---
?>
<!-- --- START: User Table Display --- -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />

<div class="container mt-5 d-flex flex-column align-items-center">
  <h2 class="mb-4">All Users Database Table</h2>

  <!-- --- START: Filter & Add User Buttons --- -->
    
    <!-- <button id="showActive" class="btn btn-primary me-2"></button> -->

  </div> -->

  <?php
function userFilterButtons() {
    ?>
    <div class="mb-3">
        <a href="show_user_data.php" class="btn btn-primary me-2">All Users</a>
        <a href="active_user.php" class="btn btn-success me-2">Active Users</a>
        <a href="inActive_user.php" class="btn btn-danger me-2">Inactive Users</a>
        <a href="approved_status_user.php" class="btn btn-danger me-2">Approved User Status</a>
        <a href="../form.php" class="btn btn-warning me-2">Add User</a>
        <a href="admin_file.php" class="btn btn-light  me-2">Go back Admin Pannel</a>
    </div>
    <?php
}
?>
<?php userFilterButtons(); ?>

    <!-- <button id="showInactive" class="btn btn-secondary me-2"></button> -->
    <!-- <button id="showAll" class="btn btn-outline-dark">All Users</button> -->
  <!-- --- END: Filter & Add User Buttons --- -->

  <table id="userTable" class="table table-bordered">
    <thead>
      <tr>
        <th>User ID</th>
        <th>Role ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Address</th>
        <th>Approval Status</th>
        <th>Active Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr id="user-<?= $row['user_id']; ?>" data-active="<?= $row['is_active']; ?>">
          <td><?= $row['user_id']; ?></td>
          <td><?= $row['role_id']; ?></td>
          <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
          <td><?= htmlspecialchars($row['email']); ?></td>
          <td><?= htmlspecialchars($row['address']); ?></td>
          <td>
            <div class="btn-group btn-group-sm">
              <button class="btn btn-success approve-btn" data-id="<?= $row['user_id']; ?>" data-status="approved" <?= $row['is_approved'] === 'approved' ? 'disabled' : '' ?>>Approved</button>
              <button class="btn btn-warning text-white approve-btn" data-id="<?= $row['user_id']; ?>" data-status="pending" <?= $row['is_approved'] === 'pending' ? 'disabled' : '' ?>>Pending</button>
              <button class="btn btn-danger approve-btn" data-id="<?= $row['user_id']; ?>" data-status="rejected" <?= $row['is_approved'] === 'rejected' ? 'disabled' : '' ?>>Rejected</button>
            </div>
          </td>
          <td>
            <button class="btn btn-sm toggle-active-btn btn-<?= $row['is_active'] ? 'primary' : 'secondary' ?>" data-id="<?= $row['user_id']; ?>">
              <?= $row['is_active'] ? 'Active' : 'Inactive'; ?>
            </button>
          </td>
          <td>
            <a href="update_user.php?user_id=<?= $row['user_id']; ?>" class="btn btn-sm btn-info">Update</a>
            <button class="btn btn-sm btn-dark mt-1 view-user-btn" data-id="<?= $row['user_id']; ?>">View Info</button>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<!-- --- END: User Table Display --- -->

<!-- --- START: User Info Modal --- -->
<div class="modal fade" id="userInfoModal" tabindex="-1" aria-labelledby="userInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userInfoModalLabel">User Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="userInfoContent">
        <!-- Dynamic content goes here -->
      </div>
    </div>
  </div>
</div>
<!-- --- END: User Info Modal --- -->

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function () {
    var table = $('#userTable').DataTable();
</script>

<script>  
    // --- START: Filter Buttons ---
    $('#showActive').on('click', function () {
      table.rows().every(function () {
        let activeStatus = $(this.node()).data('active');
        if (activeStatus == 1) {
          $(this.node()).show();
        } else {
          $(this.node()).hide();
        }
      });
      table.draw();
    });

    $('#showInactive').on('click', function () {
      table.rows().every(function () {
        let activeStatus = $(this.node()).data('active');
        if (activeStatus == 0) {
          $(this.node()).show();
        } else {
          $(this.node()).hide();
        }
      });
      table.draw();
    });

    $('#showAll').on('click', function () {
      table.rows().every(function () {
        $(this.node()).show();
      });
      table.draw();
    });
    // --- END: Filter Buttons ---

    // --- START: Approve Button Handler ---
    $('#userTable').on('click', '.approve-btn', function () {
      let userId = $(this).data('id');
      let status = $(this).data('status');
      let buttonGroup = $(this).closest('.btn-group');

      $.post('', { action: 'approval', user_id: userId, status: status }, function (response) {
        if (response.trim() === "success") {
          buttonGroup.find('.btn').prop('disabled', false);
          buttonGroup.find(`[data-status="${status}"]`).prop('disabled', true);
        } else {
          alert('Failed to update approval status.');
        }
      });
    });
    // --- END: Approve Button Handler ---

    // --- START: Active Toggle Button Handler ---
    $('#userTable').on('click', '.toggle-active-btn', function () {
      let button = $(this);
      let userId = button.data('id');

      $.post('', { action: 'toggle_active', user_id: userId }, function (response) {
        response = response.trim();
        if (response === '1') {
          button.text('Active').removeClass('btn-secondary').addClass('btn-primary');
          table.row('#user-' + userId).nodes().to$().data('active', 1).show();
          table.draw();
        } else if (response === '0') {
          // Instead of removing the row, just update data-active and hide row, so filtering still works
          table.row('#user-' + userId).nodes().to$().data('active', 0).hide();
          table.draw();
        } else {
          alert('Failed to toggle active status.');
        }
      });
    });
    // --- END: Active Toggle Button Handler ---

    // --- START: View User Info Button Handler ---
    $('#userTable').on('click', '.view-user-btn', function () {
      let userId = $(this).data('id');

      $.get('view_user_info.php', { user_id: userId }, function (data) {
        $('#userInfoContent').html(data);
        $('#userInfoModal').modal('show');
      }).fail(function () {
        $('#userInfoContent').html('<div class="alert alert-danger">Failed to load user info.</div>');
        $('#userInfoModal').modal('show');
      });
    });
    // --- END: View User Info Button Handler ---
  });
</script>
