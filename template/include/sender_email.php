<style>
    #table-list-data td{
        padding: 1px 4px;
    }
</style> 
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$sender = getSenderEmail($_GET['id']);
?>
<form id="forma-sender-email">
<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="right">Название:</td>
        <td>
            <input type="text" name="name" value="<?=$sender['name']?>" size="30">
        </td>
    </tr>
    
    <tr>
        <td align="right">Email отправителя:</td>
        <td>
            <input type="text" name="email" value="<?=$sender['email']?>" size="30">
        </td>
    </tr>
    
</table>
<input type="hidden" name="id" value="<?=$sender['id']?>">
</form>
<hr>
<p style="text-align:center;">
<?php if($_GET['id']){ ?>
    <button class="button" onclick="ajax_sender_email('edit');">Сохранить</button>
<?php }else{ ?>
    <button class="button" onclick="ajax_sender_email('add');">Сохранить</button>
<?php } ?>
<button class="disabled" onclick="CloseModal();">Отмена</button>
</p>