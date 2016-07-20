<?php 
    require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/binotelPhones/binotelPhones.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/binotelGroups/binotelGroups.php');
    if (!empty($_GET['id']))
        $binotelPhone = getBinotelPhone($_GET['id']);
?>

<script type="text/javascript" src="/template/additionalFiles/binotelPhones/binotelPhonesInclude.js"></script>
<link rel="stylesheet" href="/template/additionalFiles/binotelPhones/binotelPhonesInclude.css">

<form id="forma-binotel-groups">
    <table id="table-list-data" width="100%" cellpadding="0" cellspacing="5" border="0">
        <tr>
            <td class="column-1">
                Группа
            </td>
            <td class="column-2">
                <select class="bngroup">
                    <?php
                    if ($binotelPhone){
                    ?>
                    <option value="<?=$binotelPhone['group']?>"><?=$binotelPhone['group']?></option>
                    <?php
                    } 
                    ?>
                    <option disabled>------------</option>
                    <?php 
                    $binotelGroups = getBinotelGroupsList('select');
                    while ($binotelGroup = mysql_fetch_assoc($binotelGroups)){
                    ?>           
                        <option value="<?=$binotelGroup['group']?>"><?=$binotelGroup['group']?></option> 
                    <?php }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
             <td class="column-1">
                Телефон
            </td>
            <td class="column-2">
                <input type="text" class="phone" value="<?=$binotelPhone['phone']?>" placeholder="Номер телефона" style="width: 300px">
            </td>
        </tr>
    </table>
    <input type="hidden" name="id" class="binotel-phone-id" value="<?=$_GET['id']?>">
</form>

<hr>
<p style="text-align:center;">
    <button class="button button-save-binotel-phone">Сохранить</button>
    <button class="disabled close-modal">Отмена</button>
</p>    
