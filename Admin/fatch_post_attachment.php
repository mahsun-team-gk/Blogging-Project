<!-- START: Display Post Attachments -->        
        <?php
        require_once("require/database_connection.php");
        require_once("admin_file.php");
        ?>
        <div class="container mt-5">
            <h4 class="mb-4">Post Attachments</h4>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Post ID</th>
                        <th>Title</th>
                        <th>Preview</th>
                        <th>File Path</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // START: Fetch post attachments
                    $query = "SELECT post_atachment_id, post_id, post_attachment_title, post_attachment_path, is_active, created_at, updated_at FROM post_atachment";
                    $result = $connection->query($query);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $status = $row['is_active'] === 'Active' ? 'Active' : 'Inactive';
                            $buttonClass = $status === 'Active' ? 'btn-success' : 'btn-danger';
                            $toggleStatus = $status === 'Active' ? 'Inactive' : 'Active';
                            $file_path = $row['post_attachment_path'];
                            $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                            $preview_html = '';
                            if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                $preview_html = '<img src="'.$file_path.'" style="max-width: 100px; max-height: 60px;" class="img-thumbnail">';
                            } 
                            elseif ($file_extension === 'pdf') {
                                $preview_html = '<i class="fas fa-file-pdf fa-2x text-danger"></i>';
                            } 
                            elseif (in_array($file_extension, ['doc', 'docx'])) {
                                $preview_html = '<i class="fas fa-file-word fa-2x text-primary"></i>';
                            } 
                            elseif (in_array($file_extension, ['xls', 'xlsx'])) {
                                $preview_html = '<i class="fas fa-file-excel fa-2x text-success"></i>';
                            } 
                            else {
                                $preview_html = '<i class="fas fa-file fa-2x text-secondary"></i>';
                            }
                            echo "<tr>";
                            echo "<td>{$row['post_atachment_id']}</td>";
                            echo "<td>{$row['post_id']}</td>";
                            echo "<td>" . htmlspecialchars($row['post_attachment_title']) . "</td>";
                            echo "<td>{$preview_html}</td>"; // New preview column
                            echo "<td><small>" . htmlspecialchars($row['post_attachment_path']) . "</small></td>";
                            echo "<td><span class='badge bg-{$buttonClass}'>{$status}</span></td>";
                            echo "<td>{$row['created_at']}</td>";
                            echo "<td>{$row['updated_at']}</td>";
                            echo "<td>
                                    <form method='post' style='display:inline;'>
                                        <input type='hidden' name='attachment_id' value='{$row['post_atachment_id']}'>
                                        <input type='hidden' name='new_status' value='{$toggleStatus}'>
                                        <button type='submit' name='toggle_status' class='btn {$buttonClass} btn-sm'>
                                            Set {$toggleStatus}
                                        </button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No attachments found.</td></tr>";
                    }

                    ?>
                </tbody>
            </table>
        </div>
<!-- // END: Fetch post attachments -->

        
<!-- // START: Handle Status  -->
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'], $_POST['attachment_id'], $_POST['new_status'])) {
            $attachment_id = intval($_POST['attachment_id']);
            $new_status = $_POST['new_status'];

            $update_sql = "UPDATE post_atachment SET is_active = ?, updated_at = NOW() WHERE post_atachment_id = ?";
            $stmt = $connection->prepare($update_sql);
            if ($stmt) {
                $stmt->bind_param("si", $new_status, $attachment_id);
                $stmt->execute();
                $stmt->close();
                // Redirect to avoid form resubmission
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error updating status: " . $connection->error . "</div>";
            }
        }
        ?>
<!-- // END: Handle Status Toggle -->