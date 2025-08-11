<?php
require_once("require/database_connection.php");
require_once("admin_file.php");

$successMsg = "";
$errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_category'])) {
    // Variables declare karo, trim karo
    $title = trim($_POST['category_title']);
    $description = trim($_POST['category_description']);
    $status = trim($_POST['category_status']);

    if ($title !== "" && $description !== "" && $status !== "") {
        // Simple insert query without prepare
        $query = "INSERT INTO category (category_title, category_description, category_status, created_at, updated_at) 
                  VALUES ('$title', '$description', '$status', NOW(), NOW())";

        if ($connection->query($query) === TRUE) {
            $successMsg = "Category added successfully.";
        } else {
            $errorMsg = "Insert failed: " . $connection->error;
        }
    } else {
        $errorMsg = "All fields are required.";
    }
}

// Fetch categories
$fetchQuery = "SELECT category_id, category_title, category_description, category_status, created_at FROM category ORDER BY category_id DESC";
$result = $connection->query($fetchQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Add New Category</h2>

    <?php if ($successMsg): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMsg); ?></div>
    <?php elseif ($errorMsg): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="hidden" name="add_category" value="1">

        <div class="mb-3">
            <label for="category_title" class="form-label">Category Title</label>
            <input type="text" class="form-control" id="category_title" name="category_title" required>
        </div>

        <div class="mb-3">
            <label for="category_description" class="form-label">Description</label>
            <textarea class="form-control" id="category_description" name="category_description" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="category_status" class="form-label">Status</label>
            <select class="form-select" name="category_status" id="category_status" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add Category</button>
    </form>

    <hr class="my-5">

    <h3 class="mb-3 text-center">All Categories</h3>
    <?php if ($result && $result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['category_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['category_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['category_description']); ?></td>
                            <td>
                                <form method="post" action="status_change.php" style="display:inline;">
                                    <input type="hidden" name="category_id" value="<?php echo $row['category_id']; ?>">
                                    <input type="hidden" name="current_status" value="<?php echo $row['category_status']; ?>">
                                    <button type="submit" class="btn btn-sm btn-<?php echo $row['category_status'] === 'Active' ? 'success' : 'secondary'; ?>">
                                        <?php echo $row['category_status']; ?>
                                    </button>
                                </form>
                            </td>
                            <td><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">No categories found.</div>
    <?php endif; ?>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$connection->close();
?>
