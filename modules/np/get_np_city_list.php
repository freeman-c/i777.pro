<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/system/controller.php');

function getNPCityList(){
	db_connect();
	$npCityList = mysql_query("SELECT name FROM np_city ORDER BY name") or die(mysql_error());
	$npCityList = db_result_to_array($npCityList);
	return $npCityList;
}

?>