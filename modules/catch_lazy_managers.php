<?php 

require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

//подключаемся к базе
$connection_opt = db_connect();

session_start();
db_connect();


if ($_POST['param'] == "awayFromKeyboard")
	$mode = "Нет на месте";
elseif($_POST['param'] == "dontGetTask")
	$mode = "Не получать задания";


if ($_POST['state'] == 'true')
	AddLog('1','Пользователь {user} ВКЛЮЧИЛ режим "'.$mode.'"');
else
	AddLog('1','Пользователь {user} ОТКЛЮЧИЛ режим "'.$mode.'"');
echo json_encode($_POST);

?>