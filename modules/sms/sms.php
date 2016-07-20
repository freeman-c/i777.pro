<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/history/historyController.php');

function turboSMSAuth(){
    $client = new SoapClient ('http://turbosms.in.ua/api/wsdl.html'); 

    $auth = Array ( 
        'login' => TurboSMSLogin, 
        'password' => TurboSMSPassword 
    );
    print_r($auth);
    try {
        $result = $client->Auth($auth); // Авторизируемся на сервере 
        
    } catch (Exception $e) {       
    }

    //БАЛАНС - Получаем количество доступных кредитов 
    // $result = $client->GetCreditBalance ();   
    // echo 'Баланс: '.$result->GetCreditBalanceResult.' кредитов'.PHP_EOL;
    if ($result)
        return $client;
    else
        return false;
}

function sendingSMS($turboSMSConnect, $phone, $text){
    $sender = '2016.biz.ua';
    $phone = '+38'.$phone;
    //*********************************************
    $charset = mb_detect_encoding($text);//Определяем кодировку
    //**********************************************
    if($charset=='UTF-8'){
        $text = $text;
    }
    else{
        $text = iconv('windows-1251', 'utf-8', $text);
    }
    $sms = Array ( 
        'sender' => $sender, 
        'destination' => $phone, 
        'text' => $text 
    ); 
    // print_r($sms);
    $result = $turboSMSConnect->SendSMS ($sms);
    print_r($result);
}

function sendSMS($id, $turboSMSConnect){
    db_connect();

    $row = mysql_fetch_assoc(mysql_query("SELECT id, phone, ttn, status, sms3, sms11, sms14, sms29 FROM zakazy WHERE id = {$id}"));
    print_r($row);
    if ($row['status'] == 11 && $row['sms11'] == 0){
        // $templates_sms = getTemplateSMS(8);
        // $text = str_replace('{id}', $row['id'], $templates_sms['text']);
        // sendingSMS($turboSMSConnect, $row['phone'], $text);
    }
    elseif  ($row['status'] == 14 && $row['sms14'] == 0) {
        $templates_sms = getTemplateSMS(1);
        $text = str_replace('{ttn}', $row['ttn'], $templates_sms['text']);
        sendingSMS($turboSMSConnect, $row['phone'], $text);
        AddLog("1","<b>Заказ №{$id}</b> Отпралено СМС <b>\"Заказ отправлен\"</b>", "SMS");
    }
    elseif ($row['status'] == 29 && $row['sms29'] == 0){
        $templates_sms = getTemplateSMS(2);
        $text = str_replace('{ttn}', $row['ttn'], $templates_sms['text']);
        sendingSMS($turboSMSConnect, $row['phone'], $text);
        AddLog("1","<b>Заказ №{$id}</b> Отпралено СМС <b>\"Заказ в отделении\"</b>", "SMS");
    }
    elseif ($row['status'] == 3 && $row['sms3'] == 0){
        // $templates_sms = getTemplateSMS(4);
        // $text = str_replace('{ttn}', $row['ttn'], $templates_sms['text']);
        // sendingSMS($turboSMSConnect, $row['phone'], $text);
        mysql_query("UPDATE zakazy SET sms11 = 1 WHERE id = ".$row['id']);

    }
    mysql_query("UPDATE zakazy SET sms{$row['status']} = 1 WHERE id = ".$row['id']);
}
?>