<?php
// session_start();
require_once("require/database_connection.php");
require_once("admin_file.php");

// ===== Insert Comment =====
if (isset($_POST['comment']) && isset($_GET['post_id']) && isset($_SESSION['users']['user_id'])) {
    $post_id = $_GET['post_id'];
    $user_id = $_SESSION['users']['user_id'];
    $comment = trim($_POST['comment']);

    if ($comment != '') {
        $insert_sql = "INSERT INTO post_comment (post_id, user_id, comment, is_active, created_at) 
                       VALUES ('$post_id', '$user_id', '$comment', 0, NOW())";
        $insert_result = mysqli_query($connection, $insert_sql);

        if ($insert_result) {
            $success_msg = "Comment submitted successfully! Awaiting approval.";
        } else {
            $error_msg = "Error inserting comment: " . mysqli_error($connection);
        }
    } else {
        $error_msg = "Comment cannot be empty.";
    }
}

// ===== Fetch Approved Comments =====
$comments = [];
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $fetch_sql = "SELECT pc.comment, pc.created_at, u.first_name 
                  FROM post_comment pc 
                  JOIN users u ON pc.user_id = u.user_id 
                  WHERE pc.post_id = '$post_id' AND pc.is_active = 1 
                  ORDER BY pc.created_at DESC";
    $result = mysqli_query($connection, $fetch_sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $comments[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Comments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="comment-box bg-white p-4 rounded shadow-sm">
        <h3 class="mb-4">Leave a Comment</h3>

        <?php if (isset($_SESSION['users']['user_id'])): ?>
            <?php if (isset($success_msg)) echo '<div class="alert alert-success">'.$success_msg.'</div>'; ?>
            <?php if (isset($error_msg)) echo '<div class="alert alert-danger">'.$error_msg.'</div>'; ?>

            <form method="POST">
                <div class="mb-3">
                    <textarea name="comment" class="form-control" rows="3" placeholder="Write your comment here..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">Please <a href="login.php">log in</a> to comment.</div>
        <?php endif; ?>

        <hr class="my-4">
        <h4>All Comments</h4>

        <?php if (count($comments) > 0): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-card border p-3 mb-3 rounded">
                    <div class="comment-header mb-2">
                        <strong><?= $comment['first_name'] ?></strong>
                        <small class="text-muted float-end"><?= date("d M Y, h:i A", strtotime($comment['created_at'])) ?></small>
                    </div>
                    <div class="comment-body"><?= nl2br(htmlspecialchars($comment['comment'])) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">No comments yet.</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
