<!-- fetch blog database -->
    <?php
    require_once("require/database_connection.php");
    require_once("admin_file.php");
    // print_r($_REQUEST);
    $sql = "SELECT `blog_id`, `user_id`, `blog_title`, `post_per_page`, `blog_background_image`, `blog_status`, `created_at`, `updated_at` FROM `blog`";
    $result = mysqli_query($connection, $sql);
    ?>    
    <!DOCTYPE html>
    <html>
    <head>
        <title>All Blogs</title>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .blog-card {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                border: none;
                overflow: hidden;
                margin-bottom: 30px;
                transition: transform 0.2s ease;
            }

            .blog-card:hover {
                transform: translateY(-5px);
            }

            .blog-image {
                height: 200px;
                object-fit: cover;
            }

            .status-badge {
                font-size: 0.75rem;
                padding: 0.3rem 0.6rem;
            }
        </style>
    </head>
    <body>

    <div class="container py-5">
        <h2 class="text-center mb-5">Blog Directory</h2>
        <div class="row">

            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while($blog = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card blog-card">
                            <?php if (!empty($blog['blog_background_image'])): ?>
                                <img src="uploads/<?= htmlspecialchars($blog['blog_background_image']) ?>" class="card-img-top blog-image" alt="Blog Background Image">

                            <?php else: ?>
                                <img src="" class="card-img-top blog-image" alt="Default Image">
                            <?php endif; ?>

                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($blog['blog_title']) ?></h5>
                                <p class="card-text">
                                    <strong>User ID:</strong> <?= $blog['user_id'] ?><br>
                                    <strong>Posts Per Page:</strong> <?= $blog['post_per_page'] ?><br>
                                    <strong>Status:</strong>
                                    <span class="badge bg-<?= $blog['blog_status'] === 'active' ? 'success' : 'secondary' ?> status-badge">
                                        <?= ucfirst($blog['blog_status']) ?>
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer text-muted">
                                Created: <?= date("M j, Y", strtotime($blog['created_at'])) ?><br>
                                Updated: <?= date("M j, Y", strtotime($blog['updated_at'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-info text-center">No blogs found.</div>
            <?php endif; ?>
        </div>
    </div>
    </body>
    </html>
<!-- fetch blog database -->
    
