<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
AddLog('1','Рассылка: Отправлен Email пользователем {user}. Получатель: '.$_POST['email'].'. Тема: '.$_POST['header'].''); 
set_time_limit(600); //время выполнения скрипта (в секундах) 5мин=300, 10мин=600, 20мин=1200
ini_set("memory_limit","128M");
?>
<style>
    .message-send-email-ok{
        padding: 8px;
        color: green;
        font-size: 14px;
    }
</style>
<?php
//sleep(1);

/*$email = explode(", ", $_POST['email']);
echo count($email);
$elements = count($email);
$count = $elements - 1;

$i=0;
$m=0;*/
//for($i; $i <= $count; $i++){
    //echo $email[$m++].'<br>';
        //$to  = $email[$i]; //$to  = $_POST['email'];
        foreach($_POST as $ArrKey => $ArrStr){
            $ArrKey = $_POST[$ArrKey];
        }

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
?>

<script>
    $(document).ready(function(){ 
        var col = $('#send-complete').text();
        $('#send-complete').text(Number(col)+1);        
        var complete = Number($('#send-complete').text());
        
        var width = 800 / <?=$_POST['all']?>;         
        $('#bar').append('<div style="width:'+(width).toFixed(0)+'px;"></div>'); 
        
        var percent = (complete * 100)/<?=$_POST['all']?>;        
        $('#percent').html((percent).toFixed(0)+'%');
        //$('#bar').html('<div style="width:'+(percent).toFixed(0)+'%;"></div>');        
        
            if(complete > <?=$_POST['all'];?>){
                $('#send-mail-result-box').html('<h2 style="color:green;"><p>'+
                                                    '<img src="/image/done.png" style="margin: 0px 2px -2px 0px;"> Рассылка завершена.'+
                                                '</p></h2>'+
                                                '<h2><p>Отправлено писем: <?=$_POST['all'] + 1;?></p></h2>');
                    WaitingBarHide();                        
            }else{}
        //$('#send-mail-result-message').append('<img src="/image/done.png"><php=$email[$m++]?>, ');
        //$('#send-mail-result-message').append('<img src="/image/done.png"><php=$_POST['email']?>, ');
    });
</script>
<?php
            //echo '<span class="message-send-email-ok"><img src="/image/done.png" style="margin: 0px 2px -4px 0px;">'.$_POST['email'].'</span>';
      
}
//}
?>