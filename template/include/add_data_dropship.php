<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

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
?>
<style>
    #box-table{
        max-height: 264px;
        overflow: auto;
        border: 1px dashed #ABABAB;
    }
    #table-dropship-data{
        font-size: 12px;
    }
    .editable{
        color: #284986;
        margin: 0px !important;
        padding: 1px 1px !important;
        line-height: 15px;
        min-width: 160px !important;
        /*min-height: 50px !important;*/
        overflow: hidden;
        font-size: 12px;
    }
</style>
<?php
    db_connect();
    $query = "SELECT * FROM `dropshipping_table_".$_GET['table']."` ORDER BY id DESC LIMIT 1";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    $rows_in_table = $sum['id'];
    $next_id_table = $rows_in_table + 1;
?>
<script>
    $('#forma-dropshipping textarea[name="id"]').val('<?=$next_id_table?>');
    $('#forma-dropshipping textarea[name="id"]').attr('readonly','true').css('background','#E6E6E6');
    $('#forma-dropshipping textarea[name="date"]').val('<?=date("Y-m-d");?>');
</script>
<form id="forma-dropshipping">
    <div id="box-table">
    <table id="table-dropship-data">                
                <?php foreach ($names as $name): ?>
                    <tr>
                        <td align="right" width="180px" style="font-weight: bold;">
                            <?=getDropshippingTableTitle($_GET['table'],$name)?>
                        </td>
                        <td><textarea name="<?=$name?>" class="editable" rows="1" cols="40" spellcheck="false"></textarea></td>
                    </tr>   
                <?php endforeach; ?>
    </table>  
    <input type="hidden" name="table" value="<?=$_GET['table']?>"> 
    </div>
    
    <div style="text-align: center;">
        <hr>
        <button class="button" onclick="ajax_dropship('add'); return false;">Сохранить</button>
    </div>
    
</form>    