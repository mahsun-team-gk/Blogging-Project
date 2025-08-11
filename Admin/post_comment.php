<!-- post comments         -->
        
        <?php
        require_once("require/database_connection.php");
        require_once("admin_file.php");
        $user_id = 1; 
        $post_id = 1;         
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['comment'])) {
            $comment = mysqli_real_escape_string($connection, $_POST['comment']);
            $sql_insert = "INSERT INTO post_comment (post_id, user_id, comment) VALUES ($post_id, $user_id, '$comment')";
            mysqli_query($connection, $sql_insert);
        }
        $sql = "SELECT `post_comment_id`, `post_id`, `user_id`, `comment`, `is_active`, `created_at` FROM `post_comment` WHERE `post_id` = $post_id AND `is_active` = 1 ORDER BY `created_at` DESC";
        $result = mysqli_query($connection, $sql);
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Post Comments</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                .comment-box {
                    margin-top: 30px;
                }
                .comment-card {
                    box-shadow: 0 0 5px rgba(0,0,0,0.1);
                    margin-bottom: 15px;
                }
            </style>
        </head>
        <body>
        <div class="container mt-5">
            <h3 class="mb-4">Comments</h3>
            <div class="card p-3 mb-4">
                <form method="POST">
                    <div class="mb-3">
                        <textarea name="comment" class="form-control" rows="3" placeholder="Write your comment here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Post Comment</button>
                </form>
            </div>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="card comment-card p-3">
                        <p class="mb-1"><strong>User ID:</strong> <?= $row['user_id'] ?></p>
                        <p class="mb-2"><?= nl2br(htmlspecialchars($row['comment'])) ?></p>
                        <small class="text-muted">Posted on <?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-info">No comments yet. Be the first to comment!</div>
            <?php endif; ?>
        </div>
        </body>
        </html>
<!-- post comments         -->
