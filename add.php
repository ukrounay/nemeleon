<!DOCTYPE html>
<html lang="ua">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Додавання статті</title>
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
        <input type="text" class="form-control" name="header" id="name" placeholder="//" required><label> Введіть заголовок статті</label><br>
        <input type="text" class="form-control" name="indeximg" id="indeximg" placeholder="//" required><label>Вкажіть посилання на картинку статті</label><br>
        <input type="text" class="form-control" name="urls" id="urls" placeholder="//" required><label> Введіть посилання та його тип, 'url;type|url;type'. Доступні типи: 'img', 'wiki'. По мірі додавання контенту я буду писати парсери для інших сайтів та типів даних.</label><br>
        <input type="text" class="form-control" name="tags" id="tags" placeholder="//" required><label> Введіть теги статті 'tag;tag'</label><br>
        <select name="theme" id="theme">
            <option value="Загальне" selected>Загальне</option>
            <option value="Політика">Політика</option>
            <option value="Технології">Технології</option>
            <option value="Події">Події</option>
            <option value="Блог">Блог</option>
        </select><label>Виберіть тематику статті</label><br>
        <button class="btn btn-success" name="add" type="submit">Додати статтю</button>
    </form>
    <br>
    <p><a href="index.php">Головна</a></p>
</body>
<?php
    require "libs/db.php"; 
    if (!empty($_POST)) {
        $data = $_POST;
        $article = R::dispense('articles');
        $article->header = $data['header'];
        $article->indeximg = $data['indeximg'];
        $article->urls = $data['urls'];
        $article->tags = $data['tags'];
        $article->theme = $data['theme'];
        R::store($article);
    }

?>