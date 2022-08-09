<?php
    require "libs/db.php"; 
    if (!empty($_POST)) {
        $data = $_POST;

        $article = R::dispense('articles');
        $article->header = $data['header'];
        $article->indeximg = $data['indeximg'];
        foreach ($_POST['subhead'] as $row) { $subheads[] = $row; }
        foreach ($_POST['url'] as $row) { $urls[] = $row; }
        foreach ($_POST['type'] as $row) { $types[] = $row; }
        
        for ($i = 0; $i < count($urls); $i++) {
            $pieces[] = $subheads[$i];
            $pieces[] = $urls[$i];
            $pieces[] = $types[$i];
            $urls[$i] = implode('||', $pieces);
        }

        $urlset = implode('|||', $urls);
        $article->urls = $urlset;


        // $article->urls = $data['urls'];
        $article->tags = $data['tags'];
        $article->theme = $data['theme'];
        R::store($article);
        // echo '<p>додавання статей через цю сторінку тимчасово не працюэ, я задовбався писати цей код в час ночі</p>';
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
        min-height: 40px; 
        border-radius: 8px 8px 0px 8px !important; 
        font-family: sans-serif; 
        transition: 0s;
    }
    .form-control {
        border-radius: 8px; 
        border: 2px solid #dddddd; 
        padding: 10px; 
        height: 40px;
    }
    select {padding: 10px; line-height: 1.0;}
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
        box-sizing: border-box;
        position: relative;
        left: 50%;
        transform: translate(-50%,0%);
        display: grid;
        grid-template-columns: 1fr 20px; 
        padding: 20px; 
        margin: 10px 0px; 
        border-radius: 8px;
        overflow: hidden;
        transition: 1s;
        width: 100%;
    }
    .quote.good {background-color: #10aaaa50; }
    .quote p {line-height: 1.0; margin: 0px;}
    .link {display: grid; grid-template-columns: 1fr auto; gap: 10px;}
    #add-btn {margin: 0px;}
    table {border-collapse: collapse;}
    td, th {padding: 0px;}
    table .form-control {display: inline-block; margin-right: 5px;}
</style>
<meta name="theme-color" content="#202020">
<body>
<section>
    <!-- <div class="quote good">
        <p>Перед вставлянням тексту треба обовязково збільшити розмір текстового поля, бо при переповненні чомусь можливість змінити розмір пропадає.</p>
        <i class="fa-solid fa-xmark" onclick="quoteHide(0)"></i>
    </div> -->
    <h2>Додавання статей</h2>
    <form action="add" method="post">

        <label>Введіть заголовок статті</label>
        <input autofocus type="text" class="form-control" name="header" id="name" placeholder="//" required maxlength="60"><br>

        <label>Введіть анотацію/передмову/підводку для статті</label>
        <input type="text" class="form-control" name="prescript" id="prescript" placeholder="//"><br>


        <label>Вкажіть посилання на картинку статті</label>
        <textarea class="form-control" name="indeximg" id="indeximg" placeholder="//"></textarea><br>

        <label>Заповніть таблицю, щоб додати на сторінку контент. По мірі додавання контенту в майбутньому я буду писати парсери для конкретних сайтів та скрипти для відображення різноманітних типів даних.</label>
        <table id="content-table">
            <thead>
                <th>Заголовок розділу</th>
                <th>Контент розділу</th>
                <th>Тип контенту</th>
            </thead>
            <tr>
                <td><textarea class="form-control" name="subhead[0]" placeholder="//"></textarea></td>
                <td><textarea class="form-control" name="url[0]" placeholder="//"></textarea></td>
                <td>
                    <select name="type[0]" class="form-control">
                        <option value="text">текст/html</option>
                        <option value="img">картинка</option>
                        <option value="video">відео</option>
                        <option value="wiki">вікіпедія</option>
                        <option value="tweet">віджет твіта</option>
                    </select>
                </td>
            </tr>
        </table>
        <button onclick="addBefore()" type="button" id="add-btn"><i class="fa-solid fa-plus"></i></button>

        <label> Введіть теги статті через крапку з комою (tag;tag)</label>
        <textarea class="form-control" name="tags" id="tags" placeholder="//"></textarea><br>

        <label>Виберіть тематику статті</label>
        <select class="form-control" name="theme" id="theme" required>
            <option value="Загальне" selected>Загальне</option>
            <option value="Політика">Політика</option>
            <option value="Технології">Технології</option>
            <option value="Розваги">Розваги</option>
            <option value="Блог">Блог</option>
        </select><br>

        <button class="btn btn-success" name="add" type="submit">Додати статтю</button>

        <button onclick="location.href='index'" type="button">Головна</button>

        <button onclick="location.href='http://127.0.0.1/openserver/phpmyadmin/index.php?route=/database/structure&server=1&db=nemeleon'" type="button">База даних</button>

    </form>
</section>
<script>
    function quoteHide(num) {document.getElementsByClassName("quote")[num].style.display = 'none';}
    function addBefore(){
        var tr = document.createElement("tr");
        tr.innerHTML = '<td><textarea class="form-control" name="subhead[0]" placeholder="//" required></textarea></td><td><textarea class="form-control" name="url[0]" placeholder="//" required></textarea></td><td><select name="type[0]" class="form-control" required><option value="text">текст/html</option><option value="img">картинка</option><option value="video">відео</option><option value="wiki">вікіпедія</option><option value="tweet">віджет твіта</option></select></td>';
        document.getElementById('content-table').appendChild(tr);
    }
</script>
</body>
</html>