<!-- insert post category     -->
    <?php
    require_once("require/database_connection.php");

    if (isset($_POST['submit'])) {
        $post_title = $_POST['post_title'];
        $post_summary = $_POST['post_summary'];
        $category_ids = $_POST['category_ids']; // array of selected categories
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        // Handle image upload
        $featured_image = '';
        if (!empty($_FILES['featured_image']['name'])) {
            $featured_image = time() . '_' . $_FILES['featured_image']['name'];
            move_uploaded_file($_FILES['featured_image']['tmp_name'], "uploads/" . $featured_image);
        }

        // Insert post
        $insert_post_sql = "INSERT INTO post (post_title, post_summary, featured_image, created_at, updated_at)
                            VALUES ('$post_title', '$post_summary', '$featured_image', '$created_at', '$updated_at')";
        
        if ($connection->query($insert_post_sql)) {
            $post_id = $connection->insert_id;

            // Insert into post_category
            foreach ($category_ids as $category_id) {
                $connection->query("INSERT INTO post_category (post_id, category_id, created_at, updated_at)
                                    VALUES ($post_id, $category_id, '$created_at', '$updated_at')");
            }

            echo "Post created and categories linked successfully!";
        } else {
            echo "Error: " . $connection->error;
        }
    }
    ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="post_title" placeholder="Post Title" required><br>
        <textarea name="post_summary" placeholder="Post Summary" required></textarea><br>

        <label>Select Categories:</label><br>
        <select name="category_ids[]" multiple required>
            <?php
            require_once("require/database_connection.php");
            $cat_query = "SELECT category_id, category_title FROM category";
            $cat_result = $connection->query($cat_query);
            while ($cat = $cat_result->fetch_assoc()) {
                echo "<option value='{$cat['category_id']}'>{$cat['category_title']}</option>";
            }
            ?>
        </select><br>

        <input type="file" name="featured_image"><br>
        <button type="submit" name="submit">Create Post</button>
    </form>
<!-- insert post category     -->
