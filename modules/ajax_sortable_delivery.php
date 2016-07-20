<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
db_connect();
$sort = 1;
foreach ($_POST['menu_id'] as $id){    
    mysql_query("UPDATE `delivery` SET sort = '{$sort}' WHERE id = '{$id}' ");
    $sort++;
}
?>