<?php

/*
	Примеры категории settings.
*/

/*
	ВНИМАНИЕ! В bootstrap.php - прописаны данные для авторизации и инициализация API библиотеки.
	Пожалуйста ознакомьтесь с этим файлом.
*/
require_once(__DIR__ .'/bootstrap.php');



/*
	Пример 1: выбор всех сотрудников.
	Параметры: пустой массив.

	Разъяснения данных в информации о сотруднике:
		- employeeID  - идентификатор сотрудника
		- email  - email сотрудника
		- name  - имя сотрудника
		- mobilePhoneNumber  - мобильный номер сотрудника (в разработке)
		- presenceState  - статус сотрудника (активен / неактивен, используется для функции липкости)
		- department  - название отдела
		- extNumber  - внутренний номер сотрудника (пример: 902)
		- extHash  - SIP номер сотрудника
		- extStatus:
			- status  - состояние внутренней линии сотрудника (online - онлайн, inuse - разговаривает, ringing - совершается вызов на эту линию, offline - офлайн)
*/

$result = $api->sendRequest('settings/list-of-employees', array());

if ($result['status'] === 'success') {
	var_dump($result['listOfEmployees']);
} else {
	printf('Что-то пошло не так! %s', PHP_EOL);
	printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
	Пример 2: выбор всех сценариев для входящих звонков.
	Параметры: пустой массив.

	Разъяснения данных в информации о сценарии:
		- тип сценария (routeWithTime - временной сценарий, route - обычный сценарий):
			- id  - идентификатор сценария (внимение, в сценариях route и routeWithTime могут быть одинаковые идентификаторы)
			- name  - имя сценария
			- description  - информация о сценарии
*/

$result = $api->sendRequest('settings/list-of-routes', array());

if ($result['status'] === 'success') {
	var_dump($result['listOfRoutes']);
} else {
	printf('Что-то пошло не так! %s', PHP_EOL);
	printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
	Пример 3: выбор всех голосовых файлов (приветствий).
	Параметры: пустой массив.

	Разъяснения данных в информации о сценарии:
		- id  - идентификатор голосового файла
		- name  - имя голосового файла
		- type  - тип голосового файла (в разработке)
*/

$result = $api->sendRequest('settings/list-of-voice-files', array());

if ($result['status'] === 'success') {
	var_dump($result['listOfVoiceFiles']);
} else {
	printf('Что-то пошло не так! %s', PHP_EOL);
	printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}