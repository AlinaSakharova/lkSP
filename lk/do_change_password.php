<?php
//изменение пароля
require "boot.php";
require('../config.php');
$data = $_POST;
$id = $_SESSION['logged_user']['iduser'];
$query = $dbconnect->query("SELECT password FROM sptest.user WHERE iduser = '$id'");
$user = mysqli_fetch_assoc($query);

if(isset($data['do_change_password'])) {

    if (mb_strlen($data['new_password']) < 6 || mb_strlen($data['new_password']) > 15){

		$_SESSION['message'] = [
		'text' => 'Пароль должен быть от 6 до 15 символов',
		'status' => 'error'
		];
		header('Location: change_password.php');
	}

	
	if ($_SESSION['message']['status'] != 'error'){

		if (password_verify($data['old_password'], $user['password']) ) 
		{
			
			$password = $dbconnect->real_escape_string($_POST["new_password"]);
			$password = password_hash($data['new_password'], PASSWORD_DEFAULT);
			$sql = $dbconnect->query("UPDATE sptest.user SET password = '$password' WHERE iduser = '$id'");
			
			$dbconnect->close();
			
			$_SESSION['message'] = [
			'text' => 'Пароль изменен',
			'status' => 'success'
			];

            $_SESSION['auth'] = null;
            session_destroy();
            header('Location: index.php');
	
		} 
		else 
		{
			$_SESSION['message'] = [
			'text' => 'Текущий пароль введен неверно',
			'status' => 'error'
			];
			header('Location: change_password.php');
		}
	}
	else
	{
		header('Location: change_password.php');
	}

}



