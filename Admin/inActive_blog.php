<?php
require_once("require/database_connection.php");
require_once("admin_file.php");

// Handle activation form submit
if (isset($_POST['activate_blog'])) {
    $blog_id = (int)$_POST['blog_id'];
    
    // Simple query to update blog_status to 'active'
    $update_query = "UPDATE blog SET blog_status = 'active' WHERE blog_id = $blog_id";
    
    if (mysqli_query($connection, $update_query)) {
        echo "<div style='color: green; padding:10px;'>Blog ID $blog_id activated successfully.</div>";
    } else {
        echo "<div style='color: red; padding:10px;'>Failed to activate Blog ID $blog_id.</div>";
    }
}

// Fetch inactive blogs from DB
$sql = "SELECT blog_id, blog_title, blog_status FROM blog WHERE blog_status = 'inactive'";
$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inactive Blogs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

    <h2 class="mb-4">Inactive Blogs</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['blog_id'] ?></td>
                        <td><?= htmlspecialchars($row['blog_title']) ?></td>
                        <td><span class="badge bg-secondary"><?= ucfirst($row['blog_status']) ?></span></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="blog_id" value="<?= $row['blog_id'] ?>">
                                <button type="submit" name="activate_blog" class="btn btn-success btn-sm">Activate</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No inactive blogs found.</div>
    <?php endif; ?>

</body>
</html>
