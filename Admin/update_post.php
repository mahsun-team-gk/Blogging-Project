<!-- // --- Start: Manage Posts and Edit Post in one file (Simple mysqli_query version) --- -->
    <?php
    require_once("require/database_connection.php");
    require_once("admin_file.php");

    $post_id = 0;
    $post_title = $post_summary = $post_description = $featured_image = $post_status = "";
    $is_comment_allowed = 0;
    $message = "";

    // Check if editing a post
    if (isset($_GET['post_id']) && is_numeric($_GET['post_id'])) {
        $post_id = (int)$_GET['post_id'];
        $query = "SELECT post_title, post_summary, post_description, featured_image, post_status, is_comment_allowed FROM post WHERE post_id = $post_id";
        $result = mysqli_query($connection, $query);
        if ($row = mysqli_fetch_assoc($result)) {
            $post_title = $row['post_title'];
            $post_summary = $row['post_summary'];
            $post_description = $row['post_description'];
            $featured_image = $row['featured_image'];
            $post_status = $row['post_status'];
            $is_comment_allowed = $row['is_comment_allowed'];
        } else {
            $message = "Post not found.";
            $post_id = 0;
        }
    }

    // Handle form submit (Update Post)
    if (isset($_POST['post_id']) && is_numeric($_POST['post_id'])) {
        $post_id = (int) $_POST['post_id'];
        $post_title = trim($_POST['post_title']);
        $post_summary = trim($_POST['post_summary']);
        $post_description = trim($_POST['post_description']);
        $post_status = $_POST['post_status'];
        $is_comment_allowed = isset($_POST['is_comment_allowed']) ? 1 : 0;

        if (isset($_FILES['featured_image_file']) && $_FILES['featured_image_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = "uploads/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $tmp_name = $_FILES['featured_image_file']['tmp_name'];
            $filename = basename($_FILES['featured_image_file']['name']);
            $target_file = $upload_dir . time() . "_" . $filename;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $featured_image = $target_file;
            } else {
                $message = "<div class='alert alert-danger'>Failed to upload image.</div>";
            }
        } else {
            if (isset($_POST['existing_featured_image'])) {
                $featured_image = $_POST['existing_featured_image'];
            }
        }

        $update_query = "UPDATE post SET 
                            post_title = '$post_title', 
                            post_summary = '$post_summary', 
                            post_description = '$post_description', 
                            featured_image = '$featured_image', 
                            post_status = '$post_status', 
                            is_comment_allowed = $is_comment_allowed, 
                            updated_at = NOW() 
                        WHERE post_id = $post_id";

        if (mysqli_query($connection, $update_query)) {
            $message = "<div class='alert alert-success'>Post updated successfully.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error updating post: " . mysqli_error($connection) . "</div>";
        }
    }

    if ($post_id === 0) {
        $posts_result = mysqli_query($connection, "SELECT post_id, post_title FROM post ORDER BY post_id DESC");
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Manage Posts</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container mt-5">

        <h2>Manage Posts</h2>

        <?= $message ?>

        <?php if ($post_id === 0): ?>
            <!-- Show posts list -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Post ID</th>
                        <th>Title</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($posts_result && mysqli_num_rows($posts_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($posts_result)): ?>
                        <tr>
                            <td><?= $row['post_id'] ?></td>
                            <td><?= htmlspecialchars($row['post_title']) ?></td>
                            <td>
                                <a href="?post_id=<?= $row['post_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3">No posts found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

        <?php else: ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="post_id" value="<?= $post_id ?>">

                <div class="mb-3">
                    <label for="post_title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="post_title" name="post_title" value="<?= htmlspecialchars($post_title) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="post_summary" class="form-label">Summary</label>
                    <textarea class="form-control" id="post_summary" name="post_summary" rows="2" required><?= htmlspecialchars($post_summary) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="post_description" class="form-label">Description</label>
                    <textarea class="form-control" id="post_description" name="post_description" rows="5" required><?= htmlspecialchars($post_description) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Featured Image</label><br>
                    <?php if ($featured_image && file_exists($featured_image)): ?>
                        <img src="<?= htmlspecialchars($featured_image) ?>" alt="Featured Image" style="max-width: 200px; height: auto;">
                    <?php else: ?>
                        <p>No image uploaded</p>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="featured_image_file" class="form-label">Upload New Featured Image</label>
                    <input type="file" class="form-control" id="featured_image_file" name="featured_image_file" accept="image/*">
                    <input type="hidden" name="existing_featured_image" value="<?= htmlspecialchars($featured_image) ?>">
                </div>

                <div class="mb-3">
                    <label for="post_status" class="form-label">Status</label>
                    <select class="form-select" id="post_status" name="post_status">
                        <option value="draft" <?= $post_status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $post_status === 'published' ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="is_comment_allowed" name="is_comment_allowed" <?= $is_comment_allowed ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_comment_allowed">Allow Comments</label>
                </div>

                <button type="submit" class="btn btn-primary">Update Post</button>
                <a href="?" class="btn btn-secondary ms-2">Back to List</a>
            </form>
        <?php endif; ?>

    </div>
    </body>
    </html>
<!-- // --- End: Manage Posts and Edit Post in one file (Simple mysqli_query version) --- -->
