<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');

function getBinotelGroupsList($type){
    db_connect();

    $result = mysql_query("SELECT *
        FROM binotel_groups
        ORDER BY binotel_groups.group") or die (mysql_error());

    if ($type == 'table'){
        while ($binotelGroup = mysql_fetch_assoc($result)){
        ?>           
            <tr>
                <td> 
                    <input type="checkbox" class="selected" name="need_delete[<?=$binotelGroup['id']?>]" id="checkbox<?=$binotelGroup['id']?>" id="<?=$binotelGroup['id']?>" title="<?=$binotelGroup['id']?>"> 
                </td>
                <td style="display: block; width: 16px !important; height: 0; padding: 0; margin: -1px;">
                    <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="editBinotelGroup('<?=$binotelGroup['id']?>');">
                </td>
                <td>
                    <?=$binotelGroup['group']?>
                </td>
                <td>
                    <?=$binotelGroup['name']?>
                </td>
            </tr>
        <?php }
    }
    else
        return $result;
}

function getBinotelGroup($id){
    db_connect();
    $result = mysql_query("SELECT *
        FROM binotel_groups
        WHERE id = {$id}") or die (mysql_error());

    return mysql_fetch_assoc($result);
}

function addBinotelGroup(){
    db_connect();

    $query = "INSERT INTO binotel_groups
        SET binotel_groups.group = {$_POST['bngroup']},
        name = '{$_POST['name']}'";
    mysql_query($query) or die($query.PHP_EOL.mysql_error());

}

function editBinotelGroup(){
    db_connect();

    mysql_query("UPDATE binotel_groups
        SET binotel_groups.group = '{$_POST['bngroup']}',
        name = '{$_POST['name']}'
        WHERE id = {$_POST['id']}") or die(mysql_error());

}

function deleteBinotelGroups(){
    db_connect();
    foreach ($_POST['need_delete'] as $id => $value)
        mysql_query("DELETE FROM binotel_groups WHERE id= {$id}") or die(mysql_error());
}

switch ($_POST['operation']) {
    case 'addBinotelGroup':
        addBinotelGroup();
        die();
    case 'editBinotelGroup':
        editBinotelGroup();
        break;
    case 'getBinotelGroupsList':
        getBinotelGroupsList('table');
        die();
    case 'deleteBinotelGroups':
        deleteBinotelGroups();
        die();
}

?>