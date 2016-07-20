<?php 
	//сделано для обхода системы безопасности, когда кроссдоменный запрос блокируется браузерами.
	//из формы заказа вызываем JS функцию получения гео данных
	//она делает AJAX запрос к этому файлу
	//а этот уже непосредственно обращаеться к API сайта 2ip.com.ua
	//результат с сайта приходит в JSON`e, возвращаем как объект stdClass

	$myCurl = curl_init();
	curl_setopt_array($myCurl, array(
	    CURLOPT_URL => 'http://api.2ip.com.ua/geo.json?ip='.$_POST['client_ip'],
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_POST => true,
	));
	$response = curl_exec($myCurl);
	curl_close($myCurl);

	echo $response;
?>