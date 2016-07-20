<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
AddLog('1','Отправлено SMS пользователем {user} на номер '.$_POST['destination'].'. Отправитель: '.$_POST['sender'].'');
header ('Content-type: text/html; charset=utf-8');
//sleep(1);
// Подключаемся к серверу 
$client = new SoapClient ('http://turbosms.in.ua/api/wsdl.html');

$auth = Array ( 
'login' => 'ivan_palcun', 
'password' => 'fghrukr67' 
);
$result = $client->Auth ($auth); // Авторизируемся на сервере 
//echo $result->AuthResult.''; // Результат авторизации 

// БАЛАНС - Получаем количество доступных кредитов 
//$result = $client->GetCreditBalance ();   echo 'Баланс: '.$result->GetCreditBalanceResult.' кредитов.<br>';

//*********************************************
$charset = mb_detect_encoding($_POST['text']);//Определяем кодировку
//**********************************************
if($charset=='UTF-8'){
    $text = $_POST['text'];
}else{
    $text = iconv('windows-1251', 'utf-8', $_POST['text']);
}

echo '<div style="color:#757575;"><b>Получатель: </b></div>';
echo '<div style="max-height:180px; overflow:auto; border:1px solid #EEE;">'.$_POST['destination'].'</div>';
$count_phone = explode(",", $_POST['destination']);
echo '<b>Телефонных номеров: </b>'.count($count_phone).'';
echo '<hr>';
echo '<div style="color:#757575;"><b>Отправитель: </b>'.$_POST['sender'].'</div>';
echo '<hr>';
echo '<div style="color:#757575;"><b>Сообщение: </b><br>'.$text.'</div>';

$sms = Array ( 
'sender' => $_POST['sender'], 
'destination' => $_POST['destination'], 
'text' => $text 
); 
$result = $client->SendSMS ($sms);

if($result){
    echo '<p><h2 style="text-align:center;">Сообщение отправлено!</h2></p>';    
}
//**********************************
//echo '<br>Результат отправки:<br>';
// Выводим результат отправки. 
//echo $result->SendSMSResult->ResultArray[0] . '';
// ID первого сообщения 
//echo $result->SendSMSResult->ResultArray[1] . ''; 
// ID второго сообщения 
//echo $result->SendSMSResult->ResultArray[2] . '';

// Запрашиваем статус конкретного сообщения по ID 
//$sms = Array ('MessageId' => 'c9482a41-27d1-44f8-bd5c-d34104ca5ba9'); 
//$status = $client->GetMessageStatus ($sms); 
//echo $status->GetMessageStatusResult . '';
?>