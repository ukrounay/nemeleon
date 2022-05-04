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
<body>
    <h2>Додавання статей</h2>
    <form action="add" method="post">
        <input type="text" class="form-control" name="header" id="name" placeholder="Тема" required><br>
        <label>Введіть посиання та його тип, 'url;type|url;type...'. Доступні типи: 'html', 'img', 'post/tw', 'post/fb'</label><br>
        <input type="text" class="form-control" name="urls" id="urls" placeholder="//" required><br>
        <button class="btn btn-success" name="add" type="submit">Додати статтю</button>
    </form>
    <br>
    <p><a href="index.php">Головна</a></p>
</body>
<?php
    require "libs/db.php"; 
    $data = $_POST;
	$article = R::dispense('articles');
	$article->header = $data['header'];
	$article->urls = $data['urls'];
	R::store($article);
?>