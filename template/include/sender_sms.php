<style>
    #table-list-data td{
        padding: 1px 4px;
    }
</style> 
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$sender = getSenderSMS($_GET['id']);
?>
<form id="forma-sender-sms">
<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="right">Название:</td>
        <td>
            <input type="text" name="name" value="<?=$sender['name']?>" size="30">
        </td>
    </tr>
    
    <tr>
        <td align="right">Отправитель:</td>
        <td>
            <input type="text" name="turbosms" value="<?=$sender['turbosms']?>" size="30">
        </td>
    </tr>
    
</table>
<input type="hidden" name="id" value="<?=$sender['id']?>">
</form>
<hr>
<p style="text-align:center;">
<?php if($_GET['id']){ ?>
    <button class="button" onclick="ajax_sender_sms('edit');">Сохранить</button>
<?php }else{ ?>
    <button class="button" onclick="ajax_sender_sms('add');">Сохранить</button>
<?php } ?>
<button class="disabled" onclick="CloseModal();">Отмена</button>
</p>