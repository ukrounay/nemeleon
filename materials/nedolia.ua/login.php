<?php 
$title="Увійти"; 
require "head.php"; 
require "libs/db.php"; 
$data = $_POST;
if(isset($data['do_login'])) { 
 $errors = array();
 $user = R::findOne('users', 'login = ?', array($data['login']));

 if($user) {

 	if(password_verify($data['password'], $user->password)) {

 		$_SESSION['logged_user'] = $user;
 		
                header('Location: index.php');

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
<div class="logbox">
	<h2>Вхід на сайт</h2>
		<form action="login.php" method="post">
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
