<?php 
require "boot.php";
$title="Главная"; // ???????? ?????
require 'header.php'; // ?????????? ????? ???????
require "../config.php"; // ?????????? ???? ??? ?????????? ? ??

?>

<?php

if (empty($_SESSION['auth'])) { ?>



<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-md-offset-3 text-center">
			<div class="section-hero">
				<h1 class="headerindex"></h1>
					<span class="description">Добро пожаловать в личный кабинет сотрудника кафедры системного программирования ЮУрГУ. Для регистрации обратитесь к модератору.</span>&nbsp;
        
					&nbsp;<span class="description">Чтобы войти в личный кабинет, пройдите по</span>&nbsp;
					<a href="login.php">ссылке</a>.
<?php } ?>
			</div>
        </div>
    </div>
</div>


<?php require 'footer.php'; ?> 