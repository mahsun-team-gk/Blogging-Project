<!-- // ================== Add Category Data   ================== --> 
        
        <?php
        require_once("require/database_connection.php");
        require_once("admin_file.php");


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $category_title = trim($_POST['category_title']);
            $category_description = trim($_POST['category_description']);
            $category_status = 'active';

            if (!empty($category_title)) {
                $stmt = mysqli_prepare($connection, "INSERT INTO category (category_title, category_description, category_status, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
                mysqli_stmt_bind_param($stmt, "sss", $category_title, $category_description, $category_status);

                if (mysqli_stmt_execute($stmt)) {
                    $message = "<div class='alert alert-success'>Category added successfully.</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Failed to add category.</div>";
                }

                mysqli_stmt_close($stmt);
            } else {
                $message = "<div class='alert alert-warning'>Category title is required.</div>";
            }
        }
        ?>
<!-- // ================== Add Category Data   ================== --> 

<!-- // ================== Add Category Data  HTML ================== --> 

        <!DOCTYPE html>
        <html>
        <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <title>Add Category</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
        <div class="container mt-5">
          <h2 class="text-center mb-4">Add New Category</h2>
          <?php if (isset($message)) echo $message; ?>
          <form method="POST">
            <div class="mb-3">
              <label for="category_title" class="form-label">Category Title</label>
              <input type="text" class="form-control" id="category_title" name="category_title" required>
            </div>
            <div class="mb-3">
              <label for="category_description" class="form-label">Category Description</label>
              <textarea class="form-control" id="category_description" name="category_description" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
          </form>
        </div>
        </body>
        </html>
<!-- // ================== Add Category Data  HTML ================== --> 
        
