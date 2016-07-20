<?php 
    require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/binotelGroups/binotelGroups.php');
    if (!empty($_GET['id']))
        $binotelGroup = getBinotelGroup($_GET['id']);
?>

<script type="text/javascript" src="/template/additionalFiles/binotelGroups/binotelGroupsInclude.js"></script>
<link rel="stylesheet" href="/template/additionalFiles/binotelGroups/binotelGroupsInclude.css">

<form id="forma-binotel-groups">
    <table id="table-list-data" width="100%" cellpadding="0" cellspacing="5" border="0">
        <tr>
            <td class="column-1">
                Номер
            </td>
            <td class="column-2">
                <input type="number" class="bngroup" value="<?=$binotelGroup['group']?>" placeholder="Номер группы в Binotel">
            </td>
        </tr>
        <tr>
             <td class="column-1">
                Название
            </td>
            <td class="column-2">
                <input type="text" class="name" value="<?=$binotelGroup['name']?>" placeholder="Название группы" style="width: 300px">
            </td>
        </tr>
    </table>
    <input type="hidden" name="id" class="binotel-group-id" value="<?=$_GET['id']?>">
</form>

<hr>
<p style="text-align:center;">
    <button class="button button-save-binotel_group">Сохранить</button>
    <button class="disabled close-modal">Отмена</button>
</p>    
