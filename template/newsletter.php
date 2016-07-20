<h2><img src="<?=SITE_URL?>/image/write_e_mail_32.png" style="margin: -6px 10px -8px -4px;">Рассылка Email сообщений</h2>
<style>
    #form-newsletter-box{
        margin-top: 10px;
        /*padding: 26px 15px 26px 15px;*/
        /*width: 860px;
        height: 370px;*/
        display: inline-block;
        /*background: #EEE url(/image/newsletter-box.png) no-repeat;*/
    }
    #table-list-data{
        width: 960px;
    }
    #table-list-data td{
        padding: 4px 0px 4px 4px;        
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
        height: 420px;
    }
    .template-val{
        font-size: 12px;
        padding: 1px;
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .template-val:nth-child(odd){
    }
    .template-val:nth-child(even){
        background: #F3F3F3;
    }
    #arrow-selected{
        cursor:pointer;
        display: none;
    }
    /*-----------------*/
    #send-mail-result-box{
        margin: 20px;
        width: 824px;
        height: 330px;
        text-align: center;
    }
    /*-----------------*/
    #bar{
        background: #FFF;
        border: 1px solid #589235;
        width: 802px;
        height: 20px;
        margin: 0 auto;
        position: relative;
        text-align: left;
    }
    #bar div{
        float: left;
        background: #73BE46;
        /*background: url('image/progressbar.gif') repeat-x;*/
        height: 20px;
    }
    #percent{
        font-weight: bold;
        color: green;
        position: absolute;
        width: 30px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin-left: -15px;
        margin-top: -10px;
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
$list_templates = getTemplatesEmail();
?>
<script>
    function selected_template(event){
        $('.template-val').removeClass('selected_row');
        $('.header-template').removeAttr('id');
        $('.text-template').removeAttr('id');
        $('.sender-template').removeAttr('id');
        $('.email-template').removeAttr('id');
        t=event.target||event.srcElement;
        $(t).addClass('selected_row');
        $('#arrow-selected').show();
        $(t).find('.header-template').attr('id','selected-header-template');
        $(t).find('.text-template').attr('id','selected-text-template');
        $(t).find('.sender-template').attr('id','selected-sender-template'); 
        $(t).find('.email-template').attr('id','selected-email-template'); 
    }
    function insert_val(){
        $('#current-sel-option').remove();
        var message_header = $('#selected-header-template').val();
        var message_text = $('#selected-text-template').val();
        var message_sender = $('#selected-sender-template').val();
        var message_email = $('#selected-email-template').val();
        $('input[name="header"]').val(message_header);
        $('textarea[name="message"]').val(message_text);
        $('#current-sel-option').remove();
        $('select[name="from"]').prepend('<option value="'+message_email+'" id="current-sel-option">'+message_sender+'</option>');
        $('select[name="from"] option:first').attr('selected', 'selected');
        $('#arrow-selected').hide();
        
        CKEDITOR.instances.message.setData(message_text);
        
    }
    function SendEmailSeveral(email){
           $.ajax({
                url: '/modules/send_mail_several.php',
                type: 'POST',
                data: $('#form-newsletter-box').serialize() + '&email='+email+'',
                beforeSend: function(){ 
                    $('#send-mail-button').attr('disabled','disabled');
                    $('#send-mail-button').html('<img src="/image/ajax-load.gif"> Отправка Email...');
                },
                success: function(data){                    
                },
                error: function() { alert('Ошибка отправки Email!'); }
            });
    }
    function insert_email(){
        var title = 'Выбор Email адресов';
        var content = '<div id="box_sel_email"></div>';    
        modal(title,content);
        $('#modal-window').css({'width':'680px','margin-left':'-340px'});
        $('#box_sel_email').load('/template/include/email_list.php');
    }
$(document).ready(function(){      
    //**************************************************
        $('#send-mail-button').click(function(){
            var header = $('input[name="header"]').val();
            //var message = $('textarea[name="message"]').val();
            var message = CKEDITOR.instances.message.getData();
            var from = $('select[name="from"]').val();
            
            var email = $('textarea[name="email"]').val();
            var arr = email.split(', ');            
            var tot = $(arr).length;
            var all = tot - 1;
            
            WaitingBarShow('Ждите...Идёт отправка Email писем...');
                        $('#form-newsletter-box').html('<div id="send-mail-result-box">'+
                                                        '<img src="/image/email5_e0.gif">'+
                                                            '<h2>Выполняется отправка Email сообщений...'+
                                                            '<div id="send-mail-result-message"></div>'+
                                                            '<span id="send-complete">0</span> из '+(all + 1)+'</h2>'+
                                                            '<div id="bar"><span id="percent"></span></div>'+
                                                       '</div>');            
            for($i=0; $i<=all; $i++){  
                    
                    $.ajax({
                        url: '/modules/send_mail_several.php',
                        type: 'POST',
                        //data: $('#form-newsletter-box').serialize()+'&email='+arr[$i],
                        data:{ 
                            email: arr[$i],
                            header: header,
                            message: message,
                            from: from,
                            all: all
                        },
                        beforeSend: function(){},
                        success: function(data){
                            $('#send-mail-result-message').append(data);
                        },
                        error: function() { alert('Ошибка отправки Email!'); }
                    });                  
            }
       });
});
</script>
<?php 
    $lms = getLimitEmailSetting(); 
if( date("Y-m-d") > date('Y-m-d',strtotime($lms['date_today'])) ){
        UpdateLimitCurrentEmailZeroing();
    $lms1 = getLimitEmailSetting();
    $maximum1 = $lms1['maximum'];
    $current1 = $lms1['current'];
    $lost = $maximum1 - $current1;
}else{
    $lms2 = getLimitEmailSetting();
    $maximum2 = $lms2['maximum'];
    $current2 = $lms2['current'];
    $lost = $maximum2 - $current2;
}  
if($lost < 1){
    echo '<h3>На сегодня ('.date("d.m.Y").') лимит отправок Email-писем исчерпан.</h3>';
}else{   
?>
<form id="form-newsletter-box">

<table id="table-list-data" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="right" valign="middle">
            Получатель:
            <!--<button class="button-edit" onclick="insert_email(); return false;">
                <img src="<?=SITE_URL?>/image/list_accept.ico">
                <img src="<?=SITE_URL?>/image/arrow_large_right.ico">
            </button>-->            
        </td>
        <td>
            <!--<input type="text" name="email" size="56" value="">-->            
            <h3 style="padding: 0px; margin: 0px;">Осталось отправок на сегодня: <?=$lost;?> <a href="javascript:void(0);" onclick="javascipt:location.reload()">Обновить</a></h3>
            <!--<i>Рекомендуется не более 300 за один раз.</i>-->
            <textarea name="email" rows="4" cols="89" spellcheck="false"></textarea>
            <span id="button-insert-mail" onclick="insert_email(); return false;">⇱ Выбрать получателей из Базы клиентов</span>
            <br>
        </td>
            <td rowspan="4" valign="middle">
                <img src="<?=SITE_URL?>/image/arrow_left.png" onclick="insert_val();" id="arrow-selected">
                <div style="width:36px;"></div>
            </td>
            <td rowspan="4" width="320px" valign="top" align="center">
                <div class="title-box-template">Список Шаблонов</div>
                <div id="box-template">
                    <?php 
                    foreach ($list_templates as $sel_template): 
                        $sender_t = getSenderEmail($sel_template['sender']);
                    
                        ?>
                    <div class="template-val" onclick="selected_template(event);" ondblclick="insert_val();">
                        <img src="<?=SITE_URL?>/image/e_mail.ico" style="margin: 1px 2px 0px 0px; float: left;"/>
                        <?=$sel_template['title']?>
                        <input type="hidden" class="header-template" value="<?=htmlspecialchars($sel_template['title']);?>">
                        <!--<input type="hidden" class="text-template" value="<?=str_replace("<br />", "", $sel_template['text']);?>">-->
                        <input type="hidden" class="text-template" value="<?=htmlspecialchars($sel_template['text'])?>">
                        <input type="hidden" class="sender-template" value="<?=$sender_t['name']?>">
                        <input type="hidden" class="email-template" value="<?=$sender_t['email']?>">
                    </div>
                    <?php endforeach; ?>
                </div>
            </td>
    </tr>
    <tr>
        <td align="right" width="90px">Тема письма:</td>
        <td>
            <input type="text" name="header" size="90" value="">
        </td>
    </tr>
    <tr>
        <td align="right">Сообщение:</td>
        <td>
            <textarea id="message" name="message" rows="11" cols="55" spellcheck="false"></textarea>
<script> 
    CKEDITOR.replace('message',{
        'height': '220px',
        'filebrowserBrowseUrl':'<?=SITE_URL?>/js/ckeditor/kcfinder/browse.php?type=files',
        'filebrowserImageBrowseUrl':'<?=SITE_URL?>/js/ckeditor/kcfinder/browse.php?type=images',
        'filebrowserFlashBrowseUrl':'<?=SITE_URL?>/js/ckeditor/kcfinder/browse.php?type=flash',
        'filebrowserUploadUrl':'<?=SITE_URL?>/js/ckeditor/kcfinder/upload.php?type=files',
        'filebrowserImageUploadUrl':'<?=SITE_URL?>/js/ckeditor/kcfinder/upload.php?type=images',
        'filebrowserFlashUploadUrl':'<?=SITE_URL?>/js/ckeditor/kcfinder/upload.php?type=flash'
    });
</script> 
        </td>
    </tr>
    <tr>
        <td align="right">Отправитель:</td>
        <td>
            <select name="from">
                <option value="">- Выберите -</option>
                <option disabled>- - - - - - - - - -</option>
                <?php 
                    $elts = getSendersEmail();
                    foreach ($elts as $elt):
                ?>
                <option value="<?=$elt['email']?>"><?=$elt['name']?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="4" align="center">
            <button class="button-success" id="send-mail-button" onclick="return false;">Отправить</button>
        </td>
    </tr>
    
</table>
</form>
<?php } ?>