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

</head>
<body class="auto">
<div class="logbox">
	<h2>Вхід на сайт</h2>
		<form action="login" method="post">
			<input type="text" class="form-control" name="login" id="login" placeholder="Логін" required><br>
			<input type="password" class="form-control" name="password" id="pass" placeholder="Пароль" required><br>
			<button class="btn btn-success" name="do_login" type="submit">Авторизуватися</button>
		</form>
		<br>
		<p><a href="signup.php">Зареєструватися </a></p>
		<p>Повернутися на <a href="index.php">головну</a></p>
</div>
</body>
</html>
