<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');

function getBanIpRulesList(){
    db_connect();

    
    $result = mysql_query("SELECT *
    	FROM ban_ip
        ORDER BY id") or die (mysql_error());

    while ($banIprule = mysql_fetch_assoc($result)){
    ?>           
        <tr>
            <td> 
                <input type="checkbox" class="selected" name="need_delete[<?=$banIprule['id']?>]" id="checkbox<?=$banIprule['id']?>" id="<?=$banIprule['id']?>" title="<?=$banIprule['id']?>"> 
            </td>
            <td style="display: block; width: 16px !important; height: 0; padding: 0; margin: -1px;">
                <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="editBanIpRule('<?=$banIprule['id']?>');">
            </td>
            <td>
                <?=$banIprule['ip']?>
            </td>
            <td>
                <?=$banIprule['reason']?>
            </td>
        </tr>
    <?php }
}

function getBanIpRule($id){
    db_connect();
    
    $result = mysql_query("SELECT *
        FROM ban_ip
        WHERE id = {$id}") or die (mysql_error());

    return mysql_fetch_assoc($result);
}

function addBanIpRule(){
    db_connect();

    mysql_query("INSERT INTO ban_ip
        SET ip = '{$_POST['ip']}',
        reason = '{$_POST['reason']}'") or die(mysql_error());

}

function editBanIpRule(){
    db_connect();

    mysql_query("UPDATE ban_ip
        SET ip = '{$_POST['ip']}',
        reason = '{$_POST['reason']}'
        WHERE id = {$_POST['id']}") or die(mysql_error());

}

function deleteBanIpRule(){
    db_connect();
    foreach ($_POST['need_delete'] as $id => $value)
        mysql_query("DELETE FROM ban_ip WHERE id= {$id}") or die(json_encode(array('success' => false, 'error' => mysql_error())));
    echo json_encode(array('success' => true));
}

function existInBanListIp($ip){
    db_connect();

    $sqlResponse = mysql_query("SELECT id 
        FROM ban_ip
        WHERE ip = '{$ip}'");
    $sqlResponse = mysql_fetch_assoc($sqlResponse);
    if (!empty($sqlResponse))
        return true;
    else
        return false;
}

switch ($_POST['operation']) {
    case 'addBanIpRule':
        addBanIpRule();
        die();
    case 'editBanIpRule':
        editBanIpRule();
        break;
    case 'getBanIpRulesList':
        getBanIpRulesList();
        die();
    case 'deleteBanIpRule':
        deleteBanIpRule();
        die();
}

?>