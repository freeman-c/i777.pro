<style>
    #table-list-data td{ padding: 1px 4px; }   
</style>
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$delivery = getDelivery($_GET['id']);
?>
<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="right">Название:</td>
        <td><input type="text" name="name_new_delivery" size="24" value="<?=$delivery['name']?>">*</td>        
    </tr>
</table>
<input type="hidden" name="id_new_delivery" value="<?=$delivery['id']?>">

<hr>
<p style="text-align:center;">
<?php if($_GET['id']){ ?>
    <button class="button" onclick="ajax_delivery('edit');">Сохранить</button>
<?php }else{ ?>
    <button class="button" onclick="ajax_delivery('add');">Сохранить</button>
<?php } ?>
<button class="disabled" onclick="CloseModal();">Отмена</button>
</p>