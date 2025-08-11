<?php
// ================= Database Connection =================
require_once("require/database_connection.php");

// ================= Activate Post =================
if (isset($_POST['post_id'])) {
    $post_id = (int)$_POST['post_id'];
    if ($post_id > 0) {
        mysqli_query($connection, "UPDATE post SET post_status = 'active' WHERE post_id = $post_id");
    }
    header("Location: active_post.php");
    exit();
}

// ================= Include Admin Header =================
require_once("admin_file.php");

// ================= Get Inactive Posts =================
$posts = mysqli_query($connection, "SELECT post_id, post_title, post_summary, featured_image, created_at FROM post WHERE post_status = 'inactive' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inactive Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4 text-center">Inactive Blog Posts</h2>

    <div class="row">
        <?php if (mysqli_num_rows($posts) > 0): ?>
            <?php while ($post = mysqli_fetch_assoc($posts)): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <?php if (!empty($post['featured_image'])): ?>
                            <img src="<?= htmlspecialchars($post['featured_image']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($post['post_title']) ?></h5>
                            <p class="text-muted"><small><?= date("F j, Y", strtotime($post['created_at'])) ?></small></p>
                            <p><?= htmlspecialchars($post['post_summary']) ?></p>
                            <form method="POST" onsubmit="return confirm('Activate this post?');">
                                <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                                <button type="submit" class="btn btn-success btn-sm">Activate Post</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">No inactive posts found.</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
