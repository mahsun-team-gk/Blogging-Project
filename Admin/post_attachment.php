<!-- post attachment -->
        <?php
        require_once("require/database_connection.php");
        require_once("admin_file.php");
        $message = '';
        $message_type = ''; // 'success' or 'error'

        if (isset($_POST['upload'])) {
            $post_id = intval($_POST['post_id']);
            $attachment_title = trim($_POST['attachment_title']);
            $is_active = $_POST['is_active'] === '1' ? 'Active' : 'Inactive';
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');
            $attachment_path = '';
            if (!empty($_FILES['attachment_file']['name'])) {
                $upload_dir = "uploads/";
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $file_name = time() . '_' . basename($_FILES['attachment_file']['name']);
                $target_path = $upload_dir . $file_name;
                $file_type = strtolower(pathinfo($target_path, PATHINFO_EXTENSION));
                if ($_FILES['attachment_file']['size'] > 0) {
                    if ($_FILES['attachment_file']['size'] > 5000000) {
                        $message = "Sorry, your file is too large (max 5MB allowed).";
                        $message_type = 'error';
                    }
                    elseif (!in_array($file_type, ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                        $message = "Sorry, only JPG, JPEG, PNG, GIF, PDF, DOC, DOCX, XLS & XLSX files are allowed.";
                        $message_type = 'error';
                    }
                    elseif (move_uploaded_file($_FILES['attachment_file']['tmp_name'], $target_path)) {
                        $attachment_path = $target_path;
                    } else {
                        $message = "Sorry, there was an error uploading your file.";
                        $message_type = 'error';
                    }
                } else {
                    $message = "The uploaded file is empty.";
                    $message_type = 'error';
                }
            } else {
                $message = "Please select a file to upload.";
                $message_type = 'error';
            }
            if (!empty($attachment_path)) {
                $sql = "INSERT INTO post_atachment (post_id, post_attachment_title, post_attachment_path, is_active, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $connection->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("isssss", $post_id, $attachment_title, $attachment_path, $is_active, $created_at, $updated_at);
                    
                    if ($stmt->execute()) {
                        $message = "Attachment uploaded successfully!";
                        $message_type = 'success';
                    } else {
                        $message = "Database error: " . $stmt->error;
                        $message_type = 'error';
                    }
                    $stmt->close();
                } else {
                    $message = "Database preparation error: " . $connection->error;
                    $message_type = 'error';
                }
            }
        }
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Upload Attachment</title>
            <style>
                .form-container {
                    max-width: 500px;
                    margin: 20px auto;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                .form-group {
                    margin-bottom: 15px;
                }
                label {
                    display: block;
                    margin-bottom: 5px;
                    font-weight: bold;
                }
                input[type="text"], 
                input[type="number"], 
                input[type="file"],
                select {
                    width: 100%;
                    padding: 8px;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                }
                button {
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 15px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }
                button:hover {
                    background-color: #45a049;
                }
                .message {
                    padding: 10px;
                    margin: 10px 0;
                    border-radius: 4px;
                }
                .success {
                    background-color: #dff0d8;
                    color: #3c763d;
                    border: 1px solid #d6e9c6;
                }
                .error {
                    background-color: #f2dede;
                    color: #a94442;
                    border: 1px solid #ebccd1;
                }
            </style>
        </head>
        <body>
            <div class="form-container">
                <h2>Upload Attachment</h2>
                
                <?php if (!empty($message)): ?>
                    <div class="message <?php echo $message_type; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Post ID:</label>
                        <input type="number" name="post_id" required>
                    </div>

                    <div class="form-group">
                        <label>Attachment Title:</label>
                        <input type="text" name="attachment_title" required>
                    </div>

                    <div class="form-group">
                        <label>Choose File:</label>
                        <input type="file" name="attachment_file" required>
                        <small>Allowed formats: JPG, JPEG, PNG, GIF, PDF, DOC, DOCX, XLS, XLSX (Max 5MB)</small>
                    </div>

                    <div class="form-group">
                        <label>Status:</label>
                        <select name="is_active" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" name="upload">Upload Attachment</button>
                </form>
            </div>
        </body>
        </html>
<!-- post attachment          -->