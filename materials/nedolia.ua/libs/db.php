<?php
require "rb-mysql.php";
R::setup( 'mysql:host=nedolia.ua; dbname=users','root', '' );
if(!R::testConnection()) die('<p style="text-align: center; font-family: sans-serif;">Error: No DB connection</p>');
session_start(); 
?>