<!DOCTYPE html>
<html lang="ua">
<head>
    <?php $linkset = $_GET['articleid']; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "some article";?> • Nemeleon</title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <!-- <meta property="og:locale" content="ua_UA" /> -->
    <script src="https://kit.fontawesome.com/607590d81b.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body class="auto">
     

<header>
    <div id="article-image" class="shadow"></div>
    <h1 id="article-title"><?php echo "Звичайна пробна стаття, на якій я буду розробляти цей сайт (^^)";?></h1>
</header>
<div id="site">

    <div class="article">
        
        <div id="text">
            <div id="breadcrumps">
                <p><a href="index.php">Головна</a> / <a href="index.html">Статті</a> / <a href=""><?php echo "some article";?></a></p>
            </div>

            <ul id="inner-contentnav">
                <h3>Зміст статті</h3>
            </ul>
            <div id="parsed-text">
                <?php
                // $homepage = file_get_contents('https://www.ukrinform.ua/rubric-ato/3461823-rosijski-zagarbniki-vidstupili-poblizu-izuma.html');
                include('libs/simple_html_dom.php');
                $html = new simple_html_dom();
                $html->load_file('https://www.ukrinform.ua/rubric-ato/3461823-rosijski-zagarbniki-vidstupili-poblizu-izuma.html');
                $element = $html->find(".newsText"); 
                echo $element[0];
                ?>
            </div>
        </div>
    </div>
    <div id="navigation-right">
        <div id="dot-container"></div>
        <div id="sources"></div>
        <div id="related"></div>
    </div>
</div>
<footer>
    <div>
        
    </div>
    <div>

    </div>
    <div>

    </div>
    <div>

    </div>
</footer> 
<script src="js/main.js"></script>
</body></html>
