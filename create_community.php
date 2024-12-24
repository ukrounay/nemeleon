<?php
include('db.php'); // Database connection
include('session_start.php'); // Start session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to create a community.";
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $hex_color = trim($_POST['hex_color']);
    $description = trim($_POST['description']);

    // Basic validation
    if (empty($name) || empty($hex_color)) {
        $error = "Name and Hex Color are required.";
    } elseif (!preg_match('/^#[0-9A-Fa-f]{6}$/', $hex_color)) {
        $error = "Hex Color must be in the format #RRGGBB.";
    } else {
        // Insert into database
        $query = "INSERT INTO colors (name, hex_color, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $name, $hex_color, $description);

        if ($stmt->execute()) {
            // Redirect to the new community page
            $community_id = $stmt->insert_id;
            header("Location: community.php?id=$community_id");
            exit();
        } else {
            $error = "Failed to create community. Please try again.";
        }
    }
}
$title = "Create Community";
include('head.php');
?>

<?php if (isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<link rel="stylesheet" href="styles/form.css">
<form class="standard-form" method="POST" action="">
    <h1>Create a New Community</h1>
    <div class="input-container">
        <label for="name">Community Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div class="input-container"> 
        <label for="hex_color">Hex Color:</label>
        <input class="hex-input" type="text" id="hex_color" name="hex_color" value="#" placeholder="rrggbb" min="7" max="7" required>
        <!-- <input type="color" name="hex_color" id="hex_color"> -->
    </div>
    <div class="input-container">
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="5"></textarea>
    </div>
    <div class="button-container">
        <button onclick="location.href = 'index.php'" class="button light-filled">Back to Home</button>
        <button class="button filled" type="submit">Create Community</button>
    </div>
</form>

<?php
include('foot.php');
?>
