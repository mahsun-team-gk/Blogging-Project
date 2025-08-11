<!-- insert post cateogy -->
    <?php
    require_once("admin_file.php");
    require_once("require/database_connection.php");
    class category {
        public static function category() {
            global $connection; 
            $sql = "SELECT `post_category_id`, `post_id`, `category_id`, `created_at`, `updated_at` FROM `post_category`";
            $result = mysqli_query($connection, $sql); // Correct usage

            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Post Categories</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background: #f4f4f4;
                        padding: 30px;
                    }
                    table {
                        border-collapse: collapse;
                        width: 100%;
                        background: #fff;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    }
                    th, td {
                        border: 1px solid #ccc;
                        padding: 12px 15px;
                        text-align: center;
                    }
                    th {
                        background-color: #007BFF;
                        color: white;
                    }
                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                    h2 {
                        color: #333;
                    }
                </style>
            </head>
            <body>

            <h2>Post Category Table</h2>

            <table>
                <thead>
                    <tr>
                        <th>Post Category ID</th>
                        <th>Post ID</th>
                        <th>Category ID</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['post_category_id']) ?></td>
                                <td><?= htmlspecialchars($row['post_id']) ?></td>
                                <td><?= htmlspecialchars($row['category_id']) ?></td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                                <td><?= htmlspecialchars($row['updated_at']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            </body>
            </html>
            <?php
        }
    }
    category::category();
    ?>
<!-- // Fetch posts —category -->
