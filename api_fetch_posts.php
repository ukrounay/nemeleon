<?php
include('db.php');

// Get the page parameter (default is 0)
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$wanted_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$limit = 5;
$offset = $page * $limit;

// Function to calculate time passed
function time_passed($datetime) {
    $now = new DateTime();
    $post_time = new DateTime($datetime);
    $interval = $now->diff($post_time);

    if ($interval->y > 0) {
        return $interval->y . 'y';
    } elseif ($interval->m > 0) {
        return $interval->m . 'm';
    } elseif ($interval->d >= 7) {
        return floor($interval->d / 7) . 'w';
    } elseif ($interval->d > 0) {
        return $interval->d . 'd';
    } elseif ($interval->h > 0) {
        return $interval->h . 'h';
    } elseif ($interval->i > 0) {
        return $interval->i . 'min';
    } else {
        return $interval->s . 's';
    }
}

// Fetch the posts with their details
$query = "
    SELECT posts.id, posts.title, posts.text, posts.created_at, 
           users.id AS user_id, users.username, users.profile_picture,
           colors.hex_color, colors.id AS community_id
    FROM posts
    LEFT JOIN users ON posts.user_id = users.id
    LEFT JOIN colors ON posts.community_id = colors.id
    ORDER BY posts.created_at DESC
    LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $row['time_passed'] = time_passed($row['created_at']);

    // Calculate total rating
    $rating_query = "
    SELECT SUM(value) AS total_rating
    FROM vote
    WHERE post_id <=> ? AND comment_id IS NULL";
    $rating_stmt = $conn->prepare($rating_query);
    $rating_stmt->bind_param('i', $row['id']);
    $rating_stmt->execute();
    $rating_result = $rating_stmt->get_result();
    $row['rating'] = $rating_result->fetch_assoc()['total_rating'] ?? 0;

    $posts[] = $row;
}

// Return posts as JSON
header('Content-Type: application/json');
echo json_encode($posts);
?>
