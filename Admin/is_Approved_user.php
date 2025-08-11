<!-- isApproved status     -->
    <?php
    require_once ("admin_file.php");

    require_once ("require/database_connection.php");
    require_once("PHPMailer/src/PHPMailer.php");
    require_once("PHPMailer/src/SMTP.php");
    require_once("PHPMailer/src/Exception.php");

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // Send email function
    function sendStatusEmail($toEmail, $toName, $status) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = 'phpbasic2k25@gmail.com';
            $mail->Password = 'sffymqljdnupfzjc';
            $mail->setFrom('phpbasic2k25@gmail.com', 'Blog Team');
            $mail->addAddress($toEmail, $toName);
            $mail->Subject = "Account Approval Status Updated";
            $mail->isHTML(true);

            $body = "Dear $toName,<br>Your account approval status has been updated to: <b>" . ucfirst($status) . "</b>.<br>";
            if ($status === 'pending') {
                $body .= "Your account is pending approval. Please wait for confirmation.";
            } elseif ($status === 'approved') {
                $body .= "Congratulations! Your account has been approved.";
            } else { // rejected
                $body .= "Unfortunately, your account has been rejected.";
            }
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    if (isset($_POST['user_id'], $_POST['status'])) {
        $user_id = intval($_POST['user_id']);
        $status = strtolower(trim($_POST['status']));
        $allowed_status = ['approved', 'pending', 'rejected'];

        if ($user_id > 0 && in_array($status, $allowed_status)) {
            // Get user info for mail
            $user_res = mysqli_query($connection, "SELECT first_name, email FROM user WHERE user_id=$user_id");
            if ($user_res && mysqli_num_rows($user_res) === 1) {
                $user = mysqli_fetch_assoc($user_res);
                $first_name = $user['first_name'];
                $email = $user['email'];

                $status_safe = mysqli_real_escape_string($connection, $status);
                $update_sql = "UPDATE user SET is_approved='$status_safe' WHERE user_id=$user_id";
                if (mysqli_query($connection, $update_sql)) {
                    // Send mail
                    sendStatusEmail($email, $first_name, $status);
                    $_SESSION['msg'] = "User ID $user_id approval status updated to " . ucfirst($status);
                } else {
                    $_SESSION['msg'] = "Database error while updating status.";
                }
            } else {
                $_SESSION['msg'] = "User not found.";
            }
        } else {
            $_SESSION['msg'] = "Invalid input.";
        }
        
    }

    $sql = "SELECT user_id, first_name, last_name, email, is_approved FROM user ORDER BY user_id DESC";
    $result = mysqli_query($connection, $sql);

    $message = '';
    if (isset($_SESSION['msg'])) {
        $message = $_SESSION['msg'];
        unset($_SESSION['msg']);
    }

    function btnClass($btn, $status) {
        return ($btn === $status) ? "btn btn-sm btn-primary" : "btn btn-sm btn-outline-secondary";
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>User Approval Management</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <style>
            .btn-sm {
                min-width: 90px;
                margin-right: 5px;
            }
        </style>
    </head>
    <body class="bg-light p-4">

    <div class="container">
        <h2 class="mb-4">User Approval Status</h2>

        <?php if ($message): ?>
            <div class="alert alert-success"><?= ($message) ?></div>
        <?php endif; ?>

        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Approval Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($result)) : 
                    $status = strtolower($user['is_approved']);
                ?>
                <tr>
                    <td><?= ($user['user_id']) ?></td>
                    <td><?= ($user['first_name'] . ' ' . $user['last_name']) ?></td>
                    <td><?= ($user['email']) ?></td>
                    <td>
                        <?php foreach (['approved', 'pending', 'rejected'] as $st): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <input type="hidden" name="status" value="<?= $st ?>">
                            <button type="submit" class="<?= btnClass($st, $status) ?>"><?= ucfirst($st) ?></button>
                        </form>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    </body>
    </html>
<!-- isApproved status     -->
