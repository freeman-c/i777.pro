<script type="text/javascript" src="/template/additionalFiles/users/users.js"></script>
<link rel="stylesheet" href="/template/additionalFiles/users/users.css">

<h2>Сотрудники
    <span id="panel-button-operation">
        <button class="button" onclick="add_new_user();">+ Добавить</button>
        <button class="button-error" id="button-operation-delete" onclick="delete_user();">
            Удалить 
            <span id="count-elements-delete"></span>
        </button>
    </span>
</h2>
<br>
<form id="form-users">
<table id="table-list" class="users-table" border="0" cellspacing="0">
</table>
</form>