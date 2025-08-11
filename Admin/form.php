<?php
require_once("require/database_connection.php");
require_once("admin_file.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Registration Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .required { color: red; }
  </style>
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white text-center">
      <h3>User Registration Form</h3>
    </div>

    <div class="card-body">
      <p class="text-center text-success"><?= $_REQUEST['message'] ?? '' ?></p>

      <form action="dummy.php" method="POST" enctype="multipart/form-data" onsubmit="return validation()">
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="first_name" class="form-label">First Name <span class="required">*</span></label>
            <input type="text" id="first_name" name="first_name" class="form-control" value="<?= $first_name ?? '' ?>">
            <small class="text-danger"><?= $first_name_msg ?? '' ?></small>
          </div>

          <div class="col-md-6">
            <label for="last_name" class="form-label">Last Name <span class="required">*</span></label>
            <input type="text" id="last_name" name="last_name" class="form-control" value="<?= $last_name ?? '' ?>">
            <small class="text-danger"><?= $last_name_msg ?? '' ?></small>
          </div>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email <span class="required">*</span></label>
          <input type="email" id="email" name="email" class="form-control" value="<?= $email ?? '' ?>">
          <small id="email_msg" class="text-danger"><?= $email_msg ?? '' ?></small>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password <span class="required">*</span></label>
          <input type="password" id="password" name="password" class="form-control" value="<?= $password ?? '' ?>">
          <small class="text-danger"><?= $password_msg ?? '' ?></small>
        </div>

        <div class="mb-3">
          <label for="date_of_birth" class="form-label">Date of Birth <span class="required">*</span></label>
          <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?= $date_of_birth ?? '' ?>">
          <small class="text-danger"><?= $date_of_birth_msg ?? '' ?></small>
        </div>

        <div class="mb-3">
          <label for="address" class="form-label">Address <span class="required">*</span></label>
          <input type="text" id="address" name="address" class="form-control" value="<?= $address ?? '' ?>">
          <small class="text-danger"><?= $address_msg ?? '' ?></small>
        </div>

        <div class="mb-3">
          <label class="form-label">Gender <span class="required">*</span></label>
          <div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" id="gender_male" type="radio" name="gender" value="Male" <?= isset($gender) && $gender == 'Male' ? 'checked' : '' ?>>
              <label class="form-check-label" for="gender_male">Male</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" id="gender_female" type="radio" name="gender" value="Female" <?= isset($gender) && $gender == 'Female' ? 'checked' : '' ?>>
              <label class="form-check-label" for="gender_female">Female</label>
            </div>
          </div>
          <small id="gender_msg" class="text-danger"><?= $gender_msg ?? '' ?></small>
        </div>

        <div class="mb-3">
          <label for="profile_pic" class="form-label">Upload Profile Picture <span class="required">*</span></label>
          <input class="form-control" id="profile_pic" type="file" name="profile_pic">
          <small class="text-danger"><?= $profile_pic_msg ?? '' ?></small>
        </div>

        <div class="text-center">
          <button type="submit" name="submit" id="submit_btn" class="btn btn-success">Register</button>
          <button type="reset" class="btn btn-secondary">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="client_side_validation.js"></script>

<script>
  $(document).ready(function () {
    $('#email').on('blur', function () {
      var email = $(this).val().trim();

      if (email.length > 0) {
        $.ajax({
          url: 'check_email.php',
          type: 'POST',
          data: { email: email },
          success: function (response) {
            if (response.trim() === 'exists') {
              $('#email_msg').text('This email is already registered. Try another.');
              $('#submit_btn').prop('disabled', true);
            } else {
              $('#email_msg').text('');
              $('#submit_btn').prop('disabled', false);
            }
          },
          error: function () {
            $('#email_msg').text('Server error. Please try again.');
          }
        });
      }
    });
  });
</script>

</body>
</html>
