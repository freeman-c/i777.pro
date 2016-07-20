<?php

/*
    Примеры категории calls.
*/

/*
    ВНИМАНИЕ! В bootstrap.php - прописаны данные для авторизации и инициализация API библиотеки.
    Пожалуйста ознакомьтесь с этим файлом.
*/
require_once(__DIR__ .'/bootstrap.php');



/*
    Пример 1: инициирование двустороннего звонка с внутренней линией и внешним номером.

    Параметры: 
        — ext_number  - внутренний номер сотрудника (первый участник разговора)
        — phone_number  - телефонный номер куда нужно позвонить (второй участник разговора)
        — limitCallTime  - ограничение длительности звонка в секундах (необязательный параметр)
        — playbackWaiting  - проигрывание, первому участнику разговора, фразы: "ожидайте пожалуйста на линии, происходит соединение со 2-м участником разговора". По умолчанию стоит TRUE, принимает значения: TRUE или FALSE (необязательный параметр).
*/

$result = $api->sendRequest('calls/ext-to-phone', array(
    'ext_number' => '910',
    'phone_number' => '0443039913',
    /*'playbackWaiting' => FALSE*/
));

if ($result['status'] === 'success') {
    var_dump($result['generalCallID']);
} else {
    printf('Что-то пошло не так! %s', PHP_EOL);
    printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/* 
    Пример 2: инициирование двустороннего звонка с внутренней линией и внешним номером с последующим отслеживанием статуса.

    Параметры: 
        — ext_number  - внутренний номер сотрудника (первый участник разговора)
        — phone_number  - телефонный номер куда нужно позвонить (второй участник разговора)
        — limitCallTime  - ограничение длительности звонка в секундах (необязательный параметр)
        — playbackWaiting  - проигрывание, первому участнику разговора, фразы: "ожидайте пожалуйста на линии, происходит соединение со 2-м участником разговора". По умолчанию стоит TRUE, принимает значения: TRUE или FALSE (необязательный параметр).
*/

$result = $api->sendRequest('calls/ext-to-phone', array(
    'ext_number' => '901',
    'phone_number' => '0443039913'
));

if ($result['status'] === 'success') {
    $numberOfAttempts = 10;
    $delayBetweenAttempts = 10;

    for ($i=0; $i<$numberOfAttempts; $i++) {
        $result = $api->sendRequest('stats/call-details', array(
            'generalCallID' => $result['generalCallID']
        ));

        if ($result['status'] !== 'success') {
            printf('Что-то пошло не так! %s', PHP_EOL);
            printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);

            break;
        }

        if (count($result['callDetails'][$generalCallID])) {
            if ($result['callDetails'][$generalCallID]['disposition'] === 'ONLINE') {
                printf('Сотрудник говорит с клиентом! %s', PHP_EOL);

                break;
            } elseif ($result['callDetails'][$generalCallID]['disposition'] !== '') {
                printf('Звонок завершился! %s', PHP_EOL);

                break;
            }
        }

        sleep($delayBetweenAttempts);
    }
} else {
    printf('Что-то пошло не так! %s', PHP_EOL);
    printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
    Пример 3: инициирование двустороннего звонка c двумя внешними номерами.

    Параметры:
        — phoneNumber1  - первый внешний номер
        — phoneNumber2  - второй внешний номер
        — trunkNumber  - номер через который будут совершаться оба звонка
        — limitCallTime  - ограничение длительности звонка в секундах (необязательный параметр)
        — playbackWaiting  - проигрывание, первому участнику разговора, фразы: "ожидайте пожалуйста на линии, происходит соединение со 2-м участником разговора". По умолчанию стоит TRUE, принимает значения: TRUE или FALSE (необязательный параметр).


    В АТС Binotel этот двусторонний звонок фактически будет состоять с 2-х звонков. Первый звонок на первый внешний номер, второй звонок на второй внешний номер соответственно.
    У этих двух звонков будет одинаковое имя кто звонил, пример: "Звонок 0321879" (0321879 - случайный набор чисел).
    API запрос возвращает generalCallID первого звонка (на первый внешний номер).
    Для отслеживания второго звонка (на второй внешний номер), нужно:
        1) выбрать все звонки за этот день
        2) найти второй звонок по имени кто звонил (пример: Звонок 0321879).
        3) запомнить generalCallID второго звонка, для последующих отслеживаний
*/

$result = $api->sendRequest('calls/phone-to-phone', array(
    'phoneNumber1' => '0970002233',
    'phoneNumber2' => '0443334333',
    'trunkNumber' => '0443039913',
    'limitCallTime' => '120'
));

if ($result['status'] === 'success') {
    var_dump($result['generalCallID']);
} else {
    printf('Что-то пошло не так! %s', PHP_EOL);
    printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/* 
    Пример 4: перевод звонка с участием.

    Параметры: 
        — generalCallID  - идентификатор звонка
        — phone_number  - номер на который переводится звонок
*/

$result = $api->sendRequest('calls/attended-call-transfer', array(
    'generalCallID' => '22661563',
    'phone_number' => '912'
));

if ($result['status'] === 'success') {
    var_dump($result);
} else {
    printf('Что-то пошло не так! %s', PHP_EOL);
    printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/* 
    Пример 5: завершение звонка.

    Параметры: 
        — generalCallID  - идентификатор звонка
*/

$result = $api->sendRequest('calls/hangup-call', array(
    'generalCallID' => '22661891'
));

if ($result['status'] === 'success') {
    var_dump($result);
} else {
    printf('Что-то пошло не так! %s', PHP_EOL);
    printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/* 
    Пример 6: звонок с оповещением голосового файла.

    Параметры: 
        — phone_number  - телефонный номер кому будет проигрываться оповещение
        - voiceFileID  - идентификатор голосового файла (смотрите в samples-api-rest-settings.php, settings/list-of-voice-files)
*/

$result = $api->sendRequest('calls/call-with-announcement', array(
    'phone_number' => '0443039913',
    'voiceFileID' => '4'
));

if ($result['status'] === 'success') {
    var_dump($result);
} else {
    printf('Что-то пошло не так! %s', PHP_EOL);
    printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}