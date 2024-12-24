<?php
include('db.php');
include('session_start.php');


if (!isset($_GET['id'])) {
    echo "User not found.";
    exit();
}

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

$displayed_user_id = intval($_GET['id']);

// Fetch user details
$query = "SELECT id, username, email, profile_picture, about FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $displayed_user_id);
$stmt->execute();
$result = $stmt->get_result();
$displayed_user = $result->fetch_assoc();

if (!$displayed_user) {
    echo "User not found.";
    exit();
}

// Fetch user posts (individual and community)
$individual_posts_query = "SELECT * FROM posts WHERE user_id = ? AND community_id IS NULL ORDER BY created_at DESC";
$community_posts_query = "SELECT posts.*, colors.id AS community_id, colors.hex_color AS community_color 
    FROM posts 
    JOIN colors ON posts.community_id = colors.id 
    WHERE posts.user_id = ?
    ORDER BY posts.created_at DESC";

$individual_stmt = $conn->prepare($individual_posts_query);
$individual_stmt->bind_param("i", $displayed_user_id);
$individual_stmt->execute();
$individual_posts = $individual_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$community_stmt = $conn->prepare($community_posts_query);
$community_stmt->bind_param("i", $displayed_user_id);
$community_stmt->execute();
$community_posts = $community_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch user overall rating
$rating_query = "
    SELECT 
        (SELECT SUM(vote.value) 
         FROM vote 
         LEFT JOIN posts ON vote.post_id = posts.id 
         WHERE posts.user_id = ?) +
        (SELECT SUM(vote.value) 
         FROM vote 
         LEFT JOIN comments ON vote.comment_id = comments.id 
         WHERE comments.user_id = ?) AS rating";
$rating_stmt = $conn->prepare($rating_query);
$rating_stmt->bind_param("ii", $displayed_user_id, $displayed_user_id);
$rating_stmt->execute();
$rating = $rating_stmt->get_result()->fetch_assoc();

// Fetch communities the user belongs to
$communities_query = "SELECT colors.* 
    FROM colors 
    JOIN membership ON colors.id = membership.community_id 
    WHERE membership.user_id = ?";
$communities_stmt = $conn->prepare($communities_query);
$communities_stmt->bind_param("i", $displayed_user_id);
$communities_stmt->execute();
$communities = $communities_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$title = $displayed_user['username'];

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


function hexToHsl($hexColor) {
    $hexColor = ltrim($hexColor, '#');

    // Convert HEX to RGB
    $r = hexdec(substr($hexColor, 0, 2)) / 255;
    $g = hexdec(substr($hexColor, 2, 2)) / 255;
    $b = hexdec(substr($hexColor, 4, 2)) / 255;

    // Find min and max values for RGB
    $max = max($r, $g, $b);
    $min = min($r, $g, $b);
    $delta = $max - $min;

    // Calculate Hue
    if ($delta == 0) {
        $h = 0; // No difference
    } elseif ($max == $r) {
        $h = 60 * fmod((($g - $b) / $delta), 6);
    } elseif ($max == $g) {
        $h = 60 * (($b - $r) / $delta + 2);
    } else {
        $h = 60 * (($r - $g) / $delta + 4);
    }
    $h = ($h < 0) ? $h + 360 : $h;

    // Calculate Lightness
    $l = ($max + $min) / 2;

    // Calculate Saturation
    $s = ($delta == 0) ? 0 : $delta / (1 - abs(2 * $l - 1));

    return ['h' => $h, 's' => $s, 'l' => $l];
}

function generateOrderedGradientStyle($colors) {
    if(count($colors) == 1) {return $colors[0];}

    $colorsWithHsl = array_map(function($color) {
        return ['hex' => $color, 'hsl' => hexToHsl($color)];
    }, $colors);

    usort($colorsWithHsl, function($a, $b) {
        return $a['hsl']['h'] <=> $b['hsl']['h'];
    });

    $sortedColors = array_column($colorsWithHsl, 'hex');
    $gradient = 'linear-gradient(to right, ' . implode(', ', $sortedColors) . ')';

    return $gradient;
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

$hex_colors = [];

foreach($communities as $community) 
    array_push($hex_colors, $community['hex_color']);

$displayed_user_gradient = generateOrderedGradientStyle($hex_colors);

?>

<link rel="stylesheet" href="styles/profile.css">

<header>
    <div class="profile-image-container">
        <span class="profile-image" style="background-image: url('<?= ($displayed_user['profile_picture'] != null || $displayed_user['profile_picture'] != "") ? ('uploads/'.$displayed_user['id'] .'/'. $displayed_user['profile_picture']) : 'styles/images/no-pfp.svg' ?>');"></span>
    </div>
    <div class="details">
        <h2><?= htmlspecialchars($displayed_user['username']) ?></h2>
        <?= $displayed_user['email'] ? "<p><a href='mailto:" . $displayed_user['email'] . "'>" . $displayed_user['email'] . "</a></p>" : '' ?>
        <?= $displayed_user['about'] ? "<p>" . nl2br($displayed_user['about']) . "</p>" : ''?>
        <p>Rating: <strong>
            <?= $rating['rating'] ? $rating['rating'] : 0 ?>
        </strong></p>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $displayed_user_id): ?>
            <p class="actions">
                <a class="button filled button-slim" href="add_post.php">Create post</a>
                <a class="button light-filled button-slim" href="settings.php">Edit profile</a>
                <a class="button light-filled button-slim" href="logout.php">Logout</a>
            </p>
        <?php endif; ?>
    </div>
</header>

<section class="content-container">
    <aside id="colors" class="user-colors">
        <h2 class="hash-title"><a href="#colors">Colors</a></h2>
        <?php if (count($communities) <= 0): ?>
            <p>This user is not part of any communities.</p>
        <?php else: ?>
            <div class="gradient" style="background: <?= $displayed_user_gradient ?>;"></div>
            <div class="horisontal-list">
            <?php foreach ($communities as $community): ?>
                <a class="badge" href="community.php?id=<?= $community['id'] ?>" style="
                    --color: <?= $community['hex_color'] ?>;
                    --contrast-color: <?= getContrastingTextColor($community['hex_color']) ?>;
                "><?= $community['hex_color'] ?></a>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </aside>

    <div id="publications" class="user-publications">
        <h2 class="hash-title"><a href="#publications">Publications</a></h2>

        <div class="tabs-nav">
            <button class="tab active" onclick="changeTab(this)" data-tab="individual">Individual</button>
            <button class="tab" onclick="changeTab(this)" data-tab="community">Communities</button>
        </div>

        <div class="tabs">

            <div id="individual" class="tab-content" data-tab="individual">
                <?php foreach ($individual_posts as $post): ?>
                    <p class="brief-post">
                        <a href="post.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a>
                        <span><?= time_passed($post['created_at']) ?> ago</span>
                    </p>
                    <span class="ph-horisontal"></span>
                <?php endforeach; ?>
            </div>

            <div id="community" class="tab-content hidden" data-tab="community">
                <?php foreach ($community_posts as $post): ?>
                    <p class="brief-post">
                        <span>
                            <a href="post.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a>
                            <span>in 
                                <a class="badge" href="community.php?id=<?= $post['community_id'] ?>" style="
                                    --color: <?= $post['community_color'] ?>;
                                    --contrast-color: <?= getContrastingTextColor($post['community_color']) ?>;
                                "><?= $post['community_color'] ?></a>
                            </span>
                        </span>
                        <span><?= time_passed($post['created_at']) ?> ago</span>
                    </p>
                    <span class="ph-horisontal"></span>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

</section>



<script>
function changeTab(elem) {
    const tabId = elem.getAttribute('data-tab');
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    elem.classList.add('active');
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
    document.getElementById(tabId).classList.remove('hidden');
}
</script>