<h2>Производители
    <span id="panel-button-operation">
        <button class="button" onclick="add_new_manufacturer('<?=$_SESSION['user']['login']?>');">+ Добавить</button>
        <button class="button-error" id="button-operation-delete" onclick="delete_manufacturer();">Удалить <span id="count-elements-delete"></span></button>
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
        text-align: left;
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
    .man-icon{
        margin: 0px 8px -4px 0px;
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
<form id="form-manufacturer">  
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
            <th width="300px">Описание</th>            
            <th>Статус</th>
            
            <th width="22px"></th>
        </tr>
    </thead>
    <tbody>
<?php 
$manufacturers = getManufacturers();
foreach ($manufacturers as $manufacturer):
?>
    <tr>
        <td>
            <input type="checkbox" class="selected" name="need_delete[<?=$manufacturer['id']?>]" id="checkbox<?=$manufacturer['id']?>">
        </td>
        <td>
            <?php
            /*if(empty($manufacturer['image'])){echo '<img class="man-icon" src="'.SITE_URL.'/image/manufacturer/noimage.png">';}
                else{
                if(file_exists($_SERVER['DOCUMENT_ROOT'].'/image/manufacturer/'.$manufacturer['image'])) {
                    echo '<img class="man-icon" src="'.SITE_URL.'/image/manufacturer/'.$manufacturer['image'].'">';}
                else {
                    echo '<img class="man-icon" src="'.SITE_URL.'/image/manufacturer/noimage.png">';}}
            */?>
            <?=$manufacturer['name']?> 
        </td>
        <td style="color:#ABABAB;"><?=$manufacturer['id']?></td>
        <td style="color:#ABABAB; font-size: 12px;"><?=$manufacturer['description']?></td>        
        <td>
            <?php if($manufacturer['status'] > 0){?>
                    <img src="<?=SITE_URL?>/image/on.png" class="on-off" onclick="change_status_brand('<?=$manufacturer['id']?>','0');">
            <?php }else{?>
                    <img src="<?=SITE_URL?>/image/off.png" class="on-off" onclick="change_status_brand('<?=$manufacturer['id']?>','1');">
            <?php } ?>
        </td>
        <td>
            <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_manufacturer('<?=$manufacturer['id']?>');">
        </td>
    </tr>  
<?php endforeach;?>
    </tbody>
</table>
</form>