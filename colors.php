<?php 
    include('db.php');
    include('session_start.php');

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

    $title = "Colors";

    include('head.php');

    $query = "SELECT * FROM colors";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $colors = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

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
<style>
    header {
        background-color: var(--cl-bg-containers);
        padding: var(--p-cont);
        border-radius: var(--br-cont);
        display: flex;
        flex-direction: column;
        gap: var(--p);
        margin-bottom: var(--p-cont);
    }
    header h1 {
        color: var(--cl-accent-dark);
        font-size: 2.6rem;
    }
    
    header p {
        font-size: 1.2rem;
        line-height: 1.4;
    }
    .colors {
        display: flex;
        flex-wrap: wrap;
        gap: var(--p-s);
    }

    .badge {
        display: inline-block;
        padding: var(--p-s);
        text-decoration: none;
        outline: 2px solid transparent;
        background-color: var(--color);
        color: var(--contrast-color);
    }
    .badge:hover {
        outline-color: var(--contrast-color);;
    }
</style>

<header>
    <h1>Colors</h1>
    <p>Palette is limited, but not your mind</p>
    <p><a class="button filled button-cta" href="create_community.php">Add new color <i class="ni-arrow-right"></i></a></p>
</header>

<h2 class="hash-title"><a href="#explore">Explore</a></h2>
<div class="colors">
    <?php foreach ($colors as $color): ?>
        <a class="badge" href="community.php?id=<?= $color['id'] ?>" style="
            --color: <?= $color['hex_color'] ?>;
            --contrast-color: <?= getContrastingTextColor($color['hex_color']) ?>;
        "><?= $color['hex_color'] ?></a>
    <?php endforeach; ?>
</div>
