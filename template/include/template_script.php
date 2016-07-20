<style>
    #table-list-data td{
        padding: 1px 4px;
    }
    #script-text-box{
        position: relative;
        display: inline-block;
        width: auto;
    }
    #script-text-box span{
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
    .warning-script-count{
        color:#FFF !important;
        background: #9BD14F !important;
        text-shadow: none !important;
    }
    .warning-script-count-i{
        font-family: Verdana;
        font-size: 11px;
        color:#900;
    }
</style>
<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$templates = getTemplatesScript();
$template = getTemplateScript($_GET['id']);
?>

<form id="forma-template-script">
<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="right">Тип</td>
        <td>
            <select name="type" style="width: 380px">
                <?php 
                if ($template['type'] == 0){
                ?>
                    <option value="0">Стандартный</option>
                <?php }
                else { ?>
                    <option value="1">Специальный</option>";
                <?php } ?>
                <option disabled>- - - - - - - - -</option> 
                <option value="0">Стандартный</option>
                <option value="1">Специальный</option>
            </select>
        </td>
    </tr>
    <tr>
        <td align="right">Название</td>
        <td>
            <input type="text" name="title" size="36" value="<?=$template['title']?>" style="width: 370px">
        </td>
    </tr>
    <tr>
        <td>
            Вложен в
        </td>
        <td>
            <select name="parent_name" style="width: 380px">
                <option value="<?=$template['p_name']?>"><?=$template['p_title']?></option>
                <option value=""></option>
                <option disabled>- - - - - - - - -</option>
                <?php 
                foreach ($templates as $elem):
                    if ($elem['name'] != $template['name']) {?>
                    <option value="<?=$elem['name']?>"><?=$elem['title']?></option>
               <?php } endforeach; ?>                
            </select>
        </td>
    </tr>
    <tr>
        <td align="right">Текст</td>
        <td>
            <?php 
                $text = str_replace("<br />", "", $template['text']);
            ?>         
            <div id="script-text-box"> 
                <textarea style="width: 370px" id="text-script" spellcheck="false" name="text" rows="10" cols="100"><?=$text?></textarea>
            </div>            
        </td>
    </tr> 
</table>
<input type="hidden" name="id" value="<?=$template['id']?>">
</form>

<hr>
<p style="text-align:center;">
<?php if($_GET['id']){ ?>
    <button class="button" onclick="ajax_template_script('edit');">Сохранить</button>
<?php }else{ ?>
    <button class="button" onclick="ajax_template_script('add');">Сохранить</button>
<?php } ?>
<button class="disabled" onclick="CloseModal();">Отмена</button>
</p>    