<?php
session_start();
require_once("require/database_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        header("Location: login.php?error=Please enter both email and password.");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: login.php?error=Please enter a valid email address.");
        exit();
    }

    $stmt = $connection->prepare("SELECT * FROM user WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (strtolower($user['is_approved']) === 'approved' && strtolower($user['is_active']) === 'active') {
            $_SESSION['users'] = $user;

            if ($user['role_id'] == 1) {
                header("Location: Admin/admin_file.php");
                exit();
            } elseif ($user['role_id'] == 2) {
                header("Location: index.php");
                exit();
            }

            header("Location: login.php?error=Unknown user role. Contact support.");
            exit();
        }

        header("Location: login.php?error=Your account is pending approval or is inactive.");
        exit();
    }

    header("Location: login.php?error=Invalid email or password.");
    exit();
}

mysqli_close($connection);

