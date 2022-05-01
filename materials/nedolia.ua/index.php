<?php $title="Nedolia"; require 'head.php'; require "libs\db.php"; ?>
<?php if(isset($_SESSION['logged_user'])) : ?>
<?php require 'indexlogged.php'; ?>
<?php else : ?>
<?php require 'indexguest.php'; ?>
<?php endif; ?>
<?php require 'foot.php'; ?>