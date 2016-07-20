<style>
    #box-email-selected{
        height: 250px;
        overflow: auto;
        border: 1px solid #CCC;
        background: #FFF;
    }
    #box-email-selected div:nth-child(odd){
        background: #EEE;
    }
    #box-email-selected div:nth-child(even){
        background: #FFF;
    }
    .selected_row_mail{
        /*background: #FF972F*/
        background: #CCE974 !important;
        color: green;
        font-weight: bold;
    }
    #div-select-all-email{
        display: inline-block;
        width: auto;
        font-weight: bold;
        color: green;
        padding: 0px 8px 0px 1px;
    }
    #div-select-all-email:hover{
        background: #CCE974;
    }
    #div-select-all-email label:hover{
        cursor: pointer;
    }
    #list-message{
        float: right; 
        margin-right: 20px;
    }
</style>
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$clients = getClientsToEmail();
?>
<script>
function SELECTED_PHONE(){
    $(".sel-mail").each(function(){
        if( $(this).is(":checked") ){
            $(this).closest("div").addClass("selected_row_mail");
        }else{
            $(this).closest("div").removeClass("selected_row_mail");
        }
    });
}
function COUNT_SELECTED_PHONE(){
    var count = $(".sel-mail:checked").length;
    if(count > 0){
        $('#button-operation-delete').fadeIn();
        $('#list-message').html('Выделено элементов: <b>'+count+'</b>');
    }else{
        $('#button-operation-delete').hide();
        $('#list-message').html('');
    }
}
function SELECT_ALL_PHONE(){
     $('#select-all-email').click(function(event) {
         if(this.checked) {
             $('.sel-mail').each(function() { 
                 this.checked = true; 
                 SELECTED_PHONE();
                 COUNT_SELECTED_PHONE();
             });
         }else{
             $('.sel-mail').each(function() {
                 this.checked = false;
                 SELECTED_PHONE();
                 COUNT_SELECTED_PHONE();
             });         
         }
     });
}
function SELECT_SHIFT_PHONE() {
  var _last_selected = null, checkboxes = $( ".sel-mail" );
  checkboxes.click( function( e ) {
    var ix = checkboxes.index( this ), checked = this.checked;
    if ( e.shiftKey && ix != _last_selected ) {
      checkboxes.slice( Math.min( _last_selected, ix ), Math.max( _last_selected, ix ) )
       .each( function() { 
           this.checked = checked
       });       
      _last_selected = null;
    } else { _last_selected = ix }
    SELECTED_PHONE();
    COUNT_SELECTED_PHONE();
  })
}
function insert_selected_phone_in_textarea(){
    var i=0;
    $('textarea[name="destination"]').text('');    
    $(".sel-mail:checked").each(function(){
        
        if( $(this).is(":checked") ){
            $('textarea[name="destination"]').append(''+$(this).attr('name')+',');
            i++;
        }    
    });  
        var list = $('textarea[name="destination"]').text(); 
        var clear = list.substring(0, list.length - 2);
        $('textarea[name="destination"]').text(clear);
        if(i > 299){
            //alert('Выбрано '+i+' номеров! Рекомендуется не более 300. ');
        }
    CloseModal();
}

$(document).ready(function(){   
        SELECT_SHIFT_PHONE();
        SELECT_ALL_PHONE();
    $('body').click(function(){
        SELECT_SHIFT_PHONE();
    });
    $('#select-group-clients').change(function(){
        $('#box-email-selected').html('<img src="image/loader_big.gif" style="margin-bottom: -6px;"> &nbsp Подождите, пожалуйста...');
        var group_id = $(this).val();
        $.ajax({
            type: "POST",
            url: "/modules/update_phone_list.php",
            data: {group_id:group_id},
            beforeSend: function(){
                $('#count-emails').text(0);
                $('#box-email-selected').html('<img src="image/loader_big.gif" style="margin-bottom: -6px;"> &nbsp Подождите, пожалуйста...');
            },
            success: function(res){ 
                //alert(res);
                if(res==''){ 
                    $('#box-email-selected').html('<p style="color:red; text-align:center;">Нет телефонных номеров в данной группе!</p>');
                }else{
                    $('#box-email-selected').html(res);
                }
                
                var mails = $('.sel-mail').length;
                $('#count-emails').text(mails);
            },
            error: function() { alert('Ошибка ajax! cod: update_phone_list.php'); }
        });
    });
});
</script>
<div>
    <p>
        Группа клиентов получателей &nbsp;
        <select id="select-group-clients">
                <option>- Выберите группу -</option>
                <option disabled>- - - - - - - - - - - - - - - -</option>
                <option value="">Все группы</option>
                <option disabled>- - - - - - - - - - - - - - - -</option>
            <?php 
                $groups = GetClientsGroups(); 
                foreach ($groups as $group):?>
                <option value="<?=$group['id']?>"><?=$group['name']?></option>
           <?php endforeach; ?>
        </select>
    </p>
    
<div id="div-select-all-email"> 
    <input type="checkbox" id="select-all-email"> <label for="select-all-email">Выбрать все</label> 
</div>
<span id="list-message"></span>
</div>

<div id="box-email-selected">
<?php /*
$i=0;
foreach ($clients as $client):
    $phone = preg_replace('/[^0-9]/', '', $client['phone']); //убираем всё, кроме цифр
    $first_symbol = substr($phone, 0, 1); //проверяем первый символ начала строки
    if($first_symbol=='0'){$phone = '+38'.$phone;}
    if($first_symbol=='8'){$phone = '+3'.$phone;}
    if($first_symbol=='3'){$phone = '+'.$phone;}
    $number = substr($phone, 0, 1); //если номер начинается с "+"
    if(strlen($phone) == 13 && $number=='+'){ //если 13 символов
        $i++;
?>
<div>
<input type="checkbox" class="sel-mail" name="<?=$phone?>" id="<?=$client['id']?>">
<?=$phone?>
<span style="color:#ABABAB;"> - <?=$client['name']?></span>
<span style="color:#ABABAB; float: right; margin-right: 4px; font-weight: 100;"> <?=$client['site']?></span>
</div>
<?php } endforeach;*/ ?>   
</div>
 <p>Всего подгружено: <b id="count-emails"><?=$i;?></b></p>
<hr>
<div style="text-align:center;">
    <button class="button" onclick="insert_selected_phone_in_textarea();">Выбрать</button>
</div>