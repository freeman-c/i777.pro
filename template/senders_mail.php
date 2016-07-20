<h2><img src="<?=SITE_URL?>/image/write_e_mail_32.png" style="margin: -6px 10px -8px -4px;">Отправители в Email шаблонах
    <span id="panel-button-operation">
        <button class="button" onclick="add_new_sender_email();">+ Добавить</button>
        <button class="button-error" id="button-operation-delete" onclick="delete_sender_email();">Удалить <span id="count-elements-delete"></span></button>
    </span>
</h2>

<form id="form-sender-email">
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
        <td width="20px"></td>
    </tr>
    </thead>
    <tbody>
<?php 
$senders = getSendersEmail();
foreach ($senders as $sender):
?>
    <tr>
        <td> 
            <input type="checkbox" class="selected" name="need_delete[<?=$sender['id']?>]" id="checkbox<?=$sender['id']?>" id="<?=$sender['id']?>" title="<?=$sender['id']?>"> 
        </td>
        <td colspan="2">
            <span class="sender-icon"></span>
            <span><?=$sender['name']?></span>           
        </td>
        <td style="color:#757575;">
            <?=$sender['email']?>
        </td>
        <td>
            <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_sender_email('<?=$sender['id']?>');">
            <!--<img src="<?=SITE_URL?>/image/del.png" class="option-button">-->
        </td>
    </tr>
    
<?php endforeach; ?>
    </tbody>
</table>
</form>