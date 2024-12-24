<?php
include('db.php'); // Database connection
include('session_start.php'); // Start session

// Get the community ID from the URL
$community_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch community details
$query = "SELECT * FROM colors WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $community_id);
$stmt->execute();
$community_result = $stmt->get_result();

if ($community_result->num_rows === 0) {
    echo "Community not found.";
    exit();
}

$community = $community_result->fetch_assoc();

// Fetch the members count
$query = "SELECT COUNT(*) AS member_count FROM membership WHERE community_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $community_id);
$stmt->execute();
$member_count_result = $stmt->get_result();
$member_count = $member_count_result->fetch_assoc()['member_count'];

// Fetch members list
$query = "
    SELECT u.id, u.username 
    FROM users u
    JOIN membership m ON u.id = m.user_id
    WHERE m.community_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $community_id);
$stmt->execute();
$members_result = $stmt->get_result();

// Fetch posts in the community
$query = "
    SELECT p.*, u.username 
    FROM posts p
    JOIN users u ON p.user_id = u.id
    WHERE p.community_id = ?
    ORDER BY p.created_at DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $community_id);
$stmt->execute();
$posts_result = $stmt->get_result();

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

$is_member = false;

if ($logged) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT 1 FROM membership WHERE user_id = ? AND community_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $community_id);
    $stmt->execute();
    $is_member_result = $stmt->get_result();
    $is_member = $is_member_result->num_rows > 0;
}

// Handle join/leave requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$logged) {
        echo "You must be logged in to join or leave the community.";
        exit();
    }

    if (isset($_POST['join'])) {
        // Join the community
        $query = "INSERT INTO membership (user_id, community_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $community_id);
        $stmt->execute();
        header("Location: community.php?id=$community_id");
        exit();
    } elseif (isset($_POST['leave'])) {
        // Leave the community
        $query = "DELETE FROM membership WHERE user_id = ? AND community_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $community_id);
        $stmt->execute();
        header("Location: community.php?id=$community_id");
        exit();
    }
}

$title = $community["name"];

include('head.php');


function isColorDark($hexColor) {
    $hexColor = ltrim($hexColor, '#');
    $r = hexdec(substr($hexColor, 0, 2));
    $g = hexdec(substr($hexColor, 2, 2));
    $b = hexdec(substr($hexColor, 4, 2));
    $luminance = (0.2126 * $r + 0.7152 * $g + 0.0722 * $b) / 255;
    return $luminance < 0.5;
}

function getContrastingTextColor($hexColor) {
    return isColorDark($hexColor) ? '#FFFFFF' : '#000000';
}

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


?>
<header>
<h1>
<span class="badge large-badge" style="
    --color: <?= $community['hex_color'] ?>;
    --contrast-color: <?= getContrastingTextColor($community['hex_color']) ?>;
"><?= $community['hex_color'] ?></span>
<span><?= htmlspecialchars($community['name']) ?> </span>
</h1>

<p><?= nl2br(htmlspecialchars($community['description'])) ?></p>
<p><?= $member_count ?> members</p>

<p>
<?php if ($logged): ?>
    <?php if ($is_member): ?>
        <form method="GET" action="add_post.php">
            <input type="hidden" name="community_id" value="<?= $community_id ?>">
            <button type="submit">Post in This Community</button>
        </form>
        <form method="POST" action="">
            <button type="submit" name="leave">Leave Community</button>
        </form>
    <?php else: ?>
        <form method="POST" action="">
            <button type="submit" name="join">Join Community</button>
        </form>
    <?php endif; ?>
<?php else: ?>
    <p><a href="login.php">Login</a> to join this community.</p>
<?php endif; ?>
</p>
  
</header>


<h2 class="hash-title" id="members"><a href="#members">Members</a></h2>

<ul>
    <?php while ($member = $members_result->fetch_assoc()): ?>
        <li><?= htmlspecialchars($member['username']) ?></li>
    <?php endwhile; ?>
</ul>

<h2 class="hash-title" id="posts"><a href="#posts">Posts</a></h2>
<?php if ($posts_result->num_rows > 0): ?>
    <?php while ($post = $posts_result->fetch_assoc()): ?>
        <p class="brief-post">
            <span>
                <a href="user.php?id=<?= $post['user_id'] ?>"><?= $post['username'] ?></a> posted
                <a href="post.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a>
            </span>
            <span><?= time_passed($post['created_at']) ?> ago</span>
        </p>
        <span class="ph-horisontal"></span>
    <?php endwhile; ?>
<?php else: ?>
    <p>No posts in this community yet</p>
<?php endif; ?>