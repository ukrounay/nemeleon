<?php
include('db.php');
include('session_start.php');

$title = "Feed";
$slider = TRUE;

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // Fetch user data (optional)
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $logged = TRUE;
} else {
    $logged = FALSE;
}

include('head.php');

include('welcome.php');

echo '<div class="content-container">';
include('recommendations.php');
include('feed.php');
echo '</div>';

include('foot.php');
?>