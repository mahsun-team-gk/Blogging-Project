<!-- DataTables Active Users CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />

  <?php
  // Start of PHP Code
  require_once("require/database_connection.php");
  require_once("admin_file.php");

  // ============ Handle Deactivation Before Output ============
  if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'deactivate') {
      $user_id = (int) $_GET['id'];
      $deactivate_query = "UPDATE user SET is_active = 'InActive' WHERE user_id = $user_id";
      if (mysqli_query($connection, $deactivate_query)) {
          // header("Location: active_user.php");
          
      } else {
          echo "<div class='alert alert-danger'>Failed to deactivate user.</div>";
      }
  }
  ?>

  <!-- ========== HTML Layout Starts ========== -->
  <div class="col px-4">
    <div class="container mt-4">

      <!-- Title Card -->
      <div class="row mb-3">
        <div class="col-12">
          <div class="card bg-primary text-white text-center">
            <div class="card-body">
              <i class="fa fa-users fa-2x mb-2"></i>
              <?php
              $active_users_query = "SELECT * FROM user WHERE is_active = 'Active'";
              $active_result = mysqli_query($connection, $active_users_query);
              $total_user = mysqli_num_rows($active_result);
              ?>
              <h4><?= $total_user ?? 0; ?> Active Users</h4>
            </div>
          </div>
        </div>
      </div>

      <!-- Table of Active Users -->
      <div class="row">
        <div class="col-12">
          <div class="table-responsive">
            <table id="myTable" class="table table-bordered table-striped align-middle">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Request</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $user_query = "SELECT * FROM user WHERE is_active = 'Active' ORDER BY user_id DESC";
                $user_result = mysqli_query($connection, $user_query);

                if (mysqli_num_rows($user_result) > 0) {
                  $count = 0;
                  while ($row = mysqli_fetch_assoc($user_result)) {
                    $count++;
                    $name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                    $email = htmlspecialchars($row['email']);
                    $status = "<span class='badge bg-success'>Active</span>";

                    switch ($row['is_approved']) {
                      case 'Approved':
                        $request = "<span class='badge bg-success'>Approved</span>";
                        break;
                      case 'Rejected':
                        $request = "<span class='badge bg-danger'>Rejected</span>";
                        break;
                      case 'Pending':
                        $request = "<span class='badge bg-warning text-dark'>Pending</span>";
                        break;
                      default:
                        $request = "<span class='badge bg-secondary'>Unknown</span>";
                    }

                    $id = (int) $row['user_id'];
                ?>
                    <tr>
                      <td><?= $count ?></td>
                      <td><?= $name ?></td>
                      <td><?= $email ?></td>
                      <td><?= $status ?></td>
                      <td><?= $request ?></td>
                      <td>
                        <a href="update_user.php?id=<?= $id ?>" class="btn btn-sm btn-info">View</a>
                        <a href="update_user.php?id=<?= $id ?>&action=update" class="btn btn-sm btn-warning">Update</a>
                        <a href="?action=deactivate&id=<?= $id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to deactivate this user?');">Deactivate</a>
                      </td>
                    </tr>
                <?php
                  }
                } else {
                  echo "<tr><td colspan='6' class='text-center text-danger'>No active users found.</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- ========== JS Scripts ========== -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      new DataTable('#myTable');
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables Active Users CSS -->
