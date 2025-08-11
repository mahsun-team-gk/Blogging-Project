<!-- update user information -->
        <?php
        require_once("require/database_connection.php");
        require_once("admin_file.php");


        $error = '';
        $success = '';

        if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
        } else {
            die("Invalid user ID.");
        }

        if (isset($_POST['update_user'])) {
            $first_name = $_POST['first_name'];
            $last_name  = $_POST['last_name'];
            $email      = $_POST['email'];
            $address    = $_POST['address'];

            if (empty($first_name) || empty($last_name) || empty($email)) {
                $error = "First name, last name, and email are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format.";
            } else {
                $update_query = "UPDATE user SET 
                    first_name = '$first_name', 
                    last_name = '$last_name', 
                    email = '$email', 
                    address = '$address' 
                    WHERE user_id = $user_id";

                if (mysqli_query($connection, $update_query)) {
                    $success = "User information updated successfully.";
                } else {
                    $error = "Failed to update user information.";
                }
            }
        }

        $select_query = "SELECT first_name, last_name, email, address FROM user WHERE user_id = $user_id";
        $result = mysqli_query($connection, $select_query);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            $first_name = $row['first_name'];
            $last_name  = $row['last_name'];
            $email      = $row['email'];
            $address    = $row['address'];
        } else {
            die("User not found.");
        }


        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
          <meta charset="UTF-8">
          <title>Update User Info</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>

        <div class="container mt-5">
          <h2 class="mb-4">Update User Information</h2>

          <?php if (!empty($error)) { ?>
            <div class="alert alert-danger"><?= $error ?></div>
          <?php } ?>

          <?php if (!empty($success)) { ?>
            <div class="alert alert-success"><?= $success ?></div>
          <?php } ?>

          <form method="post">
            <div class="mb-3">
              <label class="form-label">First Name *</label>
              <input type="text" name="first_name" class="form-control" value="<?= $first_name ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Last Name *</label>
              <input type="text" name="last_name" class="form-control" value="<?= $last_name ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Email *</label>
              <input type="email" name="email" class="form-control" value="<?= $email ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Address</label>
              <textarea name="address" class="form-control"><?= $address ?></textarea>
            </div>

            <button type="submit" name="update_user" class="btn btn-primary">Update</button>
            <a href="all_user.php" class="btn btn-secondary">Back</a>
          </form>
        </div>

        </body>
        </html>
<!-- update user information -->
        
