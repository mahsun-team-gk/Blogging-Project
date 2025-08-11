<?php
require_once("require/database_connection.php");
require_once("admin_file.php");

// session_start(); // session start zaroori hai

$message = "";

// Get user_id from session (adjust key as per your session variable)
$user_id = $_SESSION['users']['user_id'];
// print_r($_SESSION['users']);
// die();

if ($user_id === 0) {
    die("User not logged in.");
}

if (isset($_POST['submit'])) {
    $blog_title = trim($_POST['blog_title']);
    $post_per_page = (int)$_POST['post_per_page'];
    $blog_status = $_POST['blog_status'];
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    $image_name = '';
    if (!empty($_FILES['blog_background_image']['name'])) {
        $image_name = time() . '_' . basename($_FILES['blog_background_image']['name']);
        $upload_path = "uploads/" . $image_name;
        move_uploaded_file($_FILES['blog_background_image']['tmp_name'], $upload_path);

    
        // print_r($_FILES);
        // die();
    }

    $query = "INSERT INTO blog (user_id, blog_title, post_per_page, blog_background_image, blog_status, created_at, updated_at)
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $connection->prepare($query);


    $stmt->bind_param("isissss", $user_id, $blog_title, $post_per_page, $image_name, $blog_status, $created_at, $updated_at);


    if ($stmt->execute()) {
        $blog_id = $stmt->insert_id;
        $message = "<div style='color: green;'> Blog created successfully! <a href='insert_post.php?blog_id=$blog_id'>Click here to add a Post</a></div>";
    } else {
        $message = "<div style='color: red;'> Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Blog</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        form { width: 400px; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        label { font-weight: bold; display: block; margin-top: 10px; }
        input[type="text"], input[type="number"], select, input[type="file"] {
            width: 100%; padding: 8px; margin-top: 5px;
        }
        button { margin-top: 15px; padding: 10px 20px; }
        .message { margin-top: 20px; }
    </style>
</head>
<body>

<h2>Create a New Blog</h2>

<?php if ($message): ?>
    <div class="message"><?= $message ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>Blog Title:</label>
    <input type="text" name="blog_title" required>

    <label>Posts Per Page:</label>
    <input type="number" name="post_per_page" required min="1">

    <label>Background Image:</label>
    <input type="file" name="blog_background_image">

    <label>Status:</label>
    <select name="blog_status" required>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select>

    <button type="submit" name="submit">Create Blog</button>
</form>

</body>
</html>
