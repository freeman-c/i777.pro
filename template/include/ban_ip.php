<?php 
    require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/banIp/banIp.php');
    if (!empty($_GET['id']))
        $banIpRule = getBanIpRule($_GET['id']);
?>

<script type="text/javascript" src="/template/additionalFiles/banIp/banIpInclude.js"></script>
<link rel="stylesheet" href="/template/additionalFiles/banIp/banIpInclude.css">

<form id="forma-ban-ip">
    <table id="table-list-data" width="100%" cellpadding="0" cellspacing="5" border="0">
        <tr>
            <td class="column-1">
                IP
            </td>
            <td class="column-2">
                <input type="text" class="ip" value="<?=$banIpRule['ip']?>" placeholder="IP адрес">
            </td>
        </tr>
        <tr>
             <td class="column-1">
                Причина
            </td>
            <td class="column-2">
                <input type="text" class="reason" value="<?=$banIpRule['reason']?>" placeholder="Причина бана" style="width: 300px">
            </td>
        </tr>
    </table>
    <input type="hidden" name="id" id="ban-ip-id" value="<?=$_GET['id']?>">
</form>

<hr>
<p style="text-align:center;">
    <button class="button button-save-ban-ip-rule">Сохранить</button>
    <button class="disabled close-modal">Отмена</button>
</p>    
