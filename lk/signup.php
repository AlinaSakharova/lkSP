<?
$title="Добавить пользователя"; // название формы
require 'header.php'; // подключаем шапку проекта
require "../config.php"; // подключаем файл для соединения с БД


	if ($_SESSION['logged_user']['role'] == 'moderator') { ?>

<div class="container ">
		<div class="row justify-content-center">
			<div class="col-md-4 col-md-offset-4 text-center">
	   <!-- Форма регистрации -->
		<h2>Добавить пользователя</h2>
		<form action="do_signup.php" method="post">
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
			<input type="email" class="form-control" name="email" id="email" placeholder="Введите Email"><br>
			<input type="text" class="form-control" name="name" id="name" placeholder="Введите имя"><br>
			<div class="radio">
			<p class="form">Выберите категорию пользователя</p>
  			<label>
   				 <input type="radio" class="form" name="role" value="user" checked>
    				Пользователь
  			</label>
			</div>
			<div class="radio">
  			<label>
    				<input type="radio" class="form" name="role" value="moderator">
    				Модератор
  			</label>
			
			</div>
			<!--input type="password" class="form-control" name="password" id="password" placeholder="чЧЕДЙФЕ РБТПМШ"><br-->
			<!--input type="password" class="form-control" name="password_2" id="password_2" placeholder="рПЧФПТЙФЕ РБТПМШ"><br-->
			<button class="btn btn-success" name="do_signup" type="submit">Зарегистрировать</button>
		</form>
		<br>
		<p>Вы можете вернуться на <a href="index.php">главную страницу</a>.</p>
			</div>
		</div>
	</div>
	<?php } 
		else{ 
			header('Location: index.php'); 
       		} ?>
<?php require 'footer.php'; ?> <!-- Подключаем подвал проекта -->