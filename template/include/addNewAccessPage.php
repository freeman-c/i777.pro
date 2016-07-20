
<?php 
    require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/access/accessController.php');
     if (!empty($_GET['id']))
         $access = getAccess_($_GET['id']);
?>

<script type="text/javascript" src="/template/additionalFiles/access/accessIncl.js"></script>
<link rel="stylesheet" href="/template/additionalFiles/access/accessIncl.css">

<form id="forma-access">
    <table id="table-list-data" width="100%" cellpadding="0" cellspacing="5" border="0">
        <tr>
            <td>Название страницы</td>
            <td> <input type="text" id="name" value="<?=$access['name']?>"> </td>
        </tr>
        <tr>
            <td>php-файл</td>
            <td> <input type="text" id="link" value="<?=$access['link']?>"> </td>
        </tr>
    </table>
    <input type="hidden" class="id" id="access-id" value="<?=$_GET['id']?>">
</form>

<hr>
<p style="text-align:center;">
    <button class="button button-save-access">Сохранить</button>
    <button class="disabled close-modal">Отмена</button>
</p>