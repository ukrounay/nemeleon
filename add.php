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
<style>
    * {box-sizing: border-box;}
    section {max-width: 600px; margin: auto;}
    h2, input, label {display: block; width:100%;}
    label {line-height: 1.0; margin: 10px 0px;}
    .form-control {border-radius: 20px; border: 1px solid #202020; padding:10px; height:40px;}
    select {margin-bottom: 10px; padding: 10px; line-height: 1.0;}
    button {margin: 10px 5px 10px 0px; padding: 10px; line-height: 1.0; height: 40px; border-radius: 20px; background-color: white; color: #202020; border: 1px solid #202020; font-weight: bold;}
    button:hover {background-color: #202020; color: #f4f4f4;}
</style>
<meta name="theme-color" content="#202020">
<body>
<section>
    <h2>Додавання статей</h2>
    <form action="add" method="post">
        <label> Введіть заголовок статті</label>
        <input type="text" class="form-control" name="header" id="name" placeholder="//" required><br>
        <label>Вкажіть посилання на картинку статті</label>
        <input type="text" class="form-control" name="indeximg" id="indeximg" placeholder="//" required><br>
        <label> Введіть посилання та його тип, 'url;type|url;type'. Доступні типи: 'img', 'wiki'. По мірі додавання контенту я буду писати парсери для інших сайтів та типів даних.</label>
        <input type="text" class="form-control" name="urls" id="urls" placeholder="//" required><br>
        <label> Введіть теги статті 'tag;tag'</label>
        <input type="text" class="form-control" name="tags" id="tags" placeholder="//" required><br>
        <label>Виберіть тематику статті</label>
        <select class="form-control" name="theme" id="theme">
            <option value="Загальне" selected>Загальне</option>
            <option value="Політика">Політика</option>
            <option value="Технології">Технології</option>
            <option value="Події">Події</option>
            <option value="Блог">Блог</option>
        </select><br>
        <button class="btn btn-success" name="add" type="submit">Додати статтю</button><button onclick="location.href='index'">Головна</button>
    </form>
    
</section>
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