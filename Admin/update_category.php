<?php
// ======= Database Connection & Header =======
require_once("require/database_connection.php");
require_once("admin_file.php");

// ======= Initialize Variables =======
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$category_title = "";
$category_description = "";

// ======= Fetch Selected Category Data =======
if ($category_id > 0) {
    $query = "SELECT category_title, category_description FROM category WHERE category_id = $category_id";
    $result = mysqli_query($connection, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $category_title = $row['category_title'];
        $category_description = $row['category_description'];
    }
}

// ======= Handle Update Submission =======
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_category'])) {
    $update_id = (int)$_POST['category_id'];
    $new_title = trim($_POST['category_title']);
    $new_description = trim($_POST['category_description']);

    if (!empty($new_title)) {
        $update_query = "UPDATE category SET category_title = '" . mysqli_real_escape_string($connection, $new_title) . "', category_description = '" . mysqli_real_escape_string($connection, $new_description) . "', updated_at = NOW() WHERE category_id = $update_id";
        if (mysqli_query($connection, $update_query)) {
            echo "<div class='alert alert-success text-center'>Category updated successfully.</div>";
            $category_title = $new_title;
            $category_description = $new_description;
            $category_id = $update_id;
        } else {
            echo "<div class='alert alert-danger text-center'>Error updating category.</div>";
        }
    }
}

// ======= Get All Categories for Dropdown =======
$categories = mysqli_query($connection, "SELECT category_id, category_title FROM category ORDER BY category_title ASC");
?>

<!-- ======= HTML Layout ======= -->
<!DOCTYPE html>
<html>
<head>
    <title>Update Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Update Category</h2>

    <!-- Dropdown Form -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <select name="category_id" class="form-select" onchange="this.form.submit()">
                <option value="">Select a Category</option>
                <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                    <option value="<?= $cat['category_id'] ?>" <?= $cat['category_id'] == $category_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category_title']) ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit" class="btn btn-secondary">Load</button>
        </div>
    </form>

    <?php if ($category_id > 0): ?>
    <!-- Update Category Form -->
    <form method="POST">
        <input type="hidden" name="category_id" value="<?= $category_id ?>">

        <div class="mb-3">
            <label class="form-label">Category Title</label>
            <input type="text" name="category_title" class="form-control" value="<?= htmlspecialchars($category_title) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Category Description</label>
            <textarea name="category_description" class="form-control" rows="3" required><?= htmlspecialchars($category_description) ?></textarea>
        </div>

        <button type="submit" name="update_category" class="btn btn-primary">Update Category</button>
    </form>
    <?php endif; ?>
</div>

</body>
</html>
