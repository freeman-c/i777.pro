<style>
    #table-list-data td{
        padding: 1px 4px;
    }
    #discont{
        color: #C60;
    }
</style>
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$c = getCategory($_GET['id']);
?>
<script>
$(document).ready(function(){
    $('#select-type-category').change(function(){
        if($(this).val()=='Родительская'){
            $('#connect-category').removeAttr('name');
            $('#connect-category').fadeOut();
            $('#parent-id').attr('name','parent_id');
        }else{
            $('#connect-category').attr('name','parent_id');
            $('#connect-category').fadeIn();
            $('#parent-id').removeAttr('name');
        }
    });
});
</script>

<form id="forma-category">
<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td width="80px">Название:</td>
        <td> <input type="text" name="name" value="<?=$c['name']?>" size="32"> </td>
    </tr>
    <tr>
        <td>Тип:</td>
        <td>
            <select id="select-type-category">
<?php if($c['parent_id'] > 0){ ?>
                <option>Подчиняемая</option>
<?php }else{ ?>
                <option>Родительская</option>
<?php } ?> 
                <option>- - - Выберите - - -</option>
                <option>Родительская</option>
                <option>Подчиняемая</option>
            </select>
            
<?php if($c['parent_id'] > 0){ ?>            
            <select name="parent_id" id="connect-category">
                <?php $current_parent = getCategory($c['parent_id']); ?>
                <option><?=$current_parent['name']?></option>                
                
                <option disabled>- - - - - - - -</option>
                <?php 
                $parents = getParentCategories();
                foreach ($parents as $parent):?>
                
                    <option value="<?=$parent['id']?>"><?=$parent['name']?></option>
                    
                    <?php 
                    $categorys = getCategories($parent['id']);
                    foreach ($categorys as $category):?>
                    
                    <option value="<?=$category['id']?>">&nbsp; &nbsp; &nbsp;<?=$category['name']?></option>
                    
                    <?php endforeach; ?>                
                <?php endforeach; ?>
            </select> 
<?php }else{ ?>
            <select id="connect-category" style="display: none;">
                <option>- - - Выберите - - -</option>
                <?php 
                $parents = getParentCategories();
                foreach ($parents as $parent):?>
                
                    <option value="<?=$parent['id']?>"><?=$parent['name']?></option>
                    
                    <?php 
                    $categorys = getCategories($parent['id']);
                    foreach ($categorys as $category):?>
                    
                    <option value="<?=$category['id']?>">&nbsp; &nbsp; &nbsp;<?=$category['name']?></option>
                    
                    <?php endforeach; ?>                
                <?php endforeach; ?>
            </select>
            <input type="hidden" id="parent-id" name="parent_id" value="0">
<?php } ?>            
        </td>
    </tr>
</table>
    <input type="hidden" name="id" value="<?=$_GET['id']?>">
</form>

<hr>
<p style="text-align:center;">
<?php if($_GET['id']){ ?>
    <button class="button" onclick="ajax_category('edit');">Сохранить</button>
<?php }else{ ?>
    <button class="button" onclick="ajax_category('add');">Сохранить</button>
<?php } ?>
<button class="disabled" onclick="CloseModal();">Отмена</button>
</p>