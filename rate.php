<?php
include('db.php');
include('session_start.php');
header('Content-Type: application/json');

// Parse incoming data
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$type = isset($_GET['type']) ? $_GET['type'] : '';
$value = isset($_GET['value']) ? intval($_GET['value']) : 0;
$user_id = $_SESSION['user_id'];

if (!in_array($value, [-1, 1])) {
    echo json_encode(['error' => true, 'message' => 'Invalid vote value.']);
    exit;
}

$post_id = null;
$comment_id = null;

if ($type === 'post') {
    $post_id = $id;
} elseif ($type === 'comment') {
    $comment_id = $id;
} else {
    echo json_encode(['error' => true, 'message' => 'Invalid entity type.']);
    exit;
}

// Check if the user already voted
$query = "
    SELECT value FROM vote 
    WHERE user_id = ? AND post_id <=> ? AND comment_id <=> ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('iii', $user_id, $post_id, $comment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User has already voted
    $existing_vote = $result->fetch_assoc()['value'];
    if ($existing_vote == $value) {
        // Same vote: Remove it
        $delete_query = "DELETE FROM vote WHERE user_id = ? AND post_id <=> ? AND comment_id <=> ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param('iii', $user_id, $post_id, $comment_id);
        $delete_stmt->execute();
    } else {
        // Different vote: Update it
        $update_query = "UPDATE vote SET value = ? WHERE user_id = ? AND post_id <=> ? AND comment_id <=> ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('iiii', $value, $user_id, $post_id, $comment_id);
        $update_stmt->execute();
    }
} else {
    // New vote: Insert it
    $insert_query = "INSERT INTO vote (user_id, post_id, comment_id, value) VALUES (?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param('iiii', $user_id, $post_id, $comment_id, $value);
    $insert_stmt->execute();
}

// Calculate the new total rating
$rating_query = "
    SELECT SUM(value) AS total_rating
    FROM vote
    WHERE post_id <=> ? AND comment_id <=> ?";
$rating_stmt = $conn->prepare($rating_query);
$rating_stmt->bind_param('ii', $post_id, $comment_id);
$rating_stmt->execute();
$rating_result = $rating_stmt->get_result();
$total_rating = $rating_result->fetch_assoc()['total_rating'] ?? 0;

// Return JSON response
echo json_encode(['error' => false, 'total_rating' => $total_rating]);
