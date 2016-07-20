<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/stockInTrade/stockInTrade.php');

function getProductListForCreateTTN($orderId){
    db_connect();
    $query = "SELECT P.name, P.model, 
            P.weight, P.width, P.height, P.length, 
            P.description, PO.price, PO.quantity 
            FROM product as P
            LEFT JOIN product_order as PO ON PO.product_id = P.id
            WHERE PO.order_id = '{$orderId}'";
    $result = mysql_query($query);
    if (!$result)
        die (mysqlResponseFail($query, mysql_error()));
    // echo mysqlResponseDone($query);
    return dbResultToAssocc($result);
}


function getCargoSizes($productsInOrder){
    $cargoSizes['width'] = $productsInOrder[0]['width'];
    $cargoSizes['height'] = $productsInOrder[0]['height'];
    $cargoSizes['length'] = $productsInOrder[0]['length'];

    for ($i=0; $i < count($productsInOrder); $i++){
        $i == 0 ? $s = 1 : $s = 0;
        for ($k = $s; $k < $productsInOrder[$i]['quantity']; $k++){
            $pr_sizes = array(
                'width' => $productsInOrder[$i]['width'],
                'height' => $productsInOrder[$i]['height'],
                'length' => $productsInOrder[$i]['length']
            );

            if ($cargoSizes['width'] == min($cargoSizes))
                $cargoSizes['width'] += min($pr_sizes);

            elseif ($cargoSizes['height'] == min($cargoSizes))
                $cargoSizes['height'] += min($pr_sizes);

            elseif ($cargoSizes['length'] == min($cargoSizes))
                $cargoSizes['length'] += min($pr_sizes);

            if ($pr_sizes['width'] > $cargoSizes['width']) $cargoSizes['width'] = $pr_sizes['width'];
            if ($pr_sizes['height'] > $cargoSizes['height']) $cargoSizes['height'] = $pr_sizes['height'];
            if ($pr_sizes['length'] > $cargoSizes['length']) $cargoSizes['length'] = $pr_sizes['length'];
        }

    }
    $cargoSizes['volume'] = ($cargoSizes['width'] * $cargoSizes['height'] * $cargoSizes['length']) / 4000;
    return $cargoSizes;
}

function sendRequestCreateTTN($row){
    db_connect();
    date_default_timezone_set(TIME_ZONE);
    //собрать строку описания товара
    //будет содержать перечень товаров в посылке
    $productsInOrder = getProductListForCreateTTN($row['order_id']);
    print_r($productsInOrder);
    for ($i=0; $i < count($productsInOrder) ; $i++) { 
        if ($i != 0) $description .= ' / ';
        $description .= $productsInOrder[$i]['description'].' - '.$productsInOrder[$i]['quantity'];
    }
    if (strlen($description) > 100)
        $description = mb_strimwidth($description, 0, 97, '...');
    
    $weight = 0;
    for ($i=0; $i < count($productsInOrder); $i++)
        $weight += $productsInOrder[$i]['weight']*$productsInOrder[$i]['quantity'];
    if ($weight == 0 || empty($weight))
        $weight = 0.2;

    $cargoSizes = getCargoSizes($productsInOrder);

    //создать экземпляр класса для работы с НП
    $np = new LisDev\Delivery\NovaPoshtaApi2(API2_NP);

    $row['bayer_name'] = preg_replace('|[\s]+|s', ' ', $row['bayer_name']);
    $row['bayer_name'] = trim($row['bayer_name']);
    $recipient = explode(' ', $row['bayer_name']);      //получил из сплошной строки - фамилию имя отчество получателя
    $phone = $row['phone']; 

    $arr = explode(',', $row['delivery_adress']);
    $recipientCity = $arr[0];
    $recipientWarenhouse = trim($arr[1]);                          
    if ($row['total'] < 300)                            //если стоимость товара <300
        $cost = 300;                                    //стоимость груза = 300
    else                                                //иначе
        $cost = $row['total'];                          //стоимость груза = стоимости товаров    
    $redeliveryCost = $row['total'];                    //возвращаемая сумма (наложенный платеж)(стоимость товаров)   

    $sender = array(
            'FirstName' => 'Діана',//$sender['FirstName'],
            'MiddleName' => 'Леонідівна',//$sender['MiddleName'],
            'LastName' => 'Шевчук',//$sender['LastName'],
            'Phone' => '0937266071',
            'City' => 'Дніпро',
            'Warehouse' => 'Відділення №16 (до 30 кг): пл. Героїв Майдану, 1',//$senderWarehouses['data'][14]['DescriptionRu'],
        );

    $recipient = array(
            'LastName' => $recipient[0],
            'FirstName' => $recipient[1],
            'MiddleName' => $recipient[2],
            'Phone' => $phone,
            'City' => $recipientCity,
            'Region' => 'Киевская',
            'Warehouse' => $recipientWarenhouse,
        );

    $package = array(
            // Дата отправления
            'DateTime' => date('d.m.Y'),
            // Тип доставки, дополнительно - getServiceTypes()
            'ServiceType' => 'WarehouseWarehouse',
            // Тип оплаты, дополнительно - getPaymentForms()
            'PaymentMethod' => 'Cash',
            // Кто оплачивает за доставку
            'PayerType' => 'Recipient',
            // Стоимость груза в грн
            'Cost' => $cost,
            // Кол-во мест
            'SeatsAmount' => '1',
            // Описание груза
            'Description' => 'Побутові товари',
            // Тип доставки, дополнительно - getCargoTypes
            'CargoType' => 'Cargo',
            // Вес груза
            'Weight' => $weight,
            'VolumeWeight' => $cargoSizes['volume'],
            //дополнительная информация
            'AdditionalInformation' => $description,
            // Обратная доставка
        );

    $redeliveryParam = array(
            'BackwardDeliveryData' => array(
                array(
                    // Кто оплачивает обратную доставку
                    'PayerType' => 'Recipient',
                    // Тип доставки
                    'CargoType' => 'Money',
                    // Значение обратной доставки
                    'RedeliveryString' => $redeliveryCost,
                )
            ));
    $optionsSeatParam = array(
            'OptionsSeat' => array(
                array(
                    'volumetricVolume' => $cargoSizes['volume'],
                    'volumetricWidth' => $cargoSizes['width'],
                    'volumetricLength' => $cargoSizes['length'],
                    'volumetricHeight' => $cargoSizes['height'],
                    'weight' => $weight
                )
            ));
    if ($row['payment'] == 4)
        $package = array_merge($package, $redeliveryParam);
    $package = array_merge($package, $optionsSeatParam);
    print_r($sender);
    print_r($recipient);
    print_r($package);
    // Генерирование новой накладной
    $result = $np->newInternetDocument($sender, $recipient, $package);
    print_r($result);
    //вернул полученный номер накладной
    if ($result['success'] == 1)
        return $result;
    else
        return false;
}

function printTTN(){
    require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/history/historyController.php');
echo "string";

    $link = 'http://my.novaposhta.ua/orders/printDocument/';
    db_connect();
    $turboSMSConnect = turboSMSAuth();
    foreach ($_POST['need_delete'] as $id => $value) {
        $query = "SELECT * 
            FROM zakazy 
            WHERE id = {$id}";
        $result = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
        $order = mysql_fetch_array($result);

        if ($order['delivery'] == 'Новая Почта' && ($order['payment'] == 1 || $order['payment'] == 4)){
            $response = sendRequestCreateTTN($order);
            AddLog("1", "{user} | <b>Заказ №{$id}</b> Запрос на печать ТТН", "NP");

            if ($response){   

                $response['data'][0]['EstimatedDeliveryDate'] = date("Y-m-d", strtotime($response['data'][0]['EstimatedDeliveryDate']));
                $statusNew = 14;

                //списать со склада отправленные товары
                changeProductStock($id, $statusNew, "order");
                
                $query = "UPDATE zakazy 
                        SET ttn='{$response['data'][0]['IntDocNumber']}', 
                        status={$statusNew}, 
                        date_complete='".date('Y-m-d')."',
                        date_arrive = '{$response['data'][0]['EstimatedDeliveryDate']}'
                        WHERE id=".(int)$id;
                mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
                

                $link .= "orders[]/{$response['data'][0]['IntDocNumber']}/";

                AddLog("1", "{user} | <b>Заказ №{$id}</b> Создана <b>ТТН №{$response['data'][0]['IntDocNumber']}</b>", "NP");

                if ($turboSMSConnect)
                    sendSMS($id, $turboSMSConnect);
            }
            else
                AddLog("0", "{user} | <b>Заказ №{$id}</b> Ошибка создания ТТН", "NP");
        }
    }
    $npApiKey = API2_NP;
    $link .= "type/html/apiKey/{$npApiKey}";
    echo $link;
}

?>