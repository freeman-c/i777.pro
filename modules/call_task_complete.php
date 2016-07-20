<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

db_connect();
	
//удаляем задание из списка (как такое, которое было выполнено)
//чтобы другой сотрудник не получил это же задание
mysql_query("UPDATE call_tasks SET done = 1 WHERE id = {$_POST['taskId']}");

?>



