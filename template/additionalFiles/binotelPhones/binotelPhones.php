<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');

function getBinotelPhonesList(){
    db_connect();

    $result = mysql_query("SELECT *
        FROM binotel_phones
        ORDER BY binotel_phones.group") or die (mysql_error());

    while ($binotelPhone = mysql_fetch_assoc($result)){
    ?>           
        <tr>
            <td> 
                <input type="checkbox" class="selected" name="need_delete[<?=$binotelPhone['id']?>]" id="checkbox<?=$binotelPhone['id']?>" id="<?=$binotelPhone['id']?>" title="<?=$binotelPhone['id']?>"> 
            </td>
            <td style="display: block; width: 16px !important; height: 0; padding: 0; margin: -1px;">
                <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="editBinotelPhone('<?=$binotelPhone['id']?>');">
            </td>
            <td>
                <?=$binotelPhone['group']?>
            </td>
            <td>
                <?=$binotelPhone['phone']?>
            </td>
        </tr>
    <?php }
}

function getBinotelPhone($id){
    db_connect();
    $result = mysql_query("SELECT binotel_phones.group, phone
        FROM binotel_phones
        WHERE id = {$id}") or die (mysql_error());

    return mysql_fetch_assoc($result);
}

function getBinotelGroupByPhone($phone){
    db_connect();
    $query = "SELECT binotel_phones.group
        FROM binotel_phones
        WHERE phone = '{$phone}'";
    $result = mysql_query($query) or die ($query.mysql_error());

    $result = mysql_fetch_assoc($result);
    return $result['group'];
}

function addBinotelPhone(){
    db_connect();
    $_POST['phone'] = preg_replace('/[^0-9]/', '', $_POST['phone']);
    $query = "INSERT INTO binotel_phones
        SET binotel_phones.group = {$_POST['bngroup']},
        phone = '{$_POST['phone']}'";
    mysql_query($query) or die($query.PHP_EOL.mysql_error());

}

function editBinotelPhone(){
    db_connect();
    $_POST['phone'] = preg_replace('/[^0-9]/', '', $_POST['phone']);
    mysql_query("UPDATE binotel_phones
        SET binotel_phones.group = '{$_POST['bngroup']}',
        phone = '{$_POST['phone']}'
        WHERE id = {$_POST['id']}") or die(mysql_error());

}

function deleteBinotelPhones(){
    db_connect();
    foreach ($_POST['need_delete'] as $id => $value)
        mysql_query("DELETE FROM binotel_phones WHERE id= {$id}") or die(mysql_error());
}

switch ($_POST['operation']) {
    case 'addBinotelPhone':
        addBinotelPhone();
        die();
    case 'editBinotelPhone':
        editBinotelPhone();
        break;
    case 'getBinotelPhonesList':
        getBinotelPhonesList();
        die();
    case 'deleteBinotelPhones':
        deleteBinotelPhones();
        die();
}

?>