<!-- // ================== START: Handle POST  ================== -->          
            <?php
            require_once("require/database_connection.php");
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
                $post_id = intval($_POST['post_id']);
                $new_status = 'inactive';

                $query = "UPDATE post SET post_status = ? WHERE post_id = ?";
                $stmt = mysqli_prepare($connection, $query);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "si", $new_status, $post_id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                }
                header("Location: inActive_post.php");
                exit();
            }
            require_once("admin_file.php");
            $query = "SELECT post_id, post_title, post_summary, featured_image, created_at FROM post WHERE post_status = 'active' ORDER BY created_at DESC";
            $result = mysqli_query($connection, $query);
            ?>
<!-- // ================== END: Fetch active posts ================== -->

<!-- // ================== HTLML Structe  ================== -->
            <!DOCTYPE html>
            <html>
            <head>
                <title>Active Posts</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    .post-card {
                        margin-bottom: 30px;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    }
                    .featured-image {
                        max-height: 200px;
                        object-fit: cover;
                    }
                </style>
            </head>
            <body>

            <div class="container my-5">
                <h2 class="mb-4 text-center">Active Blog Posts</h2>

                <div class="row">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card post-card">
                                    <?php if (!empty($row['featured_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['featured_image']); ?>" class="card-img-top featured-image" alt="Featured Image">
                                    <?php endif; ?>

                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['post_title']); ?></h5>
                                        <p class="card-text text-muted"><small><?php echo date("F j, Y", strtotime($row['created_at'])); ?></small></p>
                                        <p class="card-text"><?php echo htmlspecialchars($row['post_summary']); ?></p>
                                        <a href="post_detail.php?id=<?php echo $row['post_id']; ?>" class="btn btn-sm btn-primary">Read More</a>

                                        <!-- Deactivate button -->
                                        <form method="POST" style="display:inline-block; margin-left:10px;">
                                            <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure you want to deactivate this post?')">Deactivate</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="alert alert-info text-center">No active posts found.</div>
                    <?php endif; ?>
                </div>
            </div>
            </body>
            </html>
<!-- // ================== HTLML Structe  ================== -->
            
