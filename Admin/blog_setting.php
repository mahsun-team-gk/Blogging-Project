<?php
// session_start(); // Make sure session is started!

require_once("require/database_connection.php");
require_once("admin_file.php");

// Get the user ID from session at the very start
$userId = isset($_SESSION['users']['user_id']) ? $_SESSION['users']['user_id'] : null;

// Check if user is logged in
if (!$userId) {
    die("User not logged in.");
}

// Save blog settings if form submitted
if (isset($_POST['save_settings'])) {
    $title_color = $_POST['title_color'];
    $bg_color = $_POST['background_color'];
    $font = $_POST['font_style'];

    $settings = [
        'title_color' => $title_color,
        'background_color' => $bg_color,
        'font_style' => $font
    ];

    foreach ($settings as $key => $val) {
        $check = mysqli_query($connection, "SELECT * FROM setting WHERE user_id = $userId AND setting_key = '$key'");
        if (mysqli_num_rows($check) > 0) {
            mysqli_query($connection, "UPDATE setting SET setting_value = '$val', updated_at = NOW() WHERE user_id = $userId AND setting_key = '$key'");
        } else {
            mysqli_query($connection, "INSERT INTO setting (user_id, setting_key, setting_value, setting_status, created_at, updated_at) VALUES ($userId, '$key', '$val', 'Active', NOW(), NOW())");
        }
    }
    echo '<div class="alert alert-success">Settings saved successfully.</div>';
}

// Function to get setting value from DB
function get_setting($con, $user_id, $key) {
    $res = mysqli_query($con, "SELECT setting_value FROM setting WHERE user_id = $user_id AND setting_key = '$key' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        return $row['setting_value'];
    }
    return '';
}

// Fetch current settings for the form
$title_color = get_setting($connection, $userId, 'title_color');
$bg_color = get_setting($connection, $userId, 'background_color');
$font = get_setting($connection, $userId, 'font_style');
?>

<!-- Blog Settings Form -->
<div class="card mb-4">
    <div class="card-header bg-light-subtle">Blog Settings</div>
    <div class="card-body">
        <form method="POST">
            <label>Post Title Color</label>
            <input type="color" name="title_color" class="form-control" value="<?= htmlspecialchars($title_color) ?>"><br>

            <label>Post Background Color</label>
            <input type="color" name="background_color" class="form-control" value="<?= htmlspecialchars($bg_color) ?>"><br>

            <label>Font Style</label>
            <select name="font_style" class="form-select">
                <option value="">Select Font</option>
                <option value="Arial" <?= ($font == 'Arial') ? 'selected' : '' ?>>Arial</option>
                <option value="Georgia" <?= ($font == 'Georgia') ? 'selected' : '' ?>>Georgia</option>
                <option value="Verdana" <?= ($font == 'Verdana') ? 'selected' : '' ?>>Verdana</option>
            </select><br>

            <button type="submit" name="save_settings" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
