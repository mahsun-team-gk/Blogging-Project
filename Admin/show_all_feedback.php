<!-- show all feedback -->
    <?php
    require_once("require/database_connection.php");
    require_once("admin_file.php");

    // Delete feedback if requested and redirect back
    if (isset($_GET['delete_feedback_id'])) {
        $feedback_id = (int)$_GET['delete_feedback_id'];
        $delete_sql = "DELETE FROM user_feedback WHERE feedback_id = $feedback_id";
        if (mysqli_query($connection, $delete_sql)) {
            // header("Location: show_all_feedback.php?msg=deleted");
            // exit;
        } else {
            echo "Error deleting feedback: " . mysqli_error($connection);
        }
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Manage Feedback</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="container-fluid mt-5">

        <h2 class="mb-4">User Feedback</h2>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-success">Feedback deleted successfully.</div>
        <?php endif; ?>

        <form method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, or feedback" 
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            
        </form>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Feedback ID</th>
                    <th>User ID</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Feedback</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $limit = 10;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                $search = $_GET['search'] ?? '';
                $search_sql = '';
                if (!empty($search)) {
                    $search_safe = mysqli_real_escape_string($connection, $search);
                    $search_sql = "WHERE user_name LIKE '%$search_safe%' OR user_email LIKE '%$search_safe%' OR feedback LIKE '%$search_safe%'";
                }

                $query = "SELECT * FROM user_feedback $search_sql ORDER BY feedback_id DESC LIMIT $limit OFFSET $offset";
                $result = mysqli_query($connection, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['feedback_id'] ?></td>
                            <td><?= $row['user_id'] ?></td>
                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                            <td><?= htmlspecialchars($row['user_email']) ?></td>
                            <td><?= htmlspecialchars($row['feedback']) ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <a href="show_all_feedback.php?delete_feedback_id=<?= $row['feedback_id'] ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Are you sure you want to delete this feedback?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile;
                } else { ?>
                    <tr><td colspan="7" class="text-center">No feedback found.</td></tr>
                <?php } ?>
            </tbody>
        </table>

        <?php
        // Pagination
        $total_query = "SELECT COUNT(*) AS total FROM user_feedback $search_sql";
        $total_result = mysqli_query($connection, $total_query);
        $total_row = mysqli_fetch_assoc($total_result);
        $total_records = $total_row['total'];
        $total_pages = ceil($total_records / $limit);
        ?>

        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>

    </body>
    </html>
<!-- show all feedback -->
