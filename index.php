<?php 
//header('Content-Type: text/html; charset=utf-8');
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/engine.php';
require $_SERVER['DOCUMENT_ROOT'].'/modules/npapi2/npapi2.php';

session_set_cookie_params(50400); //14 часов

session_start();
if (!$_SESSION['user']){
    header('Location: '.SITE_URL.'/login.php');
    exit;
}
else{
    Render();
}
?>