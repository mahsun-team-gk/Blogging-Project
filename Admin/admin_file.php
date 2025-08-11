
<!-- admin dashboard -->
            <?php 
            session_start();
                require_once("require/database_connection.php");
            if (session_status() == PHP_SESSION_NONE) {
             session_start();
            }
                    if (!isset($_SESSION['users'])) {
                        header("Location: ../login.php");
                        exit();
                    } else {
                        if ($_SESSION['users']['role_id'] == '1') {
                            $first_name = $_SESSION['users']['first_name']; 
                            echo '<div class="container-fluid p-0 sticky-top">
                                    <div class="row px-0">
                                        <div class="col-12">
                                            <h1 class="bg-primary rounded-pill text-white text-center p-3">
                                                Welcome ' . ($first_name) . ' to Admin Dashboard
                                            </h1>
                                        </div>
                                    </div>
                                  </div>';
                        } else {
                            header("Location: admin_file.php");
                            exit();
                        }
                    }
                 ?>
                <?php
                class admin {
                public static function admin() {
                ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Dashboard</title>

<!-- admin page css -->
            <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
        <style>
            body{
                background-color: red !i;
            }
            .sidebar {
                background-color: #343a40;
            }
            .sidebar .nav-link {
                color: rgba(255, 255, 255, 0.75);
            }
            .sidebar .nav-link:hover, .sidebar .nav-link.active {
                color: #fff;
                background-color: rgba(255, 255, 255, 0.1);
            }
            .main-content {
                background-color: #f8f9fa;
            }
            .dashboard-card {
                transition: transform 0.3s;
            }
            .dashboard-card:hover {
                transform: translateY(-5px);
            }
            .sidebar {
            position: fixed;          
            top: 0;
            left: 0;
            height: 100vh;            
            width: 200px;             
            background-color: navy;
            color: white;
            padding-top: 20px;
        }

        .content {
            margin-left: 260px;  
            padding: 20px;
        }
        </style>
<!-- admin page css -->

<!-- start section Drop down -->
    </head>
    <body>         
    <div class="container-fluid sticky-top ">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar bg-primary subtle text-white ">
                <div class=" pt-3 ">
                    <div class="text-center mb-4">
    <!-- Users -->
                    </div>
                    <ul class="nav flex-column ">
                        <li class="nav-item">
                            <a class="nav-link active" href="admin_file.php">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                            <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#usersCollapse">
                                <i class="bi bi-people me-2"></i> Users
                            </a>
                            <div class="collapse" id="usersCollapse">
                                <ul class="nav flex-column ps-4">
                                    <li class="nav-item">
                                        <a class="nav-link" href="form.php">Add User</a>
                                    </li>
                                    <li class="nav-item">
                                    <a class="nav-link" href="all_user.php">All Users</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a class="nav-link" href="active_user.php">Active</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="inActive_user.php">InActive</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="is_Approved_user.php">IS_APPROVED</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
     <!-- Users -->

   <!-- Posts -->
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#postsCollapse">
                                <i class="bi bi-file-earmark-post me-2"></i>Posts</a>
                            <div class="collapse" id="postsCollapse">
                                <ul class="nav flex-column ps-4">
                                    <li class="nav-item">
                                        <a class="nav-link" href="post_database.php">All Posts</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="insert_post.php">insert post</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="update_post.php">Update Posts</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="active_post.php">Active Posts</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="inActive_post.php">InActive Posts</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="post_category.php">Post Catogery</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a class="nav-link" href="category.php">Insert Categories</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="post_attachment.php">Insert Attachment</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="fatch_post_attachment.php">Fatch Attachment</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="post_comment.php">Post Comment</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
    <!-- Posts -->

    <!-- Category -->
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#categoryCollapse">
                                <i class="bi bi-file-earmark-post me-2"></i>Category
                            </a>
                            <div class="collapse" id="categoryCollapse">
                                <ul class="nav flex-column ps-4">
                                    <li class="nav-item">
                                        <a class="nav-link" href="add_category.php">Add Category</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="update_category.php">Update Category</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="active_category.php">Active Category</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="inActive_category.php">InActive Category</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
    <!-- Category -->

    <!-- Blog Setting -->                        
                        <li class="nav-item dropdown">
                              <a class="nav-link  dropdown-toggle" href="#blogCollapse"  data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-chat-left-text me-2" class="collapse" id="blogCollapse" ></i> Blog Setting
                              </a>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="blog.php">All Blog </a></li>
                                <li><a class="dropdown-item" href="insert_blog.php">Insert  Blog </a></li>
                                <li><a class="dropdown-item" href="follow_blog.php">Follow Blogs</a></li>
                                <li><a class="dropdown-item" href="blog_setting.php">Blog Settings </a></li>
                                <li><a class="dropdown-item" href="active_blog.php">Active Blogs</a></li>
                                <li><a class="dropdown-item" href="inActive_blog.php">InActive Blogs</a></li>
                              </ul>
                            </li>

                            <li class="nav-item mt-3">
                            <a class="nav-link text-warning" href="all_settings.php">
                                <i class="bi bi-box-arrow-right me-2"></i> All Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#commentsCollapse">
                                <i class="bi bi-chat-left-text me-2"></i> Comments
                            </a>
                            <div class="collapse" id="commentsCollapse">
                                <ul class="nav flex-column ps-4">
                                    <li class="nav-item">
                                        <a class="nav-link" href="all_comments.php">All Comments</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="add_comments.php">Add Comment</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Pending Approval</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="feedback.php">
                                <i class="bi bi-envelope me-2"></i> Feedback
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="show_all_feedback.php">
                                <i class="bi bi-envelope me-2"></i> Show All Feedback
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="setting.php">
                                <i class="bi bi-gear me-2"></i> Blog Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
<!-- start section end -->



<!-- admin login section -->

    <main class="col-md-9 ms-sm-auto col-lg-10  main-content">
    <nav class="navbar navbar-expand-md navbar  shadow-sm ">
        <div class="container-fluid">
            <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="dropdown ms-auto">
                <?php
                $isUserLoggedIn = isset($_SESSION['users']);
                $isAdminLoggedIn = isset($_SESSION['admin']);
                ?>
                <?php if ($isUserLoggedIn): ?>
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo htmlspecialchars($_SESSION['users']['user_image']); ?>" alt="User Image" width="32" height="32" class="rounded-circle me-2">
                        <strong><?php echo htmlspecialchars($_SESSION['users']['first_name'] . ' ' . $_SESSION['users']['last_name']); ?></strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="../edit_profile.php"><i class="bi bi-person-fill"></i> Edit Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-chat-dots-fill"></i> Messages</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear-fill"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                <?php elseif ($isAdminLoggedIn): ?>
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownAdmin" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../images/1 (1).jpg" alt="Admin" width="32" height="32" class="rounded-circle me-2">
                        <strong>Admin</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownAdmin">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person-fill"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear-fill"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                <?php endif; ?>
            </div>

        </div>
    </nav>

<!-- <footer class="footer text-center text-white py-3" style="background-color: #343a40; position: fixed; bottom: 0; width: 100%;">
    © 2025 Your Blog Name. All rights reserved.
</footer>
 -->        <!-- other content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content py-4">
                <div class="row mb-4">                  
                </div>
                
                        <?php 
                }
            } // close class properly
            admin::admin(); 
            ?>


    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- admin login section -->

