<div id="parsed-text">
<?php
// $homepage = file_get_contents('https://www.ukrinform.ua/rubric-ato/3461823-rosijski-zagarbniki-vidstupili-poblizu-izuma.html');
include('simple_html_dom.php');
$html = new simple_html_dom();
$html->load_file('https://www.ukrinform.ua/rubric-ato/3461823-rosijski-zagarbniki-vidstupili-poblizu-izuma.html');
$element = $html->find(".newsText"); 
echo $element[0];
?>
</div>


