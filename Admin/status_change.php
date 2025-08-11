<!-- status change -->
    <?php
    require_once("require/database_connection.php");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $category_id = intval($_POST['category_id']);
        $current_status = $_POST['current_status'];

        $new_status = ($current_status === 'Active') ? 'Inactive' : 'Active';

        $query = "UPDATE category SET category_status = ?, updated_at = NOW() WHERE category_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("si", $new_status, $category_id);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            exit;
        } else {
            echo "Status update failed: " . $stmt->error;
        }

        $stmt->close();
    }

    $connection->close();
    ?>
<!-- status change -->
    
