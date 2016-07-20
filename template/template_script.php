<h2>Скрипты
    <span id="panel-button-operation">
        <button class="button" onclick="add_new_template_script();">+ Добавить</button>
        <button class="button-error" id="button-operation-delete" onclick="delete_template_script();">Удалить <span id="count-elements-delete"></span></button>
    </span>
</h2>
<form id="form-template-script">
<table id="table-list" border="0" cellspacing="0">
    <thead>
    <tr>
        <td width="20px"> 
            <div id="box-input-select-all">
                <input type="checkbox" id="select-all-checkbox">
                <div class="box-arrow-down"></div>
            </div> 
        </td>
        <td>Название</span> </td>
        <td>Вложен в</td>
        <td>Текст</td>
        <td>Отображать<br>в CRM</td>
    </tr>
    </thead>
    <tbody>
<?php 
$templates = getTemplatesScript();
foreach ($templates as $template):
?>

    <tr>
        <td> 
            <input type="checkbox" class="selected" name="need_delete[<?=$template['id']?>]" id="checkbox<?=$template['id']?>" id="<?=$template['id']?>" title="<?=$template['id']?>"> 
        </td>
        <td>
            <img src="<?=SITE_URL?>/image/help-icons.png" class="tooltip" title="<?=htmlspecialchars($template['text']);?>" style="margin:-2px 0px -2px 2px; cursor: help;">
            <span><?=$template['title']?></span>
        </td>
        <td>
            <?php 
            if (!empty($template['parent_name'])){
            ?>
            <img src="<?=SITE_URL?>/image/help-icons.png" class="tooltip" title="<?=htmlspecialchars($template['p_text']);?>" style="margin:-2px 0px -2px 2px; cursor: help;">
            <span><?=$template['p_title']?></span>
            <?php } ?>
        </td>
        <td style="color:#3F80C0;">
            <?=$template['text']?>
        </td>
        <td>
            <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_template_script('<?=$template['id']?>');">
        </td>
    </tr>
    
<?php endforeach; ?>
    </tbody>
</table>
</form>