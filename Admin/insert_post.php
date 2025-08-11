            <?php
// ======= START:  Comments section =======

            require_once("require/database_connection.php");
            require_once("admin_file.php");



            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $blog_id            = $_POST['blog_id'];
                $post_title         = trim($_POST['post_title']);
                $post_summary       = trim($_POST['post_summary']);
                $post_status        = $_POST['post_status'];
                $is_comment_allowed = isset($_POST['is_comment_allowed']) ? 1 : 0;
                $category_ids       = isset($_POST['categories']) ? $_POST['categories'] : [];                
                $featured_image = '';



                if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
                    $target_dir = "uploads/";
                    $featured_image = $target_dir . basename($_FILES['featured_image']['name']);
                    move_uploaded_file($_FILES["featured_image"]["tmp_name"], $featured_image);
                }

                $stmt = mysqli_prepare($connection, "INSERT INTO post (blog_id, post_title, post_summary, featured_image, post_status, is_comment_allowed, created_at, updated_at) VALUES ( ?, ?, ?, ?, ?, ?, NOW(), NOW())");
                mysqli_stmt_bind_param($stmt, "issssi", $blog_id, $post_title, $post_summary, $featured_image, $post_status, $is_comment_allowed);


                if (mysqli_stmt_execute($stmt)) {
                    $post_id = mysqli_insert_id($connection);
                    foreach ($category_ids as $category_id) {
                        $cat_stmt = mysqli_prepare($connection, "INSERT INTO post_category (post_id, category_id, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");

                        mysqli_stmt_bind_param($cat_stmt, "ii", $post_id, $category_id);
                        mysqli_stmt_execute($cat_stmt);
                        mysqli_stmt_close($cat_stmt);
                    }

                    if (!empty($_FILES['attachments']['name'][0])) {
                        foreach ($_FILES['attachments']['name'] as $index => $name) {
                            $path = "uploads/" . basename($name);
                            move_uploaded_file($_FILES['attachments']['tmp_name'][$index], $path);
                            $attach_stmt = mysqli_prepare($connection, "INSERT INTO post_atachment (post_id, post_attachment_title, post_attachment_path, is_active, created_at, updated_at) VALUES (?, ?, ?, 1, NOW(), NOW())");
                            mysqli_stmt_bind_param($attach_stmt, "iss", $post_id, $name, $path);
                            mysqli_stmt_execute($attach_stmt);
                            mysqli_stmt_close($attach_stmt);
                        }
                    }

                    echo "<div class='alert alert-success'>Post created successfully.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error creating post.</div>";
                }
                mysqli_stmt_close($stmt);
            }
            $blogs = mysqli_query($connection, "SELECT blog_id, blog_title FROM blog WHERE blog_status = 'active'");
            $categories = mysqli_query($connection, "SELECT category_id, category_title FROM category WHERE category_status = 'active'");
            ?>

            <!DOCTYPE html>
            <html>
            <head>
                <title>Create Post</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body>
            <div class="container mt-5">
                <h2>Create New Post</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="blog_id" class="form-label">Select Blog</label>
                        <select class="form-select" id="blog_id" name="blog_id" required>
                            <option value="">-- Select Blog --</option>
                            <?php while($blog = mysqli_fetch_assoc($blogs)): ?>
                                <option value="<?= $blog['blog_id'] ?>"><?= htmlspecialchars($blog['blog_title']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="post_title" class="form-label">Post Title</label>
                        <input type="text" class="form-control" name="post_title" required>
                    </div>

                    <div class="mb-3">
                        <label for="post_summary" class="form-label">Post Summary</label>
                        <textarea class="form-control" name="post_summary" rows="2" required></textarea>
                    </div>


                    
                    <div class="mb-3">
                        <label for="featured_image" class="form-label">Featured Image</label>
                        <input type="file" class="form-control" name="featured_image">
                    </div>
                    <!-- Status -->
                    <div class="mb-3">
                        <label for="post_status" class="form-label">Post Status</label>
                        <select class="form-select" name="post_status" required>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    <!-- Allow Comments -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_comment_allowed" id="is_comment_allowed">
                        <label class="form-check-label" for="is_comment_allowed">Allow Comments</label>
                    </div>

                    <!-- Category Checkboxes -->
                    <div class="mb-3 text-primary">
                        <label class="form-label">Categories</label><br>
                        <?php while($category = mysqli_fetch_assoc($categories)): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="<?= $category['category_id'] ?>" id="cat<?= $category['category_id'] ?>">
                                <label class="form-check-label" for="cat<?= $category['category_id'] ?>"><?= htmlspecialchars($category['category_title']) ?></label>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <!-- Attachments -->
                    <div class="mb-3">
                        <label for="attachments" class="form-label">Additional Attachments</label>
                        <input type="file" class="form-control" name="attachments[]" multiple>
                    </div>

                    <button type="submit" class="btn btn-primary">Create Post</button>
                </form>
            </div>
            </body>
            </html>
<!-- // ======= START:  Comments section ======= -->
