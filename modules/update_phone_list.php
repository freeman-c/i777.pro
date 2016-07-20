<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
//sleep(1);
function getEmailsInEmptyGroupsClients(){
    db_connect();    
    $query = "SELECT * FROM `clients`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getEmailsInGroupsClients($type){
    db_connect();    
    $query = "SELECT * FROM `clients` WHERE type='$type' ORDER BY id DESC";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
//***********************************************************************
if(empty($_POST['group_id'])){
    $clients_upd = getEmailsInEmptyGroupsClients();
}else{
    $clients_upd = getEmailsInGroupsClients($_POST['group_id']);
}
//***********************************************************************
$i=0;
foreach ($clients_upd as $client_upd):
    $phone = preg_replace('/[^0-9]/', '', $client_upd['phone']); //убираем всё, кроме цифр
    $first_symbol = substr($phone, 0, 1); //проверяем первый символ начала строки
    if($first_symbol=='0'){$phone = '+38'.$phone;}
    if($first_symbol=='8'){$phone = '+3'.$phone;}
    if($first_symbol=='3'){$phone = '+'.$phone;}
    $number = substr($phone, 0, 1); //если номер начинается с "+"
    if(strlen($phone) == 13 && $number=='+'){ //если 13 символов 
        $i++;
?>
<div>
<input type="checkbox" class="sel-mail" name="<?=$phone?>" id="<?=$client_upd['id']?>">
<?=$phone?>
<span style="color:#ABABAB;"> - <?=$client_upd['name']?></span>
<span style="color:#ABABAB; float: right; margin-right: 4px; font-weight: 100;"> <?=$client_upd['site']?></span>
</div>
<?php } endforeach; ?>