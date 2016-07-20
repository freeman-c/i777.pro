<style>
    #table-list-data td{
        padding: 1px 4px;
    }   
</style>
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$client = getClient($_GET['id']);
?>
<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
    <!--<tr>
        <td align="right">Фамилия:</td>
        <td><input type="text" name="surname_new_client" size="24" value="<?=$client['surname']?>">*</td>
    </tr>-->
    <tr>
        <td align="right">Имя:</td>
        <td><input type="text" name="name_new_client" size="28" value="<?=$client['name']?>">*</td>
    </tr>
    <!--<tr>
        <td align="right">Отчество:</td>
        <td><input type="text" name="lastname_new_client" size="24" value="<?=$client['lastname']?>">*</td>
    </tr>-->
    <tr>
        <td align="right">Группа:</td>
        <td>
            <?php $gruppa = GetClientsGroup($client['type']); ?>
            <select name="type">
                    <?php if($_GET['id']){ ?>
                        <option value="<?=$gruppa['id']?>"><?=$gruppa['name']?></option>
                        <option value="">- - - - - - - - -</option>
                        <?php 
                            $groups = GetClientsGroups(); 
                            foreach ($groups as $group):?>
                            <option value="<?=$group['id']?>"><?=$group['name']?></option>
                       <?php endforeach; ?> 
                    <?php }else{ ?>
                        <?php 
                            $groups = GetClientsGroups(); 
                            foreach ($groups as $group):?>
                            <option value="<?=$group['id']?>"><?=$group['name']?></option>
                       <?php endforeach; ?> 
                    <?php } ?>  
            </select>*
        </td>
    </tr>
    <tr>
        <td align="right">Телефон:</td>
        <td><input type="text" name="phone_new_client" size="14" value="<?=$client['phone']?>" maxlength="13">*</td>
    </tr>
    <tr>
        <td align="right">Email:</td>
        <td><input type="text" name="email_new_client" size="28" value="<?=$client['email']?>"></td>
    </tr>
    <tr>
        <td align="right">Дополнительно:</td>
        <td><textarea name="description_new_client" rows="3" cols="27" spellcheck="false"><?=$client['description']?></textarea></td>
    </tr>
    <tr>
        <td colspan="2" align="center" style="color:#757575;">* - обязательные поля</td>
    </tr>
</table>
<input type="hidden" name="id_new_client" value="<?=$client['id']?>">

<hr>
<p style="text-align:center;">
<?php if($_GET['id']){ ?>
    <button class="button" onclick="ajax_clients('edit');">Сохранить</button>
<?php }else{ ?>
    <button class="button" onclick="ajax_clients('add');">Сохранить</button>
<?php } ?>
<button class="disabled" onclick="CloseModal();">Отмена</button>
</p>
