<style>
    #table-list-data td{
        padding: 1px 4px;
    }   
    #table-change-password{
        display: none;
        padding: 1px;
        background: #DDD;
        font-size: 12px;
    }
    #table-change-password input{
        font-size: 12px;
    }
    #change-pass-button-show{
        display: inline-block;
        width: auto;
        padding: 0px 2px 0px 20px;
        background: url('/image/icons_nav_panel.png') 0px -961px no-repeat;  
        color: #757575;
        cursor: pointer;
    }
</style>
<script>
$(document).ready(function(){
    $('#change-pass-button-show').click(function() {
        jQuery(this).text('сменить пароль');
        if($('#table-change-password').is(':visible')){
              jQuery(this).text('сменить пароль');
        }else{
              jQuery(this).text('отменить');
        }
        $('#table-change-password').slideToggle('fast');
        return false;
    });
});
</script>
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

function COUNTusersInDB(){
    db_connect();
    $query = "SELECT COUNT(*) FROM `users`";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}
if(COUNTusersInDB() > MAX_LIMIT_USERS or COUNTusersInDB() == MAX_LIMIT_USERS){
    echo '<h3>Превышен лимит!<br>Вы больше не можете добавить пользователя!</h3>';
}else{
    
$user = getUser($_GET['id']);
$user_desc = getUserDescById($_GET['id']);
$dostupy = GetUsersAccesGroup();
?>
<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="right">login:</td>
        <td><input type="text" name="login_new_user" size="24" value="<?=$user_desc['login']?>" style="width: 250px">*</td>
    </tr>
<?php if(!$_GET['id']){ ?>   
    <tr>
        <td align="right">Пароль:</td>
        <td><input type="password" name="pass_new_user" size="24" value="" style="width: 250px">*</td>
    </tr>    
<?php }else{?>
    <tr>
        <td colspan="2" align="center"> 
            <div id="change-pass-button-show">сменить пароль</div>
            
            <table id="table-change-password">
                <tr>
                    <td align="right">Новый пароль:</td>
                    <td>
                            <input type="password" name="pass_new_user" size="20" value="" style="width: 250px">*
                    </td>
                </tr>
            </table>
            
        </td>
    </tr>
<?php } ?> 
    <tr>
        <td align="right">Права доступа:</td>
        <td>
            <select name="access_new_user" style="width: 260px">
                <?php if($_GET['id']){ ?>
                <option value="<?=$user['access']?>"><?=getAccessType($user['access'])?></option>
                <?php }else {?>
                <option value="1">Выберите...</option>
                <?php } ?>
                <option disabled>- - - - - - - - - -</option>
                <?php foreach ($dostupy as $dostup):?>
                <option value="<?=$dostup['id']?>"><?=$dostup['name']?></option>    
                <?php endforeach; ?>
            </select>*
        </td>
    </tr>
    <tr>
        <td align="right">Линия Binotel</td>
        <td><input type="number" name="line" size="24" value="<?=$user_desc['line']?>" style="width: 250px" min="900" max="1000"></td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td align="right">Фамилия</td>
        <td><input type="text" name="surname_new_user" size="24" value="<?=$user_desc['surname']?>" style="width: 250px">*</td>
    </tr>
    <tr>
        <td align="right">Имя</td>
        <td><input type="text" name="name_new_user" size="24" value="<?=$user_desc['name']?>" style="width: 250px">*</td>
    </tr>
    <tr>
        <td align="right">Отчество</td>
        <td><input type="text" name="lastname_new_user" size="24" value="<?=$user_desc['lastname']?>" style="width: 250px">*</td>
    </tr>
<!--     <tr>
        <td colspan="2"><hr></td>
    </tr> -->
    <!-- <tr>
        <td align="right">Email:</td>
        <td><input type="text" name="email_new_user" size="24" value="<?=$user['email']?>"></td>
    </tr> -->
    <!-- <tr>
        <td align="right">Место работы:</td>
        <td>
            <select name="place_work_new_user">
                <?php if($_GET['id']){ ?>
                <option value="<?=$user['place_work']?>"><?=getPlaceWorkName($user['place_work'])?></option>
                <?php }else {?>
                <option value="1">Выберите...</option>
                <?php } ?>
                <option disabled>- - - - - - - - - -</option>
                <?php foreach ($place_work as $pw):?>
                <option value="<?=$pw['id']?>"><?=$pw['name']?></option>    
                <?php endforeach; ?>
            </select>*
        </td>
    </tr> -->
    <!-- <tr>
        <td align="right">Дополнительно:</td>
        <td><textarea name="description_new_user" rows="3" cols="23" spellcheck="false"><?=$user_desc['description']?></textarea></td>
    </tr> -->
    <tr>
        <td colspan="2" align="center" style="color:#757575;">* - обязательные поля</td>
    </tr>
</table>
<input type="hidden" name="id_new_user" value="<?=$user_desc['id']?>">

<hr>
<p style="text-align:center;">
<?php if($_GET['id']){ ?>
    <button class="button" onclick="editUser()">Сохранить</button>
<?php }else{ ?>
    <button class="button" onclick="addUser();">Сохранить</button>
<?php } ?>
<button class="disabled" onclick="CloseModal();">Отмена</button>
</p>
<?php } ?>