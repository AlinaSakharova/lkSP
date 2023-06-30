<?php
$title="Авторизация"; //
require 'header.php'; //

require "../config.php";
?>
<?php

	if (!empty($_SESSION['auth'])) { 
	    header('Location: index.php');
	 } else{ ?>
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-4 col-md-offset-4 text-center">
		<h2>Войти в личный кабинет</h2>
		<form action="do_login.php" method="post">
				<?
				if (isset($_SESSION['message'])) {
					if ($_SESSION['message']['status'] == 'success')
					{
						echo "<p class=\"text-success\">".$_SESSION['message']['text'];
					}
				else
					{
						echo "<p class=\"text-danger\">".$_SESSION['message']['text'];
					}
				
					unset($_SESSION['message']);
				}
			?>
			<input type="text" class="form-control" name="email" id="email" placeholder="Введите Email" required><br>
			<input type="password" class="form-control" name="password" id="pass" placeholder="Введите пароль" required><br>
			<button class="btn btn-success" name="do_login" type="submit">Войти</button>
		</form>
			<a href="reset_password.php">Забыли пароль?</a>
		</div>
	</div>
</div>
		<br>
		
		<p class="text-center">Вы можете вернуться <a href="index.php">на главную страницу</a>.</p>
		<?php } ?>
<?php require 'footer.php'; ?>