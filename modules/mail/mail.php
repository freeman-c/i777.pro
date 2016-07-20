<?php 

function sendMailAboutNewOrder2($client, $phone, $product){
    $recipient = "ilovesell2@inbox.ru"; // Ваш Электронный адрес
    $message = "ФИО: {$client}\nКонтактный телефон: {$phone}";

    mail($recipient, $product, $message);
}

function sendMailAboutNewOrder($arr, $rowtest){
    $recipient = "ilovesell@inbox.ru";
    db_connect();

    $query = "SELECT z.id
    FROM zakazy as z
    WHERE z.order_id = '".$arr['order_id']."'";
    $row = mysql_fetch_array(mysql_query($query));

    $subject = $arr['subject'];
    $message .= "НОВЫЙ ЗАКАЗ №".$row['id'].":
    Клиент: ".$arr['bayer_name']."
    Телефон: ".$arr['phone']."
    IP: ".$arr['ip']."
    Источник: http://".$arr['site']."

ЕСЛИ НЕТ НОМЕРА ЗАКАЗА - ЗАКАЗ  НЕ ЗАШЕЛ В CRM
ТРЕБУЕТСЯ ПРОВЕРКА!!!
ВОЗМОЖНО ЭТО ДУБЛЬ ИЛИ ВОЗНИКЛИ ИНЫЕ ПРОБЛЕМЫ";

    if (!empty($rowtest)){
        $message .= "
        ДУБЛЬ ЗАКАЗА №";
        foreach ($rowtest as $key => $value) {
            $message .= $value['id']." ";
        }
    }
    mail($recipient, $subject, $message);
}

function sendMailAboutGetCall($order){
    $recipient = "ilovesell@inbox.ru";
    $subject = "GET CALL";
    $message = "ЗАКАЗ №".$order['id']."
    Телефон: ".$order['phone'];
    if (!empty($order['site']))
    	$message .= "
   	Источник: http://".$order['site'];

    mail($recipient, $subject, $message);
}



?>
