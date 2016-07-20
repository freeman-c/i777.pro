<h2>Способы доставки
    <span id="panel-button-operation">
        <button class="button" onclick="add_new_delivery();">+ Добавить</button>
        <button class="button-error" id="button-operation-delete" onclick="delete_delivery();">Удалить <span id="count-elements-delete"></span></button>
    </span>
</h2>
<style>
    .menu-item{
        cursor: move;
    }
</style>
<script>
$(document).ready(function(){
    $('#table-list tbody').sortable({
        axis: 'y',
        opacity: 0.6,
        //cancel: ".no-sortable",
        stop: function(){
            var menu_id = $('#table-list tbody').sortable("toArray");
            //alert(menu_id);
            $.ajax({
                type: "POST",
                url: "/modules/ajax_sortable_delivery.php",
                data: {menu_id:menu_id},
                beforeSend: function(){
                    WaitingBarShow('Изменение порядка сортировки...')
                },
                success: function(data){
                    //alert(data);
                    WaitingBarHide();
                    MessageTray('Порядок способов доставки сохранен!');
                },
                error: function() { alert('Ошибка ajax! cod: ajax_sortable_delivery.php'); }
            });
        }
    });
});
</script>
<form id="form-delivery">
<table id="table-list" border="0" cellspacing="0">
    <thead>
    <tr>
        <td width="20px"> 
            <div id="box-input-select-all">
                <input type="checkbox" id="select-all-checkbox">
                <div class="box-arrow-down"></div>
            </div> 
        </td>
        <td align="center" colspan="3"> <span id="table-message"></span> </td>
        <td></td>
        <td width="20px"></td>
    </tr>
    </thead>
    <tbody>
<?php 
$sposoby = getDeliverys();
foreach ($sposoby as $sposob):
    
/*if($sposob['locked'] > 0){ 
    $class = 'no-sortable';
}else{
    $class = 'menu-item';
}*/ $class = 'menu-item';     
?>
    <tr class="<?=$class;?>" id="<?=$sposob['id']?>">
        <td align="center">
            <?php 
                if($sposob['locked'] > 0){ ?> 
                <input type="checkbox" disabled="disabled" title="<?=$sposob['id']?>">       
            <?php }else{ ?>
                <input type="checkbox" class="selected" name="need_delete[<?=$sposob['id']?>]" id="checkbox<?=$sposob['id']?>" title="<?=$sposob['id']?>">
            <?php } ?>
        </td>
        <td><?=$sposob['name']?> &nbsp; 
            <span style="color:#C3C3C3;">(<?=CountDeliveryInOrders($sposob['name']);?> записей)</span>
        </td>
        <td colspan="2"><?=$sposob['adress']?></td>
        <td>
            <?php 
            if($sposob['locked'] > 0){ ?> 
                <span style="color:#CCC;"><?=$sposob['sort']?></span>     
            <?php }else{ ?>
                <span style="color:#EEE;"><?=$sposob['sort']?></span>
            <?php } ?>
            
        </td>
        <td>
            <?php 
            if($sposob['locked'] > 0){ ?> 
                <img src="<?=SITE_URL?>/image/locked.ico" title="<?=$sposob['id']?>" style="opacity: 0.4;">       
            <?php }else{ ?>
                <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_delivery('<?=$sposob['id']?>');">
                <!--<img src="<?=SITE_URL?>/image/del.png" class="option-button">-->
            <?php } ?>            
        </td>
    </tr>
    
<?php endforeach; ?>
    </tbody>
</table>
    <br>
    <p style="color:#757575;">* Чтобы изменить порядок сортировки - просто перетащите элемент.</p>
</form>