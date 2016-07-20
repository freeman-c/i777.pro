<!--<h2>
    Состояние заказов "Товар отправлен" по номеру ТТН
</h2>-->
<style>
    #form-tnn{
        overflow: auto;
        width: 1040px;
        max-height: 380px;
        padding-bottom: 30px;
        border-top: 1px solid #B3DCE6;
        border-left: 1px solid #B3DCE6;
        border-right: 1px solid #B3DCE6;
        border-bottom: 1px solid #B3DCE6;        
    }
    #table-tnn{
        /*width: 1300px;*/
        font-size: 12px;
        font-family: 'tooltip';
    }
    #table-tnn thead th{
        white-space: nowrap;
        font-weight: bold;
        text-align: center;
        padding: 8px 8px;
    }
    #table-tnn tbody td{
        white-space: nowrap;
        padding: 1px 8px;
        border-bottom: 1px solid #EEEEEE;
    }
    #table-tnn tbody tr:hover{
        background: #FF9;
    }
    .id-tnn{
        color: #454545;
        /*border:1px solid #DDD;*/
        /*padding: 0px 3px;*/
        font-size: 10px;       
    }
    .id-tnn:hover{
        cursor: default;
        background: #FF6;
    }
</style>
<script type="text/javascript" src="<?=SITE_URL?>/modules/np/chosen/chosen.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="<?=SITE_URL?>/modules/np/chosen/chosen.css">

<script type="text/javascript">
function sort_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
function type_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
$(document).ready(function(){
    $('#sort_status').chosen({
        /*'disable_search_threshold:10'*/
        disable_search: true
    });     
});
</script>
<h2>
    Состояние заказов 
        <select id="sort_status" onChange="type_jumpMenu('parent',this,0)" style="width:200px;">
        <?php 
            $st = 14;
            if($_GET['status']){$st = $_GET['status'];}
            $sta = getStatus($st);
        ?>   
            <option value="<?=SITE_URL?>/?action=state&status=<?=$sta['id']?>"><?=$sta['name']?></option>
            <option disabled>- - - - - - - - - -</option>
        <?php 
            $statusy = getStatusy();
            foreach ($statusy as $status):
        ?>
            <option value="<?=SITE_URL?>/?action=state&status=<?=$status['id']?>"><?=$status['name']?></option>
        <?php endforeach; ?>
        </select>
    по номеру ТТН
</h2>
<p>
<form id="form-tnn">
<table id="table-tnn" cellspacing="0" border="0">
    <thead>
        <tr>
            <th>id</th>
            <th><b style="color:#FFF;">opt</b></th>
            <th>Покупатель</th>
            <th>Контактн. телеф.</th>
            <th>Статус оплаты</th>
            <th>Сумма заказа</th>
            <th>Адрес доставки</th>
            <th>№ ТТН</th>
            <th>Состояние</th>
            <th>Оформил</th>
            <th>Офис</th>
            <th>Добавлен</th>
            <th>Изменён</th>
        </tr>
    </thead>
    <tbody>
<?php 
    $zakazy = getOrdersTTN_NP();
if($zakazy){    
    foreach ($zakazy as $zakaz): 
?>
   
        <tr>
            <td><span class="id-tnn" title="<?=$zakaz['order_id']?>"><?=$zakaz['id']?></span></td>
            <td>
                <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_zakaz('<?=$zakaz['id']?>');" style="margin-bottom: -4px;">
                <!--<img src="<?=SITE_URL?>/image/del.png" class="option-button">-->
            </td>
            <td><?=$zakaz['bayer_name']?></td>
            <td>
				<?php 
				$phone = preg_replace('/[^0-9]/', '', $zakaz['phone']); //убираем всё, кроме цифр
				?>
	<img src="<?=SITE_URL?>/image/mobile_phone_arrow.ico" onclick="SendSMS('<?=$phone?>','<?=trim($zakaz['ttn'])?>',event);" class="send-sms-icon"><?=$zakaz['phone']?>
			</td>
            <td>
                <?php
                    if($zakaz['payment']==0){ //- Ожидается -
                        //echo '<img src="'.SITE_URL.'/image/hourglass.ico" style="float:left; margin:0px 2px -1px 0px;">
                        echo '  <span style="color:#C60;">- '.getStatusPaymentName(0).' -</span>';
                    }
                    if($zakaz['payment']==4){ //Налож. платеж
                        echo '<img src="'.SITE_URL.'/image/mail.ico" style="float:left; margin:0px 2px -1px 0px;">
                                <span style="color:#505E18; font-weight:bold;">'.getStatusPaymentName(4).'</span>';
                    }
                    if($zakaz['payment']==2){ //Предоплата
                        echo '<img src="'.SITE_URL.'/image/arrow-sublevel.png" style="float:left; margin:0px 2px -1px 0px;">
                                <span style="color:#900; font-weight:bold;">'.getStatusPaymentName(2).'</span>';
                    }
                    if($zakaz['payment']==1){ //Оплачено
                        echo '<img src="'.SITE_URL.'/image/money.ico" style="float:left; margin:0px 0px -1px 0px;">
                              <span style="color:green; font-weight:bold;">'.getStatusPaymentName(1).'</span>'; 
                    }
                    if($zakaz['payment']==3){ //Отказ
                        echo '<img src="'.SITE_URL.'/image/cross.ico" style="float:left; margin:0px 2px -1px 0px;">
                                <span style="color:#900; font-weight:bold;">'.getStatusPaymentName(3).'</span>';
                    }
                ?>
            </td>
            <td align="right" style="font-weight: bold; color:#900; padding-left:16px;"><?=$zakaz['total']?></td>
            <td style="font-size: 11px; color:#666;">
                <div style="width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?=$zakaz['delivery_adress']?>">
                        <?=$zakaz['delivery_adress']?>
                </div>
            </td>
            <td style="font-size: 11px; color:#454545;"><?=trim($zakaz['ttn'])?></td>
            <td align="center">
                <input type="hidden" id="ttn-<?=trim($zakaz['ttn'])?>" value="<?=trim($zakaz['ttn'])?>">
                <span id="text-ttn-<?=trim($zakaz['ttn'])?>"></span>
<script>
$(document).ready(function(){ 
    //setTimeout(function(){
            var ttn = $('#ttn-<?=trim($zakaz['ttn'])?>').val();
            if(ttn.length > 0){
                    $.ajax({
                        url: "<?=SITE_URL?>/modules/np/status.php",
                        method: 'POST',
                        data : {ttn:ttn},
                        beforeSend: function(){
                            $('#text-ttn-<?=trim($zakaz['ttn'])?>').html('<img src="<?=SITE_URL?>/image/ajax-load.gif">');
                        },
                        success: function(data){
                            //alert(data); 
                            if(data=='Одержаний'){$('#text-ttn-<?=trim($zakaz['ttn'])?>').html('<span style="color: green;">'+data+'</span>');}
                            else if(data=='Прибув у відділення'){$('#text-ttn-<?=trim($zakaz['ttn'])?>').html('<span style="color:#C60;">'+data+'</span>');}                            
                            else if(data=='Відмова'){$('#text-ttn-<?=trim($zakaz['ttn'])?>').html('<span style="color: red;">'+data+'</span>');}
                            else if(data=='Вантаж повертається Відправнику'){$('#text-ttn-<?=trim($zakaz['ttn'])?>').html('<span style="color: red;">'+data+'</span>');}
                            else if(data=='Нараховується плата за зберігання'){$('#text-ttn-<?=trim($zakaz['ttn'])?>').html('<span style="color: red;">'+data+'</span>');}
                            
                            else {$('#text-ttn-<?=trim($zakaz['ttn'])?>').html('<span style="color:#000000;">'+data+'</span>');}
                            
                        },
                        error: function() { /*alert('Error API nova_poshta: status (ttn: <?=trim($zakaz['ttn'])?>)');*/ }                    
                    });
            }else{
                $('#text-ttn-<?=trim($zakaz['ttn'])?>').html('<span style="color:red;">*ERROR*</span>');
            }
    //},500);
        
});
</script>   </td>
            <td>
                <?php
                    $info_name_user = get_user_description_login($zakaz['user']);
                ?>
                <?=$info_name_user['surname']?> <?=$info_name_user['name']?>
            </td>
            <td>
                <?php $office = getOffice($zakaz['office']); ?><?=$office['name']?>
            </td>
            <td style="font-size: 10px; color:#757575;"><?=$zakaz['date']?></td>
            <td style="font-size: 10px; color:#757575;"><?=$zakaz['date_update']?></td>
        </tr>
    
<?php
endforeach;
}else{  /*echo '<tr>
                <td align="center"> --- </td>
                <td align="center"> &nbsp &nbsp &nbsp </td>
                <td align="center"> --- </td>
                <td align="center"> --- </td>
                <td align="center"> --- </td>
                <td align="center"> --- </td>
                <td align="center"> --- </td>
                <td style="color:red;">Нет номера ТТН</td>
                <td align="center"> --- </td>
                <td align="center"> --- </td>
                <td align="center"> --- </td>
                <td align="center"> --- </td>
                <td align="center"> --- </td>
            </tr>';*/
    echo '<td align="center" colspan="13" style="color:red;"><h3>В заказах "'.$sta['name'].'" нет записей с номером ТТН</h3></td>';
}
       
?>
    </tbody>
</table>
</form>
</p>
<p>
<?php 
if($zakazy){ 
    navigationZakazyTTN_NP();
}
?>
</p>