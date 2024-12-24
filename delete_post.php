<?php
include('db.php');
include('session_start.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']);
    $post_id = intval($_POST['post_id']);

    // Verify user is the post's author
    $verify_query = "SELECT user_id FROM posts WHERE id = ?";
    $stmt = $conn->prepare($verify_query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    if ($post && $post['user_id'] === $user_id) {
        // Delete post and its comments
        $delete_query = "DELETE FROM posts WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $post_id);
        if ($stmt->execute()) {
            header("Location: index.php"); // Redirect to homepage
            exit();
        } else {
            echo "Error deleting post.";
        }
    } else {
        echo "Unauthorized action.";
    }
} else {
    echo "Unauthorized action.";
}
?>
