<style>
    #table-list-data td{
        padding: 1px 4px;
    }   
</style>
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$office = getOffice($_GET['id']);
?>
<form id="forma-offices">
<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="right">Название:</td>
        <td><input type="text" name="name" size="24" value="<?=$office['name']?>">*</td>
    </tr>
    <tr>
        <td align="right">Email:</td>
        <td><input type="text" name="email" size="24" value="<?=$office['email']?>">*</td>
    </tr>
    <tr>
        <td align="right">Адрес:</td>
        <td><input type="text" name="adress" size="56" value="<?=$office['adress']?>"></td>
    </tr>
    <tr>
        <td colspan="2" align="center" style="color:#757575;">* - обязательные поля</td>
    </tr>
</table>
<input type="hidden" name="id" value="<?=$office['id']?>">
</form>
<hr>
<p style="text-align:center;">
<?php if($_GET['id']){ ?>
    <button class="button" onclick="ajax_offices('edit');">Сохранить</button>
<?php }else{ ?>
    <button class="button" onclick="ajax_offices('add');">Сохранить</button>
<?php } ?>
<button class="disabled" onclick="CloseModal();">Отмена</button>
</p>