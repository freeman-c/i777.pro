<style>
    #table-list-data td{
        padding: 2px 4px;
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
        height: 360px;
    }
    .template-val{
        font-size: 12px;
        padding: 1px;
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
</style>
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

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
$(document).ready(function(){      
    //**************************************************
        $('#send-mail-button').click(function(){
           var email = $('input[name="email"]').val();
           var header = $('input[name="header"]').val();
           //var message = $('textarea[name="message"]').val();  
           var message = CKEDITOR.instances.message.getData();
           var from = $('select[name="from"]').val();
           $.ajax({
                url: '/modules/send_mail.php',
                type: 'POST',
                data:{
                    email: email,
                    header: header,
                    message: message,
                    from: from
                },
                beforeSend: function(){ 
                    $('#send-mail-button').attr('disabled','disabled');
                    $('#send-mail-button').html('<img src="/image/ajax-load.gif"> Отправка Email...');
                },
                success: function(data){
                    CloseModal();
                    var title = 'Отправка письма клиенту на Email';             
                    modal(title,data);
                    MessageTray('Email cообщение отправлено');
                    $('#send-mail-button').html('Сообщение отправлено');
                    //$('#send-mail-button').attr('disabled','disabled').addClass('disable');
                },
                error: function() { alert('Ошибка отправки Email!'); }
            });
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
<h3 style="padding: 0px; margin: 0px;">Осталось отправок на сегодня: <?=$lost;?></h3>
<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="right" width="90px">Получатель:</td>
        <td>
            <input type="text" name="email" size="40" value="<?=$_GET['email']?>">
        </td>
            <td rowspan="4" valign="middle">
                <img src="<?=SITE_URL?>/image/arrow_left.png" onclick="insert_val();" id="arrow-selected">
                <div style="width:36px;"></div>
            </td>
            <td rowspan="4" width="240px" valign="top" align="center">
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
                            <input type="hidden" class="text-template" value="<?=htmlspecialchars($sel_template['text']);?>">
                            <input type="hidden" class="sender-template" value="<?=$sender_t['name']?>">
                            <input type="hidden" class="email-template" value="<?=$sender_t['email']?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </td>
    </tr>
    <tr>
        <td align="right">Тема письма:</td>
        <td>
            <input type="text" name="header" size="60" value="">
        </td>
    </tr>
    <tr>
        <td align="right">Сообщение:</td>
        <td>
            <textarea id="message" name="message" rows="12" cols="39" spellcheck="false">Здравствуйте <?=$_GET['fio']?>.</textarea>
 <script> 
    CKEDITOR.replace('message',{
        'height': '240px',
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
            <button class="button" id="send-mail-button">Отправить</button>
            <button class="disabled" onclick="CloseModal();">Отмена</button>
        </td>
    </tr>
    
</table>
<?php } ?>