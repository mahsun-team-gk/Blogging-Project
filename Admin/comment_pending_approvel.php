<!-- comment pending  section     -->
    <?php
    require_once("admin_file.php");
    class pending_approvel{
    public static function pending_approvel(){
    ?>
            <style>
            .pending-comment {
                background-color: #fff8e1;
                border-left: 4px solid #ffc107;
                }
            .approved-comment {
                background-color: #e8f5e9;
                border-left: 4px solid #4caf50;
            }
        </style>
    </head>
    <body class="bg-light">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <h2 class="mb-4 text-center">Comments</h2>
                    <div class="card mb-4 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Admin Controls</h5>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary me-2">Pending (3)</button>
                                    <button class="btn btn-sm btn-outline-success">Approved (5)</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="card mb-3 pending-comment">
                            <div class="card-body">
                                <div class="d-flex">
                                    <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="User">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h5 class="mb-0">New User</h5>
                                            <small class="text-muted">Pending approval</small>
                                        </div>
                                        <p class="mb-2">This is a new comment waiting for approval.</p>
                                        <div class="d-flex">
                                            <button class="btn btn-sm btn-success me-2">Approve</button>
                                            <button class="btn btn-sm btn-danger">Reject</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3 approved-comment">
                            <div class="card-body">
                                <div class="d-flex">
                                    <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="User">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h5 class="mb-0">John Doe</h5>
                                            <small class="text-muted">Approved 2 hours ago</small>
                                        </div>
                                        <p class="mb-0">This comment has been approved by admin.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex">
                                    <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="User">
                                    <div>
                                        <h5 class="mb-1">Jane Smith</h5>
                                        <small class="text-muted">Approved 1 day ago</small>
                                        <p class="mt-2 mb-0">This is another approved comment.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Add Your Comment</h5>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Comment</label>
                                    <textarea class="form-control" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit for Approval</button>
                                <small class="d-block mt-2 text-muted">All comments require admin approval.</small>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    }
    pending_approvel::pending_approvel();
    ?>
<!-- comment pending  section     -->
    