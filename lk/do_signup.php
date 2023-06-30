<?
require('boot.php');
require('../config.php');
// Создаем переменную для сбора данных от пользователя по методу POST
$data = $_POST;

$query = $dbconnect->query("SELECT email FROM sptest.user");
$user = mysqli_fetch_assoc($query);
$emails = array();
while ($row=mysqli_fetch_assoc($query))
{
	$emails[] = $row['email'];
}
//var_dump(in_array($data['email'], $emails));
function send_mail($email, $password)
{
	$to      = $email;
	$subject = 'Регистрация на sp.susu.ru';
	$message = 'Вы зарегистрированы в личном кабинете sp.susu.ru. Ваш email:'.$email.' Ваш пароль: '.$password.' Зайдите в личный кабинет и поменяйте пароль';
	$headers = 'From: sp.susu.ru' . "\r\n" .
    		   'Reply-To: sp.susu.ru' . "\r\n" .
    	           'X-Mailer: PHP/' . phpversion();

	$send = mail($to, $subject, $message, $headers);
   	if($send){
			
    		echo "message send";
			
    	}
	else{
		echo "error!";
	}
}

function gen_password($length = 6)
{				
	$chars = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP'; 
	$size = strlen($chars) - 1; 
	$password = ''; 
	while($length--) {
		$password .= $chars[random_int(0, $size)]; 
	}
	return $password;
}
 

if (!function_exists('random_int')) {
	function random_int($min, $max) {
		if (!function_exists('mcrypt_create_iv')) {
			trigger_error('mcrypt must be loaded for random_int to work', E_USER_WARNING);
			return null;
		}
		if (!is_int($min) || !is_int($max)) {
			trigger_error('$min and $max must be integer values', E_USER_NOTICE);
			$min = (int)$min;
			$max = (int)$max;
		}
		if ($min > $max) {
			trigger_error('$max can\'t be lesser than $min', E_USER_WARNING);
			return null;
		}
		$range = $counter = $max - $min;
		$bits = 1;
		while ($counter >>= 1) {
			++$bits;
		}
		$bytes = (int)max(ceil($bits/8), 1);
		$bitmask = pow(2, $bits) - 1;
		if ($bitmask >= PHP_INT_MAX) {
			$bitmask = PHP_INT_MAX;
		}
 
		do {
			$result = hexdec(bin2hex(mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM))) & $bitmask;
		} while ($result > $range);
		return $result + $min;
	}
}


if(isset($data['do_signup'])) {
	
	if(trim($data['email']) == '') {
        

		$_SESSION['message'] = [
			'text' => 'E-mail не введен',
			'status' => 'error'
		];
		
	}


	if(trim($data['name']) == '') {

		$_SESSION['message'] = [
			'text' => 'Имя не введено',
			'status' => 'error'
		];
		

	}
       
    if (mb_strlen($data['name']) < 3 || mb_strlen($data['name']) > 50){
		
	   $_SESSION['message'] = [
			'text' => 'Длина имени должна быть менее 3 и более 50 символов',
			'status' => 'error'
		];
	


    }
	
	if(isset($data['email'])){
		if(in_array($data['email'], $emails))
		{
			
			$_SESSION['message'] = [
				'text' => 'Email уже существует',
				'status' => 'error'
				];
				
		}
		
	}

   /* if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $data['email'])) {

	    $_SESSION['message'] = [
			'text' => 'E-mail не соответствует маске',
			'status' => 'error'
		];
		header('Location: signup.php'); 
    }
*/
	if($_SESSION['message']['status'] != 'error') {


    	$email = $dbconnect->real_escape_string($_POST["email"]);
		$name = $dbconnect->real_escape_string($_POST["name"]);
		$role = $dbconnect->real_escape_string($_POST["role"]);		
		$password = gen_password($length = 6);
		$password2 = password_hash($password, PASSWORD_DEFAULT);
		$sql = $dbconnect->query("INSERT INTO sptest.user (password, email, name, role) VALUES ('$password2', '$email', '$name', '$role')");
	     var_dump($password, $password2);
		$dbconnect->close();
		send_mail($email, $password);
        	$_SESSION['message'] = [
		    'text' => 'Пользователь успешно зарегистрирован',
		    'status' => 'success'
		     ];
		  
		    header('Location: signup.php');

	} 
	else 
	{
		header('Location: signup.php');

	}
}

