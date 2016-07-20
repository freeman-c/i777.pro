<?php

/*
	Примеры категории stats.
*/

/*
	ВНИМАНИЕ! В bootstrap.php - прописаны данные для авторизации и инициализация API библиотеки.
	Пожалуйста ознакомьтесь с этим файлом.
*/
require_once(__DIR__ .'/bootstrap.php');



/*
	Разъяснения данных в информации о звонке:
		- generalCallID  - главный идентификатор звонка
		- callID  - идентификатор записи разговора (используется для получения ссылки на запись разговора)
		- startTime  - время начала звонка
		- callType  - тип звонка: входящий - 0, исходящий  - 1
		- internalNumber  - внутренний номер сотрудника / группы в виртуальной АТС
		- externalNumber  - телефонный номер клиента
		- customerData:
			- id  - идентификатор клиента в Мои клинтах
			- name  - имя клиента в Мои клинтах
		- employeeName  - имя сотрудника
		- employeeEmail  - email сотрудника
		- dstNumbers  - спискок номеров которые были в обработке звонка (когда звонок входящий это будет список попыток звонков)
			- dstNumber  - номер кому звонили (когда звонок входящий это будет внутренняя линия сотрудника или группа при груповом звонке, при исхощяем звонке это будет номер на который звонит сотрудник)
		- waitsec  - ожидание до соединения
		- billsec  - длительность разговора
		- disposition  - состояние звонка (ANSWER - успешный звонок, TRANSFER - успешный звонок который был переведен, ONLINE - звонок в онлайне, BUSY - неуспешный звонок по причине занятости, NOANSWER - неуспешный звонок по причине не ответа, CANCEL - неуспешный звонок по причине отмены звонка, CONGESTION - неуспешный звонок, CHANUNAVAIL - неуспешный звонок, VM - голосовая почта без сообщения, VM-SUCCESS - голосовая почта с сообщением)
		- isNewCall  - был ли входящий звонок новым
		- did  - номер на который пришел вызов во входящем звонке
		- didName  - имя номера на который пришел вызов во входящем звонке
		- trunkNumber  - номер через который совершался исходящий звонок
*/



/*
	Пример 1: входящие или исходящие звонки за период.

	Вариант адреса:
		- incoming-calls-for-period - для входящих
		- outgoing-calls-for-period - для исходящих

	Параметры: 
		- startTime  - время начала выбора звонков (в формате unix timestamp)
		- stopTime  - время окончания выбора звонков (в формате unix timestamp)
*/

$result = $api->sendRequest('stats/outgoing-calls-for-period', array(
	'startTime' => 1370034000, // Sat, 01 Jun 2013 00:00:00 +0300
	'stopTime' => 1370120399 // Sat, 01 Jun 2013 23:59:59 +0300
));

if ($result['status'] === 'success') {
	var_dump($result['callDetails']);
} else {
	printf('Что-то пошло не так! %s', PHP_EOL);
	printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
	Пример 2: входящие или исходящие звонки с N времени по настоящее время.

	Вариант адреса:
		- all-incoming-calls-since - для входящих
		- all-outgoing-calls-since - для исходящих

	Параметры: 
		- timestamp  - время начала выбора звонков (в формате unix timestamp)
*/

$lastRequestTimestamp = 1370034000; // Sat, 01 Jun 2013 00:00:00 +0300

$result = $api->sendRequest('stats/all-incoming-calls-since', array(
	'timestamp' => $lastRequestTimestamp
));

if ($result['status'] === 'success') {
	var_dump($result['callDetails']);
} else {
	printf('Что-то пошло не так! %s', PHP_EOL);
	printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
	Пример 3: звонки которые в онлайне.
	Параметры: пустой массив.
*/

$result = $api->sendRequest('stats/online-calls', array());

if ($result['status'] === 'success') {
	var_dump($result['callDetails']);
} else {
	printf('Что-то пошло не так! %s', PHP_EOL);
	printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
	Пример 4: звонки за день (как входящие, так и исходящие).

	Параметры:
		- dayInTimestamp  - день (в формате unix timestamp, при отсутствии этого параметра звонки буду взяты за сегодня)
*/

$result = $api->sendRequest('stats/list-of-calls-per-day', array(
	'dayInTimestamp' => mktime(0, 0, 0, 11, 25, 2015)
));

if ($result['status'] === 'success') {
	var_dump($result['callDetails']);
} else {
	printf('Что-то пошло не так! %s', PHP_EOL);
	printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
	Пример 5: звонки по внутреннему номеру сотрудника за период (как входящие, так и исходящие).

	Параметры:
		- internalNumber  - внутренний номер сотрудника
		- startTime  - время начала выбора звонков (в формате unix timestamp)
		- stopTime  - время окончания выбора звонков (в формате unix timestamp)

	Ограничения: период не может быть больше 7 дней.
*/

$result = $api->sendRequest('stats/list-of-calls-by-internal-number-for-period', array(
	'internalNumber' => '901',
	'startTime' => 1370034000, // Sat, 01 Jun 2013 00:00:00 +0300
	'stopTime' => 1370638799 // Fri, 07 Jun 2013 23:59:59 +0300
));

if ($result['status'] === 'success') {
	var_dump($result['callDetails']);
} else {
	printf('Что-то пошло не так! %s', PHP_EOL);
	printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
	Пример 6: потерянные звонки за сегодня.
	Параметры: пустой массив.
*/

$result = $api->sendRequest('stats/list-of-lost-calls-today', array());

if ($result['status'] === 'success') {
	var_dump($result['callDetails']);
} else {
	printf('Что-то пошло не так! %s', PHP_EOL);
	printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
	Пример 7: звонки по номеру телефона (как входящие, так и исходящие).

	Параметры:
		- number  - номер или номера в массиве
*/

$result = $api->sendRequest('stats/history-by-number', array(
	'number' => array('0443039913', '0443039884')
));

if ($result['status'] === 'success') {
	var_dump($result['callDetails']);
} else {
	printf('Что-то пошло не так! %s', PHP_EOL);
	printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
	Пример 8: звонки по идентификатору клиента (как входящие, так и исходящие).

	Параметры: 
		- customerID  - идентификатор клиента или идентификаторы клиентов в массиве
*/

$result = $api->sendRequest('stats/history-by-customer-id', array(
	'customerID' => '6611'
));

if ($result['status'] === 'success') {
	var_dump($result['callDetails']);
} else {
	printf('Что-то пошло не так! %s', PHP_EOL);
	printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
	Пример 9: недавние звонки по внутреннему номеру сотрудника (как входящие, так и исходящие). Используется для реализации функции "Мои недавние звонки" для сотрудника.

	Параметры:
		- internalNumber  - внутренний номер сотрудника

	Ограничения: звонки за последние 2 недели и не более 50 звонков.
*/

$result = $api->sendRequest('stats/recent-calls-by-internal-number', array(
	'internalNumber' => '901'
));

if ($result['status'] === 'success') {
	var_dump($result['callDetails']);
} else {
	printf('Что-то пошло не так! %s', PHP_EOL);
	printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
	Пример 10: данные о звонке по идентификатору звонка.

	Параметры:
		- generalCallID  - идентификатор звонка или массив c идентификаторами звонков
*/

$result = $api->sendRequest('stats/call-details', array(
	'generalCallID' => array('2255713', '2256039', '2252553')
));

if ($result['status'] === 'success') {
	var_dump($result['callDetails']);
} else {
	printf('Что-то пошло не так! %s', PHP_EOL);
	printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
	Пример 11: получение ссылки на запись разговора.
	Внимание: время жизни ссылки на запись разговора 15 минут.

	Параметры:
		- callID  - идентификатор записи разговора
*/

$result = $api->sendRequest('stats/call-record', array(
	'callID' => '12501059'
));

if ($result['status'] === 'success') {
	var_dump($result['url']);
} else {
	printf('Что-то пошло не так! %s', PHP_EOL);
	printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}


