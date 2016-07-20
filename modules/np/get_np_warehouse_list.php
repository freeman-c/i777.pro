<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/system/controller.php');

if ($_POST['format'] == 'select') getNPWarehouseListAsSelect();

function getNPWarehouseListAsSelect(){

	db_connect();

	$npWarehouseList = mysql_query("SELECT NPW.name FROM np_warehouse AS NPW WHERE NPW.np_city_ref = (SELECT NPC.ref FROM np_city AS NPC WHERE NPC.name = '{$_POST['cityName']}')") or die(mysql_error());
	while ($npWarehouseListElem = mysql_fetch_assoc($npWarehouseList)) { 
		echo '<option value="'.str_replace('"', '&quot;', $npWarehouseListElem['name']).'">'.$npWarehouseListElem['name'].'</option>';
	} 
}

function getNPWarehouseList($npCity){
	db_connect();

	$result = mysql_query("SELECT NPW.name FROM np_warehouse AS NPW WHERE NPW.np_city_ref = (SELECT NPC.ref FROM np_city AS NPC WHERE NPC.name = '{$npCity}')");

	while ($npWarehouseListElem = mysql_fetch_assoc($result)) {
		$npWarehouseList[] = $npWarehouseListElem;
	}
	return $npWarehouseList;
}
?>