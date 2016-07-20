<h2><img src="<?=SITE_URL?>/image/sms_32.png" style="margin: -6px 10px -8px -4px;">Рассылка SMS сообщений</h2>
<style>
    #sms-table td{
        padding: 4px 8px; 
    }
    #sms-text-box{
        position: relative;
        display: inline-block;
        width: auto;
    }
    #sms-text-box span{
        position: absolute;
        bottom: 1px;
        left: 1px;
    }
    #text-count-symbols{
        color:#3F80C0;
        font-size: 11px;
        border-top:1px solid #CCC;
        border-right: 1px solid #CCC;
        padding: 0px 6px 2px;
        border-radius: 0px 5px 0px 3px;
        -moz-border-radius: 0px 5px 0px 3px;
        -webkit-border-radius: 0px 5px 0px 3px;
        background: #E9EFF8;
        text-shadow: 0px 1px 1px #FFF;
    }
    #text-count-symbols:hover{
        cursor: pointer;
    }
    .warning-sms-count{
        color:#FFF !important;
        background: #9BD14F !important;
        text-shadow: none !important;
    }
    .warning-sms-count-i{
        font-family: Verdana;
        font-size: 11px;
        color:#900;
    }
    /*----- box template -----*/
    .title-box-template{
        color: #4A8CC7;
        font-family: "magistral";
        font-size: 17px;
    }
    #box-template{
        border: 1px solid #CCC;
        background: #FFF;
        text-align: left;
        overflow: auto;
        height: 150px;
    }
    .template-val{
        padding: 1px;
        cursor: pointer;
    }
    /*.template-val:hover{
        background: #FF9 !important;
    }*/
    .template-val:nth-child(odd){
    }
    .template-val:nth-child(even){
        background: #F3F3F3;
    }
    #arrow-selected{
        cursor:pointer;
        display: none;
    }
    
    #button-insert-mail{
        color: #599CFF;
        border-bottom: 1px dashed currentColor;
        font-size: 12px;
    }
    #button-insert-mail:hover{
        color: #2F53FF;
        cursor: pointer;
    }
</style> 
<?php 
$list_templates = getTemplatesSMS();
?>
<script>
    function selected_template(event){
        $('.template-val').removeClass('selected_row');
        $('.text-template').removeAttr('id');
        $('.sender-template').removeAttr('id');
        $('.service-template').removeAttr('id');
        t=event.target||event.srcElement;
        $(t).addClass('selected_row');
        $('#arrow-selected').show();
        $(t).find('.text-template').attr('id','selected-text-template');
        $(t).find('.sender-template').attr('id','selected-sender-template'); 
        $(t).find('.service-template').attr('id','selected-service-template'); 
        
        //alert('<?=$_GET['ttn']?>');
    }
    function insert_val(){
        $('#current-sel-option').remove();
        var message_text = $('#selected-text-template').val();
        var message_sender = $('#selected-sender-template').val();
        var message_service = $('#selected-service-template').val();
        $('textarea[name="text"]').val(message_text);        
        var update_text = $('textarea[name="text"]').val().replace('{ttn}','<?=$_GET['ttn']?>');        
        //alert(target_text);
        $('textarea[name="text"]').val(update_text);
        $('#current-sel-option').remove();
        $('select[name="sender"]').prepend('<option value="'+message_service+'" id="current-sel-option">'+message_sender+'</option>');
        $('select[name="sender"] option:first').attr('selected', 'selected');
        $('#arrow-selected').hide();
        
        var maxLength = $('#text-sms').attr('maxlength');
        var curLength = $('#text-sms').val().length;
        var remaning = maxLength - curLength;
            if (remaning < 0) remaning = 0;
            $('#text-count-symbols').html('осталось <b>'+remaning+'</b> из <b>'+maxLength+'</b>');
            if (remaning == 0){
                $('#text-count-symbols').addClass('warning-sms-count');
                $('#text-count-symbols').html('осталось <b>'+remaning+'</b> из <b>'+maxLength+'</b> - ok');
            }
            else{
                $('#text-count-symbols').removeClass('warning-sms-count');
            }        
    }
    function insert_phone(){
        var title = 'Выбор телефонных номеров';
        var content = '<div id="box_sel_phone"></div>';    
        modal(title,content);
        $('#modal-window').css({'width':'680px','margin-left':'-340px'});
        $('#box_sel_phone').html('<img src="image/loader_big.gif" style="margin-bottom: -6px;"> &nbsp Подождите, пожалуйста...');
        $('#box_sel_phone').load('/template/include/phone_list.php');
    }
</script>
    <table id="sms-table" width="100%" border="0" cellpadding="" cellspacing="0">                
        <tr>
            <td width="150px"></td>
            <td>&nbsp;</td>
            <td rowspan="4" valign="middle" width="32px">
                <img src="<?=SITE_URL?>/image/arrow_left.png" onclick="insert_val();" id="arrow-selected">
            </td>
            <td rowspan="4" width="320px" valign="top" align="center">
                <div class="title-box-template">Список Шаблонов</div>
                <div id="box-template">
                    <?php 
                    foreach ($list_templates as $sel_template): 
                        $sender_t = getSenderSMS($sel_template['sender']);
                    
                        ?>
                        <div class="template-val" onclick="selected_template(event);" ondblclick="insert_val();">
                            <img src="<?=SITE_URL?>/image/phone.ico" style="margin: 1px 2px 0px 0px; float: left;"/>
                            <?=$sel_template['title']?>
                            <input type="hidden" class="text-template" value="<?=str_replace("<br />", "", htmlspecialchars($sel_template['text']));?>">
                            <input type="hidden" class="sender-template" value="<?=$sender_t['name']?>">
                            <input type="hidden" class="service-template" value="<?=$sender_t['turbosms']?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td align="right">Телефон получателей</td>	
            <td>
                <i>Рекомендуется не более 300 за один раз.</i>
                <textarea id="destination" name="destination" cols="80" rows="6" placeholder="+380509997755,+380670002211,+380934447733"></textarea>
                <span id="button-insert-mail" onclick="insert_phone(); return false;">⇱ Выбрать получателей из Базы клиентов</span>
            <br>
            </td>	
        </tr>
        <tr>
            <td align="right">Текст сообщения</td>	
            <td>
<script>
$(document).ready(function(){
        /*$("#destination").keyup(function() {
            this.value = this.value.replace(/[^0-9,\.]/g,'');
        });   */ 
        var maxLength = $('#text-sms').attr('maxlength');           
        $('#text-count-symbols').html('осталось <b>'+maxLength+'</b> из <b>'+maxLength+'</b>');
        $('#text-sms').keyup(function(){
            var curLength = $('#text-sms').val().length;
            $(this).val($(this).val().substr(0, maxLength));
            var remaning = maxLength - curLength;
            if (remaning < 0) remaning = 0;
            //$('#text-count-symbols').html(''+remaning+'/'+curLength+'');
            $('#text-count-symbols').html('осталось <b>'+remaning+'</b> из <b>'+maxLength+'</b>');
            if (remaning == 0){
                $('#text-count-symbols').addClass('warning-sms-count');
                $('#text-count-symbols').html('осталось <b>'+remaning+'</b> из <b>'+maxLength+'</b> - ok');
            }
            else{
                $('#text-count-symbols').removeClass('warning-sms-count');
            }
        });
   //************************************************************************
   $('#send-sms-button').click(function(){
       var destination = String($('textarea[name="destination"]').val());
       var number_phone = destination;
       var text = $('textarea[name="text"]').val();
       var sender = $('select[name="sender"]').val();       
            $.ajax({
                 url: '/modules/send_sms.php',
                 type: 'POST',
                 data:{
                     destination: number_phone,
                     text: text,
                     sender: sender
                 },
                 beforeSend: function(){                
                     $('#send-sms-button').html('<img src="/image/ajax-load.gif"> Отправка SMS...');
                 },
                 success: function(data){
                     CloseModal();
                     var title = 'Отправка SMS сообщения';             
                     modal(title,data);
                     MessageTray('Сообщение отправлено');
                     $('#send-sms-button').html('Сообщение отправлено');
                     $('#send-sms-button').attr('disabled','disabled').addClass('disable');
                 },
                 error: function() { alert('Ошибка отправки СМС!'); }
             });
   });
   
});    
</script>                         
    <div id="sms-text-box"> 
        <textarea id="text-sms" name="text" rows="10" cols="50" maxlength="350" spellcheck="false"></textarea>
                <span id="text-count-symbols"></span>
            </div>
        </td>	
    </tr>
    <tr>
        <td align="right">Отправитель</td>	
        <td>
            <select name="sender">
                    <option value="" id="current-sel-option">- Выберите -</option>
                    <option disabled>- - - - - - - -</option>
                <?php 
                $senders = getSendersSMS();
                foreach ($senders as $sender): ?>
                    <option value="<?=$sender['turbosms']?>"><?=$sender['name']?></option>
                <?php endforeach;
                ?>               
            </select>
        </td>	
    </tr>
    <tr>
        <td colspan="4" align="center">
            <br>
            <button class="button" id="send-sms-button">Отправить</button>
            <button class="disabled" onclick="CloseModal();">Отмена</button>
        </td>
    </tr>
</table>
 