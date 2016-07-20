<h2>Доступ</h2>
<style>
    .acess-button{
        border: 1px solid #FFF; 
        padding: 1px;        
        margin: 0px 0px -4px 0px;
    }
    .acess-button:hover{
        border: 1px solid #CCC;
        background: #FFC;
        cursor: pointer;
    }
</style>
<table id="table-list" border="0" cellspacing="0">
<?php
$access_list = getAccessList();
// $users = getUsers();
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
<?php endforeach; ?>  
</table>