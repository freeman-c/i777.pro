<h2>Валюты
    <span id="panel-button-operation">
        <button class="button" onclick="add_new_valuta('<?=$_SESSION['user']['login']?>');">+ Добавить</button>
        <button class="button-error" id="button-operation-delete" onclick="delete_valuta();">Удалить <span id="count-elements-delete"></span></button>
    </span>
</h2>
<br>
<style>
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
<form id="form-valuta">  
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
            <th width="300px">Обозначение</th>
            
            <th width="22px"></th>
        </tr>
    </thead>
    <tbody>
<?php 
$valuts = getValuts();
foreach ($valuts as $valuta):
?>
    <tr>
        <td>
            <input type="checkbox" class="selected" name="need_delete[<?=$valuta['id']?>]" id="checkbox<?=$valuta['id']?>">
        </td>
        <td>
            <?=$valuta['name']?> 
        </td>
        <td style="color:#757575; font-size: 12px;"><?=$valuta['symbol']?></td>
        <td>
            <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_valuta('<?=$valuta['id']?>');">
        </td>
    </tr>  
<?php endforeach;?>
    </tbody>
</table>
</form>