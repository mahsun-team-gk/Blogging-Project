<!-- // Fetch following data -->
    <?php
    require_once("require/database_connection.php");
    require_once("admin_file.php");
    $sql = "SELECT `follow_id`, `follower_id`, `blog_following_id`, `status`, `created_at`, `updated_at` FROM `following_blog`";
    $result = mysqli_query($connection, $sql);
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Following Blogs</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .table-wrapper {
                margin-top: 50px;
                background: #fff;
                padding: 30px;
                box-shadow: 0 0 15px rgba(0,0,0,0.1);
                border-radius: 10px;
            }
            th {
                background-color: #007BFF;
                color: white;
            }
            .status-badge {
                padding: 5px 10px;
                border-radius: 20px;
                font-size: 0.8rem;
            }
        </style>
    </head>
    <body>

    <div class="container table-wrapper">
        <h2 class="text-center mb-4">Blog Following List</h2>
        <table class="table table-bordered table-hover text-center align-middle">
            <thead>
                <tr>
                    <th>Follow ID</th>
                    <th>Follower ID</th>
                    <th>Following Blog ID</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['follow_id'] ?></td>
                            <td><?= $row['follower_id'] ?></td>
                            <td><?= $row['blog_following_id'] ?></td>
                            <td>
                                <span class="badge bg-<?= $row['status'] === 'active' ? 'success' : 'secondary' ?> status-badge">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td><?= date("M d, Y", strtotime($row['created_at'])) ?></td>
                            <td><?= date("M d, Y", strtotime($row['updated_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-muted">No following records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </body>
    </html>
<!-- // Fetch following data -->

