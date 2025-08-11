<!-- // --- all suer status approved --- -->
    <?php
    session_start();
    require_once("require/database_connection.php");
                    $isUserLoggedIn = isset($_SESSION['users']);
                    $isAdminLoggedIn = isset($_SESSION['admin']);
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
// <!-- // --- all suer status approved --- -->


// --- START: PHPMailer Setup ---
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['user_id'], $_POST['status']) && $_POST['action'] === 'approval') {
        $userId = intval($_POST['user_id']);
        $status = $_POST['status'];
        $allowed = ['approved', 'pending', 'rejected'];
        if (!in_array($status, $allowed)) {
            echo 'error';
            exit;
        }
        $stmt = mysqli_prepare($connection, "SELECT email, first_name, last_name FROM user WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $email, $firstName, $lastName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        $stmt = mysqli_prepare($connection, "UPDATE user SET is_approved = ? WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "si", $status, $userId);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if ($result && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->Username = 'phpbasic2k25@gmail.com';
                $mail->Password = 'sffymqljdnupfzjc';
                $mail->setFrom('phpbasic2k25@gmail.com', 'Blog Team');
                $mail->addReplyTo('phpbasic2k25@gmail.com');
                $mail->addAddress($email, "$firstName $lastName");
                $mail->isHTML(true);
                if ($status === 'approved') {
                    $mail->Subject = 'Account Approved';
                    $mail->Body = "<h3>Dear $firstName $lastName,</h3><p>Your account has been approved.</p><p>Regards,<br>Admin</p>";
                    $mail->AltBody = "Dear $firstName $lastName,\n\nYour account has been approved.\n\nRegards,\nAdmin";
                } elseif ($status === 'rejected') {
                    $mail->Subject = 'Account Rejected';
                    $mail->Body = "<h3>Dear $firstName $lastName,</h3><p>We regret to inform you your account was rejected.</p><p>Regards,<br>Admin</p>";
                    $mail->AltBody = "Dear $firstName $lastName,\n\nYour account was rejected.\n\nRegards,\nAdmin";
                }
                $mail->send();
            } catch (Exception $e) {
                error_log("Mailer Error: {$mail->ErrorInfo}");
            }
        }
        echo $result ? 'success' : 'error';
        exit;
    }
    ?>
<!-- // --- START: PHPMailer Setup --- -->

<!-- // --- html structure --- -->
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>User Approval Status</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
      <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
      <style>
        .btn-group-sm .btn { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
        .disabled-btn { opacity: 0.65; cursor: not-allowed; }
      </style>
    </head>
    <body>
      <?php
    function userFilterButtons() {
        ?>
        <div class="mb-3  mt-4 text-center ">
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
    <div class="container-fluid">
      <h2 class="text-center mb-4">User Approval Status</h2>
      <table id="approvalTable" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Role</th>
            <th>Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>Approval Action</th>
            <th>Current Status</th>
          </tr>
        </thead>
        <tbody>
    <?php
// <!-- // --- html structure --- -->

// --- START: Display Users ---
    $sql = "SELECT user_id, role_id, first_name, last_name, email, address, is_approved FROM user";
    $res = mysqli_query($connection, $sql);
    while ($row = mysqli_fetch_assoc($res)) {
        $status = $row['is_approved'];
        $statusClass = match($status) {
            'approved' => 'text-success',
            'pending' => 'text-warning',
            'rejected' => 'text-danger',
            default => '',
        };
        echo "<tr>
          <td>{$row['user_id']}</td>
          <td>{$row['role_id']}</td>
          <td>{$row['first_name']} {$row['last_name']}</td>
          <td>{$row['email']}</td>
          <td>{$row['address']}</td>
          <td>
            <div class='btn-group btn-group-sm'>
              <button class='btn btn-success approve-btn' data-id='{$row['user_id']}' data-status='approved' " . ($status === 'approved' ? 'disabled' : '') . ">Approve</button>
              <button class='btn btn-warning text-white approve-btn' data-id='{$row['user_id']}' data-status='pending' " . ($status === 'pending' ? 'disabled' : '') . ">Pending</button>
              <button class='btn btn-danger approve-btn' data-id='{$row['user_id']}' data-status='rejected' " . ($status === 'rejected' ? 'disabled' : '') . ">Reject</button>
            </div>
          </td>
          <td class='fw-bold {$statusClass}'>" . ucfirst($status) . "</td>
        </tr>";
    }
    ?>
<!-- // --- END: Display Users --- -->

<!-- // --- ajax work --- -->

        </tbody>
      </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function () {
      $('#approvalTable').DataTable();
      $(document).on('click', '.approve-btn', function () {
        let userId = $(this).data('id');
        let status = $(this).data('status');
        let buttonGroup = $(this).closest('.btn-group');
        let row = $(this).closest('tr');
        if (status === 'rejected' && !confirm('Are you sure to reject this user?')) return;
        $.post('', { action: 'approval', user_id: userId, status: status }, function (response) {
          if (response.trim() === 'success') {
            buttonGroup.find('.btn').prop('disabled', false);
            buttonGroup.find(`[data-status="${status}"]`).prop('disabled', true);
            let statusCell = row.find('td:last');
            statusCell.text(status.charAt(0).toUpperCase() + status.slice(1));
            statusCell.removeClass().addClass('fw-bold').addClass(status === 'approved' ? 'text-success' : status === 'pending' ? 'text-warning' : 'text-danger');
          } else {
            alert('Failed to update status.');
          }
        });
      });
    });
    </script>
    </body>
    </html>
<!-- // --- ajax work --- -->

