<?php


//  http://crm.opt.city/modules/binotel/api-call-settings.php/?getCallDomain=test&srcNumber=0638383006

/*
    ВНИМАНИЕ!!!! В целях безопасности, откройте доступ к этому скрипту только с серверов Binotel!
    Сервера Binotel:
        - 194.88.218.114
        - 194.88.218.116
        - 194.88.218.117
 */


function isNewClient($phone){
    db_connect();
    $query = "SELECT id FROM zakazy 
        WHERE phone = '{$phone}' 
        AND cart = 0
        LIMIT 1";
    $result = mysql_query($query) or die(mysql_error());
    $result = mysql_fetch_assoc($result);    if (empty($result))
        return true;
    else
        return false;
}


function checkOrderDublicate($param)
{
    // ПРОВЕРКА НА ДУБЛИ
    //возвращает false если дубль
    switch ($param['type']) {
        case 'OrderFromSite':
            $result = mysql_query("SELECT id FROM zakazy WHERE phone = '".$param['phone']."' AND site = '".$param['site']."' AND status = 3 AND new = 1 AND cart = 0") or die(mysql_error());
            $row = db_result_to_array($result);
            if (count($row) == 0 || empty($row))
                return true;
            break;

        case 'GetCall':
            $result = mysql_query("SELECT id FROM zakazy WHERE phone = '".$param['phone']."' AND site = '".$param['site']."' AND status = 3 AND new = 1 AND cart = 0") or die(mysql_error());
            $row = db_result_to_array($result);
            if (count($row) == 0 || empty($row))
                return true;
            break;
        default:
            return true;
    }
    return false;
}


if ($_SERVER['REMOTE_ADDR'] !== '194.88.218.114' && $_SERVER['REMOTE_ADDR'] !== '194.88.218.116' && $_SERVER['REMOTE_ADDR'] !== '194.88.218.117') {
    die(sprintf('Access denied!%s', PHP_EOL));
}

require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/binotelPhones/binotelPhones.php');
$group = getBinotelGroupByPhone($_REQUEST['didNumber']);
if ($group != 801)
    die();

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/callTask/callTask.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/logging/logging.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/mail/mail.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/banPhone/banPhone.php');

$connection_opt = db_connect();
date_default_timezone_set(TIME_ZONE);

$orderId = date('dmy0Gis');
$date = date('Y-m-d');
$shortDateTtime = date('d.m H:i');

$isNew = isNewClient($_REQUEST['srcNumber']);
$priority = 25;

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

if ($existInBanList)
    $banComment = "ЗАБАНЕН!!! ";

$query = "SELECT id, site, phone, bayer_name, comment
    FROM zakazy 
    WHERE phone ='{$_REQUEST['srcNumber']}'
    AND cart = 0
    ORDER BY id DESC";

$current_order = mysql_query($query) or die("строка 57 ".mysql_error());
$current_order = mysql_fetch_assoc($current_order);

$updateQuery = "UPDATE zakazy
        SET comment = '{$banComment}GC {$shortDateTtime}
{$current_order['comment']}'
        WHERE id = {$current_order['id']}";

mysql_query($updateQuery) or die(mysql_error());


if ($dublicate && !$existInBanList){ 
    addCallTask($current_order['id'], date('Y-m-d H:i:s', time()+30), $priority, 0);

    if($isNew)
        logGetCall($current_order, 'new');
    else
        logGetCall($current_order, 'old');

    sendMailAboutGetCall($current_order);
}

if (!$existInBanList){
    die(json_encode(array(
        'customerData' => array(
            'name' => "{$current_order['bayer_name']}",
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