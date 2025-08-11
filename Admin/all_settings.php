<!-- All setting of blogs-->
        <?php
        require_once("require/database_connection.php");
        require_once("admin_file.php");
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setting_id'])) {
            $setting_id = $_POST['setting_id'];
            $setting_value = $_POST['setting_value'];

            $stmt = $connection->prepare("UPDATE setting SET setting_value = ?, updated_at = NOW() WHERE setting_id = ?");
            $stmt->bind_param("si", $setting_value, $setting_id);
            $stmt->execute();
            $stmt->close();
        }
        $sql = "SELECT `setting_id`, `user_id`, `setting_key`, `setting_value`, `setting_status`, `created_at`, `updated_at` FROM `setting`";
        $result = mysqli_query($connection, $sql);
        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <title>User Settings</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                .settings-box {
                    margin: 40px auto;
                    background: #fff;
                    padding: 30px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    border-radius: 10px;
                }
                th {
                    background-color: #0d6efd;
                    color: white;
                }
            </style>
        </head>
        <body>

        <div class="container settings-box">
            <h2 class="text-center mb-4">User Settings</h2>
            <table class="table table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th>Setting ID</th>
                        <th>User ID</th>
                        <th>Setting Key</th>
                        <th>Value</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <form method="post">
                                    <td><?= $row['setting_id'] ?></td>
                                    <td><?= $row['user_id'] ?></td>
                                    <td><?= htmlspecialchars($row['setting_key']) ?></td>
                                    <td>
                                        <input type="text" name="setting_value" value="<?= htmlspecialchars($row['setting_value']) ?>" class="form-control">
                                        <input type="hidden" name="setting_id" value="<?= $row['setting_id'] ?>">
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $row['setting_status'] === 'active' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($row['setting_status']) ?>
                                        </span>
                                    </td>
                                    <td><?= date("M d, Y", strtotime($row['created_at'])) ?></td>
                                    <td><?= date("M d, Y", strtotime($row['updated_at'])) ?></td>
                                    <td>
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </td>
                                </form>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-muted">No settings found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        </body>
        </html>
<!-- All setting of blogs-->
        
