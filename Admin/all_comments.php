<!-- all comments      -->
    <?php
    require_once("require/database_connection.php");
    require_once("admin_file.php");

    if (isset($_POST['comment_id']) && isset($_POST['action'])) {
        $comment_id = (int)$_POST['comment_id'];
        $action = $_POST['action'];

        if ($action === 'activate') {
            $new_status = 1;
        } elseif ($action === 'deactivate') {
            $new_status = 0;
        } else {
            $new_status = null;
        }

        if ($new_status !== null) {
            $update_sql = "UPDATE post_comment SET is_active = $new_status WHERE post_comment_id = $comment_id";
            mysqli_query($connection, $update_sql);
        }
     
    }

    $sql = "SELECT pc.post_comment_id, pc.post_id, u.first_name, pc.comment, pc.is_active, pc.created_at
            FROM post_comment pc
            JOIN user u ON pc.user_id = u.user_id
            ORDER BY pc.created_at DESC";

    $result = mysqli_query($connection, $sql);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Manage Comments</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    </head>
    <body>
    <div class="container mt-5">
        <h2 class="mb-4">Manage Post Comments</h2>

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Post ID</th>
                        <th>User</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Action</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['post_comment_id'] ?></td>
                            <td><?= $row['post_id'] ?></td>
                            <td><?= htmlspecialchars($row['first_name']) ?></td>
                            <td><?= htmlspecialchars($row['comment']) ?></td>
                            <td>
                                <span class="badge <?= $row['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $row['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!$row['is_active']): ?>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="comment_id" value="<?= $row['post_comment_id'] ?>" />
                                        <button type="submit" name="action" value="activate" class="btn btn-sm btn-success">Activate</button>
                                    </form>
                                <?php else: ?>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="comment_id" value="<?= $row['post_comment_id'] ?>" />
                                        <button type="submit" name="action" value="deactivate" class="btn btn-sm btn-danger">Deactivate</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">No comments found.</p>
        <?php endif; ?>
    </div>
    </body>
    </html>
<!-- all comments      -->
    
