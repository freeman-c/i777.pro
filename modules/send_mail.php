<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
AddLog('1','Отправлен Email пользователем {user}. Получатель: '.$_POST['email'].'. Тема: '.$_POST['header'].'');
?>
<style>
    .message-send-email-ok{
        padding: 8px;
        color: green;
        font-size: 15px;
    }
</style>
<?php
//sleep(1);

$to  = $_POST['email'];
$dat = date("d.m.Y H:i:s");
$subject = '=?UTF-8?B?'.base64_encode($_POST['header'])."?=";

//$message = substr( nl2br(htmlspecialchars(trim($_POST['message']))) ,0,1000);
$message =  $_POST['message'];

$headers  = 'MIME-Version: 1.0'."\r\n";
$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
$headers .= 'Date: '.$dat."\r\n";; 
$headers .= 'From: '.$_POST['from']."";

$send = mail($to, $subject, $message, $headers);

if($send){
UpdateLimitCurrentEmailValue();
    echo '<p>
            <h2 style="text-align:center;">Письмо успешно отправлено!</h2>
            <div class="message-send-email-ok">Кому: <b>'.$_POST['email'].'</b></div>
          </p>
          <p style="text-align:center;">
                <button class="disabled" onclick="CloseModal();">&nbsp Ok &nbsp</button>
          </p>';  
    
}
?>