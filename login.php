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
        border-radius: 20px; 
        border: 1px solid #202020; 
        padding:10px; 
        height:40px;
    }
    button {
        margin: 10px 5px 10px 0px; 
        padding: 10px; 
        line-height: 1.0; 
        height: 40px; 
        border-radius: 20px; 
        background-color: white; 
        color: #202020; 
        border: 1px solid #202020; 
        font-weight: bold;
    }
    button:hover {background-color: #202020; color: #f4f4f4;}
    .quote {
        display: grid;
        grid-template-columns: 1fr 20px; 
        padding: 20px; 
        margin: 10px 0px; 
        border-radius: 20px;
    }
    .quote.good {background-color: #00ff9d98; }
	.quote.bad {background-color: #ff646498; }
    .quote p {line-height: 1.0; margin: 0px;}
</style>
<meta name="theme-color" content="#202020">
</head>
<body class="auto">
<div class="logbox">
		<h2>Вхід на сайт</h2>
		<form action="login" method="post">
			<input type="text" class="form-control" name="login" id="login" placeholder="Логін" required><br>
			<input type="password" class="form-control" name="password" id="pass" placeholder="Пароль" required><br>
			<button class="btn btn-success" name="do_login" type="submit">Авторизуватися</button><br>
			<button class="btn btn-success" name="do_signup" type="submit">Зареєструватись</button>
			<button onclick="location.href='index'">Головна</button>
		</form>
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

 } else {
 	$errors[] = 'Користувач з таким логіном не зареєстрований.';
 }

if(!empty($errors)) {

		echo '<div style="text-align: center; padding: 20px; color: red; "><p>' . array_shift($errors). '</p></div>';
	}
}
?>
</div>
</body>
</html>
