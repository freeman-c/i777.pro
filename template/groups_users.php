<h2>Группы пользователей
    <span id="panel-button-operation">
        <button class="button" onclick="add_new_group_user();">+ Добавить</button>
        <!--<button class="disabled">Отмена</button>
        <button class="button-success">Сохранить</button>
        <button class="button-edit">Изменить</button>-->
        <button class="button-error" id="button-operation-delete" onclick="delete_group_user();">Удалить <span id="count-elements-delete"></span></button>
    </span>
</h2>
<form id="form-group-user">
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
$user_groups = GetUsersAccesGroup();
foreach ($user_groups as $user_group):
?>
    <tr>
        <td> <input type="checkbox" class="selected" name="need_delete[<?=$user_group['id']?>]" id="checkbox<?=$user_group['id']?>"> </td>
        <td>
            <span class="group-icon"></span>
            <?=$user_group['name']?> &nbsp; 
            <span class="count-user-group">(<?=getUsersCountGroup($user_group['id']);?>)</span>
        </td>
        <td></td>
        <td></td>
        <td>
            <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_group_user('<?=$user_group['id']?>','<?=$user_group['name']?>');">
            <!--<img src="<?=SITE_URL?>/image/del.png" class="option-button" onclick="delete_group_user();">-->
        </td>
    </tr>
    
<?php endforeach; ?>
    </tbody>
</table>
</form>