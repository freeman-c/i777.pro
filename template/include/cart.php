<style>
    #table-list-data td{
        padding: 1px 4px;
    }
    #discont{
        color: #C60;
    }
    #tovar-dopropdaja{
        background: #CCC;
        padding: 8px 10px;
        position: relative;
    }
    #close-doprodaja{
        position: absolute;
        top: -5px;
        right: 0px;
        font-weight: bold;
        cursor: pointer;
    }    
</style>
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$order = getOrder($_GET['id']);
$status = getStatus($order['status']);
$statusy = getStatusy();
?>
<script>
function Product_Order(){
    var id = '<?=$order['order_id']?>';
    $('#order-product').load('/template/include/order_product.php?order_id='+id+'');
}
$(document).ready(function(){
    Product_Order();
});    
</script>

<form id="forma-zakazy">
<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td colspan="2" align="center" style="font-weight:bold;">
            Заказ № <?=$order['id']?> от <?=$order['date']?>
            <hr>
        </td>      
    </tr>
    <tr>
        <td align="right"><b>Покупатель:</b></td>
        <td><?=$order['bayer_name']?></td>        
    </tr>
    <tr>
        <td align="right"><b>Телефон:</b></td>
        <td>
            <?=$order['phone']?>
            <?php 
                if(strlen($order['user'])) {                
                $user_info = get_user_description_login($order['user']); 
                ?> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                <b>Оформил:</b> <span style="color:#3370A6; font-weight:bold;"><?=$user_info['surname']?> <?=$user_info['name']?> <?=$user_info['lastname']?></b>
             </span>              
            <?php } ?> 
        </td>        
    </tr>
    <tr>
        <td align="right"><b>Email:</b></td>
        <td><?=$order['email']?></td>        
    </tr>
    <tr>
        <td colspan="2" align="center">
            <hr>Товар:
            <div id="order-product"></div>
            
            <!--<?=$order['product']?>
            (
            <b><?=$order['price']?></b> x 
            <b><?=$order['quantity']?></b> =                    
            <b><?=$order['total_price']?></b>
            ) со скидкой            
            (<b><?=$order['discont']?></b>) %
            = 
            <b><?=$order['total']?></b>-->
            
            <div style="text-align:right;">
            Всего:  
                <input type="text" name="total" size="7" value="<?=$order['total']?>" readonly style="color:#900; font-weight:bold; cursor:no-drop;">
            </div> 
            
        </td>        
    </tr>
    <tr>
        <td colspan="2" align="center" style="font-weight:bold;"><hr>Доставка:</td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <b>Тип:</b> <?=$order['delivery']?> &nbsp; &nbsp;  
            <b>Адрес:</b> <?=$order['delivery_adress']?> &nbsp; &nbsp; 
            <b>ТТН:</b> <?=$order['ttn']?> &nbsp; &nbsp; 
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <hr>  
<?php 
if( $order['date_complete'] == '0000-00-00' or empty($order['date_complete']) ){
    $complete='';
    $date_complete = 'disabled';
    $order['date_complete']='0000-00-00';
}else{   
    $complete='checked'; 
    $date_complete = 'readonly';
} 
?>
            <input type="checkbox" id="complete_status" <?=$complete?>> Сдано заказ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            <input type="text" name="date_complete" <?=$date_complete?> size="10" value="<?=$order['date_complete']?>" style="cursor:no-drop;">
        </td>
    <tr>
        <td colspan="2" align="center"><hr></td>
    </tr> 
    
    <tr>
        <td colspan="2">
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        <b>Статус заказа:</b>           
            <?=$status['name']?>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<span id="cancel_description">
    <b>Причина отказа:</b> <br><?=$order['cancel_description']?>
</span>           
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        <b>Статус оплаты:</b>
                    <?php if($order['payment']==1){echo 'Оплачено';}
                          if($order['payment']==0){echo 'Ожидается';}
                          if($order['payment']==2){echo 'Предоплата';}
                    ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <hr>
            <b>Комментарий:</b> <?=$order['comment']?>            
        </td>
    </tr>
</table>
<input type="hidden" name="id" value="<?=$order['id']?>">
</form>
<hr>
<p style="text-align:center;">
<button class="disabled" onclick="CloseModal();">&nbsp OK &nbsp</button>
</p>