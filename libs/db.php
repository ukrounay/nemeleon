<?php
require "rb-mysql.php";
R::setup( 'mysql:host=nemeleon.inf; dbname=nemeleon','root', '' );
if(!R::testConnection()) die('<p style="text-align: center; font-family: sans-serif;">Error: No DB connection</p>');
session_start();
?>
