<h2>Корзина
    <span id="panel-button-operation">
        <button class="button-edit" id="button-restore" onclick="restore_zakazy();">Восстановить</button>
        <button class="button-error" id="button-operation-delete" onclick="destroy_zakaz();">Удалить <span id="count-elements-delete"></span></button>
    </span>
</h2>

<style>
    #table-list{
        background: #FFF;
        font-size: 12px;
    }
    #table-list thead th{
        padding: 5px 5px;
        white-space: nowrap;
    }
    #table-list tbody tr:hover{
        background: #FF9;
    }
    #table-list tbody td{
        padding: 1px 5px;
        white-space: nowrap;
        text-shadow: none;
        color: #757575;
        font-size: 12px;
    }
    #button-restore{
        display: none;
    }
</style>
<br>
<?php 
$orders = getOrdersInCart();
if($orders){?>

<form id="form-zakazy">  
    <table id="table-list" class="table-box-orders" border="0" cellspacing="0">
    <thead>
        <tr>
            <th> 
                <div id="box-input-select-all">
                    <input type="checkbox" id="select-all-checkbox">
                    <div class="box-arrow-down"></div>
                </div> 
            </th>
            <th>Номер</th>
            <th>Покупатель</th>
            <th>Телефон</th>
            <th>Статус</th>
            <th>Источник</th>
            <th>Товар</th>
            <th>Всего</th>
            <th>Оформил</th>
            <th><b style="color:#FFF;">opt</b></th>
    </thead>
    <tbody>
<?php 
foreach ($orders as $order):
    $status = getStatus($order['status']);
?>
        <tr>
            <td><input type="checkbox" class="selected" name="need_delete[<?=$order['id']?>]" id="checkbox<?=$order['id']?>" title="id: <?=$order['id']?>"> </td>
            <td style="font-family:'tooltip'; font-size:11px;"><?=$order['id']?></td>
            <td>                
                <div style="max-width:240px; overflow:hidden; text-overflow:ellipsis;">
                    <span class="order-icon"></span><?=$order['bayer_name']?>
                </div>
            </td>
            <td><div style="max-width:110px; overflow:hidden; text-overflow:ellipsis;"><?=$order['phone']?></div></td>
            <!--<td><?php //$order['email']?> </td>-->          
            <td>
                <div style="padding:1px 2px; height:12px; border: 1px solid #EEE; background:<?=$status['color']?> ;" title="<?=$status['name']?>"></div>
            </td>
            <td style="font-family:'tooltip'; font-size:11px;"><?=$order['site']?></td>
            <td><?=$order['product']?></td>
            <td><?=$order['total']?></td>
            <td><?php if($order['user']){$manager = get_user_description_login($order['user']);?><span style="color: #3370A6;"><?=$order['user']?></span> (<span style="font-family:'tooltip'; font-size:11px;"><?=$manager['surname']?> <?=$manager['name']?></span>)<?php } ?></td>
            <td width="16px">
            <img class="option-button" src="<?=SITE_URL?>/image/info.png" onclick="info_order('<?=$order['id']?>');">
            </td>
        </tr> 
<?php endforeach; ?>
        </tbody>
    </table>
    </form> 
<?php }else{ echo '<h3>Нет удалённых заказов.</h3>';} ?>