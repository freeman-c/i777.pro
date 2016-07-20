<?php

/*
    Примеры API PUSH.

    API PUSH используется для уведомления Вашего скрипта о каждом звонке по состояниям: поступления звонока, ответа на звонок, завершения звонка.
    
    Этот способ работает через HTTP протокол, данные отправляются методом POST.

    С протоколом HTTP можно ознакомится по ссылке: http://ru.wikipedia.org/wiki/HTTP
    С методом POST можно ознакомится по ссылке: http://ru.wikipedia.org/wiki/HTTP#POST


    АТС Binotel отправляет 3 запроса на каждый звонок:
        1) во время поступления звонка
        2) во время ответа на звонок
        3) во время перевода звонока
        4) во время завершения звонка
*/


/* 
    Пример POST данных оповещения при поступлении входящего звонка в АТС Binotel.
*/
$postData = array(
    'didNumber' => '0443334000',
    'srcNumber' => '0670219424',
    'generalCallID' => '2500834',
    'callType' => '0',
    'companyID' => '3041',
    'requestType' => 'receivedTheCall'
);



/* 
    Пример POST данных оповещения при поступлении исходящего звонка в АТС Binotel.
*/
$postData = array(
    'extNumber' => '904',
    'dstNumber' => '0671290023',
    'generalCallID' => '2590912',
    'callType' => '1',
    'companyID' => '3041',
    'requestType' => 'receivedTheCall'
);



/* 
    Пример POST данных оповещение когда сотрудник ответил на входящий звонок (звонок на группу сотрудников).
*/
$postData = array(
    'didNumber' => '0442334000',
    'srcNumber' => '0670219424',
    'extNumber' => '903',
    'generalCallID' => '2500834',
    'callType' => '0',
    'companyID' => '3041',
    'requestType' => 'answeredTheCall'
);



/* 
    Пример POST данных оповещение когда сотрудник ответил на входящий звонок (прямой звонок на сотрудника).
*/
$postData = array(
    'extNumber' => '901',
    'generalCallID' => '2711245',
    'companyID' => '3041',
    'requestType' => 'answeredTheCall'
);



/* 
    Пример POST данных оповещение когда абонент ответил на исходящий звонок.
*/
$postData = array(
    'generalCallID' => '2739109',
    'companyID' => '3041',
    'requestType' => 'answeredTheCall'
);



/* 
    Пример POST данных оповещение когда звонок был переведен.
*/
$postData = array(
    'generalCallID' => '5031953',
    'companyID' => '3041',
    'requestType' => 'transferredTheCall',
    'internalNumber' => '901'
);



/* 
    Пример POST данных оповещение когда звонок завершился.
*/
$postData = array(
    'generalCallID' => '2744830',
    'billsec' => '35',
    'disposition' => 'ANSWER',
    'companyID' => '3041',
    'requestType' => 'hangupTheCall'
);


/*
    Разъяснения данных посылаемых от АТС Binotel:
        didNumber - номер на который поступил звонок
        srcNumber - номер абонента в поступившем звонке
        dstNumber - номер абонента в совершенном звонке
        extNumber - внутренний короткий номер сотрудника
        internalNumber - внутренний короткий номер сотрудника
        requestType - тип PUSH запроса
        generalCallID - идентификатор звонка
        callType - тип звонка: входящий - 0, исходящий - 1
        companyID - идентификатор компании в АТС Binotel
        billsec - длительность разговора в секундах
        disposition - состояние звонка

    Другие переменные которые присылаются и не описаны выше - являются устаревшими.
    Их использование нежелательно, так как в ближайшее время будут удалены из PUSH API.
*/