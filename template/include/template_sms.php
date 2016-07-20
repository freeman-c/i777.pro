<style>
    #table-list-data td{
        padding: 1px 4px;
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
</style>
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$template = getTemplateSMS($_GET['id']);
?>

<form id="forma-template-sms">
<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="right">Название шаблона:</td>
        <td>
            <input type="text" name="title" size="36" value="<?=$template['title']?>">
        </td>
    </tr>
    <tr>
        <td align="right">Содержимое:</td>
        <td>
            <?php 
                $text = str_replace("<br />", "", $template['text']);
            ?>
<script>
$(document).ready(function(){
    
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
   
});    
</script>                         
    <div id="sms-text-box"> 
        <textarea id="text-sms" maxlength="350" spellcheck="false" name="text" rows="8" cols="35"><?=$text?></textarea>
        <span id="text-count-symbols"></span>
    </div>            
        </td>
    </tr>
    <tr>
        <td align="right">Отправитель СМС:</td>
        <td>
            <select name="sender">
            <?php 
            $current_sender = getSenderSMS($template['sender']);
            ?>
                <option value="<?=$current_sender['id']?>"><?=$current_sender['name']?></option>
                <option disabled>- - - - - - - -</option>
            <?php 
            $senders = getSendersSMS();
            foreach ($senders as $sender): ?>
                <option value="<?=$sender['id']?>"><?=$sender['name']?></option>
            <?php endforeach;
            ?>               
            </select>
        </td>
    </tr>    
</table>
<input type="hidden" name="id" value="<?=$template['id']?>">
</form>

<hr>
<p style="text-align:center;">
<?php if($_GET['id']){ ?>
    <button class="button" onclick="ajax_template_sms('edit');">Сохранить</button>
<?php }else{ ?>
    <button class="button" onclick="ajax_template_sms('add');">Сохранить</button>
<?php } ?>
<button class="disabled" onclick="CloseModal();">Отмена</button>
</p>