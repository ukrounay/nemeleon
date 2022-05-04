<!DOCTYPE html>
<html lang="ua">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" href="materials/fontawesome/css/all.css">
    <meta property="og:locale" content="ua_UA" />
    <!-- <script src="https://kit.fontawesome.com/607590d81b.js" crossorigin="anonymous"></script> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
    <script src="libs/jquerylib.js"></script>
    <meta name="theme-color" content="#202020">
</head>
<body class="auto">

<?php 
	$title="Форма регистрації"; 
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

    // проверка на правильность написания Email
    if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $data['email'])) {

	    $errors[] = 'Неправильний е-mail';
    
    }

	// Проверка на уникальность логина
	if(R::count('users', "login = ?", array($data['login'])) > 0) {

		$errors[] = "Користувач с таким логіном існує";
	}

	// Проверка на уникальность email

	if(R::count('users', "email = ?", array($data['email'])) > 0) {

		$errors[] = "Користувач с таким е-mail існує";
	}


	if(empty($errors)) {

		// Все проверено, регистрируем
		// Создаем таблицу users
		$user = R::dispense('users');

                // добавляем в таблицу записи
		$user->login = $data['login'];
		$user->email = $data['email'];
		$user->name = $data['name'];
		$user->family = $data['family'];

		// Хешируем пароль
		$user->password = password_hash($data['password'], PASSWORD_DEFAULT);

		// Сохраняем таблицу
		R::store($user);
        echo '<div style="color: green; ">Вы успешно зарегистрированы! Можно <a href="login.php">авторизоваться</a>.</div><hr>';

	} else {
                // array_shift() извлекает первое значение массива array и возвращает его, сокращая размер array на один элемент. 
		echo '<div style="color: red; ">' . array_shift($errors). '</div><hr>';
	}
}
?>

<div class="logbox">
		<h2>Форма регістрації</h2>
		<form action="signup.php" method="post">				
			<input type="text" class="form-control" name="login" id="login" placeholder="логін"><br>
			<input type="email" class="form-control" name="email" id="email" placeholder="e-mail"><br>
			<input type="text" class="form-control" name="name" id="name" placeholder="ім'я" required><br>
			<input type="text" class="form-control" name="family" id="family" placeholder="прізвище" required><br>
			<input type="password" class="form-control" name="password" id="password" placeholder="пароль"><br>
			<input type="password" class="form-control" name="password_2" id="password_2" placeholder="повторіть пароль"><br>
			<button class="btn btn-success" name="do_signup" type="submit">Зареєструватись</button>
		</form>
		<br>
		<p><a href="login.php">Увійти</a></p>
		<p>Повернутися на <a href="index.php">головну</a></p>
	</div>
</body>
</html>