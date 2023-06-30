<?php 
require "boot.php";
require_once(realpath('../config.php'));

$error = false;
$data = $_POST;
function send_mail($email)
{
	$hash = md5($email . time());
	$to      = $email;
	$subject = 'Восстановление пароля';
	$message = '
                <html>
                <head>
                <title>Подтвердите Email</title>
                </head>
                <body>
                <p>Чтобы восстановить пароль, перейдите по <a href="https://sp-test.susu.ru/lk/recover.php?hash='.$hash.'">ссылке</a></p>
                </body>
                </html>
                ';
	$headers = 'From: sp.susu.ru' . "\r\n" .
    		   'Reply-To: sp.susu.ru' . "\r\n" .
    	       'X-Mailer: PHP/' . phpversion().
			   "Content-type: text/html; charset=utf-8\r\n";

	$send = mail($to, $subject, $message, $headers);
   	if($send){
    		echo "message send";
			echo $hash;
    	}
	else{
		echo "error!";
	}
	return $hash;
}

if(isset($data['do_reset_password']))
{
	$email = trim($_REQUEST['email']);
	///var_dump($email);
	$query = $dbconnect->query("SELECT email FROM sptest.user");
	$user = mysqli_fetch_assoc($query);
	
	if($user) {
		
 		
		$hash = send_mail($email);
		//var_dump($hash);
		$sql = $dbconnect->query("UPDATE sptest.user SET hash = '$hash' WHERE email = '$email'");
		$error = true;
		$_SESSION['message'] = [
			'text' => 'Письмо со ссылкой на восстановление пароля отправлено на email '.$email,
			'status' => 'success'
			];
			header('Location: login.php');
		
		
 	} 
	else {
			$error = true;
			$_SESSION['message'] = [
			'text' => 'Пользователь с таким email не найден',
			'status' => 'error'
			];
			header('Location: login.php');
 	}
}

if (isset($data['do_new_password'])){
	if (mb_strlen($data['new_password']) < 6 || mb_strlen($data['new_password']) > 15){
		//var_dump('symp');
		$error = true;
		$_SESSION['message'] = [
		'text' => 'Пароль должен быть от 6 до 15 символов',
		'status' => 'error'
		];
		header("Location: recover.php?hash='$hash'");
	}
	$password = $dbconnect->real_escape_string($_POST["new_password"]);
	
	if ($error == false){
		//var_dump($_SESSION);
		$id = $_SESSION['iduser'];
		$password = $dbconnect->real_escape_string($_POST["new_password"]);
		
		$password = password_hash($data['new_password'], PASSWORD_DEFAULT);
		
		$sql = $dbconnect->query("UPDATE sptest.user SET password = '$password' WHERE iduser = '$id'");
		//var_dump($sql);
		$dbconnect->close();
		
		$_SESSION['message'] = [
		'text' => 'Пароль изменен',
		'status' => 'success'
		];
		header('Location: login.php');
	}
	else
	{
		header("Location: recover.php?hash='$hash'");
	}

	
}