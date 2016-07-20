<?php


//  http://crm.opt.city/modules/binotel/api-call-settings.php/?getCallDomain=test&srcNumber=0638383006

/*
    ВНИМАНИЕ!!!! В целях безопасности, откройте доступ к этому скрипту только с серверов Binotel!
    Сервера Binotel:
        - 194.88.218.114
        - 194.88.218.116
        - 194.88.218.117
 */

if ($_SERVER['REMOTE_ADDR'] !== '194.88.218.114' && $_SERVER['REMOTE_ADDR'] !== '194.88.218.116' && $_SERVER['REMOTE_ADDR'] !== '194.88.218.117') {
    die(sprintf('Access denied!%s', PHP_EOL));
}

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/controller.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/callTask/callTask.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/mail/mail.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/banPhone/banPhone.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/binotelPhones/binotelPhones.php');


// AddLog("1","<b>GC Номер клиента:</b> {$_REQUEST['srcNumber']} <b>Номер сотрудника:</b> {$_REQUEST['didNumber']}","GC");

//Если номер назначения пустой - заказ априори на отдел продаж, потому не проверяем группу
if (!empty($_REQUEST['didNumber'])){
    $group = getBinotelGroupByPhone($_REQUEST['didNumber']);
    
    AddLog("1","<b>GetCall на другой отдел | Номер клиента:</b> {$_REQUEST['srcNumber']} | <b>Номер сотрудника:</b> {$_REQUEST['didNumber']}","GC");

    if ($group != 801)
        die();
}

$connection_opt = db_connect();
date_default_timezone_set(TIME_ZONE);

// $orderId = date('dmy0Gis');
$orderId = uniqid("gc_",true);
$date = date('Y-m-d');
$shortDateTtime = date('d.m H:i');

$isNew = isNewClient($_REQUEST['srcNumber']);
$priority = 15;

// ПРОВЕРКА НА ДУБЛИ
$checkedParam = array('type' => 'GetCall', 'phone' => $_REQUEST['srcNumber'], 'site' => $_REQUEST['getCallDomain']);
//если не дубль
$dublicate = checkOrderDublicate($checkedParam);
$existInBanList = existInBanListPhone($_REQUEST['srcNumber']);

if ($dublicate && $isNew && !$existInBanList){ 
    //добавляем геткол как новый заказ
    $query = "INSERT INTO `zakazy` SET 
            order_id='{$orderId}',
            site = '{$_REQUEST['getCallDomain']}',     
            phone='{$_REQUEST['srcNumber']}',      
            date='{$date}',
            date_update='{$date}',
            date_stat='{$date}',                                        
            status='3', 
            delivery='Новая Почта',      
            payment='4',
            new='1',
            cart='0'";
    mysql_query($query, $connection_opt) or die("строка 48 ".mysql_error());

    $priority = 50;
}

$query = "SELECT id, site, phone, bayer_name, comment
    FROM zakazy 
    WHERE phone ='{$_REQUEST['srcNumber']}'
    AND cart = 0
    ORDER BY id DESC";

$current_order = mysql_query($query) or die("строка 57 ".mysql_error());
$current_order = mysql_fetch_assoc($current_order);

if ($existInBanList){
    $banComment = "ЗАБАНЕН!!! ";
    AddLog("1", "GetCall Номер {$current_order['phone']} ЗАБАНЕН", "GC");
}

$updateQuery = "UPDATE zakazy
        SET comment = '{$banComment}GC {$shortDateTtime}
{$current_order['comment']}'
        WHERE id = {$current_order['id']}";

mysql_query($updateQuery) or die(mysql_error());


if ($dublicate && !$existInBanList){ 
    addCallTask($current_order['id'], date('Y-m-d H:i:s', time()+30), $priority, 0);

    if($isNew)
        $logText = "<b>GetCall от нового клиента</b>";
    else
        $logText = "<b>GetCall от существующего клиента</b>";

    $logText .= " | <b>Заказ № {$current_order['id']}</b> | <b>Номер клиента: </b>{$current_order['phone']}";
    if (!empty($current_order['site']))
        $logText .= " | <b>Сайт</b>: {$current_order['site']}";
    if (!empty($_REQUEST['didNumber']))
        $logText .= " | <b>Номер сотрудника:</b> {$_REQUEST['didNumber']}";
    AddLog('1', $logText, "GC");

    sendMailAboutGetCall($current_order);
}

if (!$existInBanList){
    die(json_encode(array(
        'customerData' => array(
            'name' => "{$current_order['bayer_name']}",
            'assignedToEmployeeEmail' => 'test@gmail.com',
            'linkToCrmUrl' => "http://admin.gh1tyuo5.in.ua/?action=zakazy&order={$current_order['id']}&phone={$current_order['phone']}",
            'linkToCrmTitle' => "Открыть заказ в CRM"
        )
    )));
}
else{
    die(json_encode(array(
        'customerData' => array(
            'name' => 'НОМЕР ЗАБАНЕН!!!',
            'assignedToEmployeeEmail' => 'test@gmail.com',
            'linkToCrmUrl' => "http://admin.gh1tyuo5.in.ua/?action=zakazy&order={$current_order['id']}&phone={$current_order['phone']}",
            'linkToCrmTitle' => "НОМЕР ЗАБАНЕН!!!"
        )
    )));
}