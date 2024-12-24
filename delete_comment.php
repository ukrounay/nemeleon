<?php
include('db.php');
include('session_start.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']);
    $comment_id = intval($_POST['comment_id']);

    // Verify user is the comment's author
    $verify_query = "SELECT user_id FROM comments WHERE id = ?";
    $stmt = $conn->prepare($verify_query);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comment = $result->fetch_assoc();

    if ($comment && $comment['user_id'] === $user_id) {
        // Delete the comment and its child comments
        $delete_query = "DELETE FROM comments WHERE id = ? OR parent_comment_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("ii", $comment_id, $comment_id);
        if ($stmt->execute()) {
            header("Location: post.php?id=" . $_POST['post_id']); // Redirect to post page
            exit();
        } else {
            echo "Error deleting comment.";
        }
    } else {
        echo "Unauthorized action.";
    }
} else {
    echo "Unauthorized action.";
}
?>
