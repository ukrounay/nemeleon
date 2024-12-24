<?php
include('db.php'); // Database connection
include('session_start.php'); // Start session

// Check if a post ID is provided
if (!isset($_GET['id'])) {
    echo "Post ID is missing.";
    exit();
}
$post_id = intval($_GET['id']);

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

// Fetch post data
$query = "SELECT p.*, u.username, u.profile_picture, c.id AS community_id, c.name AS community_name, c.hex_color
          FROM posts p
          JOIN users u ON p.user_id = u.id
          LEFT JOIN colors c ON p.community_id = c.id
          WHERE p.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$post_result = $stmt->get_result();

if ($post_result->num_rows === 0) {
    echo "Post not found.";
    exit();
}

$post = $post_result->fetch_assoc();

// Fetch comments
$comments_query = "
    SELECT c.*, u.username, u.profile_picture
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.post_id = ?
    ORDER BY c.parent_comment_id ASC, c.created_at ASC";
$stmt = $conn->prepare($comments_query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$comments_result = $stmt->get_result();

// Group comments by parent_comment_id for nesting
$comments = [];
while ($comment = $comments_result->fetch_assoc()) {
    $parent_id = $comment['parent_comment_id'] ?? 0; // 0 for top-level comments
    $comments[$parent_id][] = $comment;
}

// Handle comment form submission

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
//     $comment_text = trim($_POST['comment_text']);
//     $parent_comment_id = isset($_POST['parent_comment_id']) ? intval($_POST['parent_comment_id']) : null;

//     if (!empty($comment_text)) {
//         $insert_comment_query = "
//             INSERT INTO comments (post_id, user_id, parent_comment_id, text)
//             VALUES (?, ?, ?, ?)";
//         $stmt = $conn->prepare($insert_comment_query);
//         $stmt->bind_param("iiis", $post_id, $user_id, $parent_comment_id, $comment_text);
//         $stmt->execute();

//         // Refresh page to show the new comment
//         header("Location: post.php?id=$post_id");
//         exit();
//     }
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
    $comment_text = trim($_POST['comment_text']);
    $parent_comment_id = isset($_POST['parent_comment_id']) && !empty($_POST['parent_comment_id']) 
        ? intval($_POST['parent_comment_id']) 
        : null;

    if (!empty($comment_text)) {
        $insert_comment_query = "
            INSERT INTO comments (post_id, user_id, parent_comment_id, text)
            VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_comment_query);
        $stmt->bind_param("iiis", $post_id, $user_id, $parent_comment_id, $comment_text);

        if (!$stmt->execute()) {
            error_log("Error inserting comment: " . $stmt->error);
        } else {
            header("Location: post.php?id=$post_id");
            exit();
        }
    } else {
        error_log("Comment text was empty.");
    }
}


// Fetch post votes

$post_votes_query = "
    SELECT SUM(value) as rating
    FROM vote 
    WHERE post_id = ? AND comment_id IS NULL";
$stmt = $conn->prepare($post_votes_query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$votes_result = $stmt->get_result();
$votes = $votes_result->fetch_assoc();

$title = $post['title'];

include('head.php');


function mdSimpleFormat($text) {
    // Replace bold (**text**)
    $text = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $text);

    // Replace italic (*text*)
    $text = preg_replace('/\*(.*?)\*/', '<i>$1</i>', $text);

    // Replace strikethrough (~~text~~)
    $text = preg_replace('/~~(.*?)~~/', '<s>$1</s>', $text);

    // Replace blockquotes (> text)
    $text = preg_replace('/^> (.+)$/m', '<blockquote>$1</blockquote>', $text);

    // Replace unordered lists (- item or * item)
    $text = preg_replace('/^[-*] (.*?)(\n|$)/m', '<li>$1</li>', $text);
    $text = preg_replace_callback(
        '/(<li>.*<\/li>)/s',
        function ($matches) {
            return '<ul>' . $matches[1] . '</ul>';
        },
        $text
    );

    // Wrap all remaining text blocks that are not blockquotes or lists with <p>
    $text = preg_replace_callback(
        '/^(?!<blockquote>|<li>|<\/?ul>|<\/?blockquote>)(.*?)(\n|$)/m',
        function ($matches) {
            $content = trim($matches[1]);
            return $content ? '<p>' . $content . '</p>' : '';
        },
        $text
    );

    // Remove extra line breaks between <ul> or <blockquote> elements
    $text = preg_replace('/<\/ul>\s*<ul>/', '', $text);
    $text = preg_replace('/<\/blockquote>\s*<blockquote>/', '<br>', $text);

    return trim($text);
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


<link rel="stylesheet" href="styles/post.css">
<div class="post">
    <div class="author-bar">
        <span class="author">
            <span class="author-icon">
                <span class="community-color" style="background-color: <?= $post['hex_color'] ?>"></span>
                <span class="author-pfp" style="background-image: url('uploads/<?= $post['user_id']?>/<?= $post['profile_picture'] ?>');"></span>
            </span>
            <span class="author-text">
                <span class="user-name">
                    <a href="user.php?id=<?= htmlspecialchars($post['user_id']) ?>"><?= htmlspecialchars($post['username']) ?></a>
                    <?= isset($post['community_id']) && $post['community_id'] != null ? ('in <a class="badge" href="community.php?id=' . $post['community_id'] .'" style="
                                background-color: ' . $post['hex_color'] . ';
                                color: ' . getContrastingTextColor($post['hex_color']) . ';
                            ">' . $post['hex_color'] . '</a>') : '' ?>
                </span>
                <span class="time-passed">posted on <?= htmlspecialchars($post['created_at']) ?></span>
            </span>
            <span>

            </span>
        </span>
    </div>
    <div class="post-content">
        <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>
        <div class="text"><?= mdSimpleFormat($post['text']) ?></div>
        <div class="toolbar">
            <div class="actions left">
                <?php if (isset($user_id)): ?>
                    <div class="vote-block" data-id="<?= $post['id']; ?>" data-type="post">
                        <button class="vote-btn upvote" data-value="1"><i class="ni-angle-up"></i></button>
                        <span class="vote-rating"><?= $votes['rating'] ?? "0"; ?></span>
                        <button class="vote-btn downvote" data-value="-1"><i class="ni-angle-down"></i></button>
                    </div>

                    <?php if ($user_id === $post['user_id']): ?>
                        <div>
                            <form method="POST" action="delete_post.php" style="display:inline;">
                                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                                <button type="submit" class="action destructive" onclick="return confirm('Are you sure you want to delete this post?')">Delete Post</button>
                            </form>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <p><a class="button action" href="login.php">Log in to vote</a>
                <?php endif; ?>

            </div>
            <div class="actions right">

                <!-- <span class="action"><i class="ni-bookmark"></i> Save</span>
                <span class="action"><i class="ni-connection"></i> Share</span> -->
            </div>
        </div>
    </div>
</div>


<h2 id="comments" class="hash-title"><a href="#comments">Comments</a></h2>

<?php if (empty($comments[0])): ?>
    <p>No comments yet. Be the first to comment!</p>
<?php else: ?>
    <?php
    function render_comments($comments, $parent_id = 0, $level = 0) {
        global $conn, $user_id, $post_id;
        if (isset($comments[$parent_id])) {
            echo "<div class='comment-section" . ($level > 0 ? ' nested' : '') . "'>";
            echo "<div class='first-comments'>";
            for ($i = 0, $n = count($comments[$parent_id]); $i < $n; $i++) {
                $comment = $comments[$parent_id][$i];
                if ($level < 3) {
                    // Fetch comment votes
                    $comment_votes_query = "
                    SELECT SUM(vote.value) as rating
                    FROM vote 
                    WHERE comment_id = ?";
                    $stmt = $conn->prepare($comment_votes_query);
                    $stmt->bind_param("i", $comment['id']);
                    $stmt->execute();
                    $vote_result = $stmt->get_result();
                    $vote = $vote_result->fetch_assoc();
                    if ($i == $n - 1) {
                        echo "</div><div class='last-comment'>";
                    }
                    // Render comment details
                    
                    echo "<div class='comment" . (isset($comments[$comment['id']]) && count($comments[$comment['id']]) > 0 ? " parent" : "") ."'>
                            <div class='commentator-title'>
                                <span class='commentator-pfp' style=\"background-image: url('uploads/" . $comment['user_id'] . "/" . $comment['profile_picture'] . "');\"></span>
                                <a href='user.php?id=" . $comment['user_id'] . "'>" . htmlspecialchars($comment['username']) . "</a>
                                <span class='time-passed'>" . time_passed($comment['created_at']) . "</span>
                            </div>
                            <div class='comment-body'>
                            <div class='text'>" . mdSimpleFormat($comment['text']) . "</div>
                            <div class='toolbar'>
                                <div class='secondary-actions left'>";
                    // Display voting buttons
                    if ($user_id) {
                        echo "
                        <div class='vote-block' data-id='" . $comment['id'] . "' data-type='comment'>
                            <button class='vote-btn upvote' data-value='1'><i class='ni-angle-up'></i></button>
                            <span class='vote-rating'>" . ($vote['rating']  ?? '0') . " </span>
                            <button class='vote-btn downvote' data-value='-1'><i class='ni-angle-down'></i></button>
                        </div>
                        <button class='action' onclick=\"replyToComment(" . $comment['id'] . ", '" . htmlspecialchars($comment['username']) . "')\"><i class='ni-comment'></i> Reply</button>";
            
                    } else {
                        echo "<a class='button action' href='login.php'>Log in to vote</a>";
                    }

                    if ($user_id === $comment['user_id']) {
                        echo "
                            <form method='POST' action='delete_comment.php' style='display:inline;'>
                                <input type='hidden' name='post_id' value=" . $post_id . ">
                                <input type='hidden' name='comment_id' value=" . $comment['id'] . ">
                                <button class='action destructive' type='submit' onclick='return confirm(\"Are you sure you want to delete this comment?\")'>Delete</button>
                            </form>
                        ";                  
                    }
                    echo "</div></div></div>";
                    
                    
                    
                    render_comments($comments, $comment['id'], $level + 1);
                    echo "</div>";
                } else {
                    echo "</div><div class='last-comment'>";
                    echo '<p class="comments-placeholder"><a href="post.php?id=' .$post_id .'&comment_id=' . $parent_id . '"><span class="plus-in-circle">+</span>See more</p>';
                    break;
                }
            }
            echo "</div></div>";
        }
    }
    render_comments($comments, isset($_GET['comment_id']) ? intval($_GET['comment_id']) : 0);
    ?>
<?php endif; ?>

<div class="bottom-action-bar">
<?php if ($user_id): ?>
    <form method="POST">
        <input type="hidden" id="parent_comment_id" name="parent_comment_id" value="">
        <div class="comment-input-container">
            <textarea class="auto-resizable" name="comment_text" placeholder="Write your comment here..." required></textarea>
            <button class="button button-slim filled" type="submit"><i class="ni-arrow-up"></i></button>
        </div>
        <div class="reply hidden" id="reply_bar">
            <button class="button button-inline light-filled" type="button" id="cancel_reply" onclick="cancelReply()"><i class="ni-x"></i> cancel</button>
            <label id="reply_label">replying to...</label>
        </div>
    </form>
<?php else: ?>
    <p><a href="login.php">Log in</a> to leave a comment</p>
<?php endif; ?>
</div>

<script>

    const parentInput = document.getElementById('parent_comment_id');
    const replyLabel = document.getElementById('reply_label');
    const replyBar = document.getElementById('reply_bar');

    function replyToComment(commentId, commentAuthor) {
        parentInput.value = commentId;
        replyLabel.textContent = "replying to " + commentAuthor;
        replyBar.classList.remove('hidden');
    }

    function cancelReply() {
        parentInput.value = '';
        replyBar.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Attach click event listeners to all voting buttons
        document.querySelectorAll('.vote-btn').forEach(button => {
            button.addEventListener('click', async function () {
                const voteBlock = this.closest('.vote-block');
                const id = voteBlock.dataset.id;
                const type = voteBlock.dataset.type;
                const value = this.dataset.value;

                try {
                    // Send GET request
                    const response = await fetch(`rate.php?id=${id}&type=${type}&value=${value}`);
                    const result = await response.json();

                    if (result.error) {
                        console.error('Error:', result.message || 'An error occurred.');
                        voteBlock.querySelector('.vote-rating').textContent = '-';
                    } else {
                        voteBlock.querySelector('.vote-rating').textContent = result.total_rating;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    voteBlock.querySelector('.vote-rating').textContent = '-';
                }
            });
        });
    });

</script>

<?php
include('foot.php');
?>