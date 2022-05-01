<?php 
require __DIR__ . '\parts\header.php'; // подключаем шапку проекта
require __DIR__ . '\db.php'; // подключаем файл для соединения с БД

// Производим выход пользователя
unset($_SESSION['logged_user']);

// Редирект на главную страницу
header('Location: index.php');

require __DIR__ . '\parts\footer.php'; // Подключаем подвал проекта
?>