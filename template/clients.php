<h2>Клиенты
    <span id="panel-button-operation">
        <!--<span id="table-message" style="font-size: 15px; margin-right: 20px;"></span>-->
        <button class="button" onclick="add_new_client();">+ Добавить</button>
        <button class="button-error" id="button-operation-delete" onclick="delete_client();">Удалить <span id="count-elements-delete"></span></button>
    </span>
</h2>
<style>
    #form-clients{
        overflow: auto;
        width: 1060px;
        height: 380px;
        padding-bottom: 30px;
        border-top: 1px solid #6A9FD0;
        border-left: 1px solid #B3DCE6;
        border-right: 1px solid #B3DCE6;
        border-bottom: 1px solid #B3DCE6;      
    }
    #table-list{
        font-family: "tooltip";
        font-size: 12px;
    }
    #table-list thead td{
        text-align: center;
        padding: 1px 8px;
        cursor: default;
    }
    /*-------------------------*/
    .dataTables_filter{ 
        /*float: none;*/
        text-align: left;
        padding: 0px 0px 0px 10px;
        margin: 0px;
    }
    .dataTables_filter input[type="text"]{
        width: 240px;
    }
    /*------------------------*/
    #between{
        background: #D9EDF2;
        padding: 4px 10px;
        position: absolute;
        left: 340px;
        top: 24px;
        z-index: 9;
    }
    #search-ajax-button{
        padding: 2px 10px;
        font-size: 13px;
        border-radius: 5px;
    }
</style>
<script>
$(document).ready(function(){
    $(document).keypress(function(e) { 
        if(e.keyCode=='27'){ // pressed ESC
            CloseModal();
        }
    });
    
    /*$selected=document.location.href;
        $.each($("#ul-statusy li a"),function(){
            if(this.href==$selected){
                $(this).addClass('tab-status-active');
                $(this).parent().parent().show();
        };
    });*/
        
    $('#search-ajax-button').click(function(){
            var target = $('#input-ajax-search-in-table').val();
            //var target = $(this).val();
            var proces = false;
            if(target.length > 2 && !proces){
                //setTimeout(function(){
                        $.ajax({
                            url: '<?=SITE_URL?>/modules/ajax_search_clients.php',
                            method: 'GET',
                            data: {
                                search:target,
                                type:'<?=$_GET['type']?>',
                                complete:'<?=$_GET['complete']?>',
                                between:'<?=$_GET['between']?>',
                                d_start:'<?=$_GET['d_start']?>',
                                d_end:'<?=$_GET['d_end']?>'
                            },
                            beforeSend: function() {
                                proces = true;
                                //WaitingBarShow('Поиск в базе...');                            
                                $('#input-ajax-search-in-table').attr('disabled','disabled');
                                $('#search-ajax-button').attr('disabled','disabled');
                                $('#search-ajax-button').html('&nbsp; &nbsp; <img src="/image/ajax-load.gif"> &nbsp; &nbsp;');
                            },
                            success: function(data){
                                //$("#table-list tbody tr").hide();
                                //$('#navigation-pagination').hide();
                                $("#table-list tbody tr").remove();
                                $('#navigation-pagination').hide();
                                $("#table-list tbody").append(data);
                            },
                            complete: function(){
                                //WaitingBarHide();
                                proces = false;
                                $('#input-ajax-search-in-table').removeAttr('disabled');
                                $('#search-ajax-button').removeAttr('disabled');
                                $('#search-ajax-button').html('Найти');
                                
                                $('#table-list tbody tr').hover(function(){
                                    $(this).find(".option-button").show();
                                }, function() {
                                    $(this).find(".option-button").hide();
                                });  
                                $('.tooltip').tooltip({
                                    track: false, //true включает "привязку" подсказки к движущемуся указателю мыши
                                    content: function() {
                                        return $(this).attr('title');
                                    }        
                                });
                            }
                        }); 
                //},1500);
                                   
            }else{
                //$("#table-list tbody tr").show();
                //$('#navigation-pagination').show();
                alert('Введите минимум 3 символа для поиска!');
            }
        });    
    
    $('#table-list').dataTable( {
        "aLengthMenu": [ 10,25,50,100,200,500,1000 ],
        //"iDisplayLength": 50,
        "iDisplayLength": 25,
        "bJQueryUI" : true,
        "sPaginationType": "full_numbers",
        "aoColumnDefs": [{'bSortable': false, 'aTargets':[0] }],
        //"aaSorting": [[ 16, "desc" ]],
        //"columnDefs": [{ type: 'formatted-num', targets: 12 }],
        "bPaginate": false,
        "bFilter": false //Поиск в таблице
    });
    //************************* date picker *******************
    $('#between-start').datepicker({
        onSelect: function () {
                    $start = $('#between-start').val();
                    $end = $('#between-end').val();        
                    var url = '<?=SITE_URL.$_SERVER['REQUEST_URI']?>';
                    var arr = url.split('&');
                    if(arr.length == 1){
                        $a_href = '<?=SITE_URL?>/?action=clients&between=1&d_start='+$start+'&d_end='+$end+'';
                    }
                    if(arr.length >= 2){
                        $status = $('#get-status').val();
                        $a_href = '<?=SITE_URL?>/?action=clients&type='+$status+'&between=1&d_start='+$start+'&d_end='+$end+'';
                    }
                    $('#button-between').attr('href',$a_href);
        }
    });
    $('#between-end').datepicker({
        onSelect: function () {
                    $start = $('#between-start').val();
                    $end = $('#between-end').val();        
                    var url = '<?=SITE_URL.$_SERVER['REQUEST_URI']?>';
                    var arr = url.split('&');
                    if(arr.length == 1){
                        $a_href = '<?=SITE_URL?>/?action=clients&between=1&d_start='+$start+'&d_end='+$end+'';
                    }
                    if(arr.length >= 2){
                        $status = $('#get-status').val();
                        $a_href = '<?=SITE_URL?>/?action=clients&type='+$status+'&between=1&d_start='+$start+'&d_end='+$end+'';
                    }
                    $('#button-between').attr('href',$a_href);
        }
    });
    
    $('#between-start').keyup(function(){
        $start = $(this).val();
        $end = $('#between-end').val();        
        var url = '<?=SITE_URL.$_SERVER['REQUEST_URI']?>';
        var arr = url.split('&');
        if(arr.length == 1){
            $a_href = '<?=SITE_URL?>/?action=clients&between=1&d_start='+$start+'&d_end='+$end+'';
        }
        if(arr.length >= 2){
            $status = $('#get-status').val();
            $a_href = '<?=SITE_URL?>/?action=clients&type='+$status+'&between=1&d_start='+$start+'&d_end='+$end+'';
        }
        $('#button-between').attr('href',$a_href);
    });
    $('#between-end').keyup(function(){
        $end = $(this).val();
        $start = $('#between-start').val();
        var url = '<?=SITE_URL.$_SERVER['REQUEST_URI']?>';
        var arr = url.split('&');
        if(arr.length == 1){
            $a_href = '<?=SITE_URL?>/?action=clients&between=1&d_start='+$start+'&d_end='+$end+'';
        }
        if(arr.length >= 2){
            $status = $('#get-status').val();
            $a_href = '<?=SITE_URL?>/?action=clients&type='+$status+'&between=1&d_start='+$start+'&d_end='+$end+'';
        }
        $('#button-between').attr('href',$a_href);
    });
    
});
</script>
<p style="height: 1px; margin-top: -4px"></p>
<?php $groupy = GetClientsGroups(); ?>
<ul id="ul-statusy">     
        <?php foreach ($groupy as $tab): 
            if($_GET['type']==$tab['id']){
                $cla = 'button tab-status-active';                
            }else{
                $cla = 'button';
            }
                ?>
        <li> <a href="<?=SITE_URL?>/?action=clients&type=<?=$tab['id']?>" class="<?=$cla;?>" style="background: <?=$tab['color']?>; color:#333; text-shadow:none;"><?=$tab['name']?></a></li>
        <?php endforeach; ?> 
        
            <!--<div id="between">
                с <input type="text" id="between-start" value="<?=$_GET['d_start']?>" size="10"> 
                по <input type="text" id="between-end" value="<?=$_GET['d_end']?>" size="10">
                <a id="button-between" href="<?=SITE_URL.$_SERVER['REQUEST_URI']?>" class="button">ok</a>
                <input type="hidden" id="get-status" value="<?=$_GET['type']?>" size="2">
            </div>-->
    </ul>

<form id="form-clients">
    
    <div style="background: #F6F6F6; font-family: 'magistral'; padding: 6px 8px; margin: 6px 6px -10px 6px; position: relative;">
        <span style="position: relative;">    
            Поиск в базе: <input type="text" size="28" id="input-ajax-search-in-table">
            <button class="button" id="search-ajax-button" onclick="return false;">Найти</button>
        </span>
        &nbsp;
        <!--<span id="between">
            с <input type="text" id="between-start" value="<?=$_GET['d_start']?>" size="10"> 
            по <input type="text" id="between-end" value="<?=$_GET['d_end']?>" size="10">
            <a id="button-between" href="<?=SITE_URL.$_SERVER['REQUEST_URI']?>" class="button">ok</a>
            <input type="hidden" id="get-status" value="<?=$_GET['status']?>" size="2">
        </span>-->

    </div>
    
<table id="table-list" border="0" cellspacing="0">
    <thead>
    <tr>
        <td width="20px"> 
            <div id="box-input-select-all">
                <input type="checkbox" id="select-all-checkbox">
                <div class="box-arrow-down"></div>
            </div> 
        </td>
        <td>Клиент</td>
        <td>Мобильный телефон</td>
        <td>Группа</td>
        <td>Электронная почта</td>
        <td>Комент-й</td>
        <td>IP-адрес</td>
        <td>Сайт</td>
        <td><b style="color:#FFF;">optn</b></td>
    </tr>
    </thead>
    <tbody>
<?php 
$clients = getClients();
foreach ($clients as $client):     
    
    /*$phone0 = trim($client['phone']);
    $phone1 = str_replace(" ","",$phone0); // space
    $phone2 = str_replace("-","",$phone1); // -
    $phone3 = str_replace("(","",$phone2); // (
    $phone4 = str_replace(")","",$phone3); // )
    $phone5 = str_replace(".","",$phone4); // .
    $phone6 = str_replace("+","",$phone5); // +
    $phone7 = str_replace("*","0",$phone6); // *   
    $phone = $phone7;*/
    $phone = preg_replace('/[^0-9]/', '', $client['phone']); //убираем всё, кроме цифр
    $first_symbol = substr($phone, 0, 1); //проверяем первый символ начала строки
    if($first_symbol=='0'){$phone = '38'.$phone;}
    if($first_symbol=='8'){$phone = '3'.$phone;}
    if($first_symbol=='3'){$phone = ''.$phone;}
    if($first_symbol=='7'){$phone = '+'.$phone;}
    ?>
    <tr style="color:<?=$bad;?>">
        <td> <input type="checkbox" class="selected" name="need_delete[<?=$client['id']?>]" id="checkbox<?=$client['id']?>" id="<?=$client['id']?>"> </td>
        <td style="white-space: nowrap;">            
            <div style="width:200px; overflow:hidden; text-overflow:ellipsis;">
			<span class="client-icon"></span>
			<?=$client['name']?>
			</div>
        </td>
        <td>
            <?php if(strlen($phone) < 10 or strlen($phone) > 12){ ?>
            <img src="<?=SITE_URL?>/image/error.ico" style="margin: 0px 2px -4px 0px;"><span style="color:red;"><?=$client['phone'];?></span>
            <?php }else{
                ?>
                <img src="<?=SITE_URL?>/image/mobile_phone_arrow.ico" onclick="SendSMS('<?=$phone;?>');" class="send-sms-icon"><?=$client['phone'];?>
            <?php }?>
        </td>
        <td>
			<div style="min-width: 70px;">
        <?php $group = GetClientsGroup($client['type']); ?>
            <?php if($client['type']==2){ ?>
                <img src="<?=SITE_URL?>/image/arrow_large_right.ico" style="margin: 0px 0px -3px 0px;">
                <b style="color:green;"><?=$group['name'];?></b>
            <?php }elseif($client['type']==3){ ?>
                <b style="color:#C60;"><?=$group['name'];?></b>  
			<?php }elseif($client['type']==4){ ?>
                <b><?=$group['name'];?></b>			
            <?php }else{ ?>
                <?=$group['name'];?>
            <?php } ?>
			</div>
        </td>
        <td>
            <?php if($client['email']){ ?>            
            <span style="color:#757575; font-size: 11px;">
                <?php //if(preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $client['email'])){ ?>
                <?php if(preg_match("/[-a-zA-Z0-9_\.]{3,20}@[-a-zA-Z0-9]{2,64}\.[a-zA-Z\.]{2,9}/", $client['email'])){ ?>
                <img src="<?=SITE_URL?>/image/email_go.ico" onclick="SendMail('<?=$client['name']?>','<?=$client['email']?>');" class="send-email-icon">
                <?=$client['email']?>
                <?php }else{?>
                <img src="<?=SITE_URL?>/image/error.ico" style="margin: 0px 0px -4px 0px;"> <span style="color:red;"><?=$client['email']?></span>
                <?php }?>
            </span>
            <?php }else{ ?>
            <div style="color:#ABABAB; text-align:center;">- нет данных -</div>
            <?php } ?>
        </td>
        <td style="max-width: 100px;">
            <?php if($client['description']){ ?>
            <span style="color:#757575; font-size: 11px;"><?=$client['description']?></span>
            <?php }else{ ?>
            <span style="color:#ABABAB;">-</span>
            <?php } ?>
        </td>
        <td style="color:#ABABAB; font-size: 11px;"><?=$client['ip']?></td>
        <td style="color:#ABABAB; font-size: 11px;">
                    <?php if(strlen($client['site'])>0){
                        echo '<span style="color:#757575;">'.$client['site'].'</span>';
                    }else{echo '- добавлен вручную -';}?>
        </td>
        <td>
            <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_client('<?=$client['id']?>');">
            <!--<img src="<?=SITE_URL?>/image/del.png" class="option-button">-->
        </td>
    </tr>
    
<?php endforeach; ?>
    </tbody>
</table>   
    <span id="navigation-pagination">
        <?php navigationClients(); ?>
    </span>
    <br><br>
</form>

<!--<br><br>Всего покупателей: <b><?=count($clients);?></b>-->