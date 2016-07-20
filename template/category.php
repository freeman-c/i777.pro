<h2>Категории товаров
    <span id="panel-button-operation">
        <button class="button" onclick="add_new_category('<?=$_SESSION['user']['login']?>');">+ Добавить</button>
        <button class="button-error" id="button-operation-delete" onclick="delete_category();">Удалить <span id="count-elements-delete"></span></button>
    </span>
</h2>
<br>
<style>
    /*#form-category{
        overflow: auto;
        width: 1040px;
        max-height: 380px;
        padding-bottom: 30px;
        border-top: 1px solid #6A9FD0;
        border-left: 1px solid #B3DCE6;
        border-right: 1px solid #B3DCE6;
        border-bottom: 1px solid #B3DCE6;        
    }*/
    #table-list{
        background: #FFF;
    }
    #table-list thead th{
        padding: 5px 8px;
        white-space: nowrap;
    }
    #table-list tbody tr:hover{
        background: #FF9;
    }
    #table-list tbody td{
        padding: 1px 10px;
        white-space: nowrap;
        text-shadow: none;
    }
    /*--------------------*/
    .selected-row-in-table{
        background: #FF6 !important;
    }
    .arrow-category{
        font-family: Tahoma, sans-serif;
        font-size: 13px;
        color: #ABABAB;
    }
    /*--------------------*/
    .on-off{
        margin: 0px 0px -3px 0px;
        cursor: pointer;
    }
</style>
<script>
$(document).ready(function(){    
    $('#table-list tbody td').click(function(event){
        $('#table-list tbody tr').removeClass('selected-row-in-table');
        t=event.target||event.srcElement;
        $(t).parent('tr').addClass('selected-row-in-table');
    });    
});    
</script>
<form id="form-category">  
    <table id="table-list" border="0" cellspacing="0">
    <thead>
        <tr>
            <th width="16px"> 
                <div id="box-input-select-all">
                    <input type="checkbox" id="select-all-checkbox">
                    <div class="box-arrow-down"></div>
                </div> 
            </th>
            <th> <span id="table-message"></span> </th>
            <th>id</th>
            <th>Статус</th>
            
            <th width="22px"></th>
        </tr>
    </thead>
    <tbody>
<?php 
$categories = getParentCategories();
foreach ($categories as $category):
?>
    <tr>
        <td>
            <input type="checkbox" class="selected" name="need_delete[<?=$category['id']?>]" id="checkbox<?=$category['id']?>">
        </td>
        <td style="text-transform: uppercase; color: #111; font-family: 'magistral';">
            <?=$category['name']?> 
        </td>
        <td style="color:#ABABAB;"><?=$category['id']?></td>
        <td>
        <?php if($category['status'] > 0){?>
                <img src="<?=SITE_URL?>/image/on.png" class="on-off" onclick="change_status_category('<?=$category['id']?>','0');">
        <?php }else{?>
                <img src="<?=SITE_URL?>/image/off.png" class="on-off" onclick="change_status_category('<?=$category['id']?>','1');">
        <?php } ?>
        </td>
        <td>
            <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_category('<?=$category['id']?>');">
        </td>
    </tr>
        <!--***************** 2 level *********************-->
        <?php 
            $categorys2 = getCategories($category['id']);
            foreach ($categorys2 as $category2):  ?>            
        <tr>
            <td>
                <input type="checkbox" class="selected" name="need_delete[<?=$category2['id']?>]" id="checkbox<?=$category2['id']?>">
            </td>
            <td>
                &nbsp; &nbsp; &nbsp; <span class="arrow-category">↳</span> <?=$category2['name']?>
            </td>
            <td style="color:#ABABAB;"><?=$category2['id']?></td>
            <td>
            <?php if($category2['status'] > 0){?>
                    <img src="<?=SITE_URL?>/image/on.png" class="on-off" onclick="change_status_category('<?=$category2['id']?>','0');">
            <?php }else{?>
                    <img src="<?=SITE_URL?>/image/off.png" class="on-off" onclick="change_status_category('<?=$category2['id']?>','1');">
            <?php } ?>
        </td>
            <td>
                <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_category('<?=$category2['id']?>');">
            </td>
        </tr>        
                <!--***************** 3 level *********************-->
                <?php 
                    $categorys3 = getCategories($category2['id']);
                    foreach ($categorys3 as $category3):  ?>            
                <tr>
                    <td>
                        <input type="checkbox" class="selected" name="need_delete[<?=$category3['id']?>]" id="checkbox<?=$category3['id']?>">
                    </td>
                    <td style="color:#757575;">
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                        <span class="arrow-category">↳</span> <?=$category3['name']?>
                    </td>
                    <td style="color:#ABABAB;"><?=$category3['id']?></td>
                    <td>
                        <?php if($category3['status'] > 0){?>
                                <img src="<?=SITE_URL?>/image/on.png" class="on-off" onclick="change_status_category('<?=$category3['id']?>','0');">
                        <?php }else{?>
                                <img src="<?=SITE_URL?>/image/off.png" class="on-off" onclick="change_status_category('<?=$category3['id']?>','1');">
                        <?php } ?>
                    </td>
                    <td>
                        <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_category('<?=$category3['id']?>');">
                    </td>
                </tr>
                <?php endforeach;?> 
        <?php endforeach;?>    
<?php endforeach;?>
    </tbody>
</table>
</form>