<style>
    #table-list-data td{
        padding: 1px 4px;
    }   
</style>
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$status = getStatus($_GET['id']);
?>
<script type="text/javascript">
$(document).ready(function() {
    $('#colorpicker').farbtastic('#color');
});
</script>  

<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="right">Название:</td>
        <td><input type="text" name="name_new_status" size="24" value="<?=$status['name']?>">*</td>        
    </tr>
    <tr>
        <td align="right">Цвет:</td>
        <td valign="top">
        <?php if($_GET['id']){?>  
        <form><input type="text" name="color_new_status" size="24" id="color" value="<?=$status['color']?>"></form>
        <?php } else{ ?>
        <form><input type="text" name="color_new_status" size="24" id="color" value="#FFD"></form>
        <?php } ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center" style="color:#757575;">* - обязательные поля</td>
        <td rowspan="3"><div id="colorpicker"></div></td>
    </tr>
</table>
<input type="hidden" name="id_new_status" value="<?=$status['id']?>">

<hr>
<p style="text-align:center;">
<?php if($_GET['id']){ ?>
    <button class="button" onclick="ajax_statusy('edit');">Сохранить</button>
<?php }else{ ?>
    <button class="button" onclick="ajax_statusy('add');">Сохранить</button>
<?php } ?>
<button class="disabled" onclick="CloseModal();">Отмена</button>
</p>