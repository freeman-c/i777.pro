<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/history/historyController.php');

function getNPTracking($barcodeList){
    $key_api = API_NP;
    $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <file>
                    <auth>'.$key_api.'</auth>
                    <tracking>'.
                        $barcodeList.
                    '</tracking>
                </file>';

    if (!empty($barcodeList)){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://orders.novaposhta.ua/xml.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
    }
    return $response;
}


function updateOrderStatusInDB($npResponse){
    db_connect();
    $doc = new SimpleXMLElement($npResponse);
    foreach ($doc->document as $docum) {
        if (!empty($docum->attributes()->state_id))
        {
            switch ($docum->attributes()->state_id) {
                case 2: //2 - Замовлення в обробці 
                    $status_ = 14; //отправлено
                    break;
                case 4: //4 - Одержаний
                    $status_ = 30; //ЦБ
                    break;
                case 6: //6 - Відмова
                    $status_ = 32; //НЗ
                    break;
                case 31: //31 - Готується до відправлення
                    $status_ = 14; //отправлено
                    break;
                case 32: //32 - Відправлено
                    $status_ = 14; //отправлено
                    break;
                case 33: //33 - Готується до видачі
                    $status_ = 29; //отделение
                    break;
                case 34: //34 - Прибув у відділення
                    $status_ = 29; //отделение
                    break;
                
            }

        /*
        2 - Замовлення в обробці
        4 - Одержаний
        5 - Видалено
        6 - Відмова
        31 - Готується до відправлення
        32 - Відправлено
        33 - Готується до видачі
        34 - Прибув у відділення
        43 - Зворотна доставка - грошовий переказ Global Money
        44 - Зворотна доставка
        */

            //если есть докум5ент на обратную доставку денег
            if ($docum->document)
            {
                $backward_ttnSet = ", backward_ttn = '{$docum->document->attributes()->number}'";   
                if ($docum->document->attributes()->state_id == 43)
                    $status_ = 31; //FP
                elseif ($docum->document->attributes()->state_id == 44)
                    $status_ = 30; //ЦБ
            }

            $query_ = "UPDATE zakazy 
                SET status = $status_ $backward_ttnSet 
                WHERE ttn = '".$docum->attributes()->number."'
                AND status IN (14, 29, 30, 31, 33);";
            mysql_query($query_) or die(mysql_error());

            //Обновляем дату прибытия товара:
            if($status_ == 29 || $status_ == 30 || $status_ == 31) {
                $query_ = "UPDATE zakazy 
                           SET date_arrive = CURDATE() 
                           WHERE date_arrive IS NULL
                           AND ttn = '{$docum->attributes()->number}'";
                mysql_query($query_) or die(mysql_error());
            }
            
            unset($backward_ttnSet);
        }
    }
}

function updateOrderStatus(){
    db_connect();
    
    AddLog("1","<b>Инициировано автоматическое обновление статусов заказов</b>", "NP");

    $timeStart = date("H:i:s d.m.Y");    

    date_default_timezone_set(TIME_ZONE);

    mysql_query("UPDATE last_update SET Time = '".date('Y-m-d H:i:s')."' WHERE ID = 1");

    $query = "SELECT id, ttn 
        FROM zakazy 
        WHERE delivery='Новая Почта' 
        AND status in (14, 29, 30, 31, 33)
        AND date_stat >= '".date('Y-m-d', time()-(7*24*60*60))."'";

    $result = mysql_query($query) or die (mysql_error());
    $result = db_result_to_array($result);
    $barcodeList = '';
    
    for ($i = 0; $i < count($result); $i++){
        if (!empty($result[$i][1]))              
            $barcodeList .= '<barcode>'.$result[$i][1].'</barcode>';
        //отправляем запросы пачками по 100 накладных
        if (($i+1) % 100 == 0){
            $npResponse = getNPTracking($barcodeList);
            updateOrderStatusInDB($npResponse);
            //не забывая очистить список номеров накладных перед следующей отправкой
            $barcodeList = '';
        }
        // print_r($barcodeList);
        // break;
    }
    //ну и в последний раз, если накопилось <100 накладных - отправляем последний раз
    if (!empty($barcodeList)){
        $npResponse = getNPTracking($barcodeList);
        updateOrderStatusInDB($npResponse);
    }
    
    // sleep(5);
    $timeEnd = date("H:i:s d.m.Y");    
 
    mail(ADMIN_MAIL, "Update Order Status", "Обновлено заказов: ".count($result)." 
Начало: ".$timeStart."
Завершение: ".$timeEnd);
    
    AddLog("1","<b>Завершено автоматическое обновление статусов заказов. Обновлено заказов: ".count($result)."</b>", "NP");
}



updateOrderStatus();
?>