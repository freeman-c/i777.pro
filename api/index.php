<?php
error_reporting(1);
require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/controller.php');

date_default_timezone_set(TIME_ZONE);

if($_GET["data"]){
    $data = $_GET["data"];    
    $decode = urldecode($data);
    $arr = unserialize($decode);    
    
    db_connect();
    $phone = preg_replace('/[^0-9]/', '', $arr['phone']);
    $arr['order_id'] = uniqid('in_',true);
    
$checkedParam = array('type' => 'OrderFromSite', 'phone' => $phone, 'ip' => $arr['ip'], 'site' => $arr['site']);

if (checkOrderDublicate($checkedParam)){  
    mysql_query("INSERT INTO `zakazy` SET 
            order_id='".$arr['order_id']."', 
            site='".$arr['site']."',
            bayer_name='".$arr['bayer_name']."',    
            phone='".$phone."',     
            email='".$arr['email']."',  
            total='".$arr['total']."',  
            date='".date('Y-m-d')."',
            date_update='".date('Y-m-d')."', 
            date_stat='".date('Y-m-d')."',
            status='3',     
            ip='".$arr['ip']."', 
            new='1',
            cart='0',
            utm_source='".$arr['utm_source']."',
            utm_medium='".$arr['utm_medium']."',
            utm_term='".$arr['utm_term']."',
            utm_content='".$arr['utm_content']."',
            utm_campaign='".$arr['utm_campaign']."',
            comment='".$arr['comment']."' ") or die(mysql_error()); 

    mysql_query("INSERT INTO `product_order` SET  
            order_id='".$arr['order_id']."',    
            product_id='".$arr['product_id']."',    
            price='".$arr['price']."',  
            status_buy = 1,
            quantity='".$arr['count']."',   
            date='".date('Y-m-d')."'") or die(mysql_error()); 

    $query = "SELECT id, site, phone, bayer_name, ip
        FROM zakazy 
        WHERE phone ='{$phone}'
        ORDER BY id DESC";

    $current_order = mysql_query($query) or die(mysql_error());
    $current_order = mysql_fetch_assoc($current_order);
 
    addCallTask($current_order['id'], date('Y-m-d H:i:s', time()+30), 30, 0);

    logOrderFromSite($current_order);

    sendMailAboutNewOrder2($arr['bayer_name'], $arr['phone'], $arr['subject']);  

    if ($row['phone'] != '0638383006' && $row['phone']!= '0673387578' && $row['phone']!= '1111111111')
        sendSMS($arr['order_id']);
}
    sendMailAboutNewOrder($arr, $row);

}else{
    echo '<p>Error! Data not available!</p>';
}
?>
