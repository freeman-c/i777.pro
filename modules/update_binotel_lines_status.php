<?php 
header("Access-Control-Allow-Origin: *");

require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$connection_opt = db_connect();

date_default_timezone_set (TIME_ZONE);

$result = mysql_query("SELECT Time FROM last_update WHERE ID = 2", $connection_opt) or die(mysql_error());
$result = mysql_fetch_array();

$time = strtotime($result['Time']);

if (abs($time - time()) > 5)
{
    mysql_query("UPDATE last_update SET Time = '".date('Y-m-d H:i:s')."' WHERE ID = 2", $connection_opt) or die(mysql_error());

    require $_SERVER['DOCUMENT_ROOT'].'/modules/binotel/bootstrap.php';

    $result = $api->sendRequest('settings/list-of-employees', array());
    print_r($result);
	if ($result['status'] === 'success') {
		mysql_query("TRUNCATE TABLE binotel_lines_status") or die(mysql_error());
		foreach ($result['listOfEmployees'] as $key => $value) {
			echo 'Линия '.$value['extNumber'].' - '.$value['extStatus']['status'].PHP_EOL;
			mysql_query("INSERT INTO binotel_lines_status 
				SET line = {$value['extNumber']}, 
				status = '{$value['extStatus']['status']}'", $connection_opt) or die(mysql_error());
		}
	}
}

// if($_GET['cron'])
// 	mail("techitch16@gmail.com", "Test update_binotel_lines_status", "updated with crone: ".date("H:i:s d.m.Y"));
// else
// 	echo "updated without crone done";

?>
