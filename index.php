<?php $title="welcome • Nemeleon"; require 'head.php'; require "libs\db.php"; ?>
<?php if(isset($_SESSION['logged_user'])) : ?>
<?php require 'indexpage.php'; ?>
<?php else : ?>
<?php require 'indexpage.php'; ?>
<?php endif; ?>
<?php require 'foot.php'; ?>