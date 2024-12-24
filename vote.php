<?php
include('db.php'); // Database connection
include('session_start.php'); // Start session

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']);
    $post_id = intval($_POST['post_id']);
    $comment_id = isset($_POST['comment_id']) && !empty($_POST['comment_id']) ? intval($_POST['comment_id']) : null;
    $vote_value = intval($_POST['value']); // -1 for downvote, 1 for upvote

    if ($vote_value !== 1 && $vote_value !== -1) {
        die("Invalid vote value.");
    }
    
    // Check if the user already voted
    $check_vote_query = "
        SELECT value FROM vote 
        WHERE user_id = ? AND post_id = ? AND comment_id " . ($comment_id ? "= ?" : "IS NULL");
    $stmt = $conn->prepare($check_vote_query);
    if ($comment_id) {
        $stmt->bind_param("iii", $user_id, $post_id, $comment_id);
    } else {
        $stmt->bind_param("ii", $user_id, $post_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_vote = $result->fetch_assoc();

    if ($result->num_rows > 0) {
        // Vote exists, check if it matches the new vote
        $existing_vote = $result->fetch_assoc();
        if ($existing_vote['value'] == $vote_value) {
            // Same vote, delete it (toggle behavior)
            $delete_query = "
                DELETE FROM vote 
                WHERE user_id = ? AND post_id = ? AND (comment_id = ? OR comment_id IS NULL)";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("iii", $user_id, $post_id, $comment_id);
            $delete_stmt->execute();
        } else {
            // Different vote, update it
            $update_query = "
                UPDATE vote 
                SET value = ? 
                WHERE user_id = ? AND post_id = ? AND (comment_id = ? OR comment_id IS NULL)";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("iiii", $vote_value, $user_id, $post_id, $comment_id);
            $update_stmt->execute();
        }
    } else {
        // Insert new vote
        $insert_query = "
            INSERT INTO vote (user_id, post_id, comment_id, value) 
            VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("iiii", $user_id, $post_id, $comment_id, $vote_value);
        $insert_stmt->execute();
    }
    
    // Redirect back to the post page
    header("Location: post.php?id=$post_id");
    exit();
} else {
    echo "Unauthorized action.";
    exit();
}
