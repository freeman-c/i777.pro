<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
?>
<style>
    #box-new-table{
        min-height: 264px;
        max-height: 264px;
        overflow: auto;
        border: 1px dashed #ABABAB;
        padding: 4px;
    }
    #table-dropship-new{
        background: #F6F6F6;
        width: 100%;
        border-spacing: 0px;
        font-size: 12px;
        border-top: 1px solid #CCC;
        border-right: 1px solid #CCC;
    }
    #table-dropship-new input[type="text"]{
        padding: 0px 2px;
        border: 1px solid #CCC;
    }
    #table-dropship-new thead th, #table-dropship-new tbody td{
        border-bottom: 1px solid #CCC;
        border-left: 1px solid #CCC;
    }
    #table-dropship-new tbody td{
        font-size: 10px;
        font-family: Tahoma;
        line-height: 11px;
    }
    #table-dropship-new tbody tr:nth-child(odd){
        background: #F6F6F6;
    }
    #table-dropship-new tbody tr:nth-child(even){
        background: #E9EFF8;
    }
    
    #table-dropship-new tbody tr:hover{
        background: #FF9;
    }
    #table-dropship-new thead th{
        background: #FF6;
        text-align: left;
    }
    .add-row-in-table{
        padding: 1px 6px 2px;
        margin-top: 4px;
        font-size: 12px;
    }
    .readonly{
        background: #EEE;
        cursor: no-drop;
        font-weight: bold;
        color: #111 !important;
    }
    .readonly:focus{
        text-shadow: none !important;
        background: #EEE !important;
        border: 1px solid #CCC !important;
    }
    .del-row-icon{
        cursor: pointer;
    }
</style>

<script>    
function AddRowInTable(){
    $('#focus').removeAttr('id');
    $('#table-dropship-new').append('<tr>'+
                                        '<td>'+
                                            '<input type="text" size="28" id="focus" onfocus="CreateName(event);" onblur="SaveName(event);" value="" placeholder="Название столбика">'+
                                            
                                        '</td>'+                                        
                                        '<td> <img onclick="DeleteRow(event);" class="del-row-icon" src="<?=SITE_URL?>/image/minus_circle.ico"> </td>'+
                                    '</tr>');
        $('#focus').focus();                    
}
function DeleteRow(event){
    t=event.target||event.srcElement;
    $(t).closest('tr').remove();
}
function CreateName(event){
    t=event.target||event.srcElement;
        $(t).syncTranslit({
                destination: 'create_name', //id элемента-приемника
                type: 'url', //url(default) или raw () url - заменяются спец. символы, raw - с сохранением всех спец. символов
                caseStyle: 'lower', //lower(default), normal, upper
                urlSeparator: '-' //разделитель слов       
        });
}
function SaveName(event){
    t=event.target||event.srcElement;
    $value = $('#create_name').val();
    $(t).attr('name',$value);
}    
</script>

<?php if($_GET['table']){
    $connect = mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD);
    $table_name = "dropshipping_table_".$_GET['table']."";
    mysql_select_db(DB_DATABASE);
    $list_f = mysql_list_fields(DB_DATABASE,$table_name);
    $n1 = mysql_num_fields($list_f);
    // сохраним имена полей в массиве $names
    for($j=0;$j<$n1; $j++){
        $names[] = mysql_field_name ($list_f,$j);
    }
    $sql = "SELECT * FROM $table_name"; // создаем SQL запрос
    $q = mysql_query($sql,$connect) or die(); // отправляем запрос на сервер
    $n = mysql_num_rows($q); // получаем число строк результата рисуем HTML-таблицу

    db_connect();
    $query = "SELECT * FROM `dropshipping_table_".$_GET['table']."` ORDER BY id DESC LIMIT 1";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    $rows_in_table = $sum['id'];
    $next_id_table = $rows_in_table + 1;
?>
<form id="forma-dropshipping">
    <div id="box-new-table">
    <table id="table-dropship-new"> 
            <thead>
                <tr>
                    <!--<th>Имя поля</th>-->
                    <th>Название поля</th>
                    <!--<th>Сортировка</th>-->
                    <th width="16px"> </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                //for($i=0;$i<$n; $i++){
                //echo '<tr>';
                    foreach ($names as $k => $val): 
                        $value = mysql_result($q,$i,$val);
                        if($val == 'id'){?>
                        <tr><td> <input type="text" readonly size="28" style="background:#E6E6E6; cursor:not-allowed;" value="<?=getDropshippingTableTitle($_GET['table'],$val)?>"> </td>
                            <td> 
                                <img src="<?=SITE_URL?>/image/locked.ico"> 
                            </td>
                        </tr>    
                        <?php  }else{?>
                            <tr>
                            <!--<td> <input type="text" name="" size="14" value="<?=$val?>"> (англ.) без пробелов</td>
                            -->
                            <td> <input type="text" name="" size="28" value="<?=getDropshippingTableTitle($_GET['table'],$val)?>"> </td>
                            <!--<td> <input type="text" name="" size="2" value=""> только цифры</td>-->
                            <td> 
                                <img onclick="DeleteRow(event);" class="del-row-icon" src="<?=SITE_URL?>/image/minus_circle.ico"> 
                            </td>
                        </tr>
                        <?php }             
                    endforeach;
                //echo '</tr>';
                //}
                ?>
            </tbody>
    </table>  
    <input type="hidden" name="table" value="<?=$_GET['table']?>"> 
    </div>  
    <button class="button-edit add-row-in-table" onclick="AddRowInTable(); return false;">+ добавить поле</button>
    <div style="text-align: center;">
        <hr>
        <button class="button" onclick="change_dropship_table(); return false;">Сохранить</button>
    </div>    
</form> 

<!-- ************************** new table *************************** -->
<?php
}else{
?>
<script>
    $('input[name="name_table"]').focus();
    $n = $('#ul-statusy').find('li').length;
    $('input[name="name_table"]').val('Таблица '+$n+'');
</script>

<form id="form-new-table">
Название таблицы: <input type="text" name="name_table" value="Таблица X" size="28">    
<div id="box-new-table">
<table align="center" id="table-dropship-new">
    <thead>
        <tr>
            <!--<th>Имя поля</th>-->
            <th>Название поля</th>
            <!--<th>Сортировка</th>-->
            <th width="16px"> </th>
        </tr>
    </thead>
    <tbody>
        <!--<tr>
            <td> <input type="text" name="" size="12" value="id" readonly class="readonly"> (англ.) без пробелов</td>
            <td> <input type="text" name="" size="17" value="id" readonly class="readonly"> на русском </td>
            <td> <input type="text" name="" size="2" value="1" readonly class="readonly"> только цифры</td>
            <td> <img src="<?=SITE_URL?>/image/locked.ico"> </td>
        </tr>-->
        <tr>
            <!--<td> <input type="text" name="" onkeyup="CreateNameInput(event);" size="14" value="" placeholder="var_string"> (англ.) без пробелов</td>
            -->
            <td>
                <!--<input type="hidden" name="id" value="id">-->
                <input type="text" id="focus" onfocus="CreateName(event);" onblur="SaveName(event);" name="" size="28" value="" placeholder="Название столбика">
                
            </td>
            <!--<td> <input type="text" name="" size="2" value=""> только цифры</td>-->
                    
            <td width="16px"> 
                <img onclick="DeleteRow(event);" class="del-row-icon" src="<?=SITE_URL?>/image/minus_circle.ico"> 
            </td>
        </tr>
    </tbody>    
</table>    
</div>
<button class="button-edit add-row-in-table" onclick="AddRowInTable(); return false;">+ добавить поле</button>
<input type="hidden" id="create_name">

    <div style="text-align: center;">
        <hr>
        <button class="button" onclick="create_dropship_table(); return false;">Сохранить</button>
    </div>
</form>
<?php } ?>