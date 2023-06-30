<?php
require "boot.php";
require('../config.php');
$data = $_POST;
$error = 0;
$idnew = $_SESSION['news']['idnew'];
$sql = $dbconnect->query("SELECT picture FROM sptest.user, sptest.news WHERE user.iduser = news.id_user and id_status = 2 and idnew={$idnew}");
$row = mysqli_fetch_assoc($sql);
$header = $dbconnect->real_escape_string($data["header"]);
$date = $dbconnect->real_escape_string($data["date"]);
$short_info = $dbconnect->real_escape_string($data["short_info"]);
$full_info = $dbconnect->real_escape_string($data["full_info"]);
$iduser = $_SESSION['logged_user']['iduser'];
$picture = $row["picture"];

saveNew($data, $header, $date, $picture, $short_info, $full_info, $iduser, $idnew);

function randomFileName($extension = false)
{
    $extension = $extension ? '.' . $extension : '';
    do {
        $name = md5(microtime() . rand(0, 1000));
        $file = $name . $extension;
    } while (file_exists($file));

    return $file;
}


function copyPicture()
{
	$path = '../photo/';
	$types = array('image/gif', 'image/png', 'image/jpeg');
	$size = 1024000;
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		
		$extension = strtolower(substr(strrchr($_FILES['picture']['name'], '.'), 1));
		$file = randomFileName($extension);
		if (!in_array($_FILES['picture']['type'], $types)){
			$error = 1;
			$_SESSION['message'] = [
				'text' => 'Неразрешенный тип файла',
				'status' => 'error'
			];
		}
		if ($_FILES['picture']['size'] > $size){
			$error = 1;
			$_SESSION['message'] = [
				'text' => 'Большой размер файла',
				'status' => 'error'
			];
		}
		if (!@copy($_FILES['picture']['tmp_name'], $path . $file)){
			$error = 1;
			$_SESSION['message'] = [
				'text' => 'Ошибка копирования',
				'status' => 'error'
			];
		}
		
	}
	return $file ;
}


function saveNew($data, $header, $date, $picture, $short_info, $full_info, $iduser, $idnew){
	global $dbconnect;
	
	if($_FILES["picture"]["name"] != "" )
	{
        $fileName = copyPicture();
        $picture = "photo/".$fileName;
	}

	if(isset($data['do_revision_publish'])) {
	
		if(mb_strlen($data['header']) < 5 || mb_strlen($data['header']) > 300) {
			$error = 1;
			$_SESSION['message'] = [
				'text' => 'Заголовок новости должен быть более 5 и не более 300 символов',
				'status' => 'error'
			];
		}
		
		if($_SESSION['message']['status'] != 'error')  {
	
			
			$sql = $dbconnect->query("UPDATE sptest.news SET header = '$header', date = '$date', short_info = '$short_info',picture='$picture', full_info = '$full_info', id_status = 3, dateEdit = NOW() where idnew = '$idnew'");
			
			$_SESSION['message'] = [
				'text' => 'Новость отправлена на модерацию',
				'status' => 'success'
			];
			header('Location: revision.php');
		
		}
		 else {
			header('Location: revision.php');
		}
	
	}
	if (isset($data['do_revision_archive']))
	{
		
		
		$sql = $dbconnect->query("UPDATE sptest.news SET header = '$header', date = '$date', short_info = '$short_info', picture='$picture', full_info = '$full_info', id_status = 5, dateEdit = NOW() where idnew = '$idnew'");
		$_SESSION['message'] = [
			'text' => 'Новость добавлена в архив',
			'status' => 'success'
		];
		header('Location: revision.php');

	}
}




