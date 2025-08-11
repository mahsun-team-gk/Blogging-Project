<!-- all user datatable -->
      <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />

      <?php
      require_once("require/database_connection.php");
      require_once("admin_file.php");

      ?>
          <div class="col px-4">
            <div class="container mt-4">

              <!-- Users Title Card -->
              <div class="row">
                <div class="col-12 mb-3">
                  <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                      <i class="fa fa-users fa-2x mb-2"></i>
                      <?php

                      $sql = "SELECT * FROM user";
                      $result = mysqli_query($connection, $sql);

                      if (mysqli_num_rows($result) > 0) {
                        $total_user = mysqli_num_rows($result);
                      }
                      
                      ?>
                      <h4><?= $total_user ?? "";?> Users</h4>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <form action="#">
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
            
      $sql = "SELECT * FROM user order by user_id desc";
      $result = mysqli_query($connection, $sql);

      if (mysqli_num_rows($result) > 0) {
          $count = 0;
          while ($row = mysqli_fetch_assoc($result)) {
            $count++;
              $name  = $row['first_name'] . " " . $row['last_name'];
              $email = $row['email'];
              $status = $row['is_active'];
              $request = $row['is_approved'];
              $id = $row['user_id'];
                  if ($status == "Active") {
              $status_label = "<span class='badge bg-success'>Active</span>";
          } elseif ($status == "InActive") {
              $status_label = "<span class='badge bg-danger'>Inactive</span>";
          } elseif ($status == "Pending") {
              $status_label = "<span class='badge bg-warning text-dark'>Pending</span>";
          } else {
              $status_label = "<span class='badge bg-secondary'>Unknown</span>";
          }

          if ($request == "Approved") {
              $status_request = "<span class='badge bg-success'>Approved</span>";
          } elseif ($request == "Rejected") {
              $status_request = "<span class='badge bg-danger'>Rejected</span>";
          } elseif ($request == "Pending") {
              $status_request = "<span class='badge bg-warning text-dark'>Pending</span>";
          } else {
              $status_request = "<span class='badge bg-secondary'>Unknown</span>";
          }
      ?>
            <tr>
              <td><?= $count?></td>
              <td><?= $name; ?></td>
              <td><?= $email; ?></td>
              <td><?= $status_label;?></td>
              <td><?= $status_request;?></td>
              <td>
                <a href="view_user_info.php?user_id=<?= $id; ?>" class="btn btn-sm btn-info">View</a>
                <a href="update_user.php?user_id=<?= $id; ?>&action=update" class="btn btn-sm btn-warning">Update</a>

              </td>
            </tr>

      <?php

          }
      } else {
          echo "No active users found.";
      }
            
            ?>
          </tbody>
        </table>                    </form>
                  </div>
      </div>
              </div>

            </div>
  </div>

          </div>
      </div>
        <script>
          document.addEventListener('DOMContentLoaded', function () {
            new DataTable('#myTable');
          });
        </script>
      <!-- Footer -->
      <!-- <footer class="bg-dark text-center text-white py-3 mt-4">
        <p class="mb-0">&copy; 2025 Admin Panel</p>
      </footer> -->


      <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
      <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      <script>
        $(document).ready(function () {
          var table = $('#userTable').DataTable();
      </script>

      <!-- Bootstrap JS -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

      </body>
      </html>
<!-- all user datatable -->
