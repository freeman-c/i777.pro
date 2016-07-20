<style>
    #table-list-data td{
        padding: 1px 4px;
    }   
</style>
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$template = getTemplateEmail($_GET['id']);
?>

<form id="forma-template-email">
<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="right">Название шаблона:</td>
        <td>
            <input type="text" name="title" size="50" value="<?=$template['title']?>">
        </td>
    </tr>
    <tr>
        <!--<td align="right">Содержимое:</td>-->
        <td colspan="2">
            <?php 
                //$text = str_replace("<br />", "", $template['text']);
            ?>
            <textarea id="text" name="text"><?=$template['text']?></textarea>
 <script> 
    CKEDITOR.replace('text',{
        'height': '240px',
        //'uiColor': '#E5E9F4', //#CCCCCC
        //'magicline_color': 'blue', //red
        'filebrowserBrowseUrl':'<?=SITE_URL?>/js/ckeditor/kcfinder/browse.php?type=files',
        'filebrowserImageBrowseUrl':'<?=SITE_URL?>/js/ckeditor/kcfinder/browse.php?type=images',
        'filebrowserFlashBrowseUrl':'<?=SITE_URL?>/js/ckeditor/kcfinder/browse.php?type=flash',
        'filebrowserUploadUrl':'<?=SITE_URL?>/js/ckeditor/kcfinder/upload.php?type=files',
        'filebrowserImageUploadUrl':'<?=SITE_URL?>/js/ckeditor/kcfinder/upload.php?type=images',
        'filebrowserFlashUploadUrl':'<?=SITE_URL?>/js/ckeditor/kcfinder/upload.php?type=flash'
    });
    /*$('#save-template').click(function(){
        var editor_data = CKEDITOR.instances.text.getData();
        $('#message').html(editor_data);
    });*/
</script> 
<!--<textarea id="message" name="message"></textarea> <a href="#" id="save-template">get</a>-->
        </td>
    </tr>
    <tr>
        <td align="right">Отправитель письма:</td>
        <td>
            <select name="sender">
            <?php 
            $current_sender = getSenderEmail($template['sender']);
            ?>
                <option value="<?=$current_sender['id']?>"><?=$current_sender['name']?></option>
                <option disabled>- - - - - - - -</option>
            <?php 
            $senders = getSendersEmail();
            foreach ($senders as $sender): ?>
                <option value="<?=$sender['id']?>"><?=$sender['name']?></option>
            <?php endforeach;
            ?>               
            </select>
        </td>
    </tr>    
</table>
<input type="hidden" name="id" value="<?=$template['id']?>">
</form>

<hr>
<p style="text-align:center;">
<?php if($_GET['id']){ ?>
    <button class="button" onclick="ajax_template_email('edit');">Сохранить</button>
<?php }else{ ?>
    <button class="button" onclick="ajax_template_email('add');">Сохранить</button>
<?php } ?>
<button class="disabled" onclick="CloseModal();">Отмена</button>
</p>