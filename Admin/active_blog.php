<!-- active blog start -->
    <?php
    require_once("require/database_connection.php");
    require_once("admin_file.php");

    // Handle Status Toggle
    if (isset($_POST['toggle_status'])) {
        $blog_id = (int)$_POST['blog_id'];
        $current_status = $_POST['current_status'];

        // Toggle status simply
        if ($current_status === 'active') {
            $new_status = 'inactive';
        } else {
            $new_status = 'active';
        }

        $update_query = "UPDATE blog SET blog_status = '$new_status' WHERE blog_id = $blog_id";
        if (mysqli_query($connection, $update_query)) {
            echo "<div style='color: green; padding:10px;'>Blog ID $blog_id status changed to $new_status successfully.</div>";
        } else {
            echo "<div style='color: red; padding:10px;'>Failed to update blog status.</div>";
        }
    }

    // Fetch only active blogs
    $query = "SELECT blog_id, blog_title, blog_status FROM blog WHERE blog_status = 'active' ORDER BY blog_id DESC";
    $result = mysqli_query($connection, $query);

    echo "<h2>Active Blogs</h2>";
    echo "<table class='table table-bordered'>";
    echo "<thead><tr><th>ID</th><th>Title</th><th>Status</th><th>Action</th></tr></thead><tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        $blog_id = $row['blog_id'];
        $title = ($row['blog_title']);
        $status = $row['blog_status'];

        // Button will deactivate active blogs (only active shown here)
        echo "<tr>";
        echo "<td>$blog_id</td>";
        echo "<td>$title</td>";
        echo "<td><span class='badge bg-success'>active</span></td>";
        echo "<td>
                <form method='post' style='display:inline-block;'>
                    <input type='hidden' name='blog_id' value='$blog_id'>
                    <input type='hidden' name='current_status' value='$status'>
                    <button type='submit' name='toggle_status' class='btn btn-danger'>Deactivate</button>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "</tbody></table>";

    // Show inactive blogs below with Activate button
    $query_inactive = "SELECT blog_id, blog_title, blog_status FROM blog WHERE blog_status = 'inactive' ORDER BY blog_id DESC";
    $result_inactive = mysqli_query($connection, $query_inactive);

    if (mysqli_num_rows($result_inactive) > 0) {
        echo "<h2>Inactive Blogs</h2>";
        echo "<table class='table table-bordered'>";
        echo "<thead><tr><th>ID</th><th>Title</th><th>Status</th><th>Action</th></tr></thead><tbody>";

        while ($row = mysqli_fetch_assoc($result_inactive)) {
            $blog_id = $row['blog_id'];
            $title = htmlspecialchars($row['blog_title']);
            $status = $row['blog_status'];

            echo "<tr>";
            echo "<td>$blog_id</td>";
            echo "<td>$title</td>";
            echo "<td><span class='badge bg-secondary'>inactive</span></td>";
            echo "<td>
                    <form method='post' style='display:inline-block;'>
                        <input type='hidden' name='blog_id' value='$blog_id'>
                        <input type='hidden' name='current_status' value='$status'>
                        <button type='submit' name='toggle_status' class='btn btn-success'>Activate</button>
                    </form>
                  </td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    }
    ?>
<!-- active blog start -->
