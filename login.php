<?php
include('db.php');
include('session_start.php');

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    header("Location: user.php?id=$user_id");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if($_POST["action"] == "login") {
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: index.php");
                exit();
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "User not found.";
        }
    } 
    
    if($_POST["action"] == "register") {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
            $query = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $username, $password);
        
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
}

include('head.php');
?>
<link rel="stylesheet" href="styles/form.css">
<form class="standard-form" method="POST" action="">
    <h1>Lets jump into ₍ᐢ. .ᐢ₎</h1>
    <p>Name and password are all we need</p>
    <div class="input-container">
        <label for="username">Username</label>
        <input type="text" name="username" placeholder="type..." required>
    </div>
    <div class="input-container">
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="type..." required>
    </div>
    <div class="button-container">
        <button class="button filled" type="submit" name="action" value="login">Login</button>
        <button class="button light-filled" type="submit" name="action" value="register">Register</button>
    </div>
</form>

<?php 
include('foot.php');
?>