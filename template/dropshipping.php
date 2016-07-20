<h2>База
    <span id="panel-button-operation">
        <!--<button class="button" onclick="add_dropshipping_table();">+ Добавить таблицу</button>
        <button class="button-error" onclick="delete_dropship('<?=$_GET['table']?>');">Удалить текущую таблицу</button> -->
        <button class="button-error" id="button-operation-delete" onclick="delete_data_drop();">Удалить <span id="count-elements-delete"></span></button>
    </span>
</h2>
<br>
<style>
    #form-dropy{
        overflow: auto;
        width: 1020px;
        max-height: 380px;
        padding-bottom: 30px;
        border-top: 1px solid #6A9FD0;
        border-left: 1px solid #B3DCE6;
        border-right: 1px solid #B3DCE6;
        border-bottom: 1px solid #B3DCE6; 
        padding: 0px 10px 10px 10px;
    }
    #table-dropy{
        border-top: 1px solid #CCC;
        border-right: 1px solid #CCC;
        font-size: 12px;
    }
    #table-dropy tbody td{
        border-left: 1px solid #CCC;
        border-bottom: 1px solid #CCC;
        padding: 0px 2px;
        line-height: 15px;
        color: #333;
    }
    #table-dropy thead th{
        border-left: 1px solid #CCC;
        border-bottom: 1px solid #CCC;
        padding: 4px 3px;
        white-space: nowrap;
        line-height: 15px;
    }
    .title-teble-dropy{
        background: linear-gradient(to bottom, #F7FBFC 0px, #D9EDF2 40%, #ADD9E4 100%) transparent;
        text-shadow: 0px 1px 1px #FFF;
        font-weight: bold;
    }
    #table-dropy tbody tr:hover,.selected_row{
        /*background: #E9EFF8 !important;*/
        /*background: #CCDEEC !important;*/
        background: #B6C6D7 !important;
    }
    #table-dropy tbody tr:nth-child(odd){
        background: #FFF;
    }
    #table-dropy tbody tr:nth-child(even){
        background: #E9E9E9;
    }    
    .operation-drop{
        /*background: linear-gradient(to bottom, #F7FBFC 0px, #D9EDF2 40%, #ADD9E4 100%) transparent;
        border: 1px solid #6A9FD0;
        padding: 1px 1px 0px 1px;*/
        margin: 0px 4px 0px 0px;
        cursor: pointer;
    }
    .data-value{
        position: relative;
        /*vertical-align: top;
        display: table-cell;
        width: 100%;
        height: 100%;*/
    }
    .data-value span{
        position: relative;
        display: block;
        overflow: hidden;
        /*max-width: 80px;*/
        max-height: 46px;
    }
    #editable{
        background: #FFC;
        color: #284986;
        margin: 0px !important;
        padding: 0px 1px !important;
        line-height: 15px;
        position: absolute;
        top: -1px;
        left: -1px; 
        /*min-width: 110px !important;*/
        /*min-height: 50px !important;*/
        border: 1px solid #4A8CC7;
        overflow: hidden; 
    }
    .selected-td{
        background: #ADD9E4;
    }
    #add-data-in-drop{
        padding: 3px 10px;
        font-size: 13px;
    }
    #ul-statusy{
    }
    #panel-div-ul-statusy{ 
        /*float: left;
        width: 1000px;
        overflow: auto;*/
    }
    /*------------------------------------*/
    #popup-box-new-table{
        /*display: none;*/
        position: absolute;
        top: 32px;
        left: -20px;
        background: #FFD042;
        border: 1px solid #045694;
        padding: 14px 12px;
        color: #454545;
        width: 240px;
        box-shadow: 0px 1px 5px #666;
            border-radius: 3px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
        font-family: 'web';
        font-size: 13px;
        line-height: 15px;
        z-index: 999;
    }
    #close-popup-box-new-table{
        position: absolute;
        top: 2px;
        right: 2px;
        cursor: pointer;
    }
    #popup-box-new-table:before, #popup-box-new-table:after{
        content: ''; 
        position: absolute;
        left: 50px; 
        top: -21px;
        border: 10px solid transparent;
        border-bottom: 10px solid #045694;
    }
    #popup-box-new-table:after {
        border-bottom: 10px solid #FFD042;
        top: -19px; 
    }
</style>
<?php
if(!$_GET['table']){
    $_GET['table']='1';
}else{
    $_GET['table'];
}
//$dropy = getDropshippingDocumentList();

?>
    <!--<ul id="ul-statusy">     
        <?php /*foreach ($dropy as $drop): ?>
            <li id="table-<?=$drop['id']?>"><a href="<?=SITE_URL?>/?action=dropshipping&table=<?=$drop['id']?>" class="button"><?=$drop['name']?></a></li>
        <?php endforeach; */ ?>    
            <li> <a href="#" onclick="add_dropshipping_table(); return false;" class="button-edit"><img src="<?=SITE_URL?>/image/plus.ico" style="margin-bottom:-4px;"></a></li>
    </ul> -->      
<script>
    function ClosePopup(){
        $('#popup-box-new-table').remove();
    }
    function RenameTable(){
        var name = $('#input-rename-table').val();
        //alert(name);
            $.ajax({
                url: "/index.php?action=ajax_dropship",
                method: 'POST',
                data : {
                    op:'rename', 
                    id:'<?=$_GET['table']?>',
                    name:name
                },
                beforeSend: function(){
                    WaitingBarShow('Переименование таблицы...');
                },
                success: function(data){
                    //alert(data);
                    WaitingBarHide();
                    MessageTray('Таблица переименована.');
                    //location.reload();
                },
                error: function() { alert('Error RENAME table ajax_dropship'); }                    
            });  
        $('#table-<?=$_GET['table']?> a').text(name);
        $('#popup-box-new-table').remove();
    }
$(function(){
    $.contextMenu({
        selector: '.context-menu', 
        callback: function(key, options) {
            //alert("clicked: " + key);
            switch (key) {
                case ('add'):
                    insert_data_dropship('<?=$_GET['table']?>');
                break;
                case ('settings'):
                    edit_dropshipping_table('<?=$_GET['table']?>');
                break;
                case ('edit'):                    
                    //alert('rename');
                    $text = $('#table-<?=$_GET['table']?>').text();
                    $('#table-<?=$_GET['table']?>').css('position','relative');
                    $('#table-<?=$_GET['table']?>').append('<div id="popup-box-new-table">'+
                                '<img id="close-popup-box-new-table" onclick="ClosePopup(); return false;" src="/image/close_active.png">'+
                                '<input type="text" id="input-rename-table" value="'+$text+'" size="24">'+
                                '&nbsp <button onclick="RenameTable(); return false;">ok</button>'+
                           '</div>');
                    $('#input-rename-table').focus();
                break;
                case ('delete'):
                    delete_dropship('<?=$_GET['table']?>');
                break;
            }
        },
        items: {            
            //"cut": {name: "Cut", icon: "cut"},
            //"copy": {name: "Copy", icon: "copy"},
            //"paste": {name: "Paste", icon: "paste"},
            "add": {name: "Вставить данные", icon: "add"},
            "settings": {name: "Настроить таблицу", icon: "settings"},
            "separator": "-",
            "edit": {name: "Переименовать таблицу", icon: "edit"},
            "delete": {name: "Удалить таблицу", icon: "delete"}            
            //"quit": {name: "Quit", icon: "quit"}
        }
    });
});
    

$(document).ready(function(){ 
    //***********************************************
    $selected=document.location.href;
        $.each($("#ul-statusy li a"),function(){
            if(this.href==$selected){
                $(this).addClass('tab-status-active context-menu');
                $(this).parent().parent().show();
        };
    });
    //************************************************
    $('.data-value').click(function(){
        $('.data-value').removeClass('selected-td');
        $(this).addClass('selected-td');
    });
    $('.data-value').dblclick(function(){
        var row = $(this).find('span').attr('id');        
        if(row=='id'){
            alert('AUTO_INCREMENT\nПоле (id) создается автоматически.\nМенять его нельзя!');
        }else{
            $width = $(this).width();
            $height = $(this).height();
            $n_width = ($width + 2).toFixed(0);
            $text = $(this).text();
            $(this).append('<textarea id="editable" spellcheck="false" style="width: '+$n_width+'px; height: '+$height+'px;">'+$text+'</textarea>');
            $('#editable').css('z-index','24');
            $('#editable').focus();            
                
                var row = $(this).find('span').attr('id'); 
                $('.editable-update').attr('name',row);
                var id = $(this).closest('tr').find('td:nth-child(2) span').text();
                $('.editable-update').attr('id',id);
        }
    });
    //******************* save data in column ******************
    $('body').click(function(event){
        if($('#editable').length > 0){
            if ($(event.target).closest("#editable").length === 0) {
                $text = $("#editable").val();                
                $("#editable").closest('td').html('<span>'+$text+'</span>');
                $('.editable-update').val($text); 
                    var id = $('.editable-update').attr('id');
                    var name = $('.editable-update').attr('name');
                    var text = $('.editable-update').val();
                            $.ajax({
                                url: "/index.php?action=ajax_dropship",
                                method: 'POST',
                                data : {
                                    op:'update', 
                                    table:'<?=$_GET['table']?>',
                                    id:id,
                                    name:name,
                                    text:text
                                },
                                beforeSend: function(){
                                    WaitingBarShow('Сохранение данных...');
                                },
                                success: function(data){
                                    //alert(data);
                                    WaitingBarHide();
                                    MessageTray('Таблица '+id_table+' удалена.');
                                    //location.reload();
                                    window.location.href = '/?action=dropshipping';
                                },
                                error: function() { alert('Error UPDATE data ajax_dropship'); }                    
                            });               
                $("#editable").remove();
                MessageTray('Данные в строке обновлены!');
                Text_Overflow();                
            }
        }else{
            event.stopPropagation();
        }
    }); 
    //**********************************************************
    Text_Overflow();
});

    function checkSel(event){
        t=event.target||event.srcElement;
        if( $(t).is(":checked")){
            var id = $(t).closest('td').next('td').find('span').text();
            $(t).attr('id','checkbox'+id+'');
            $(t).attr('name','need_delete['+id+']');
            //alert(id);
        }else{
            $(t).removeAttr('id');
            $(t).removeAttr('name');
            //alert('off');
        }
    }
    
</script>
<h1>Функция временно не доступна!</h1>
<h3>Ошибка определения структуры таблицы.</h3>
<!--<form id="form-dropy">
    <div style="text-align: center; padding: 4px 4px;">
        <button class="button-success" id="add-data-in-drop" onclick="insert_data_dropship('<?=$_GET['table']?>'); return false;">Вставить данные в таблицу</button>    
    </div>
    <?php  
    /*
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
    echo '<table id="table-dropy" cellspacing="0" border="0">'; // отображаем названия полей
        echo '<thead>';
            echo '<tr class="title-teble-dropy">';
                    //echo "<th valign='top'> <input type='checkbox' id='select-all-checkbox'></th>";
                    echo "<th> </th>";
                foreach ($names as $name){
                    echo "<th align='center'>".getDropshippingTableTitle($_GET['table'],$name)." </th>";
                }
            echo "</tr>";        
        echo '<thead>';
        echo '<tbody>';
            for($i=0;$i<$n; $i++){ // перебираем все строки в результате запроса на выборку
                echo "<tr>";  
                    echo "<td valign='top'>
                            <input type='checkbox' onclick='checkSel(event);' class='selected'>
                          </td>";
                    foreach  ($names as $k => $val) { // перебираем все имена полей
                        $value = mysql_result($q,$i,$val); // получаем значение поля                        
                            echo "<td valign='top' class='data-value'><span id='$val'>$value</span></td>"; // выводим значение поля                       
                    }
                echo "</tr>";
        }
        echo '</tbody>';    
echo "</table>"; 
     */   
    ?>
<input type="hidden" class="editable-update" name="" id="" value="">    
<input type="hidden" name="table" value="<?=$_GET['table']?>"> 
</form>-->