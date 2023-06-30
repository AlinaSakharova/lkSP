<?php
require "boot.php";
require "../config.php";

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="css/lk.css">
    <link rel="stylesheet" type="text/css" href="/Bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/Bootstrap/js/bootstrap.js">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="/scripts/ckeditor/ckeditor.js"></script>
	<script src="/scripts/ckeditor/adapters/jquery.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <script src="js/moment-with-locales.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	
    <meta content="text/html; charset=koi8-r">
</head>
<body>
<header>
    <div class="container">
        <div class="row ">
            <div class="col-md-6 text-start">
                <a href="http://sp.susu.ru/"><img class="logo" src="/_images/logo-sp.png" alt="Кафедра СП ЮУрГУ"></a>
            </div>
            <div class="col-md-6  text-end">
                <h1 class="susu">Южно-Уральский государственный университет(НИУ)<br>Высшая школа электроники и компьютерных наук<br>Кафедра системного программирования</h1>

            </div>
        </div>
    </div>
    <?php

    if (!empty($_SESSION['auth'])) { ?>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li><a href="add_new.php">Добавить новость</a></li>
	<?php if ($_SESSION['logged_user']['role'] == 'moderator') {?>
			<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Модерация<span class="caret"></span></a>
            <ul class="dropdown-menu">
				<li><a href="moderation.php">Модерация новостей</a></li>
			</ul>
			</li>
			<li><a href="signup.php">Добавить пользователя</a></li>
	<?php } ?>
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Мои новости<span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="rough.php">Черновики</a></li>
					<li><a href="send_moderation.php">Отправлены на проверку</a></li>
					<li><a href="revision.php">Отправлено на доработку</a></li>
                    <li><a href="publish.php">Опубликованы</a></li>
                    <?php if ($_SESSION['logged_user']['role']=='moderator'){?>
                        <li><a href="archive.php">Архив</a></li>
                    <?}?>
				</ul>
			</li>
	    <li><a href="change_password.php">Сменить пароль</a</li>
	    <li><a href="logout.php">Выйти</a></li>
        </ul>
            </div>
        </div>
    </nav>
    <?php } ?>
</header>