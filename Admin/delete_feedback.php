<!-- delete feedback -->
    <?php
    require_once("require/database_connection.php");
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_id'])) {
        $feedback_id = intval($_POST['feedback_id']);

        $stmt = $connection->prepare("DELETE FROM user_feedback WHERE feedback_id = ?");
        $stmt->bind_param("i", $feedback_id);

        if ($stmt->execute()) {
            $_SESSION['feedback_success'] = "Feedback deleted successfully.";
        } else {
            $_SESSION['feedback_success'] = "Failed to delete feedback.";
        }

        $stmt->close();
        $connection->close();
    }

    header("Location: show_all_feedback.php");
    exit;
// <!-- delete feedback -->
    
