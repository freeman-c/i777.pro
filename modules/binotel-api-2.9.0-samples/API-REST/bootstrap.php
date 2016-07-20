<?php

require_once(__DIR__ .'/BinotelApi.php');


/*
    ВНИМАНИЕ! Данные для авторизации которые ниже - нерабочие и представлены только для примера.
    Напишите письмо на адрес support@binotel.ua для получения ключа и пароля для Вашей компании.
*/

$key = '6po1f4-7oPzCo1';
$secret = '8weKdP-o2b12d-f6a2P8-eBw4c4-0d2g88wb';

$api = new BinotelApi($key, $secret);



/* 
	Для логирования и поиска неисправности включите debug mode
*/
// $api->debug = true;



/*
	Для отключения проверки подлинности сертификата
*/
// $api->disableSSLChecks();
