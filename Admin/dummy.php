<?php
// =================== START User Registration Script ===================
session_start();
require_once(__DIR__ . "/../require/database_connection.php");


// PHPMailer imports
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';
require __DIR__ . '/../PHPMailer/src/Exception.php';

// Include FPDF
require_once(__DIR__ . "/../fpdf/fpdf.php");

// Initialize variables
$first_name = $last_name = $email = $password = $date_of_birth = $address = $gender = '';
$first_name_msg = $last_name_msg = $email_msg = $password_msg = $date_of_birth_msg = $address_msg = $gender_msg = $profile_pic_msg = '';

if (isset($_POST['submit'])) {
    $first_name     = trim($_POST['first_name'] ?? '');
    $last_name      = trim($_POST['last_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $password       = $_POST['password'] ?? '';
    $date_of_birth  = $_POST['date_of_birth'] ?? '';
    $address        = $_POST['address'] ?? '';
    $gender         = $_POST['gender'] ?? '';

    $flag = true;

    // Validation patterns
    $alpha_pattern = '/^[A-Z]{1}[a-z]{2,}$/';
    $email_pattern = '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/';
    $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    $date_pattern = '/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/';

    // First Name validation
    if (empty($first_name) || !preg_match($alpha_pattern, $first_name)) {
        $flag = false;
        $first_name_msg = "Invalid first name format.";
    }

    // Last Name validation
    if (empty($last_name) || !preg_match($alpha_pattern, $last_name)) {
        $flag = false;
        $last_name_msg = "Invalid last name format.";
    }

    // Email validation and duplicate check
    if (empty($email) || !preg_match($email_pattern, $email)) {
        $flag = false;
        $email_msg = "Invalid email format.";
    } else {
        $stmt_check = mysqli_prepare($connection, "SELECT user_id FROM user WHERE email = ?");
        mysqli_stmt_bind_param($stmt_check, "s", $email);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);
        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $flag = false;
            $email_msg = "Email already exists.";
        }
        mysqli_stmt_close($stmt_check);
    }

    // Password validation
    if (empty($password) || !preg_match($password_pattern, $password)) {
        $flag = false;
        $password_msg = "Password must include uppercase, lowercase, number, special character.";
    }

    // Date of birth validation
    if (empty($date_of_birth) || !preg_match($date_pattern, $date_of_birth) || new DateTime($date_of_birth) >= new DateTime()) {
        $flag = false;
        $date_of_birth_msg = "Invalid date of birth.";
    }

    // Address validation
    if (empty($address)) {
        $flag = false;
        $address_msg = "Address is required.";
    }

    // Gender validation
    if (empty($gender)) {
        $flag = false;
        $gender_msg = "Gender is required.";
    }

    // Profile picture validation
    $upload_dir = "uploads/";
    $targetFile = "";
    $allowed_ext = ['jpg', 'jpeg', 'png'];
    $max_size = 5 * 1024 * 1024;

    if (!isset($_FILES['profile_pic']) || $_FILES['profile_pic']['error'] !== 0) {
        $flag = false;
        $profile_pic_msg = "Profile picture upload error.";
    } else {
        $file = $_FILES['profile_pic'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_ext)) {
            $flag = false;
            $profile_pic_msg = "Only JPG, JPEG, PNG allowed.";
        } elseif ($file['size'] > $max_size) {
            $flag = false;
            $profile_pic_msg = "Max file size is 5MB.";
        } elseif (!getimagesize($file['tmp_name'])) {
            $flag = false;
            $profile_pic_msg = "Invalid image.";
        } else {
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $targetFile = $upload_dir . uniqid() . '_' . basename($file['name']);
        }
    }

    if ($flag) {
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            // Save registration date for PDF
            $created_at = date('Y-m-d H:i:s');

            // Insert into DB (password stored plain as requested)
            $stmt = mysqli_prepare($connection, "INSERT INTO user (first_name, last_name, email, password, date_of_birth, address, gender, user_image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssssssss", $first_name, $last_name, $email, $password, $date_of_birth, $address, $gender, $targetFile, $created_at);

            if (mysqli_stmt_execute($stmt)) {

                // =============== Generate PDF ===================
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetTitle('Registration Details');
                $pdf->SetAuthor('Blog Team');

                // Optional logo (comment out if no logo)
                // $pdf->Image('logo.png', 10, 10, 30);

                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, 'Registration Details', 0, 1, 'C');
                $pdf->Ln(10);

                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(50, 10, 'First Name:', 0, 0);
                $pdf->Cell(0, 10, $first_name, 0, 1);

                $pdf->Cell(50, 10, 'Last Name:', 0, 0);
                $pdf->Cell(0, 10, $last_name, 0, 1);

                $pdf->Cell(50, 10, 'Email:', 0, 0);
                $pdf->Cell(0, 10, $email, 0, 1);

                $pdf->Cell(50, 10, 'Password:', 0, 0);
                $pdf->Cell(0, 10, $password, 0, 1);

                $pdf->Cell(50, 10, 'Date of Birth:', 0, 0);
                $pdf->Cell(0, 10, $date_of_birth, 0, 1);

                $pdf->Cell(50, 10, 'Gender:', 0, 0);
                $pdf->Cell(0, 10, $gender, 0, 1);

                $pdf->Cell(50, 10, 'Address:', 0, 0);
                $pdf->MultiCell(0, 10, $address, 0, 1);

                $pdf->Cell(50, 10, 'Registration Date:', 0, 0);
                $pdf->Cell(0, 10, $created_at, 0, 1);

                $pdf->Ln(15);

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->SetTextColor(255, 0, 0);
                $pdf->Cell(0, 10, 'Status: Pending Approval', 0, 1, 'C');

                $pdf->SetFont('Arial', 'I', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->MultiCell(0, 8, "Note: This is an automatically generated registration document.\nYour account will be activated after verification by our team.", 0, 'C');

                $pdf_content = $pdf->Output('S'); // PDF as string

                // =============== Send email with PDF attachment ================
                try {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->Port = 587;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->SMTPAuth = true;
                    $mail->Username = 'phpbasic2k25@gmail.com';  // Your email
                    $mail->Password = 'sffymqljdnupfzjc';       // Your app password

                    $mail->setFrom('phpbasic2k25@gmail.com', 'Blog Team');
                    $mail->addAddress($email, $first_name);
                    $mail->addReplyTo('phpbasic2k25@gmail.com');

                    $mail->isHTML(true);
                    $mail->Subject = "Account Created Successfully - Registration PDF Attached";
                    $mail->Body = "Dear $first_name,<br><br>Your account has been successfully created. Please find attached your registration details.<br><br>Regards,<br>Blog Team";

                    // Attach PDF from string
                    $mail->addStringAttachment($pdf_content, 'registration_details.pdf', 'base64', 'application/pdf');

                    $mail->send();

                    // Send Admin Notification (optional)
                    $mail->clearAddresses();
                    $mail->addAddress('admin@blog.com');
                    $mail->Subject = "New User Registration";
                    $mail->Body = "New user registered:<br>Name: $first_name $last_name<br>Email: $email<br>Password: $password";
                    $mail->send();

                } catch (Exception $e) {
                    error_log("Mailer Error: " . $mail->ErrorInfo);
                }


                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="registration_details.pdf"');
                echo $pdf_content;   

                
                }else {
                $_SESSION['message'] = "Database error: " . mysqli_error($connection);
                $_SESSION['color'] = "red";
                header("Location: form.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "Profile picture upload error.";
            $_SESSION['color'] = "red";
            header("Location: form.php");
            exit();
        }
    }
} else {
    echo "Invalid request.";
}

// =================== END User Registration Script ===================
?>
