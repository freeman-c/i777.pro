<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/controller.php');


function getAccesses(){
    db_connect();

    $access_list = getAccessList();
    // $groups = GetUsersAccesGroup();
    foreach ($access_list as $access_group):
        // if (empty($access_group['disable'])){
    ?>
        <tr>
            <td>                    
                <input type="checkbox" class="selected" name="need_delete[<?=$access_group['id']?>]" id="checkbox<?=$access_group['id']?>" title="<?=$access_group['id']?>">
            </td>   
            <td align="center" width="24px">
                <img src="/image/edit.png" class="option-button" onclick="editAccess('<?=$access_group['id']?>');">
            </td>
            <td width="16px">            
                <?php if(strlen($access_group['groups']) > 0){ ?>
                <img class="acess-button" src="<?=SITE_URL?>/image/locked.ico" onclick="get_access_group('<?=$access_group['link']?>','<?=$access_group['name']?>');">
                <?php } else { ?>
                <img class="acess-button" src="<?=SITE_URL?>/image/hand_share.ico" onclick="get_access_group('<?=$access_group['link']?>','<?=$access_group['name']?>');">
                <?php } ?>
            </td>
            <td><?=$access_group['name']?></td>
            <td><?=$access_group['link']?></td>
            
            <td width="150px" style="font-weight: bold;"> 
                <?php if(strlen($access_group['groups']) > 0){ ?>
                <img src="<?=SITE_URL?>/image/cross.ico" style="margin: 0px 0px -4px 0px;">
                <span style="color:#900;">Ограничено для:</span>
                <?php } else { ?>
                <img src="<?=SITE_URL?>/image/notification_done.ico" style="margin: 0px 0px -3px 0px;">
                <span style="color:green;">Доступно всем</span>
                <?php } ?>
            </td>        
            <td style="font-size: 11px; color:#ABABAB;"><?=$access_group['groups']?></td>
            <td width="16px">
                <img class="option-button" src="<?=SITE_URL?>/image/settings.png" onclick="get_access_group('<?=$access_group['link']?>','<?=$access_group['name']?>');">
            </td>
        </tr>
    <?php  endforeach;  
}

function getExceptions(){
    db_connect();

     $access_list = getAccessList();
    foreach ($access_list as $access):
    ?>
    <tr>
        <td width="16px">            
            <?php if(strlen($access['users']) > 0){ ?>
            <img class="acess-button" src="<?=SITE_URL?>/image/hand_share.ico" onclick="get_access('<?=$access['link']?>','<?=$access['name']?>');">
            <?php } else { ?>
            <img class="acess-button" src="<?=SITE_URL?>/image/locked.ico" onclick="get_access('<?=$access['link']?>','<?=$access['name']?>');">
            <?php } ?>
        </td>
        <td><?=$access['name']?></td>
        <td><?=$access['link']?></td>
        
        <td width="160px" style="font-weight: bold;"> 
            <?php if(strlen($access['users']) > 0){ ?>
            <img src="<?=SITE_URL?>/image/notification_done.ico" style="margin: 0px 0px -3px 0px;">
            <span style="color:green;">Есть исключения для:</span>
            <?php } else { ?>
            <img src="<?=SITE_URL?>/image/cross.ico" style="margin: 0px 0px -4px 0px;">
            <span style="color:#900;">Без исключений</span>
            <?php } ?>
        </td>        
        <td style="font-size: 11px; color:#ABABAB;"><?=$access['users']?></td>
        <td width="16px">
            <img class="option-button" src="<?=SITE_URL?>/image/settings.png" onclick="get_access('<?=$access['link']?>','<?=$access['name']?>');">
        </td>
    </tr>
<?php endforeach; 
}

function getAccess_($id){
    db_connect();
    
    $result = mysql_query("SELECT *
        FROM access
        WHERE id = {$id}") or die (mysql_error());

    return mysql_fetch_assoc($result);
}

function addAccess(){
    db_connect();

    mysql_query("INSERT INTO access
        SET link = '{$_POST['link']}',
            name = '{$_POST['name']}'") or die(mysql_error());

}

function editAccess(){
    db_connect();

    mysql_query("UPDATE access
        SET link = '{$_POST['link']}',
            name = '{$_POST['name']}'
        WHERE id = {$_POST['id']}") or die(mysql_error());

}

function deleteAccess(){
    db_connect();
    foreach ($_POST['need_delete'] as $id => $value)
        mysql_query("DELETE FROM access 
            WHERE id= {$id}") or die(json_encode(array('success' => false, 'error' => mysql_error())));
    echo json_encode(array('success' => true));
}


switch ($_POST['operation']) {
    case 'addAccess':
        addAccess();
        die();
    case 'editAccess':
        editAccess();
        break;
    case 'getAccesses':
        getAccesses();
        die();
    case 'getExceptions':
        getExceptions();
        die();
    case 'deleteAccess':
        deleteAccess();
        die();
}

?>