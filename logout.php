<?php 
require __DIR__ . '\head.php'; 
require __DIR__ . '\libs\db.php'; 
unset($_SESSION['logged_user']);
header('Location: index');
require __DIR__ . '\foot.php'; 
?>