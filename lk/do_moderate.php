<?php
require "boot.php";
require('../config.php');
$idnew = $_SESSION['news']['idnew'];
$sql = $dbconnect->query("SELECT picture FROM sptest.user, sptest.news WHERE user.iduser = news.id_user and id_status = 3 and idnew = {$idnew}");
$row = mysqli_fetch_assoc($sql);
$header = $dbconnect->real_escape_string($_POST["header"]);
$date = $dbconnect->real_escape_string($_POST["date"]);
$short_info = $dbconnect->real_escape_string($_POST["short_info"]);
$full_info = $dbconnect->real_escape_string($_POST["full_info"]);
$picture = $row["picture"];
$iduser = $_SESSION['logged_user']['iduser'];
$comment = $dbconnect->real_escape_string($_POST["comment"]);

moderation($header, $date, $short_info, $picture, $full_info, $iduser, $comment, $idnew);
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
    $size = 6000000;
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

function moderation($header, $date, $short_info, $picture, $full_info, $iduser, $comment, $idnew){
    if($_FILES["picture"]["name"] != "")
    {
        $fileName = copyPicture();
        $picture = "photo/".$fileName;
    }

    global $dbconnect;
	if(isset($_POST['do_moderate_publish'])) {
	     //echo 'кнопка нажата';
		if(mb_strlen($_POST['header']) < 5 || mb_strlen($_POST['header']) > 600) {
			$error = 1;
			$_SESSION['message'] = [
				'text' => 'Заголовок новости должен быть более 5 и не более 300 символов',
				'status' => 'error'
			];
		}

		if($_SESSION['message']['status'] != 'error')  {


			$sql = $dbconnect->query("UPDATE sptest.news SET header = '$header', date = '$date', short_info = '$short_info', full_info = '$full_info', picture = '$picture', id_status = 4, dateEdit = NOW() where idnew = '$idnew'");
            
			$_SESSION['message'] = [
				'text' => 'Новость опубликована',
				'status' => 'success'
			];
		    header('Location: moderation.php');
		
		}
		 else {
			header('Location: moderation.php');
		}
	
	}
	if(isset($_POST['do_moderate_rough']))
	{
		if(empty($_POST['comment'])) {
			$error = 1;
			$_SESSION['message'] = [
				'text' => 'Оставьте автору комментарий о том, что нужно исправить',
				'status' => 'error'
			];
		}
		if($_SESSION['message']['status'] != 'error')  {
	
			//echo"ok";
			$sql = $dbconnect->query("UPDATE sptest.news SET header = '$header', date = '$date', short_info = '$short_info', full_info = '$full_info', picture='$picture', id_status = 2, dateEdit = NOW() where idnew = '$idnew'");
			$moder = $dbconnect->query("INSERT INTO sptest.moderation (comment, date, id_new) VALUES ('$comment', NOW(), '$idnew')");
			$_SESSION['message'] = [
				'text' => 'Новость отправлена на доработку',
				'status' => 'success'
			];
		    header('Location: moderation.php');
		
		}
		 else {
			header('Location: moderation.php?idnew='.$idnew);
		}
	}
	
}


