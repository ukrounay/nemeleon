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
    h2, input, label, textarea {display: block; width:100%;}
    label {line-height: 1.0; margin: 10px 0px;}
    textarea {
        max-width: 600px; 
        min-width: 600px; 
        min-height: 40px; 
        border-radius: 20px 20px 0px 20px !important; 
        font-family: sans-serif; 
        transition: 0s;
    }
    .form-control {
        border-radius: 20px; 
        border: 1px solid #202020; 
        padding:10px; 
        height:40px;
    }
    select {margin-bottom: 10px; padding: 10px; line-height: 1.0;}
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
    .quote p {line-height: 1.0; margin: 0px;}
</style>
<meta name="theme-color" content="#202020">
<body>
<section>
    <h2>Додавання статей</h2>
    <form action="add" method="post">
        <label> Введіть заголовок статті</label>
        <input type="text" class="form-control" name="header" id="name" placeholder="//" required><br>
        <label>Вкажіть посилання на картинку статті</label>
        <textarea class="form-control" name="indeximg" id="indeximg" placeholder="//" required></textarea><br>
        <label> Введіть посилання та його тип, 'url;type|url;type'. Доступні типи: 'img', 'wiki'. По мірі додавання контенту я буду писати парсери для інших сайтів та типів даних.</label>
        <textarea class="form-control" name="urls" id="urls" placeholder="//" required></textarea><br>
        <label> Введіть теги статті 'tag;tag'</label>
        <textarea class="form-control" name="tags" id="tags" placeholder="//" required></textarea><br>
        <label>Виберіть тематику статті</label>
        <select class="form-control" name="theme" id="theme">
            <option value="Загальне" selected>Загальне</option>
            <option value="Політика">Політика</option>
            <option value="Технології">Технології</option>
            <option value="Події">Події</option>
            <option value="Блог">Блог</option>
        </select><br>
        <button class="btn btn-success" name="add" type="submit">Додати статтю</button>
        <button onclick="location.href='index'">Головна</button>
        <button onclick="location.href='http://127.0.0.1/openserver/phpmyadmin/index.php?route=/database/structure&server=1&db=nemeleon'">База даних</button>

    </form>
    <div class="quote good">
        <p>Перед вставлянням тексту треба обовязково збільшити розмір текстового поля, бо при переповненні чомусь можливість змінити розмір пропадає.</p>
        <i class="fa-solid fa-xmark" onclick="quoteHide(0)"></i>
    </div>
</section>
<script>
    function quoteHide(num) {document.getElementsByClassName("quote")[num].style.opacity = '0';}
</script>
</body>
</html>