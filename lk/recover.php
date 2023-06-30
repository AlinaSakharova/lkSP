<?php
$title="Сменить пароль";
require 'header.php';
require "../config.php"; 
if (isset($_GET['hash']) && !empty($_GET['hash'])){	
	$hash = $_GET['hash'];
	
	$query = $dbconnect->query("SELECT iduser FROM sptest.user where hash = '$hash'");
	$id = mysqli_fetch_assoc($query);
	$_SESSION['iduser'] = $id;
	//var_dump($_SESSION);
	if(isset($id)){
		
		?>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-10 col-md-offset-1 text-start">
					<h2>Новый пароль</h2>
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
						<input type="password" class="form-control" name="new_password" id="new_password" data-smk-strongPass="medium"  title="" placeholder="Введите новый пароль" required><br>
						<input type="password" class="form-control" name="new_password2" id="new_password2" title="" placeholder="Повторите пароль" required><br>					
						</div>					
						<button class="btn btn-default" name="do_new_password" id="btnStrongPass" type="submit">Сменить пароль</button>
						
					</form>
					<script>
						document.addEventListener('DOMContentLoaded', function () {
							var pass1 = document.querySelector('#new_password'),
							pass2 = document.querySelector('#new_password2')
							pass1.addEventListener('input', function () {
							this.value != pass2.value ? pass2.setCustomValidity('Пароли не совпадают') : pass2.setCustomValidity('')
							})
							pass2.addEventListener('input', function (e) {
							this.value != pass1.value ? this.setCustomValidity('Пароли не совпадают') : this.setCustomValidity('')
							})
						})
	
					</script>
	
	
				</div>
			</div>
		</div>
	<?php			
	}
	else{
		$_SESSION['message'] = [
			'text' => 'Ошибка восстановления пароля',
			'status' => 'error'
			];
			header('index.php');
	}
}