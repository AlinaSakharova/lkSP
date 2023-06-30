<?php
$title="Забыли пароль";
require 'header.php';
require "../config.php"; 
//var_dump($_GET);
?>

	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-4 col-md-offset-4 text-center">
				<h2>Сменить пароль</h2>
				<form class="form" id="formStrongPass" action="do_reset_password.php" method="post">
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
					<div class="form-group">
					<input type="email" class="form-control" name="email"  id="email" placeholder="Введите email" data-smk-msg="Обязательное поле"required><br>
										
					</div>					
					<button class="btn btn-default" name="do_reset_password" id="btnStrongPass" type="submit">Восстановить пароль</button>
				</form>
			</div>
		</div>
	</div>
	

<?php
	
 require 'footer.php'; ?> 