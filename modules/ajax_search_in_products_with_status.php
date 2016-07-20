<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
//sleep(2);
session_start();
function AjaxSearchProductOrders($product_id){
    db_connect();
    $query = "SELECT order_id,product_id FROM `product_order` WHERE product_id='$product_id' ORDER BY date DESC"; 
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function AjaxSearchOrders($order_id){
    db_connect();    
    $sort = "AND status='".$_GET['status']."' AND cart < 1";
    $between = "";
    if($_GET['complete']){ $sort = "AND status='".$_GET['status']."' AND cart < 1 AND date_complete > 0";}
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date <= '".$_GET['d_end']."'";} // AND date_update >= '2014-06-13' AND date_update <= '2014-06-15'

$session_office = "";
$session_payment = "";
$session_delivery = "";
$session_manager = "";
if(isset($_SESSION['user']['office'])){$session_office = "AND office='".$_SESSION['user']['office']."'";} 
if(isset($_SESSION['user']['payment'])){$session_payment = "AND payment= {$_SESSION['user']['payment']}"; } 
if(isset($_SESSION['user']['delivery'])){$session_delivery = "AND delivery='".$_SESSION['user']['delivery']."'";} 
if(isset($_SESSION['user']['manager'])){$session_manager = "AND user='".$_SESSION['user']['manager']."'";}  
    
    $query = "SELECT * FROM `zakazy` WHERE order_id='$order_id' $session_office $session_payment $session_delivery $session_manager $sort $between";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}

$zakazy = AjaxSearchProductOrders($_GET['product_id']);
if($zakazy){
foreach ($zakazy as $z):
    $order = AjaxSearchOrders($z['order_id']);
if($z['order_id']=$order['id']){

$status = getStatus($order['status']);
if(strlen($order['payment'] == 1)){
    $finish_class = 'fifnish-row';
}else{ $finish_class = '';}
?>
<?php if ( $order['id'] != $ddd ){ ?>
    <tr class="<?=$finish_class;?>" style="background: <?=$status['color']?>;">
        <td> <input type="checkbox" class="selected" name="need_delete[<?=$order['id']?>]" id="checkbox<?=$order['id']?>" title="id: <?=$order['order_id']?>"> 
            <span style="font-size:10px;"><?=$order['id']?></span>
        </td>        
        <td>
            <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_zakaz('<?=$order['id']?>');">
            <!--<img src="<?=SITE_URL?>/image/del.png" class="option-button">-->
        </td>        
        <td>  
            <!--<div style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; width:260px;">-->
            <?php if($order['new'] > 0){ ?>
                <img src="<?=SITE_URL?>/image/new_icon_red.gif" class="order-icon-new" style="position: abolute; left: 0px;">
                <span class="order-icon"></span> <?=$order['bayer_name']?>
            <?php }else{ ?>
                &nbsp; &nbsp;&nbsp; 
                <span class="order-icon"></span> <?=$order['bayer_name']?>
            <?php } ?>            
            <?php //$order['bayer_surname']?>              
            <?php //$order['bayer_lastname']?>
            <!--</div>-->
        </td> 
        <td>
            <?php 
            $phone = preg_replace('/[^0-9]/', '', $order['phone']); //убираем всё, кроме цифр
            /*$first_symbol = substr($phone, 0, 1); //проверяем первый символ начала строки
            if($first_symbol=='0'){$phone = '38'.$phone;}
            if($first_symbol=='8'){$phone = '3'.$phone;}
            if($first_symbol=='3'){$phone = ''.$phone;}
            if($first_symbol=='7'){$phone = '+'.$phone;}*/
            ?>
            <img src="<?=SITE_URL?>/image/mobile_phone_arrow.ico" onclick="SendSMS('<?=$phone?>','<?=$order['ttn']?>',event);" class="send-sms-icon"><?=$order['phone']?></td>
        <td>   
            <?php if(strlen($order['comment']) > 1){ ?>
            <div style="width:80px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                <img src="<?=SITE_URL?>/image/info.png" class="tooltip" title="<?=$order['comment']?>" style="margin: 0px 3px -3px 0px; background:#FF9; border:1px solid #F98A15; cursor:help;"><?=$order['comment']?>
            </div>
            <?php } ?>
        </td>		
	<td>
            <div style="width: 100px;">
                <div style="overflow:hidden; text-overflow:ellipsis; width:auto; width:80px; float:left;">
                    <?php 
                        $tovary = getProductsOrder($order['order_id']);
                        foreach ($tovary as $t):
                            $product = getProduct($t['product_id']);
                            if(strlen($product['name']) > 0){
                                echo $product['name'].', ';
                            }else{
                                echo '- ? ? ? ? ? ? ? ? -';
                            }
                        endforeach;
                    ?>
                </div> 
            </div>
            <!--<img src="<?=SITE_URL?>/image/help-icons.png" title="" style="float: right;">-->
            <span class="count-tovary">
                <?php if(count($tovary) > 0){
                    echo count($tovary);}
                    else{echo '<span style="color:red;">'.count($tovary).'</span>';}
                
                if(count($tovary) > 0){    
                ?>
                <div class="popup-box-count-tovary">
                        <div style="height: 46px; overflow: auto;">
                            <table width="100%" border="0" cellspacing="0" style="font-family: 'tooltip';">
                                <?php 
                                    foreach ($tovary as $one_tov):
                                        $one_product = getProduct($one_tov['product_id']);?>
                                <tr>
                                    <td style="padding: 0px 2px;"> <div style="width:220px; overflow:hidden; text-overflow:ellipsis;"><?=$one_product['name']?></div></td>
                                    <td style="padding: 0px 3px;" align="right"><?=$one_product['price']?> <span style="color: #ABABAB;"><?=getProductValuteSymbol($one_product['id'])?></span></td>
                                    <td style="padding: 0px 3px;" align="right"><?=$one_product['quantity']?></td>
                                </tr> 
                                <?php endforeach; ?>                                
                            </table>                
                        </div>
                </div>
                <?php } ?>
            </span>
        </td>
        
        <td style="font-weight: bold; padding-left: 16px;"><?=$order['total']?></td>
        
        <td>
            <?php
                if($order['payment']==0){ //- Ожидается -
                    //echo '<img src="'.SITE_URL.'/image/hourglass.ico" style="float:left; margin:0px 2px -1px 0px;">
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
        
	<td><?php echo $order['date'];//date('d.m.Y',strtotime($order['date']));?></td>
        <td><?php echo $order['date_update'];//date('d.m.Y',strtotime($order['date_update']));?></td>
        
        <td>
            <?php if($order['delivery']=='Новая Почта'){?>
            <span style="color:#C93E3E; font-weight: bold;">
                <img src="<?=SITE_URL?>/image/np.ico" style="float:left; margin:-3px 0px -3px 0px;">
                Новая Почта
            </span>            
            <?php }elseif ($order['delivery']=='Укрпочта') {?>
            <span style="color:#3F80C0; font-weight: bold;">
                <img src="<?=SITE_URL?>/image/ukrposhta.ico" style="float:left; margin:-1px 4px -5px 3px;"> 
                Укрпочта
            </span> 
            <?php }elseif ($order['delivery']=='Интайм') {?>
            <span style="color:#3F80C0; font-weight: bold;">
                <img src="<?=SITE_URL?>/image/intime.png" style="float:left; margin:-1px 5px -5px 3px;"> 
                &nbsp
            </span>
            <?php }elseif ($order['delivery']=='Автолюкс') {?>
            <span style="color:#3F80C0; font-weight: bold;">
                <img src="<?=SITE_URL?>/image/avtolux.png" style="float:left; margin:-1px 5px -5px 3px;"> 
                &nbsp
            </span>
            <?php }elseif ($order['delivery']=='Деливери') {?>
            <span style="color:#3F80C0; font-weight: bold;">
                <img src="<?=SITE_URL?>/image/delivery_ua.png" style="float:left; margin:-1px 5px -5px 3px;"> 
                &nbsp
            </span>
            <?php }elseif ($order['delivery']=='Ночной экспресс') {?>
            <span style="color:#3F80C0; font-weight: bold;">
                <img src="<?=SITE_URL?>/image/nexpress.png" style="float:left; margin:-1px 5px -5px 3px;"> 
                &nbsp
            </span>
            <?php }elseif ($order['delivery']=='Почта Росии') {?>
            <span style="color:#3F80C0; font-weight: bold;">
                <img src="<?=SITE_URL?>/image/postrussia.png" style="float:left; margin:-1px 5px -5px 3px;"> 
                &nbsp
            </span>
            <?php }elseif ($order['delivery']=='Курьер Россия') {?>
            <span style="color:#0056A8; font-weight: bold;">
                <img src="<?=SITE_URL?>/image/pru.ico" style="float:left; margin:-1px 5px -5px 3px;"> 
                Курьер Россия
            </span>
            <?php } else{ echo $order['delivery'];} ?>
        </td>
        <td><?=$order['delivery_adress']?></td>
        <td><?=$order['ttn']?></td>
        
        <td>
            <?php if($order['new'] > 0){ ?>
            <img src="<?=SITE_URL?>/image/new_icon_red.gif" class="order-icon-new">
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
            <?php if($order['email']){ ?>            
            <span>
                <img src="<?=SITE_URL?>/image/email_go.ico" onclick="SendMail('<?=$order['bayer_name']?>','<?=$order['email']?>');" class="send-email-icon">
                <?=$order['email']?></span>
            <?php }else{ ?>
            <div>- нет данных -</div>
            <?php } ?>
        </td>
        
        <td>
            <?php
            $info_name_user = get_user_description_login($order['user']);
            ?>
            <?=$info_name_user['surname']?> <?=$info_name_user['name']?>
        </td>
        <td>
            <?php $p_w_office = getOffice($order['office']); ?>
                    <?=$p_w_office['name']?>
        </td>        
        <td>
            <?php if($order['date_complete'] > 0) {?>
                <img src="<?=SITE_URL?>/image/done.png" style="float:left; margin:-1px 2px 0px 0px;">
                <span style="color:green; font-weight:bold;"><?=date('d.m.Y',strtotime($order['date_complete']));?></span>
            <?php } ?>
        </td>      
		<td>
			<?=$order['utm_source']?> <span class="slash-separator">/</span> <?=$order['utm_medium']?>
		</td>
		<td>
			<?=$order['utm_term']?> <span class="slash-separator">/</span> <?=$order['utm_content']?> <span class="slash-separator">/</span> <?=$order['utm_campaign']?>
		</td>	
    </tr>
     <?php } ?>
   <?php $ddd = $order['id']; ?>
<?php 
}
endforeach;
}else{
     $select_product = getProduct($_GET['product_id']);
     echo '<tr><td colspan="19" align="center"><h3>По товару "'.$select_product['name'].'" не найдено данных!</h3></td></tr>';}
?>