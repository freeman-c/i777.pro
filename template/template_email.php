<h2><img src="<?=SITE_URL?>/image/email.png" style="margin: -6px 10px -8px -4px;">Шаблоны Email
    <span id="panel-button-operation">
        <button class="button" onclick="add_new_template_email();">+ Добавить</button>
        <button class="button-error" id="button-operation-delete" onclick="delete_template_email();">Удалить <span id="count-elements-delete"></span></button>
    </span>
</h2>

<form id="form-template-email">
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
        <td>Отправитель</td>
        <td>Email отправителя</td>
        <td width="20px"></td>
    </tr>
    </thead>
    <tbody>
<?php 
$templates = getTemplatesEmail();
foreach ($templates as $template):
?>
    <tr>
        <td> 
            <input type="checkbox" class="selected" name="need_delete[<?=$template['id']?>]" id="checkbox<?=$template['id']?>" id="<?=$template['id']?>" title="<?=$template['id']?>"> 
        </td>
        <td colspan="2">
            <img src="<?=SITE_URL?>/image/mail.png" style="margin: 1px 5px 0px 0px; float: left;">
            <span><?=$template['title']?></span>
            <!--<img src="<?=SITE_URL?>/image/help-icons.png" class="tooltip" title="<?=$template['text']?>" style="margin:-2px 0px -2px 2px; cursor: help;"> -->           
        </td>
        <td style="color:#ABABAB; line-height: 13px;">
            <?php //$template['text']?>
        </td>
        <td style="color:#3F80C0;">
            <?php 
                $sender = getSenderEmail($template['sender']); 
                echo $sender['name']
            ?>
        </td>
        <td style="color:#757575;">
            <?=$sender['email']?>
        </td>
        <td>
            <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_template_email('<?=$template['id']?>');">
            <!--<img src="<?=SITE_URL?>/image/del.png" class="option-button">-->
        </td>
    </tr>
    
<?php endforeach; ?>
    </tbody>
</table>
</form>