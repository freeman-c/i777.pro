<style>
    #table-list-data td{
        padding: 1px 4px;
    }   
</style>
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$status = getManufacturer($_GET['id']);
?>
<script type="text/javascript">
$(document).ready(function() {
});
</script>  
<form id="forma-manufacturer">
<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="right">Название:</td>
        <td><input type="text" name="name" size="30" value="<?=$status['name']?>">*</td>        
    </tr>
    <tr>
        <td align="right">Описание:</td>
        <td> <textarea name="description" rows="5" cols="29"><?=$status['description']?></textarea> </td>        
    </tr>
    <tr>
        <td colspan="2" align="center" style="color:#757575;">* - обязательные поля</td>
        <td rowspan="3"><div id="colorpicker"></div></td>
    </tr>
</table>
<input type="hidden" name="id" value="<?=$status['id']?>">
</form>
<hr>
<p style="text-align:center;">
<?php if($_GET['id']){ ?>
    <button class="button" onclick="ajax_manufacturer('edit');">Сохранить</button>
<?php }else{ ?>
    <button class="button" onclick="ajax_manufacturer('add');">Сохранить</button>
<?php } ?>
<button class="disabled" onclick="CloseModal();">Отмена</button>
</p>