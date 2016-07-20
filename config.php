<?php
//*********************** localhost ************************
error_reporting(0);
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'shop_roznica');

define('TIME_ZONE', 'Europe/Kiev');
//***********************************************************
define('API_NP', '5c08c94a73f622fc74ecca7032cd9400');
define('API2_NP', 'ee684cfc3842d8d0cfd047b05013e84c');//ee684cfc3842d8d0cfd047b05013e84c   //2efc6299ba87012dadfe6408ea1c9221
define('TurboSMSLogin', 'ivan_palcun');//ivan_palcun
define('TurboSMSPassword', 'fghrukr67');//fghrukr67

define('SITE_NAME', $_SERVER['SERVER_NAME']);
define('SITE_URL', 'http://'.$_SERVER['SERVER_NAME']);
define('VERSION', 'LP-CRM Lite (beta) v.1.4.1');
define('COPYR', 'Copyright © 2016 <b>'.SITE_NAME.'</b> - All Rights Reserved.');

//***********************************************************
// 0 - тестовый доступ, 
// 1 - старт, 
// 2 - бизнес, 
// 3 - премиум
define('TARIFF', '3');
define('MAX_LIMIT_USERS', '1000000');
define('MAX_LIMIT_PRODUCTS', '1000000');
define('MAX_LIMIT_ORDERS', '999000500');


define('ADMIN_MAIL', 'opt.city.dnepr@gmail.com');

?>
