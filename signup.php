<?php 
	$title="Форма регістрації"; 
	require __DIR__ . '\libs\db.php'; 
	$data = $_POST;

if(isset($data['do_signup'])) {
	$errors = array();
	if(trim($data['login']) == '') {
		$errors[] = "Введіть логін!";
	}
	if(trim($data['email']) == '') {
		$errors[] = "Введіть е-mail";
	}
	if(trim($data['name']) == '') {
		$errors[] = "Введіть ім'я";
	}
	if(trim($data['family']) == '') {
		$errors[] = "Введіть прізвище";
	}
	if($data['password'] == '') {
		$errors[] = "Введіть пароль";
	}
	if($data['password_2'] != $data['password']) {
		$errors[] = "Повторний пароль не збігається!";
	}
	if(mb_strlen($data['login']) < 5 || mb_strlen($data['login']) > 90) {
	    $errors[] = "Неприпустима довжина логіну";
    }
    if (mb_strlen($data['name']) < 3 || mb_strlen($data['name']) > 50){
	    $errors[] = "Неприпустима довжина ім'я";
    }
    if (mb_strlen($data['family']) < 5 || mb_strlen($data['family']) > 50){
	    $errors[] = "Неприпустима довжина прізвища";
    }
    if (mb_strlen($data['password']) < 4 || mb_strlen($data['password']) > 10){
	    $errors[] = "Неприпустима довжина пароля (4 - 10 символів)";
    }
    if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $data['email'])) {
	    $errors[] = 'Неправильний е-mail';
    }
	if(R::count('users', "login = ?", array($data['login'])) > 0) {
		$errors[] = "Користувач с таким логіном існує";
	}
	if(R::count('users', "email = ?", array($data['email'])) > 0) {
		$errors[] = "Користувач с таким е-mail існує";
	}


	if(empty($errors)) {
		$user = R::dispense('users');
		$user->login = $data['login'];
		$user->email = $data['email'];
		$user->name = $data['name'];
		$user->family = $data['family'];
		$user->password = password_hash($data['password'], PASSWORD_DEFAULT);
		R::store($user);
        echo '<div  class="quote bad"><p>Акаунт зареєстрований. <a href="login">Перейти до авторизації</a>.<p></div>';
	} else {
		echo '<div class="quote bad"><p>' . array_shift($errors) . '<p></div>';
	}
}
?>
<!DOCTYPE html>
<html lang="ua">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" href="materials/fontawesome/css/all.css">
    <!-- <meta property="og:locale" content="ua_UA" /> -->
    <!-- <script src="https://kit.fontawesome.com/607590d81b.js" crossorigin="anonymous"></script> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
    <script src="libs/jquerylib.js"></script>
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
	<h2>Регістрація</h2>
	<form action="signup" method="post">				
		<input type="text" class="form-control" name="login" id="login" placeholder="логін"><br>
		<input type="email" class="form-control" name="email" id="email" placeholder="e-mail"><br>
		<input type="text" class="form-control" name="name" id="name" placeholder="ім'я" required><br>
		<input type="text" class="form-control" name="family" id="family" placeholder="прізвище" required><br>
		<input type="password" class="form-control" name="password" id="password" placeholder="пароль"><br>
		<input type="password" class="form-control" name="password_2" id="password_2" placeholder="повторіть пароль"><br>
		<button class="btn btn-success" name="do_signup" type="submit">Зареєструватись</button><br>
		<button onclick="go('signup')">Зареєструватись</button>
		<button onclick="go('index')">Головна</button>
	</form>
</div>
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
</body>
</html>