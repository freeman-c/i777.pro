<?php

	require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
	require $_SERVER['DOCUMENT_ROOT'].'/config.php';
	require $_SERVER['DOCUMENT_ROOT'].'/modules/npapi2/npapi2.php';

	$np = new LisDev\Delivery\NovaPoshtaApi2(API2_NP);

    $id = $_POST['id'];
    $recipient = explode(' ', $_POST['recipient']);
    $phone = $_POST['phone'];

    // Генерирование новой накладной
    $result = $np->newInternetDocument(
        // Данные отправителя
        array(
            // Данные пользователя
            'FirstName' => 'Анна',//$sender['FirstName'],
            'MiddleName' => 'Євгеніївна',//$sender['MiddleName'],
            'LastName' => 'Ступак',//$sender['LastName'],
            // Вместо FirstName, MiddleName, LastName можно ввести зарегистрированные ФИО отправителя или название фирмы для юрлиц
            // (можно получить, вызвав метод getCounterparties('Sender', 1, '', ''))
            // 'Description' => $sender['Description'],
            // Необязательное поле, в случае отсутствия будет использоваться из данных контакта
            'Phone' => '0679891176',
            // Город отправления
             'City' => 'Дніпропетровськ',
            // Область отправления
             'Region' => 'Дніпропетровська',
            //'CitySender' => $sender['City'],
            // Отделение отправления по ID (в данном случае - в первом попавшемся)
            //'SenderAddress' => $senderWarehouses['data'][14]['Ref'],
            // Отделение отправления по адресу
             'Warehouse' => 'Відділення №16 (до 30 кг): пл. Героїв Майдану, 1',//$senderWarehouses['data'][14]['DescriptionRu'],
        ),
        // Данные получателя
        array(
            'FirstName' => $recipient[1],
            'MiddleName' => $recipient[2],
            'LastName' => $recipient[0],
            'Phone' => $phone,
            'City' => 'Киев',
            'Region' => 'Киевская',
            'Warehouse' => '№3',
        ),
        array(
            // Дата отправления
            'DateTime' => date('d.m.Y'),
            // Тип доставки, дополнительно - getServiceTypes()
            'ServiceType' => 'WarehouseWarehouse',
            // Тип оплаты, дополнительно - getPaymentForms()
            'PaymentMethod' => 'Cash',
            // Кто оплачивает за доставку
            'PayerType' => 'Recipient',
            // Стоимость груза в грн
            'Cost' => '500',
            // Кол-во мест
            'SeatsAmount' => '1',
            // Описание груза
            'Description' => 'Кастрюля',
            // Тип доставки, дополнительно - getCargoTypes
            'CargoType' => 'Cargo',
            // Вес груза
            'Weight' => '10',
            // Объем груза в куб.м.
            'VolumeGeneral' => '0.5',
            // Обратная доставка
            'BackwardDeliveryData' => array(
                array(
                    // Кто оплачивает обратную доставку
                    'PayerType' => 'Recipient',
                    // Тип доставки
                    'CargoType' => 'Money',
                    // Значение обратной доставки
                    'RedeliveryString' => 4552,
                )
            )
        )
    );

$ttnNum = $result['data'][0]['IntDocNumber'];

//echo json_encode($response);
echo $ttnNum;

    //$connection = db_connect();
    //$query

?>