<h2>Отделы
    <span id="panel-button-operation">
        <button class="button" onclick="add_new_office();">+ Добавить</button>
        <button class="button-error" id="button-operation-delete" onclick="delete_office();">Удалить <span id="count-elements-delete"></span></button>
    </span>
</h2>
<form id="form-offices">
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
        <td>Описание</td>
        <td width="20px"></td>
    </tr>
    </thead>
    <tbody>
<?php 
$offices = getOffices();
foreach ($offices as $office):
?>
    <tr>
        <td> <input type="checkbox" class="selected" name="need_delete[<?=$office['id']?>]" id="checkbox<?=$office['id']?>" title="id: <?=$office['id']?>"> </td>
        <td>
            <span class="office-icon"></span>
            <?=$office['name']?> 
        </td>
        <td colspan="2" style="color:#3F80C0;"><?=$office['email']?></td>
        <td><?=$office['adress']?></td>
        <td>
            <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_office('<?=$office['id']?>');">
            <!--<img src="<?=SITE_URL?>/image/del.png" class="option-button">-->
        </td>
    </tr>
    
<?php endforeach; ?>
    </tbody>
</table>
</form>