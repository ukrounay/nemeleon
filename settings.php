<?php
include('db.php');
include('session_start.php');

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Fetch current user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']) ?: null;
    $about = trim($_POST['about']);
    $profile_picture = null;

    // Check if 'profile_picture' is sent as a base64 string in POST
    if (isset($_POST['profile_picture']) && !empty($_POST['profile_picture'])) {
        $base64_image = $_POST['profile_picture']; // Get base64 image string
    
        // Extract base64 data and decode
        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image, $matches)) {
            $file_type = $matches[1]; // e.g., png, jpeg
            $base64_image = substr($base64_image, strpos($base64_image, ',') + 1); // Remove the header
            $base64_image = base64_decode($base64_image);
    
            if ($base64_image === false) {
                die("Failed to decode base64 image.");
            }
    
            // Create the directory for the user if it doesn't exist
            $dir = "uploads/" . $user_id . "/";
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            } else {
                // Clean up old files
                $files = glob($dir . '*', GLOB_MARK);
                foreach ($files as $file) {
                    unlink($file); // Remove old profile pictures
                }
            }
    
            // Generate a unique filename and save
            $profile_picture = uniqid() . '.' . $file_type; // Unique filename with proper extension
            $file_path = $dir . $profile_picture;
    
            if (file_put_contents($file_path, $base64_image) === false) {
                die("Failed to save the image.");
            }
    
        } else {
            die("Invalid image data.");
        }
    }
    
    $update_query = "
        UPDATE users 
        SET username = ?, email = ?, about = ?, profile_picture = ? 
        WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $username, $email, $about, $profile_picture, $user_id);

    if ($stmt->execute()) {
        header("Location: user.php?id=$user_id");
        exit();
    } else {
        echo "Error updating profile.";
    }
}

$title = "Settings";

include('head.php');

?>

<link rel="stylesheet" href="styles/settings.css">
<link rel="stylesheet" href="styles/form.css">
<form class="standard-form" method="POST" enctype="multipart/form-data">
    <h1>Settings</h1>

    <div class="preview-pfp-container ">
        <div class="ratio c1-1">
            <img id="output-image" class="ratio-item" alt="Cropped Image" src="uploads/<?= $user['id']?>/<?= $user['profile_picture'] ?>">
        </div>
    </div>   

    <div class="input-container">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
    </div>

    <div class="input-container">
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
    </div>

    <div class="input-container">
        <label for="about">About:</label>
        <textarea name="about"><?= htmlspecialchars($user['about']) ?></textarea>
    </div>

    <div class="input-container">
        <label for="profile_picture">New profile picture:</label>
        <input type="file" id="imageInput" accept="image/*">
        <input type="text" name="profile_picture" id="imageInputFinal" class="hidden">
    </div>

    <div class="button-container">
        <button class="button light-filled" type="button" onclick="history.back()">Discard Changes</button>
        <button class="button filled" type="submit">Save Changes</button>
    </div>
</form>

<div id="crop-image-hover" class="hover hidden">
    <div class="image-container" id="imageContainer">
        <img id="image" src="" alt="Loaded Image" style="max-width: 100%; display: block;">
        <div class="clip-box" id="clipBox">
            <div class="resize-handle top-left"></div>
            <div class="resize-handle top-right"></div>
            <div class="resize-handle bottom-left"></div>
            <div class="resize-handle bottom-right"></div>
        </div>
    </div>

    <div class="button-container" id="controls" style="display: none;">
        <button class="ok-btn" id="okButton">OK</button>
        <button class="discard-btn" id="discardButton">Discard</button>
    </div>
</div>

<script>
    const hover = document.getElementById('crop-image-hover');
    const imageInput = document.getElementById('imageInput');
    const imageInputFinal = document.getElementById('imageInputFinal');
    const imageContainer = document.getElementById('imageContainer');
    const image = document.getElementById('image');
    const clipBox = document.getElementById('clipBox');
    const controls = document.getElementById('controls');
    const okButton = document.getElementById('okButton');
    const discardButton = document.getElementById('discardButton');
    const outputImage = document.getElementById('output-image');

    let startX, startY, initialWidth, initialHeight, initialLeft, initialTop;
    let isDragging = false, isResizing = false, activeHandle;

    // Handle image upload
    imageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                image.src = event.target.result;
                imageContainer.style.display = 'block';
                controls.style.display = 'flex';
                resetClipBox();
            };
            reader.readAsDataURL(file);
            hover.classList.remove('hidden');
        } else hover.classList.add('hidden');
    });

    function resetClipBox() {
        clipBox.style.left = '50px';
        clipBox.style.top = '50px';
        clipBox.style.width = '150px';
        clipBox.style.height = '150px';
    }

    function handleStart(e, resize = false) {
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;

        if (resize) {
            isResizing = true;
            activeHandle = e.target;
            startX = clientX;
            startY = clientY;
            initialWidth = parseInt(getComputedStyle(clipBox).width);
            initialHeight = parseInt(getComputedStyle(clipBox).height);
            initialLeft = parseInt(getComputedStyle(clipBox).left);
            initialTop = parseInt(getComputedStyle(clipBox).top);
        } else {
            isDragging = true;
            startX = clientX - clipBox.offsetLeft;
            startY = clientY - clipBox.offsetTop;
        }
    }

    function handleMove(e) {
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;

        if (isDragging) {
            let newLeft = clientX - startX;
            let newTop = clientY - startY;

            newLeft = Math.max(0, Math.min(image.width - clipBox.offsetWidth, newLeft));
            newTop = Math.max(0, Math.min(image.height - clipBox.offsetHeight, newTop));

            clipBox.style.left = newLeft + 'px';
            clipBox.style.top = newTop + 'px';
        }

        if (isResizing) {
            const dx = clientX - startX;
            const dy = clientY - startY;
            const delta = Math.abs(dx) < Math.abs(dy) ? dy : dx;

            if (activeHandle.classList.contains('top-left')) {
                clipBox.style.width = initialWidth - delta + 'px';
                clipBox.style.height = initialHeight - delta + 'px';
                clipBox.style.left = initialLeft + delta + 'px';
                clipBox.style.top = initialTop + delta + 'px';
            } else if (activeHandle.classList.contains('top-right')) {
                clipBox.style.width = initialWidth + delta + 'px';
                clipBox.style.height = initialHeight + delta + 'px';
                clipBox.style.top = initialTop - delta + 'px';
                // clipBox.style.left = initialTop - delta + 'px';
            } else if (activeHandle.classList.contains('bottom-left')) {
                clipBox.style.width = initialWidth - delta + 'px';
                clipBox.style.height = initialHeight - delta + 'px';
                clipBox.style.left = initialLeft + delta + 'px';
            } else if (activeHandle.classList.contains('bottom-right')) {
                clipBox.style.width = initialWidth + delta + 'px';
                clipBox.style.height = initialHeight + delta + 'px';
            }
        }
    }

    function handleEnd() {
        isDragging = false;
        isResizing = false;
    }

    clipBox.addEventListener('mousedown', (e) => handleStart(e, e.target.classList.contains('resize-handle')));
    clipBox.addEventListener('touchstart', (e) => handleStart(e, e.target.classList.contains('resize-handle')));
    document.addEventListener('mousemove', handleMove);
    document.addEventListener('touchmove', handleMove);
    document.addEventListener('mouseup', handleEnd);
    document.addEventListener('touchend', handleEnd);

    // OK Button
    okButton.addEventListener('click', () => {
        const scaleX = image.naturalWidth / image.width;
        const scaleY = image.naturalHeight / image.height;

        const cropX = parseInt(clipBox.style.left) * scaleX;
        const cropY = parseInt(clipBox.style.top) * scaleY;
        const cropWidth = clipBox.offsetWidth * scaleX;
        const cropHeight = clipBox.offsetHeight * scaleY;

        const canvas = document.createElement('canvas');
        canvas.width = cropWidth;
        canvas.height = cropHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(image, cropX, cropY, cropWidth, cropHeight, 0, 0, cropWidth, cropHeight);

        outputImage.src = canvas.toDataURL();
        imageInputFinal.value = canvas.toDataURL();
        imageInput.value = '';
        hover.classList.add('hidden');
    });

    // Discard Button
    discardButton.addEventListener('click', () => {
        hover.classList.add('hidden');
        imageInput.value = '';
        outputImage.src = '';
    });
</script>    

<?php 
include('foot.php'); 
?>