<?php
// ======= Database Connection + Header =======
require_once("require/database_connection.php");
require_once("admin_file.php");

// ======= Activate Logic =======
if (isset($_GET['activate_id'])) {
    $category_id = (int) $_GET['activate_id'];
    $query = "UPDATE category SET category_status = 'active', updated_at = NOW() WHERE category_id = $category_id";
    mysqli_query($connection, $query);
    // header("Location: inactive_category.php");
    // exit();
}

// ======= Fetch Inactive Categories =======
$result = mysqli_query($connection, "SELECT category_id, category_title FROM category WHERE category_status = 'inactive' ORDER BY category_title ASC");
?>

<!-- ======= HTML Layout ======= -->
<!DOCTYPE html>
<html>
<head>
    <title>Inactive Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Inactive Categories</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Category Title</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['category_title']) ?></td>
                <td><span class="badge bg-secondary">Inactive</span></td>
                <td>
                    <a href="?activate_id=<?= $row['category_id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to activate this category?');">Activate</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
