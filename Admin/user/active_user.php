<!-- // ================== Active uesr login   ================== -->

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
            <div class="dropdown ms-auto">
                <?php if ($isUserLoggedIn): ?>
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
// ================== Active uesr login   ================== -->

// ================== Fetch Data   ================== -->


            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['user_id'])) {
                if ($_POST['action'] === 'set_inactive') {
                    $userId = intval($_POST['user_id']);

                    $updateQuery = "UPDATE user SET is_active = 0 WHERE user_id = ?";
                    $stmt = mysqli_prepare($connection, $updateQuery);
                    mysqli_stmt_bind_param($stmt, "i", $userId);
                    $success = mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    echo $success ? 'success' : 'error';
                    exit;
                }
            }

            $sql = "SELECT user_id, role_id, first_name, last_name, email, address, is_approved, is_active FROM user WHERE is_active = 1";
            $result = mysqli_query($connection, $sql);
            ?>

            <!DOCTYPE html>
            <html lang="en">
            <head>
              <meta charset="UTF-8" />
              <title>Active Users</title>
              <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
              <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
            </head>
            <body>

            <div class="container mt-5">
              <h2 class="mb-4">Active Users Table</h2>
              <?php
            function userFilterButtons() {
                ?>
                <div class="mb-3">
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

              <table id="activeUserTable" class="table table-bordered">
                <thead>
                  <tr>
                    <th>User ID</th>
                    <th>Role ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Approval Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr id="user-<?= $row['user_id']; ?>">
                      <td><?= htmlspecialchars($row['user_id']); ?></td>
                      <td><?= htmlspecialchars($row['role_id']); ?></td>
                      <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                      <td><?= htmlspecialchars($row['email']); ?></td>
                      <td><?= htmlspecialchars($row['address']); ?></td>
                      <td>
                        <span class="badge bg-<?= 
                          strtolower($row['is_approved']) === 'approved' ? 'success' : (
                          strtolower($row['is_approved']) === 'pending' ? 'warning text-dark' : 'danger') ?>">
                          <?= ucfirst($row['is_approved']); ?>
                        </span>
                      </td>
                      <td>
                        <button class="btn btn-sm btn-danger set-inactive-btn" data-id="<?= $row['user_id']; ?>">
                          Set Inactive
                        </button>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>

            <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

            <script>
            $(document).ready(function () {
              var table = $('#activeUserTable').DataTable();

              $('#activeUserTable').on('click', '.set-inactive-btn', function () {
                var button = $(this);
                var userId = button.data('id');

                if (!confirm("Are you sure you want to set this user inactive?")) {
                  return;
                }

                $.post('', {
                  action: 'set_inactive',
                  user_id: userId
                }, function(response) {
                  response = response.trim();
                  if (response === 'success') {
                    // Remove row from DataTable
                    table.row($('#user-' + userId)).remove().draw();
                  } else {
                    alert("Failed to update user status.");
                  }
                });
              });
            });
            </script>

            </body>
            </html>
<!-- // ================== Fetch Data   ================== --> -->
            
