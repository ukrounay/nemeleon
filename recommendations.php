<?php
include('db.php');

// Fetch the top-rated post
$top_post_query = "
    SELECT posts.id, posts.title, users.username, 
           SUM(vote.value) AS total_rating
    FROM posts
    LEFT JOIN vote ON vote.post_id = posts.id
    LEFT JOIN users ON posts.user_id = users.id
    GROUP BY posts.id
    ORDER BY total_rating DESC
    LIMIT 1";
$top_post_result = $conn->query($top_post_query);
$top_post = $top_post_result->fetch_assoc();

// Fetch the largest community
$largest_community_query = "
    SELECT colors.id, colors.hex_color, COUNT(membership.user_id) AS member_count
    FROM colors
    LEFT JOIN membership ON colors.id = membership.community_id
    GROUP BY colors.id
    ORDER BY member_count DESC
    LIMIT 1";
$largest_community_result = $conn->query($largest_community_query);
$largest_community = $largest_community_result->fetch_assoc();

// Fetch the best author
$best_author_query = "SELECT users.id, users.username, 
           (SELECT SUM(vote.value) 
            FROM vote 
            LEFT JOIN posts ON vote.post_id = posts.id 
            WHERE posts.user_id = users.id) +
           (SELECT SUM(vote.value) 
            FROM vote 
            LEFT JOIN comments ON vote.comment_id = comments.id 
            WHERE comments.user_id = users.id) AS total_rating
            FROM users
            ORDER BY total_rating DESC
            LIMIT 1";
$best_author_result = $conn->query($best_author_query);
$best_author = $best_author_result->fetch_assoc();

// Close the connection
$conn->close();



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


?>

<link rel="stylesheet" href="styles/recommendations.css">
<section class="recommendations">
    <h2 class="hash-title"><a href="#recommendations">Recommendations</a></h2>
    <div id="recommendations">
        <div class="recommendation">
            <h3>Top Post</h3>
            <?php if ($top_post): ?>
                <p><?= $top_post['title'] ?> by <?= $top_post['username'] ?></p>
                <p><strong><?= $top_post['total_rating']?></strong> total rating</p>
                <a class="button-slim" href="post.php?id=<?= $top_post['id'] ?>">Read<i class="ni-arrow-right"></i></a>
            <?php else: ?>
                <p>No posts available.</p>
            <?php endif; ?>
        </div>
        <div class="recommendation">
            <h3>Largest Community</h3>
            <?php if ($largest_community): ?>
                <p><span class="badge" style="background-color: <?= $largest_community['hex_color'] ?>; color: <?= getContrastingTextColor($largest_community['hex_color'])?>"><?php echo htmlspecialchars($largest_community['hex_color']); ?></span></p>
                <p><strong><?php echo htmlspecialchars($largest_community['member_count']); ?></strong> members</p>
                <a class="button-slim" href="community.php?id=<?php echo $largest_community['id']; ?>">Visit Community<i class="ni-arrow-right"></i></a>
            <?php else: ?>
                <p>No communities available.</p>
            <?php endif; ?>
        </div>
        <div class="recommendation">
            <h3>Best Author</h3>
            <?php if ($best_author): ?>
                <p><?php echo htmlspecialchars($best_author['username']); ?></p>
                <p><strong><?= $best_author['total_rating']?></strong> total rating</p>
                <a href="user.php?id=<?= $best_author["id"]?>" class="button-slim">Visit Page<i class="ni-arrow-right"></i></a>
            <?php else: ?>
                <p>No authors available.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
