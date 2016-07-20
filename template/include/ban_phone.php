<?php 
    require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/banPhone/banPhone.php');
    if (!empty($_GET['id']))
        $banPhoneRule = getBanPhoneRule($_GET['id']);
?>

<script type="text/javascript" src="/template/additionalFiles/banPhone/banPhoneInclude.js"></script>
<link rel="stylesheet" href="/template/additionalFiles/banPhone/banPhoneInclude.css">

<form id="forma-ban-phone">
    <table id="table-list-data" width="100%" cellpadding="0" cellspacing="5" border="0">
        <tr>
            <td class="column-1">
                Телефон
            </td>
            <td class="column-2">
                <input type="text" class="phone" value="<?=$banPhoneRule['phone']?>" placeholder="Введите номер">
            </td>
        </tr>
        <tr>
             <td class="column-1">
                Причина
            </td>
            <td class="column-2">
                <input type="text" class="reason" value="<?=$banPhoneRule['reason']?>" placeholder="Введите причину бана" style="width: 300px">
            </td>
        </tr>
    </table>
    <input type="hidden" name="id" id="ban-phone-id" value="<?=$_GET['id']?>">
</form>

<hr>
<p style="text-align:center;">
    <button class="button button-save-ban-phone-rule">Сохранить</button>
    <button class="disabled close-modal">Отмена</button>
</p>    
