<!-- fetch post from database -->
    <?php
    // session_start();
    require_once("require/database_connection.php");
    require_once("admin_file.php");
    $query = "SELECT `post_id`, `blog_id`, `post_title`, `post_summary`, `post_description`, `featured_image`, `post_status`, `is_comment_allowed`, `created_at`, `updated_at` FROM `post`";
    $result = mysqli_query($connection, $query);
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>All Posts</title>
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
        <h2 class="mb-4 text-center">All Blog Posts</h2>

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
                                <a href="view_post.php?id=<?php echo $row['post_id']; ?>" class="btn btn-sm btn-primary">Read More</a>
                            </div>

                            <div class="card-footer text-muted">
                                Status: <?php echo ucfirst($row['post_status']); ?> |
                                Comments: <?php echo $row['is_comment_allowed'] ? 'Allowed' : 'Not Allowed'; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-info text-center">No posts found.</div>
            <?php endif; ?>
        </div>
    </div>
    </body>
    </html>
<!-- fetch post from database -->
    
