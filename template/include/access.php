<style>
    #forma-access{
        padding: 1px 2px;
        background: #FFF;
        border: 1px solid #CCC;
        height: 240px;
        overflow: auto;
    }   
    .on-off{
        margin: 0px 0px -3px 0px;
        cursor: pointer;
    }
    .man-icon{
        margin: 0px 8px -4px 0px;
    }
    #table-list tr:hover{
        background: #FF9 !important;
    }
</style>
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$access = getAccess($_GET['link']);
$users = getUsers();
?>
<script>
function forbidden(login,event){
    //alert(login);
    t=event.target||event.srcElement; 
    var users = $('input[name="users"]').val();
    if( $(t).attr('id')=='off' ){
        $(t).attr('id','on');
        $(t).attr('src','<?=SITE_URL?>/image/off.png');
        $(t).closest('tr').css('background','#F1F1F1');
        $(t).closest('tr').find('.icon-status-access').html('<img src="<?=SITE_URL?>/image/locked.ico" style="margin: 0px 0px -3px 0px;">');   
        var find1 = ', '+login+'';
        var find2 = ''+login+', ';
        var find3 = ''+login+'';
        var nov1 = users.replace(find1,'');
        var nov2 = nov1.replace(find2,'');
        var nov = nov2.replace(find3,'');
        $('input[name="users"]').val(nov);     
    }else{
        $(t).attr('id','off');
        $(t).attr('src','<?=SITE_URL?>/image/on.png');
        //$(t).parent().parent().find('.icon-status-access').html('&nbsp');
        $(t).closest('tr').css('background','#FFF');
        $(t).closest('tr').find('.icon-status-access').html('&nbsp');

        if(users.length > 0){
            $('input[name="users"]').val(users+', '+login+'');
        }else{
            $('input[name="users"]').val(users+''+login+'');
        }
    }
    //alert($(t).attr('id'));
}
$(document).ready(function(){
});
</script>
    <?php
        if(strlen($access['users']) > 0){
            //echo 'Для некоторых пользователей доступ закрыт.';
        }else{
            //echo 'Доступ открыт для всех пользователей.';
        }
        echo 'Ссылка: <b>'.$access['link'].'</b>';
    ?>
    <hr>
<form id="forma-access">
    <table id="table-list" width="100%" border="0" cellspacing="0">
    
    <?php
    $forbidden_array = explode(', ', $access['users']);    
    foreach ($users as $user):
        if(in_array($user['login'], $forbidden_array)){
            $tr_background = '#F1F1F1';
        }else{
            $tr_background = '#FFF';
        }
        
        echo '<tr style="background:'.$tr_background.';">';
        if(in_array($user['login'], $forbidden_array)){
    ?>        
            <td width="14px" class="icon-status-access">&nbsp</td>
            <td><?=$user['surname']?> <?=$user['name']?> <?=$user['lastname']?></td>
            <td style="color:#3F80C0;"><?=$user['login']?></td>
            <td style="color:#757575;">
                <?=getAccessType($user['access']);?>
            </td>
            <td><img src="<?=SITE_URL?>/image/on.png" id="off" onclick="forbidden('<?=$user['login']?>',event);" class="on-off"></td>
    
    <?php }else{ ?>
            <td width="14px" class="icon-status-access"><img src="<?=SITE_URL?>/image/locked.ico" style="margin: 0px 0px -3px 0px;"></td>
            <td><?=$user['surname']?> <?=$user['name']?> <?=$user['lastname']?></td>
            <td style="color:#3F80C0;"><?=$user['login']?></td>
            <td style="color:#757575;">
                <?=getAccessType($user['access']);?>
            </td>
            <td><img src="<?=SITE_URL?>/image/off.png" id="on" onclick="forbidden('<?=$user['login']?>',event);" class="on-off"></td>
    <?php }
        echo '</tr>';
    endforeach; 
    ?>  
    </table>
<input name="users" value="<?=$access['users']?>" size="50">
<input name="link" value="<?=$_GET['link']?>">
</form>
    <div style="text-align: center;">
        <img src="<?=SITE_URL?>/image/locked.ico" style="margin: 0px 0px -3px 0px;"> - доступ запрещён
        <!--<img src="<?=SITE_URL?>/image/hand_share.ico" style="margin: 0px 0px -4px 0px;"> - доступ разрешен -->
    </div>    
<hr>
<p style="text-align:center;">
<?php if($_GET['link']){ ?>
    <button class="button" onclick="ajax_access('edit');">Сохранить</button>
<?php }else{ ?>
    <button class="button" onclick="ajax_access('add');">Сохранить</button>
<?php } ?>
<button class="disabled" onclick="CloseModal();">Отмена</button>
</p>