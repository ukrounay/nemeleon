<?php 
$title="Увійти"; 
require "libs/db.php"; 
$data = $_POST;
if(isset($data['do_login'])) { 
    $errors = array();
    $user = R::findOne('users', 'login = ?', array($data['login']));

    if($user) {
        if(password_verify($data['password'], $user->password)) {
            $_SESSION['logged_user'] = $user;
            header('Location: index');
        } else {
            $errors[] = 'Пароль неправильний!';
        }
    } else {$errors[] = 'Користувач з таким логіном не зареєстрований.';}
    if(!empty($errors)) {echo '<div class="quote bad"><p>' . array_shift($errors). '</p></div>';}
}
?>
<!DOCTYPE html>
<html lang="ua">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><!--?php echo $title; ?--></title>
<link rel="stylesheet" type="text/css" href="css/base.css">
<link rel="stylesheet" href="materials/fontawesome/css/all.css">
<meta property="og:locale" content="ua_UA" />
<!-- <script src="https://kit.fontawesome.com/607590d81b.js" crossorigin="anonymous"></script> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
<script src="libs/jquerylib.js"></script>
<meta name="theme-color" content="#202020">
<style>
    * {box-sizing: border-box;}
    .logbox {max-width: 400px; margin: auto; padding: 20px;}
    h2, input {display: block; width:100%;}
    .form-control {
        border-radius: 8px; 
        border: 2px solid #dddddd; 
        padding: 10px; 
        height: 40px;
    }
    button {
        margin: 10px 5px 10px 0px; 
        padding: 10px; 
        line-height: 1.0; 
        height: 40px; 
        border-radius: 8px; 
        background-color: white; 
        color: #202020; 
        border: 2px solid #dddddd; 
        font-weight: bold;
    }
    button:hover {border: 5px solid #10aaaa; padding: 7px;}
    .quote {
        display: grid;
        grid-template-columns: 1fr 20px; 
        padding: 20px; 
        margin: 10px 0px; 
        border-radius: 8px;
    }
    .quote.good {background-color: #10aaaa50; }
	.quote.bad {background-color: #ff646498; }
    .quote p {line-height: 1.0; margin: 0px;}
</style>
<meta name="theme-color" content="#202020">
</head>
<body>
<div class="logbox">
		<h2>Вхід на сайт</h2>
		<form action="login" method="post">
			<input type="text" class="form-control" name="login" id="login" placeholder="Логін" required><br>
			<input type="password" class="form-control" name="password" id="pass" placeholder="Пароль" required><br>
			<button class="btn btn-success" name="do_login" type="submit">Авторизуватися</button><br>
			<button onclick="go('signup')">Зареєструватись</button>
			<button onclick="go('index')">Головна</button>
		</form>
<script>
    function go(loc) {
        inputs = document.querySelectorAll('input');
        for (let i = 0; i < inputs.length; i++) {
            let elem = inputs[i];
            elem.value = '';
        }
        location.href = loc;
    }
</script>
</div>
</body>
</html>
