<?php
include('db.php'); // Database connection
include('session_start.php'); // Start session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to create a post.";
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

// Get the preselected community ID from the URL (if provided)
$selected_community_id = isset($_GET['community_id']) ? intval($_GET['community_id']) : null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $text = trim($_POST['text']);
    $community_id = isset($_POST['community_id']) && $_POST['community_id'] != "" ? intval($_POST['community_id']) : null;

    // Basic validation
    if (empty($title) || empty($text)) {
        $error = "Title and content are required.";
    } elseif ($community_id && $community_id != 0) {
        // Validate if the community exists
        $query = "SELECT 1 FROM colors WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $community_id);
        $stmt->execute();
        $community_result = $stmt->get_result();
        if ($community_result->num_rows === 0) {
            $error = "Selected community does not exist.";
        }
    }

    if (!isset($error)) {
        // Insert the post
        $query = "INSERT INTO posts (user_id, community_id, title, text) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiss", $user_id, $community_id, $title, $text);
        echo $community_id;
        if ($stmt->execute()) {
            // Redirect to the user's wall or the community page
            $redirect_url = $community_id ? "community.php?id=$community_id" : "user.php?id=$user_id";
            header("Location: $redirect_url");
            exit();
        } else {
            $error = "Failed to create post. Please try again.";
        }
    }
}

// Fetch available communities for dropdown
$query = "SELECT c.id, name
FROM colors AS c
LEFT JOIN membership AS m ON c.id = m.community_id
WHERE m.user_id = ?
ORDER BY name ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$communities_result = $stmt->get_result();
$communities = $communities_result->fetch_assoc();

$title = "Add new post";

include('head.php');

?>

<?php if (isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<link rel="stylesheet" href="styles/form.css">
<form class="standard-form" method="POST" action="">
    <h1>Add a New Post</h1>
    <div class="input-container">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
    </div>
    <div class="input-container">
        <label for="text">Content:</label>
        <textarea id="text" name="text" rows="5" cols="40" required></textarea>
    </div>
    <div class="input-container">
        <label for="community_id">Community (optional):</label>
        <select id="community_id" name="community_id">
            <option value="">-- Personal Post (No Community) --</option>
            <?php while ($community = $communities_result->fetch_assoc()): ?>
                <option value="<?= $community['id'] ?>" 
                    <?= $selected_community_id == $community['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($community['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="button-container">
        <button onclick="location.href = 'index.php'" class="button light-filled">Back to Home</button>
        <button class="button filled" type="submit">Publish post</button>
    </div>
</form>

<?php
include('foot.php');
?>
