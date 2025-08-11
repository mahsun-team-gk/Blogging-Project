<!-- // --- Start: All Blog Data with Their Settings --- -->
            <?php
            require_once("admin_file.php");
            require_once("require/database_connection.php");

            $loggedInUserId = $_SESSION['users']['user_id'];

            // Get single setting value
            function get_setting($connection, $user_id, $key) {
                $query = "SELECT setting_value FROM setting WHERE user_id = $user_id AND setting_key = '$key' LIMIT 1";
                $result = mysqli_query($connection, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    return $row['setting_value'];
                }
                return '';
            }

            // Save settings on form submit
            if (isset($_POST['save_settings'])) {
                $title_color = $_POST['title_color'];
                $background_color = $_POST['background_color'];
                $font_style = $_POST['font_style'];

                $settings = [
                    'title_color' => $title_color,
                    'background_color' => $background_color,
                    'font_style' => $font_style
                ];

                foreach ($settings as $key => $value) {
                    $check_sql = "SELECT * FROM setting WHERE user_id = $loggedInUserId AND setting_key = '$key'";
                    $check_result = mysqli_query($connection, $check_sql);

                    if ($check_result && mysqli_num_rows($check_result) > 0) {
                        $update_sql = "UPDATE setting SET setting_value = '$value', updated_at = NOW() 
                                       WHERE user_id = $loggedInUserId AND setting_key = '$key'";
                        mysqli_query($connection, $update_sql);
                    } else {
                        $insert_sql = "INSERT INTO setting (user_id, setting_key, setting_value, setting_status, created_at, updated_at)
                                       VALUES ($loggedInUserId, '$key', '$value', 'Active', NOW(), NOW())";
                        mysqli_query($connection, $insert_sql);
                    }
                }

                echo '<div class="alert alert-success">Settings saved successfully.</div>';
            }

            $title_color = get_setting($connection, $loggedInUserId, 'title_color');
            $background_color = get_setting($connection, $loggedInUserId, 'background_color');
            $font_style = get_setting($connection, $loggedInUserId, 'font_style');
            ?>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">Blog Settings</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Post Title Color</label>
                            <input type="color" name="title_color" class="form-control form-control-color" value="<?= $title_color ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Post Background Color</label>
                            <input type="color" name="background_color" class="form-control form-control-color" value="<?= $background_color ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Font Style</label>
                            <select name="font_style" class="form-select">
                                <option value="">Select Font</option>
                                <option value="Arial" <?= ($font_style == 'Arial') ? 'selected' : '' ?>>Arial</option>
                                <option value="Georgia" <?= ($font_style == 'Georgia') ? 'selected' : '' ?>>Georgia</option>
                                <option value="Verdana" <?= ($font_style == 'Verdana') ? 'selected' : '' ?>>Verdana</option>
                            </select>
                        </div>

                        <button type="submit" name="save_settings" class="btn btn-primary">Save Settings</button>
                    </form>
                </div>
            </div>
<!-- // --- End: All Blog Data with Their Settings --- -->
