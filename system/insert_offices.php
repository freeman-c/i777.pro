<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

function Offices(){
    db_connect();
    $query = "SELECT * FROM `zakazy` WHERE user='alex'";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}

$offices = Offices();
foreach ($offices as $office){
    $pw = Authorization($office['user']);
    echo $pw['place_work'].' - '.$office['id'].'<br>';
    
    mysql_query("UPDATE `zakazy` SET office='".$pw['place_work']."' WHERE id='".$office['id']."' ");
}
?>
