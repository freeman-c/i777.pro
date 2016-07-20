<h2>Отправка
    <span id="panel-button-operation">        
        <button class="button-edit" onclick="printBlock('#print-curier');">
            <img src="<?=SITE_URL?>/image/print.ico" style="margin: 0px 4px -2px 0px;">Печать</button>
    </span>
</h2>
<div style="height: 8px;"></div>
<div style="padding: 4px 8px 8px;">
    Отдел: 
    <select id="session_office" onchange="SESSION_SELCTS(event);">
        <?php if($_SESSION['user']['office']){ 
            $session_office_info = getOffice($_SESSION['user']['office']);
        ?>   
            <option value="<?=$session_office_info['id']?>"><?=$session_office_info['name']?></option>
        <?php }else{ ?>
            <option value="">- Все -</option>
        <?php } ?>
        <option disabled>- - - - - - - - - -</option>
        <?php 
            $offices_list = getOffices();
            foreach ($offices_list as $office_one){
        ?>
        <option value="<?=$office_one['id']?>"><?=$office_one['name']?></option>
        <?php } ?>
        <option value="">- Все -</option>
    </select>
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    Статус оплаты: 
    <select id="session_payment" onchange="SESSION_SELCTS(event);">
        <?php if($_SESSION['user']['payment'] || $_SESSION['user']['payment']=='0'){ 
            $payment_id = $_SESSION['user']['payment'];
            $session_status_payment = getStatusPayment($payment_id);
        ?>   
            <option value="<?=$session_status_payment['id']?>"><?=$session_status_payment['name']?></option>
        <?php }else{ ?>
            <option value="">- Все -</option>
        <?php } ?>
        <option disabled>- - - - - - - - - -</option>
        <?php 
            $status_payment_list = getStatusyPayment();
            foreach ($status_payment_list as $status_payment_one){
        ?>
        <option value="<?=$status_payment_one['id']?>"><?=$status_payment_one['name']?></option>
        <?php } ?>
        <option value="">- Все -</option>
    </select>
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    Способ доставки: 
    <select id="session_delivery" onchange="SESSION_SELCTS(event);">
        <?php if($_SESSION['user']['delivery']){ ?>   
            <option value="<?=$_SESSION['user']['delivery']?>"><?=$_SESSION['user']['delivery']?></option>
        <?php }else{ ?>
            <option value="">- Все -</option>
        <?php } ?>
        <option disabled>- - - - - - - - - -</option>
        <option value="Новая Почта">Новая Почта</option>
        <option value="Самовывоз">Самовывоз</option>
        <option value="Укрпочта">Укрпочта</option>
        <option value="Интайм">Интайм</option>
        <option value="Автолюкс">Автолюкс</option>
        <option value="Деливери">Деливери</option>
        <option value="Ночной экспресс">Ночной экспресс</option>
        <option value="">- Все -</option>
    </select>
</div>

<div id="print-curier">
<style> 
    .table-curier{
        min-width: 720px !important;
    }
    .personal-id{
        border: 1px solid #FFF;
        color: #CCC;
        text-align: center;
        padding: 0px 1px;
        font-size: 10px;
    }
    .title-doc-curier{
        text-align: center;
        border-bottom: 2px solid #4A8CC7;
        padding: 2px 4px;
        display: inline-block;
        width: auto;
        margin: 0 auto;
    }
</style>

<div class="title-doc-curier">На сегодня <?=date('d.m.Y');?> г.</div>
<br>
<table id="table-list" class="table-curier" border="0" cellspacing="0">
    <thead>
    <tr>
        <td align="center">id заказа</td>
        <td align="center">Служба</td>
        <td align="center">Адрес доставки</td>
        <td align="center">Телефон</td>
        <td align="center">Получатель</td>
        <td align="center">Товар</td>
        <td align="center">Сумма</td>
        <td align="center">Дата</td>
        <td align="center">Офис</td>
        <td align="center">Комментарий</td>
		<td align="center">Оплата</td>
        <!--<td width="20px"></td>-->
    </tr>
    </thead>
    <tbody> 
<tr>
<td colspan="11" align="center" style="background:#C4D6E4; color:#04528C; font-weight:bold;">'Розница'</td>'    
</tr>
<?php

$offices = getOffices();
foreach ($offices as $office):
echo '<tr>';
echo '<td colspan="11" align="center" style="background:#C4D6E4; color:#04528C; font-weight:bold;">'.$office['name'].'</td>';    
echo '</tr>';
$orders = getOrdersForCurier();

if($orders){
?>
     
<?php foreach ($orders as $order):

$mesto_r = Authorization($order['user']);
//$mesto = getOffice($mesto_r['place_work']);
//$mesto = $order['office'];

//if($office['id']==$mesto['id']){   
if($office['id']==$order['office']){ 

if($order['date_complete']=='0000-00-00'){
?>        
    <tr>
        <td>
            <div class="personal-id" title="<?=$order['id']?>"><?=$order['id']?></div>
        </td>
        <td> 
            <?=$order['delivery']?>
        </td>
        <td><?=$order['delivery_adress']?></td>
        <td  align="center"><?=$order['phone']?></td>
        <td><?=$order['bayer_name']?></td> 
        <td>
            <?php 
                $prods_order = getProductsInOrder($order['order_id']);
                foreach ($prods_order as $prod_order):
                $tovar = getProduct($prod_order['product_id']);
            ?>
            
            <?=$tovar['name']?> - (<?=$prod_order['quantity']?> шт.)<br>
            <?php endforeach; ?>
        </td>
        <td style="color: #900;"><?=$order['total']?></td>
        <td><?=date('d.m.Y',strtotime($order['date']));?></td>
        <td style="color: #757575;">
            <?php 
                //$place_work = Authorization($order['user']);
                //$place = getOffice($place_work['place_work']);
				$place = getOffice($order['office']);
            ?>
            <?=$place['name']?>
        </td>
        <td style="font-size:11px;"><?=$order['comment']?></td>
		<td style="color: #900;">
			<?php $stat_payment = getStatusPayment($order['payment']);?>
			<?=$stat_payment['name']?>
		</td>
        
        <!--<td>
            <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_zakaz('<?=$order['id']?>');">
        </td>-->
    </tr> 

    
<?php 
}
}
//else{ echo '<h3 style="color:#F00;">Cегодня заказов для отправки нет.</h3>';}
endforeach; 
} else{ echo '<tr><td align="center" colspan="11"><h3>Нет отправленных заказов.</h3></td></tr>';} 
    ?>  

<?php endforeach; ?>
<tr>
<td colspan="12" align="center" style="background:#C4D6E4; color:#04528C; font-weight:bold;">'Опт'</td>    
</tr>
<?php

$offices2 = getOffices();
foreach ($offices2 as $office2):
echo '<tr>';
echo '<td colspan="11" align="center" style="background:#C4D6E4; color:#04528C; font-weight:bold;">'.$office2['name'].'</td>';    
echo '</tr>';
$orders2 = getOrdersForCurier();

if($orders2){
?>
     
<?php foreach ($orders2 as $order2):

$mesto_r2 = Authorization($order2['user']);
//$mesto = getOffice($mesto_r['place_work']);
//$mesto = $order['office'];

//if($office['id']==$mesto['id']){   
if($office2['id']==$order2['office']){ 

if($order2['date_complete']=='0000-00-00'){
?>        
    <tr>
        <td>
            <div class="personal-id" title="<?=$order['id']?>"><?=$order2['id']?></div>
        </td>
        <td> 
            <?=$order2['delivery']?>
        </td>
        <td><?=$order2['delivery_adress']?></td>
        <td  align="center"><?=$order2['phone']?></td>
        <td><?=$order2['bayer_name']?></td> 
        <td>
            <?php 
                $prods_order2 = getProductsInOrder($order2['order_id']);
                foreach ($prods_order2 as $prod_order2):
                $tovar2 = getProduct($prod_order2['product_id']);
            ?>
            
            <?=$tovar2['name']?> - (<?=$prod_order2['quantity']?> шт.)<br>
            <?php endforeach; ?>
        </td>
        <td style="color: #900;"><?=$order2['total']?></td>
        <td><?=date('d.m.Y',strtotime($order2['date']));?></td>
        <td style="color: #757575;">
            <?php 
                //$place_work = Authorization($order['user']);
                //$place = getOffice($place_work['place_work']);
                $place2 = getOffice($order2['office']);
            ?>
            <?=$place2['name']?>
        </td>
        <td style="font-size:11px;"><?=$order2['comment']?></td>
        <td style="color: #900;">
            <?php $stat_payment2 = getStatusPayment($order2['payment']);?>
            <?=$stat_payment2['name']?>
        </td>
        
        <!--<td>
            <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_zakaz('<?=$order['id']?>');">
        </td>-->
    </tr> 

    <?php 
}
}
//else{ echo '<h3 style="color:#F00;">Cегодня заказов для отправки нет.</h3>';}
endforeach; 
} else{ echo '<tr><td align="center" colspan="11"><h3>Нет отправленных заказов.</h3></td></tr>';} 
    ?>  

<?php endforeach; ?>


    </tbody>
</table>

</div>

