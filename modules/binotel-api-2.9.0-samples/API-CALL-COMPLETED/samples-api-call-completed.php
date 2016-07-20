<?php

/*
	Примеры API CALL COMPLETED.

	API CALL COMPLETED: используется для уведомления Вашего скрипта о каждом завершенном звонке. Обычно используется для интеграции АТС Binotel с Вашей CRM.
	
	Этот способ работает через HTTP протокол, данные отправляются методом POST.

	С протоколом HTTP можно ознакомится по ссылке: http://ru.wikipedia.org/wiki/HTTP
	С методом POST можно ознакомится по ссылке: http://ru.wikipedia.org/wiki/HTTP#POST
*/


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


	Структура данных идентична структуре данных в API-REST в категории stats.
*/


/* 
	Пример логирования POST данных, отправляемых Вашему скрипту при завершении звонка в АТС Binotel.
*/
$content = sprintf('%s%s[%s] Received new POST data!%s', PHP_EOL, PHP_EOL, date('r'), PHP_EOL);
$content .= var_export($_POST, TRUE) . PHP_EOL;
file_put_contents(sprintf('%s/api-call-completed.log', __DIR__), $content, FILE_APPEND);