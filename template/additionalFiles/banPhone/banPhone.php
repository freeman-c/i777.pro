<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');

function getBanPhoneRulesList(){
    db_connect();

    
    $result = mysql_query("SELECT *
    	FROM ban_phone
        ORDER BY id") or die (mysql_error());

    while ($banPhoneRule = mysql_fetch_assoc($result)){
    ?>           
        <tr>
            <td> 
                <input type="checkbox" class="selected" name="need_delete[<?=$banPhoneRule['id']?>]" id="checkbox<?=$banPhoneRule['id']?>" id="<?=$banPhoneRule['id']?>" title="<?=$banPhoneRule['id']?>"> 
            </td>
            <td style="display: block; width: 16px !important; height: 0; padding: 0; margin: -1px;">
                <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="editBanPhoneRule('<?=$banPhoneRule['id']?>');">
            </td>
            <td>
                <?=$banPhoneRule['phone']?>
            </td>
            <td>
                <?=$banPhoneRule['reason']?>
            </td>
        </tr>
    <?php }
}

function getBanPhoneRule($id){
    db_connect();
    
    $result = mysql_query("SELECT *
        FROM ban_phone
        WHERE id = {$id}") or die (mysql_error());

    return mysql_fetch_assoc($result);
}

function addBanPhoneRule(){
    db_connect();
    $_POST['phone'] = preg_replace('/[^0-9]/', '', $_POST['phone']);
    mysql_query("INSERT INTO ban_phone
        SET phone = '{$_POST['phone']}',
        reason = '{$_POST['reason']}'") or die(mysql_error());

}

function editBanPhoneRule(){
    db_connect();
    $_POST['phone'] = preg_replace('/[^0-9]/', '', $_POST['phone']);
    mysql_query("UPDATE ban_phone
        SET phone = '{$_POST['phone']}',
        reason = '{$_POST['reason']}'
        WHERE id = {$_POST['id']}") or die(mysql_error());

}

function deleteBanPhoneRule(){
    db_connect();
    foreach ($_POST['need_delete'] as $id => $value)
        mysql_query("DELETE FROM ban_phone WHERE id= {$id}") or die(json_encode(array('success' => false, 'error' => mysql_error())));
    echo json_encode(array('success' => true));
}


function existInBanListPhone($phone){
    db_connect();

    $sqlResponse = mysql_query("SELECT id 
        FROM ban_phone
        WHERE phone = '{$phone}'");
    $sqlResponse = mysql_fetch_assoc($sqlResponse);
    if (!empty($sqlResponse))
        return true;
    else
        return false;
}

switch ($_POST['operation']) {
    case 'addBanPhoneRule':
        addBanPhoneRule();
        die();
    case 'editBanPhoneRule':
        editBanPhoneRule();
        break;
    case 'getBanPhoneRulesList':
        getBanPhoneRulesList();
        die();
    case 'deleteBanPhoneRule':
        deleteBanPhoneRule();
        die();
}

?>