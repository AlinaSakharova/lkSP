<?php
require "boot.php";
require_once(realpath('../config.php'));

$data = $_POST;

if(isset($data['do_login'])) { 

	$email = trim($_REQUEST['email']);
	$query = $dbconnect->query("SELECT * FROM sptest.user WHERE email = '$email'");
	$user = mysqli_fetch_assoc($query);
	
	if($user) {
	
 		if ( password_verify($data['password'], $user['password']) === true) 
		{
 			$_SESSION['logged_user'] = $user;
			$_SESSION['auth'] = true;
        	header('Location: index.php');

 		} 
		else 
		{
			var_dump($user['password'], $data['password'],password_verify($user['password'], $data['password']), $_POST );
    		$_SESSION['message'] = [
			'text' => 'Неверный пароль',
			'status' => 'error'
			];
			//header('Location: login.php');
 		}

 	} 
	else {
			$_SESSION['message'] = [
			'text' => 'Пользователь с таким email не найден',
			'status' => 'error'
			];
			header('Location: login.php');
 	}

}
