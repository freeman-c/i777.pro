<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
//sleep(2);
session_start();
function getOrdersForFilter(){
    db_connect();
    mysql_query('SET NAMES utf8_general_ci;');
    $productId = $_POST['productId'];
    // $utm_source = $_POST['utm_source'];
    $status = $_POST['status'];
    $complete = $_POST['complete'];
    $dateFrom = $_POST['dateFrom'];
    $dateTo = $_POST['dateTo'];
    // $utm_term = $_POST['utm_term'];
    // $utm_content = $_POST['utm_content'];
    // $utm_campaign = $_POST['utm_campaign'];
    $searchingStr = $_POST['searchingStr'];
    $manager = $_POST['manager'];
    // $office = $_POST['office'];
    $payment = $_POST['payment'];
    $delivery = $_POST['delivery'];

    $from = $_POST['page']*$_POST['orderPerPage'];
    $orderPerPage = $_POST['orderPerPage'];

    if($_POST['productId']){ $productCond = "AND po.product_id = $productId";}
    // if($_POST['utm_source']){ $adCond = "AND z.utm_source LIKE '%$utm_source%'";}
    if($_POST['manager']){ $managerCond = "AND z.user = '$manager'"; }
    // if(!empty($_POST['office'])){ $officeCond = "AND z.office = $office"; }
    if(!empty($_POST['payment'])){ $paymentCond = "AND z.payment = $payment"; }
    if($_POST['delivery']){ $deliveryCond = "AND z.delivery = '$delivery'"; }
    
    if(!empty($_POST['status'])){ $statusCond = "AND z.status = $status";}
    if(!empty($_POST['complete'])){ $completeCond = "AND z.date_complete != '0000-00-00'";}

    if($_POST['dateFrom']){ $dateFromCond = "AND z.date_stat >= '$dateFrom'";}
    if($_POST['dateTo']){ $dateToCond = "AND z.date_stat <= '$dateTo'";}
    if($_POST['searchingStr']){ $searchingStrCond = "AND (
           z.bayer_name LIKE '%$searchingStr%' COLLATE utf8_general_ci 
        OR z.id LIKE '%$searchingStr%' 
        OR z.order_id LIKE '%$searchingStr%' COLLATE utf8_general_ci 
        OR z.phone LIKE '%$searchingStr%' COLLATE utf8_general_ci
        OR z.delivery_adress LIKE '%$searchingStr%' COLLATE utf8_general_ci
        OR z.utm_source LIKE '%$searchingStr%' COLLATE utf8_general_ci 
        OR z.utm_medium LIKE '%$searchingStr%' COLLATE utf8_general_ci 
        OR z.utm_term LIKE '%$searchingStr%' COLLATE utf8_general_ci 
        OR z.utm_content LIKE '%$searchingStr%' COLLATE utf8_general_ci 
        OR z.utm_campaign LIKE '%$searchingStr%' COLLATE utf8_general_ci 
        OR z.ttn LIKE '%$searchingStr%' COLLATE utf8_general_ci
        OR z.ip LIKE '%$searchingStr%' COLLATE utf8_general_ci
        OR z.comment LIKE '%$searchingStr%' COLLATE utf8_general_ci
        OR z.site LIKE '%$searchingStr%' COLLATE utf8_general_ci)";}

    // if($utm_source == 'MarketGid'){
    //     if($_POST['utm_term']){ $utm_termCond = "AND z.utm_term LIKE '%$utm_term%'";}
    //     if($_POST['utm_content']){ $utm_contentCond = "AND z.utm_content LIKE '%$utm_content%'";}
    //     if($_POST['utm_campaign']){ $utm_campaignCond = "AND z.utm_campaign LIKE '%$utm_campaign%'";}
    // }

    $query = "SELECT DISTINCT z.id FROM zakazy as z
    LEFT JOIN product_order as po ON z.order_id = po.order_id
    WHERE z.cart = 0 
    $productCond 
    $adCond
    $statusCond 
    $completeCond 
    $dateFromCond 
    $dateToCond 
    $managerCond 
    -- $officeCond 
    $paymentCond 
    $deliveryCond 
    $searchingStrCond 
    ORDER BY z.id DESC
    LIMIT $from, $orderPerPage"; 
    echo $query.PHP_EOL.PHP_EOL;
    $result = mysql_query($query) or die ('ошибка sql'.PHP_EOL.mysql_error());
    $result = db_result_to_array($result);

    $query = "SELECT COUNT(DISTINCT z.id) as totalOrderCount FROM zakazy as z
    LEFT JOIN product_order as po ON z.order_id = po.order_id
    WHERE z.cart = 0 
    $productCond 
    $adCond
    $statusCond 
    $completeCond 
    $dateFromCond 
    $dateToCond 
    -- $utm_termCond 
    -- $utm_contentCond 
    -- $utm_campaignCond 
    $managerCond 
    -- $officeCond 
    $paymentCond 
    $deliveryCond 
    $searchingStrCond"; 
    echo $query.PHP_EOL;
    $totalOrderCount = mysql_query($query) or die ('ошибка sql'.PHP_EOL.mysql_error());
    $totalOrderCount = mysql_fetch_array(($totalOrderCount));
    
    return array($result, $totalOrderCount['totalOrderCount']);
}

function getSimpleOrder($id){  
    $connection = db_connect();  
    $query = "SELECT Z.*, CT.date_time AS CTdt, CT.state AS CTs
        FROM zakazy AS Z
        LEFT JOIN call_tasks AS CT ON CT.order_id = Z.id
        WHERE Z.id = {$id} 
        AND cart = 0";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}

// $zakazy = getOrdersForFilter();
list($zakazy, $totalOrderCount) = getOrdersForFilter();

if($zakazy){
foreach ($zakazy as $z):
    $order = getSimpleOrder($z['id']);
$status = getStatus($order['status']);
$info_name_user = get_user_description_login($order['user']);
if($order['payment'] == 1)
    $finish_class = 'fifnish-row';
else
    $finish_class = '';
    
?>
    <tr class="<?=$finish_class;?>" style="background: <?=$status['color']?>;">
        <td style="width: 40px"> 
        <input type="checkbox" class="selected" name="need_delete[<?=$order['id']?>]" id="checkbox<?=$order['id']?>" title="id: <?=$order['order_id']?>"> 
            <span style="font-size:10px;"><?=$order['id']?></span>
        </td>     
        <td style="padding: 0; text-align: center;">
            <?php 
                if (!empty($order['CTdt']) && $order['CTs'] == 0){ ?>
                     <img src="/image/notification_done.ico" style="width: 12px">
                <?php }
            ?>
        </td>   
        <td style="display: block; width: 16px !important; height: 0; padding: 0; margin: -1px;">
            <img src="/image/edit.png" class="option-button" onclick="setCallTaskState('<?=$order['id']?>', 3); edit_zakaz('<?=$order['id']?>');">
        </td>        
        <td>  
            <?php if($order['new'] > 0){ ?>
                <img src="/image/new_icon_red.gif" class="order-icon-new" style="position: abolute; left: 0px;">
                <span class="order-icon"></span> <?=$order['bayer_name']?>
            <?php }else{ ?>
                &nbsp; &nbsp;&nbsp; 
                <span class="order-icon"></span> <?=$order['bayer_name']?>
            <?php } ?>
        </td> 
        <td>
            <?php 
            $phone = preg_replace('/[^0-9]/', '', $order['phone']);
            ?>
            <a href="tel: <?=$phone?>">
                <img src="/image/call.ico" onclick="edit_zakaz('<?=$order['id']?>');" class="send-sms-icon">
            </a>
            <img src="/image/mobile_phone_arrow.ico" onclick="SendSMS('<?=$phone?>','<?=$order['ttn']?>',event);" class="send-sms-icon">
            <?=$order['phone']?>
        </td>
        <td>
            &nbsp;
        </td>
        <td>    
            <?php if(!empty($order['comment'])){ ?>
            <div style="width:80px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                <img src="/image/info.png" class="tooltip" title="<?=$order['comment']?>" style="margin: 0px 3px -3px 0px; background:#FF9; border:1px solid #F98A15; cursor:help;"><?=$order['comment']?>
            </div>
            <?php } ?>
        </td>       
        <td>
            <div style="width: 100px;">
                <div style="overflow:hidden; text-overflow:ellipsis; width:80px; float:left;">
                    <?php 
                        $products = getProductsOrder($order['order_id']);
                        $t = 0;
                        $sum = 0;
                        foreach ($products as $product):
                            $tovar = getProduct($product['product_id']);
                            if(strlen($tovar['name']) > 0){
                                echo $tovar['name'].', ';
                                $i++; 
                                $sum += $product['quantity'];
                            }else
                                echo '- ? ? ? ? ? ? ? ? -';
                        endforeach;
                    ?>
                </div> 
            </div>
            <span class="count-tovary">
            <?php   if($sum > 0){
                        echo $sum;
                    }else{
                        echo '<span style="color:#FFF; font-weight:bold; font-size:11px; margin: 0px -3px; padding: 0px 3px; background:#F00;">0</span>';
                    }                
                if($sum > 0){    
                ?>
                <div class="popup-box-count-tovary">
                        <div style="min-height:50px; max-height:110px; overflow: auto;">
                            <table width="100%" border="0" cellspacing="0" style="font-family: 'tooltip';">
                                <?php 
                                $tvrn=1;
                                foreach ($products as $product): 
                                    $tovar = getProduct($product['product_id']);?>   
                                <tr>
                                    <td align="center" style="color:#ABABAB; padding: 1px 2px;"><?=$tvrn++;?></td>
                                    <td align="left" style="padding: 1px 2px;"> <div style="width:180px; overflow:hidden; text-overflow:ellipsis;"><?=htmlspecialchars($tovar['name'])?></div> </td>
                                    <td align="right" style="padding: 1px 2px; color:#757575;"> <input type="hidden" id="prod-price-<?=$product['id']?>" value="<?=$product['price']?>"><?=$product['price']?></td>
                                    <td align="right" style="padding: 1px 8px;"> 
                                        <input type="hidden" id="prod-quantity-<?=$product['id']?>" value="<?=$product['quantity']?>">
                                        <?=$product['quantity']?>
                                    </td>
                                    <td align="right" style="padding: 1px 2px;"> <input type="hidden" id="prod-total-price-<?=$product['id']?>"><b style="font-size: 11px;"><?=number_format($product['quantity']*$product['price'],2);?></b></td>
                                    <td align="right" style="padding: 1px 2px;"> 
                                        <b style="font-size: 11px;">
                                            <?php
                                            switch($product['status_buy']){
                                                case 1:
                                                    echo "ОП";
                                                    break;
                                                case 2:
                                                    echo "ДП";
                                                    break;
                                                case 3:
                                                    echo "ПП";
                                                    break;
                                            }
                                            ?>
                                        </b>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </table>                
                        </div>
                </div>
                <?php } ?>
            </span>
        </td>
        <td style="font-weight: bold; padding-left: 16px;">
            <?=$order['total']?>
        </td>
        <td>
            <?php
                if($order['payment']==0){ //- Ожидается -
                    echo '  <span style="color:#C60;">- '.getStatusPaymentName(0).' -</span>';
                }
                if($order['payment']==4){ //Налож. платеж
                    echo '<img src="'.SITE_URL.'/image/mail.ico" style="float:left; margin:0px 2px -1px 0px;">
                            <span style="color:#505E18; font-weight:bold;">'.getStatusPaymentName(4).'</span>';
                }
                if($order['payment']==2){ //Предоплата
                    echo '<img src="'.SITE_URL.'/image/arrow-sublevel.png" style="float:left; margin:0px 2px -1px 0px;">
                            <span style="color:#900; font-weight:bold;">'.getStatusPaymentName(2).'</span>';
                }
                if($order['payment']==1){ //Оплачено
                    echo '<img src="'.SITE_URL.'/image/money.ico" style="float:left; margin:0px 0px -1px 0px;">
                          <span style="color:green; font-weight:bold;">'.getStatusPaymentName(1).'</span>'; 
                }
                if($order['payment']==3){ //Отказ
                    echo '<img src="'.SITE_URL.'/image/cross.ico" style="float:left; margin:0px 2px -1px 0px;">
                            <span style="color:#900; font-weight:bold;">'.getStatusPaymentName(3).'</span>';
                }
                if($order['payment']==5){ //Обмен
                    echo '<img src="'.SITE_URL.'/image/exchange.ico" style="float:left; margin:0px 2px -1px 0px;">
                            <span style="color:#347098; font-weight:bold;">'.getStatusPaymentName(5).'</span>';
                }
            ?>
        </td>
        
        <td style="font-size:11px;"><?=$order['date']?>
        <?php if($order['datetime'] > 0){?>
            <span style="font:9px Arial; color: #ABABAB;"><?=date('H:i',strtotime($order['datetime']));?></span>
        <?php } ?>
        </td>
        <td style="font-size:11px;"><?=$order['date_update'];//date('d.m.Y',strtotime($order['date_update']));?></td>
        <td>
            <?php if($order['delivery']=='Новая Почта'){?>
            <span style="color:#C93E3E; font-weight: bold;">
                <img src="/image/np.ico" style="float:left; margin:-3px 0px -3px 0px;">
                Новая Почта
            </span>            
            <?php }elseif ($order['delivery']=='Укрпочта') {?>
            <span style="color:#3F80C0; font-weight: bold;">
                <img src="/image/ukrposhta.ico" style="float:left; margin:-1px 4px -5px 3px;"> 
                Укрпочта
            </span> 
            <?php }elseif ($order['delivery']=='Интайм') {?>
            <span style="color:#3F80C0; font-weight: bold;">
                <img src="/image/intime.png" style="float:left; margin:-1px 5px -5px 3px;"> 
                &nbsp
            </span>
            <?php }elseif ($order['delivery']=='Автолюкс') {?>
            <span style="color:#3F80C0; font-weight: bold;">
                <img src="/image/avtolux.png" style="float:left; margin:-1px 5px -5px 3px;"> 
                &nbsp
            </span>
            <?php }elseif ($order['delivery']=='Деливери') {?>
            <span style="color:#3F80C0; font-weight: bold;">
                <img src="/image/delivery_ua.png" style="float:left; margin:-1px 5px -5px 3px;"> 
                &nbsp
            </span>
            <?php }elseif ($order['delivery']=='Ночной экспресс') {?>
            <span style="color:#3F80C0; font-weight: bold;">
                <img src="/image/nexpress.png" style="float:left; margin:-1px 5px -5px 3px;"> 
                &nbsp
            </span>
            <?php }elseif ($order['delivery']=='Почта Росии') {?>
            <span style="color:#3F80C0; font-weight: bold;">
                <img src="/image/postrussia.png" style="float:left; margin:-1px 5px -5px 3px;"> 
                &nbsp
            </span>
            <?php }elseif ($order['delivery']=='Курьер Россия') {?>
            <span style="color:#0056A8; font-weight: bold;">
                <img src="/image/pru.ico" style="float:left; margin:-1px 5px -5px 3px;"> 
                Курьер Россия
            </span>
            <?php } else{ echo $order['delivery'];} ?>
        </td>
        <td>
            <div style="width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?=$order['delivery_adress']?>">
                <?=$order['delivery_adress']?>
            </div>            
        </td>
        <td><?=$order['ttn']?></td>
        <td><?=$order['backward_ttn']?></td>
        <td>
            <?php if($order['new'] > 0){ ?>
            <img src="/image/new_icon_red.gif" class="order-icon-new">
            <?php 
            if($order['site']){echo '<span style="color:#F00; font-weight: bold;">'.$order['site'].'</span>';
            } else { echo '<span style="color:#ABABAB;">-добавлен вручную-</span>';}
            
            }else{            
            if($order['site']){echo '<span class="from-site-icon"></span><b>'.$order['site'].'</b>';
            } else { echo '<span style="color:#ABABAB;">-добавлен вручную-</span>';}
            }
            ?>
        </td>
        
    <td>
            <div style=" background:<?=$status['color']?>; margin:0px;">
            <?php 
                if($status['id']==13 && strlen($order['cancel_description'])>0){
                    echo '<div style="padding:1px 5px;">'.$status['name'].'
                        <img src="'.SITE_URL.'/image/help-icons.png" class="tooltip" title="'.$order['cancel_description'].'" style="margin:-2px 0px -2px 2px; cursor: help;">
                            </div>';
                }else{
                    echo '<div style="padding:1px 5px;">'.$status['name'].'</div>';                             
                } 
            ?>
            </div>
        </td>
        <td>
            <?=$order['ip']?>
        </td>
        <td>
            <?=$info_name_user['surname']?> <?=$info_name_user['name']?>
        </td>     
        <td>
            <?php if($order['date_complete'] > 0) {?>
                <img src="/image/done.png" style="float:left; margin:-1px 2px 0px 0px;">
                <span style="color:green; font-weight:bold;"><?=date('d.m.Y',strtotime($order['date_complete']));?></span>
            <?php } ?>
        </td>  
        <td>
            <?=$order['utm_source']?> <span class="slash-separator">/</span> <?=$order['utm_medium']?>
        </td>
        <td>
            <?=$order['utm_campaign']?> <span class="slash-separator">/</span> <?=$order['utm_content']?> <span class="slash-separator">/</span> <?=$order['utm_term']?>
        </td>       
    </tr>
<?php 
endforeach;

$orderRangeFrom = $_POST['page']*$_POST['orderPerPage']+1;

if (($_POST['page']+1)*count($zakazy) % $_POST['orderPerPage'] == 0)
    $orderRangeTo = ($_POST['page'] + 1) * $_POST['orderPerPage'];
else
    $orderRangeTo = ($_POST['page'])*$_POST['orderPerPage'] + count($zakazy);
?>
    <tr>
        <td colspan="23">
            <span class="count-zakazy">Страница <?=$_POST['page']+1?> ( <?=$orderRangeFrom;?> - <?=$orderRangeTo?> ) 
             ВСЕГО: <?=$totalOrderCount?></span>
        </td>
    </tr>
    <tr style="display: none;">
        <td colspan="23">
            <input id="order-count" type="text" value="<?=$totalOrderCount?>">
        </td>
    </tr>
<?php
}
else{ ?>
    <tr>
        <td colspan="19" align="center"><h3>По заданным критериям поиска ничего не найдено!</h3></td>
    </tr>
    <tr style="display: none;">
        <td colspan="23">
            <input id="order-count" type="text" value="0">
        </td>
    </tr>
<?php }
?>
