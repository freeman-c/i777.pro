<?php

/*
    Примеры категории customers.
*/

/*
    ВНИМАНИЕ! В bootstrap.php - прописаны данные для авторизации и инициализация API библиотеки.
    Пожалуйста ознакомьтесь с этим файлом.
*/
require_once(__DIR__ .'/bootstrap.php');



/*
    Разъяснения данных в информации о клиенте:
        - id  - идентификатор клиента
        - name  - имя клиента
        - description  - информация о клиенте
        - email  - email клиента
        - assignedToEmployeeID  - идентификатор ответственного сотрудника
        - assignedToEmployeeNumber  - внутренний номер ответственного сотрудника
        - assignedToEmployee  - имя ответственного сотрудника
        - numbers  - список номеров клиента
        - labels:
            - id  - идентификатор метки
            - name  - название метки
*/



/*
    Пример 1: выбор всех клиентов с мини-срм "Мои клиенты".

    Параметры: пустой массив.
*/
    
$result = $api->sendRequest('customers/list', array());

if ($result['status'] === 'success') {
    var_dump($result['customerData']);
} else {
    printf('Что-то пошло не так! %s', PHP_EOL);
    printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
    Пример 2: выбор клиентов с мини-срм "Мои клиенты" по идентификатору клиента.

    Параметры:
        - customerID  - идентификатор клиента или идентификаторы клиентов в массиве.
*/

$customerID = array('6611');

$result = $api->sendRequest('customers/take-by-id', array(
    'customerID' => $customerID
));

if ($result['status'] === 'success') {
    var_dump($result['customerData']);
} else {
    printf('Что-то пошло не так! %s', PHP_EOL);
    printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
    Пример 3: выбор клиентов с мини-срм "Мои клиенты" по метке.

    Параметры:
        - labelID  - идентификатор метки.
*/

$labelID = '146';

$result = $api->sendRequest('customers/take-by-label', array(
    'labelID' => $labelID
));

if ($result['status'] === 'success') {
    var_dump($result['customerData']);
} else {
    printf('Что-то пошло не так! %s', PHP_EOL);
    printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
    Пример 4: поиск клиентов в мини-срм "Мои клиенты" по имени или по номеру телефона.

    Параметры:
        - subject  - часть имени или номера телоефона.
*/

$result = $api->sendRequest('customers/search', array(
    'subject' => 'Генадий'
));

if ($result['status'] === 'success') {
    var_dump($result['customerData']);
} else {
    printf('Что-то пошло не так! %s', PHP_EOL);
    printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
    Пример 5: создание клиента в мини-срм "Мои клиенты".

    Параметры:
        - name  - имя клиента, имя должно быть уникальным (обязательное поле!)
        - numbers  - массив номеров, все номера должны быть уникальными (обязательное поле!)
        - email  - email клиента
        - assignedToEmployeeNumber  - внутренний номер сотрудника в АТС Binotel (пример: 904, важно чтобы линия была закреплена за сотрудником в MyBinotel!)
*/

$result = $api->sendRequest('customers/create', array(
    'name' => 'New client',
    'numbers' => array(
        '0970003322', '0939990099'
    ),
    'description' => 'Информаиця о клиенте!',
    'email' => 'new.client@gmail.com',
    'assignedToEmployeeNumber' => '904',
));

if ($result['status'] === 'success') {
    var_dump($result);
} else {
    printf('Что-то пошло не так! %s', PHP_EOL);
    printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
    Пример 6: редактирование клиента в мини-срм "Мои клиенты".

    ВНИМАНИЕ: все данные в массиве которые будут передаваться, будут изменяться, по этому если Вам нужно изменить только имя клиента, необходимо передавать только поле с новым именем с идентификатором клиента. Если вам необходимо добавить новый номер, или удалить номер, для этого нужно передавать новый актуальный список номеров. Редактирование меток происходит так же как и редактирование номеров.

    Параметры:
        - id  - идентификатор клиента (обязательное поле!)
        - name  - имя клиента, имя должно быть уникальным
        - numbers  - массив номеров, все номера должны быть уникальными
        - description  - информация о клиенте
        - email  - email клиента
        - assignedToEmployeeNumber  - внутренний номер сотрудника в АТС Binotel (пример: 904, важно чтобы линия была закреплена за сотрудником в MyBinotel!)
        - labels  - массив клиента с идентификаторами меток (список меток с идентификаторами можно получить: customers/listOfLabels)


    В примере ниже мы делаем:
        - изменяем имя
        - обновляем телефонные номера
        - очищаем описание
        - убираем ответственного сотрудника
        - убираем метки
*/

$result = $api->sendRequest('customers/update', array(
    'id' => '6611',
    'name' => 'Sales Binotel',
    'numbers' => array(
        '0971553605', '0939990099'
    ),
    'description' => '',
    'assignedToEmployeeNumber' => '',
    'labels' => array()
));

if ($result['status'] === 'success') {
    var_dump($result);
} else {
    printf('Что-то пошло не так! %s', PHP_EOL);
    printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
    Пример 7: удаление клиента в мини-срм "Мои клиенты" по идентификатору клиента.

    Параметры:
        - customerID  - идентификатор клиента или идентификаторы клиентов в массиве.
*/

$customerID = array('270334');

$result = $api->sendRequest('customers/delete', array(
    'customerID' => $customerID
));

if ($result['status'] === 'success') {
    var_dump($result['status']);
} else {
    printf('Что-то пошло не так! %s', PHP_EOL);
    printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}



/*
    Пример 8: выбор всех меток с мини-срм "Мои клиенты".
    Параметры: пустой массив.

    Разъяснения данных в информации о сценарии:
        - id  - идентификатор метки
        - name  - имя метки
*/
    
$result = $api->sendRequest('customers/listOfLabels', array());

if ($result['status'] === 'success') {
    var_dump($result['listOfLabels']);
} else {
    printf('Что-то пошло не так! %s', PHP_EOL);
    printf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL);
}


