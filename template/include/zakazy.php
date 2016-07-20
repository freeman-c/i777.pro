<?php 
require_once ($_SERVER['DOCUMENT_ROOT'].'/system/controller.php');

require_once ($_SERVER['DOCUMENT_ROOT'].'/modules/np/get_np_city_list.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/modules/np/get_np_warehouse_list.php');

    $order = getOrder($_GET['id']);
    
    $status = getStatus($order['status']);
    $statusy = getStatusy();
    $task = getCallTaskForOrder($order['id']);
    if (!empty($task['date_time']))
        $task['date_time'] = date("d.m.Y H:i", strtotime($task['date_time']));
    if(isset($order['date_arrive']))
        $order['date_arrive'] = date("d.m.Y", strtotime($order['date_arrive']));
    else
        $order['date_arrive'] = "----------";

    if ($task['priority'] == 60)
        $taskHighPriorityChecked = 'checked="checked"';

    if ($order['delivery'] == 'Новая Почта'){
        $arr = explode(',', $order['delivery_adress']);
        $npCity = $arr[0];
        unset($arr[0]); 
        for ($i=1; $i < count($arr); $i++)
            $arr[$i] = trim($arr[$i]);
        $npWarehouse = implode(', ', $arr);
    }

    error_reporting(0);
    session_start();

    if (!$_SESSION['user']['new_order'])
        $_SESSION['user']['new_order'] = uniqid('mn_',true);
    if (!isset($order['order_id']))
        $order['order_id'] = $_SESSION['user']['new_order'];

?>


<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<style>
    #overlay *{
        -webkit-box-sizing: border-box;
           -moz-box-sizing: border-box;
                box-sizing: border-box;
    }
    #table-list-data td{
        padding: 1px 4px;
    }
    input[name='total']{
        background: transparent;
        border: none;
    }
    input[name='total']:focus{
        box-shadow: none;
        background: #DDD;
        border: none;
    }
    /*---*/
    #add-product-to-order{
        background: linear-gradient(to bottom, #F6FFDB 0px, #A3EA27 100%) transparent;
        border: 1px solid #9DCB2C;
        border-radius: 5px;
        color: #699A03;
        cursor: pointer;
        font-family: "tooltip";
        font-size: 12px;
        margin: 1px 0px 0px;
        padding: 0px 10px 2px;
        text-shadow: 0px 1px 1px #F6F6F6;
    }
    #order-product{
        position: relative;
        overflow: auto;
        /*min-height: 100px;
        max-height: 188px;*/
        height: 180px;
        width: 515px;
        background: #FFF;
        border: 1px solid #CCC;
    }
    #recomended-product-container{
        position: relative;
        overflow: auto;
        height: 170px;
        width: 320px;
        background: #FFF;
        border: 1px solid #CCC; 
    }

    #result-np{
        display: none;
        margin: 4px 0px -2px;
        background: #FFF;
        border: 1px dashed #D9111B;
        border-radius: 5px;
        padding: 1px;
        font-size: 11px;
    }
    #result-pr{
        display: none;
        margin: 4px 0px -2px;
        background: #FFF;
        border: 1px dashed #0055A5;
        border-radius: 5px;
        padding: 1px;
        font-size: 11px;
    }
    .sno-np{
        color: #D9111B;
        font-size: 15px;
    }
    .npComponentContainer{
        /*display: none;*/
        /*display: block;*/
    }
    .otherComponentConrainer{
        display: none;
    }
    select{
        width: 270px;
    }
    .order-table-container{
        float: left;
        width: 70%;
    }
    .script-view{
        padding-left: 20px; 
        display: none;
    }
    .menu-item-label{
        text-shadow: none;
        font-size: 13px;
        color: #5f5f5f;
    }
    .expander {
        margin: -1px 4px;
        display: none;
    }
    #scripts-table{
        border-collapse: collapse;
        width: 100%;
        height: 450px;
    }
    #scripts-table td{
        padding: 0px;
        margin: 0px;
    }
    #script-text{
        width: 100%;
    }
    #script-text-textarea{
        width: 565px;
        height: 310px;
        position: fixed;
        color: #000;
        background-color: #fff;
        padding: 5px;
        font-size: 14px;
        font-family: Arial;
        border-top: 1px solid #ABABAB;
        border-left: 1px solid #ABABAB;
    }

    .menu-item-label-container{
        text-decoration: underline;
        padding-right: 20px;
    }
    .show-script-text{
        font-size: 12px;
        cursor: pointer;
    }
    .script-container{
        width: 104%;
        height: 255px;
        overflow: scroll;
        position: relative;
        left: 2px;
        border-left: 1px solid #ABABAB;
        padding: 5px;
    }

    #modal-window {
        width: 1290px;
        margin-left: -645px;
        top: 0;
    }

    #modal-content {
        padding-bottom: 0px;
        padding-top: 0px;
    }

    .div-tooltip{
        width: 400px;
        height: 306px;
        display: none;
        position: fixed;
        z-index: 100000;
        
        font-family: 'tooltip';
        background: #FEC929;
        color: #000;
        padding: 5px 10px;
        max-width: 360px;
        box-shadow: 0px 0px 3px #999;
        text-shadow: 0px 1px 1px #FFF;
        overflow: auto;
    }
    .tooltip-text{
        margin: 0px;
        width: 100%;
        height: 90%;
        color: #000;
        resize: none;
    }
    .tooltip-img-container, .tooltip-link-container{
        width: 100%;
        text-align: center;
        margin-bottom: 10px;
    }
    .tooltip-img{
        width: 100%;        
    }
    .td-fixed-height{
        height: 30px;
    }
    .btnLinkContainer{
        position: fixed;
        top: 602px;
        font-family: Arial;
    }
    .btnLinkContainer .btnLink{
        height: 25px;
        padding: 5px;
        float: left;
        text-decoration: underline;
        cursor: pointer;        
    }
</style>

<script>
$(document).ready(function(){ 
    
    $('#modal-close').click(function(){
        setCallTaskState('<?=$order['id']?>',0);
    });

    $('#complete_status').click(function(){
        if($(this).is(':checked')){
            $('input[name="date_complete"]').removeAttr('disabled');
            $('input[name="date_complete"]').attr('readonly',true);
            $('input[name="date_complete"]').val('<?=date('Y-m-d');?>');
        }else{
            $('input[name="date_complete"]').attr('disabled','disabled');
            $('input[name="date_complete"]').val('0000-00-00');
        }
    });
    
    // $('input[name="phone"]').mask("(999) 999-99-99");

    var ttn = $('input[name="ttn"]').val();
    var delivery = $('select[name="delivery"]').val();
    if(ttn.length > 0){
        if(delivery == 'Новая Почта'){
            $.ajax({
                url: "/modules/np/info.php",
                method: 'POST',
                data : {ttn:ttn},
                beforeSend: function(){},
                success: function(data){
                    //alert(data);
                    $('#result-np').show();
                    $('#result-np').html(data);
                },
                error: function() { alert('Error API nova_poshta: tracking'); }                    
            });
        }
    }   

    $('select[name="delivery"]').chosen({disable_search: true});
    $('#city').chosen({no_result_text: 'Не удалось найти город', allow_single_deselect: true});
    $('#warehouse').chosen({no_result_text: 'Не удалось найти отделение', allow_single_deselect: true});


    function changeDeliveryType(){
        if ($('select[name="delivery"]').val() == 'Новая Почта'){
            $('.npComponentContainer').show();
            $('.otherComponentConrainer').hide();
        }
        else{
            $('.npComponentContainer').hide();
            if ($('select[name="delivery"]').val() != '')
                $('.otherComponentConrainer').show();
        }
    }

    <?php if ($_GET['status'] == '0') { 
        session_start();
        $user_info = get_user_description_login($_SESSION['user']['login']); 
        ?>
        $('#sm').find('option:contains("<?=$user_info['surname']?> <?=$user_info['name']?>")').attr('selected', 'selected');
        $('select[name="status"]').find('option:contains("Новый")').attr('selected', 'selected');
        // $('select[name="office"]').find('option:contains("Главный офис")').attr('selected', 'selected');
        $('select[name="delivery"]').find('option:contains("Новая")').attr('selected', 'selected');
        $('select[name="delivery"]').trigger('chosen:updated');
        $('select[name="payment"]').find('option:contains("Налож.")').attr('selected', 'selected');
    <?php } ?>

    changeDeliveryType();

    $('select[name="delivery"]').change(function(){
        changeDeliveryType(); 
    });

    //загружает список отделений
    function loadWarehouse(){
        $.ajax({
            url: "/modules/np/get_np_warehouse_list.php",
            method: 'POST',
            async: false,
            data : {
                format: 'select',
                cityName:$('#city').val()
            },
            success: function(data){
                // console.log(data);
                $('#warehouse').append(data);
                $("#warehouse").trigger('chosen:updated');
            }
        });
    }

    $('#city').change(function(){
        $('#warehouse').empty();
        $('#warehouse').append('<option value="">Выберите отделение</option>');
        loadWarehouse();  
    });

    $(function(){
        $(document).click(function(event) {
            if ($(event.target).closest(".div-tooltip").length || $(event.target).closest(".tooltip").length) return;
            $('.div-tooltip').fadeOut();
            event.stopPropagation();
        });
    });

    function addChildScript(parent_name, name, title, text){
        $('#'+parent_name).append('<ul id="menu-list">'
                            +'<li class="menu-item">'
                                +'<div class="category-wrapper">'
                                    +'<span class="expander"></span>'
                                    +'<div class="menu-item-label-container">'
                                       +'<span class="menu-item-label" onclick="ScriptToggle(event); showScriptText(event);">'
                                            +title
                                        +'</span>'
                                    +'</div>'
                                    +'<div class="script-view" id="'+name+'">'
                                        +'<input class="script-text" type="hidden" value="'+text+'">'
                                    +'</div>'
                                +'</div>'
                            +'</li>'
                    +'</ul>');
        $('#'+parent_name).parent().find('.expander:first').css('display', 'block');
    }

    function appendLi(type, name, title, text){
        $('.'+type).append('<li class="menu-item">'
                                +'<div class="category-wrapper">'
                                    +'<span class="expander"></span>'
                                    +'<div class="menu-item-label-container">'
                                       +'<span class="menu-item-label" onclick="ScriptToggle(event); showScriptText(event);">'
                                            +title
                                        +'</span>'
                                    +'</div>'
                                    +'<div class="script-view" id="'+name+'">'
                                        +'<input class="script-text" type="hidden" value="'+text+'">'
                                    +'</div>'
                                +'</div>'
                            +'</li>');
    }

    function createScriptList(){
    <?php 
        $scriptList = getTemplatesScript();
        $search = array("\r\n");
        foreach ($scriptList as $elem) {
            ?>
            var text = '<?=str_replace($search, "<br>", $elem['text'])?>';
            <?php
            if (!empty($elem['parent_name'])){
            ?> 
                addChildScript('<?=$elem['parent_name']?>', '<?=$elem['name']?>', '<?=$elem['title']?>', text);
            <?php
            }
            elseif ($elem['type'] == 0) {
                ?>
                    appendLi('main-scripts','<?=$elem['name']?>', '<?=$elem['title']?>', text);
                <?php
            }
            elseif ($elem['type'] == 1) {
                ?>
                    appendLi('sub-scripts', '<?=$elem['name']?>', '<?=$elem['title']?>', text);
                <?php
            }
        }
    ?>
    }

    $('#datetimepicker').datetimepicker({
        format:'d.m.Y H:i',
        minDate: 0,
        minTime: 0,
        onSelectDate: function(){
            var d = $('#datetimepicker').datetimepicker('getValue');
            if (d.getDate() != '<?=date('d')?>')
                $('#datetimepicker').datetimepicker({minTime: '00:00:00'});   
            else
                $('#datetimepicker').datetimepicker({minTime: 0});   
        }
    });
    $.datetimepicker.setLocale('ru');

    $('.no-answer').click(function(){
        <?php 
        date_default_timezone_set(TIME_ZONE);
        $call_time = date('H', time()+3600);
        ($call_time >= 9 && $call_time <= 20) ? $call_time = date('d.m.Y H:i', time()+3600) : $call_time = "08:00 ".date('d.m.Y', time()+24*3600);
        ?>
        $('#datetimepicker').val('<?=$call_time?>');
        $('.comment').text('<?=date('d.m.Y')?> НД <?=date('H:i')?>\n'+$('.comment').text());
        $('.task-high-priority').removeProp('checked');
    });

    $('.clear_datetimepicker').click(function(){
        $('#datetimepicker').val('');
        $('.task-high-priority').removeProp('checked');
    });

    $('.add_time_to_comment').click(function(){
        var comment = '   '+$('#datetimepicker').val()+'   \n'+$('.comment').val();
        $('.comment').val(comment);
    })

    function Product_Order(){
        <?php !empty($order['order_id']) ? $order_id = $order['order_id'] : $order_id = $_SESSION['user']['new_order']; ?>
        link = "/template/include/order_product.php?order_id=<?=$order_id?>";
        // alert(link);
        $('#order-product').load(link);   
    }

    function getRecomendedProductList(){
        <?php !empty($order['order_id']) ? $order_id = $order['order_id'] : $order_id = $_SESSION['user']['new_order']; ?>
        $('#recomended-product-container').load('/template/include/recomended_product_in_order.php?order_id=<?=$order_id?>');
    }

    Product_Order();
    getRecomendedProductList();

    createScriptList();

    getGeoIPInfo('<?=$order['ip']?>', '<?=$order['site']?>');   
});
</script>


<!-- блок всплывающей подсказки -->
<div class="div-tooltip">
    <textarea class="tooltip-text" readonly>
        
    </textarea> 
    <div class="tooltip-link-container">
        <a class="tooltip-link" target="_blank" href="" style="color: #000"></a>
    </div>  
    <div class="tooltip-img-container">
        <img class="tooltip-img" src="" alt="здесь должно быть изображение товара">
    </div> 
</div>

<form id="forma-zakazy"> 

    <table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tbody> 
<input type="hidden" name="id" value="<?=$order['id']?>">
<input type="hidden" name="order_id" value="<?=$order['order_id']?>">

            <tr style="font-weight:bold;">
                <td colspan="2" style="width: 30%;" class="td-fixed-height">
                    <?php if($_GET['id']){ ?>
                        Заказ №<?=$order['id']?> от <?=$order['date']?>
                    <?php } ?>   
                </td>
                <td style="width: 20%; text-align: right;">
                    <span>           
                        <?php 
                        if( $order['date_complete'] == '0000-00-00' or empty($order['date_complete']) ){
                            $complete='';
                            $date_complete = 'disabled';
                            $order['date_complete']='0000-00-00';
                        }else{   
                            $complete='checked'; 
                            $date_complete = 'readonly';
                        } 
                        ?>
                        <input type="checkbox" id="complete_status" <?=$complete?>> 
                        Отправлен 
                        <input type="text" name="date_complete" <?=$date_complete?> size="10" value="<?=$order['date_complete']?>" style="cursor:no-drop;">
                    </span>  
                </td>
                <td style="text-align: right; width: 15%;" class="td-fixed-height"> 
                    Прибыл 
                    <input type="text" readonly size="10" value="<?=$order['date_arrive']?>" style="cursor:no-drop;">

                </td>
                <td rowspan="9" style="width: 30%;" valign="top">
                    <div class="script-container">
                        <div style="width: 50%; float: left;">
                            <ul id="menu-list" class="main-scripts">
                            </ul>  
                        </div>
                        <div style="width: 50%; float: left; background-color: #D4D4D4;">
                            <ul id="menu-list" class="sub-scripts">
                            </ul>  
                        </div>
                    </div>
                </td>
            </tr>
           <!--  <tr>
                <td colspan="4">
                    <hr>
                </td>
            </tr> -->
            <tr>
                <td align="right" style="width: 100px;" class="td-fixed-height">
                    Покупатель
                </td>
                <td>
                    <input type="text" name="bayer_name" size="28" value="<?=$order['bayer_name']?>" style="width: 270px;">
                </td> 

                <td width="500px" style="overflow: hidden; max-height: 250px; max-width: 450px; min-width: 450px" rowspan="7" valign="top" colspan="2">       
                    <script>
                        function Product_Order(){
                                <?php 
                                    if(!$order['order_id']){ $order_id = $_SESSION['user']['new_order'];}
                                    else{$order_id = $order['order_id'];}
                                ?>
                            var id = '<?=$order_id?>';
                            $('#order-product').load('/template/include/order_product.php?order_id='+id+'');   
                        }
                        $(document).ready(function(){
                            Product_Order();
                            $('#modal-close').attr("onclick","CloseModal(); UnsetSession('new_order');");
                        });
                    </script>    
                    <div id="order-product"></div>
                    <div style="text-align:right;">
                        <span style="float:left;">
                            <button onclick="CheckNumericTR(); return false;" id="add-product-to-order">
                                <img src="/image/plus_circle.ico" style="margin: 0px 0px -4px 0px;">
                                добавить товар</button>
                        </span>
                    Всего:  
                        <input type="text" name="total" size="7" value="<?=$order['total']?>" readonly style="color:#900; font-weight:bold; cursor:no-drop;">
                    </div> 
                </td>
            </tr>
            <tr>
                <td align="right" class="td-fixed-height">
                Телефон
                <a href="tel: <?=$order['phone']?>">
	                <img src="/image/call.ico">
                </a>
                </td>
                <td>
                    <input type="text" name="phone" size="14" value="<?=$order['phone']?>" style="width: 270px;">
                </td> 
            </tr>
            <tr>
                <td colspan="2">
                    <table border="0" width="100%" style="border-collapse: collapse;">
                        <tr>
                            <td style="width: 95px; text-align: right;">
                                Пол
                            </td>
                            <td>
                                <select name="gender" style="width: 100px">
                                    <?php
                                    if($order['gender'] == 'male'){ ?>
                                        <option value="male">М</option>
                                    <?php }
                                    elseif ($order['gender'] == 'female'){ ?>
                                        <option value="female">Ж</option>
                                    <?php }
                                    else { ?>
                                        <option value="">Выберите</option> 
                                    <?php } ?>
                                    <option value="" disabled="">---</option>
                                    <option value="male">М</option>
                                    <option value="female">Ж</option>
                                </select>
                            </td>
                            <td style="width: 63px; text-align: right;">
                                Возраст
                            </td>
                            <td style="padding-right: 0;">
                                <select name="age" style="width: 100px">
                                    <?php
                                    if(!empty($order['age'])) { ?>
                                        <option value="<?=$order['age']?>"><?=$order['age']?></option>
                                    <?php } 
                                    else { ?>
                                        <option value="">Выберите</option>
                                    <?php } ?>
                                    <option value="" disabled="">---</option>
                                    <option value="До 18">До 18</option>
                                    <option value="18-24">18-24</option>
                                    <option value="25-30">25-30</option>
                                    <option value="31-37">31-37</option>
                                    <option value="38-45">38-45</option>
                                    <option value="46-55">46-55</option>
                                    <option value="55+">55+</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="right" class="td-fixed-height">Оформил</td>
                <td>
                    <?php 
                        $salers = getSaleManagers();
                        if ($order['user'])               
                            $user_info = get_user_description_login($order['user']); 
                        else
                            $user_info = get_user_description_login($_SESSION['user']['login']);
                    ?> 
                    <select name="user" id="sm">
                        <option value="<?=$user_info['login']?>"><?=$user_info['surname']?> <?=$user_info['name']?></option>
                        <option value="" disabled></option>
                        <?php 
                        foreach ($salers as $sm):
                            if (!empty($sm))?>
                            <option value="<?=$sm['login']?>"><?=$sm['surname']?> <?=$sm['name']?></option>
                        <?php endforeach; ?>                
                    </select>
                </td>        
            </tr>

            <tr>
                <td align="right" class="td-fixed-height">Статус заказа</td>
                <td>                   
                    <select name="status">
                        <option value="<?=$status['id']?>"><?=$status['name']?></option>
                        <option disabled>- - - - - - - - -</option>
                        <?php 
                        foreach ($statusy as $st):?>
                            <option value="<?=$st['id']?>"><?=$st['name']?></option>
                       <?php endforeach; ?>                
                    </select>
                    <?php 
                        if($status['id']==13){        
                    ?> 
                    <span id="cancel_description"><br>
                        Причина отказа: <br>
                        <textarea name="cancel_description" rows="2" cols="27"><?=$order['cancel_description']?></textarea>
                    </span>
                    <?php }else{ ?>
                    <span id="cancel_description" style="display: none;"><br>
                        Причина отказа: <br>
                        <textarea name="cancel_description" rows="2" cols="27"></textarea>
                    </span>    
                    <?php } ?>    
                </td>        
            </tr>
            
            <tr>
                <td align="right" class="td-fixed-height">Статус оплаты</td>
                <td>            
                    <?php $st_payment = getStatusPayment($order['payment']); ?>
                    <select name="payment">
                        <option value="<?=$st_payment['id']?>"><?=$st_payment['name']?></option>
                        <option disabled>- - - - - - - - -</option>
                        <?php 
                            $statusy_payment = getStatusyPayment(); 
                            foreach ($statusy_payment as $stat_payment):
                            ?>
                        <option value="<?=$stat_payment['id']?>"><?=$stat_payment['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </td>        
            </tr>
            <tr>
                <td class="td-fixed-height">
                    <b>Доставка</b>
                </td>

                <td colspan="2">
                    <div id="addres-by-ip" style="font-size: 11px">
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right" class="td-fixed-height">
                    Тип:
                </td>
                <td>
                    <select id="delivery_" name="delivery">
                        <option value="<?=$order['delivery']?>"><?=$order['delivery']?></option>
                        <option disabled="">-   -   -   -   -</option>
                        <?php 
                        $sposoby_dostavki = getDeliverys();
                        foreach ($sposoby_dostavki as $sposob_dostavki){
                        ?>
                        <option value="<?=$sposob_dostavki['name']?>"><?=$sposob_dostavki['name']?></option>
                        <?php } ?>
                    </select>
                </td>
                <td rowspan="6" valign="top"> 
                    <script>
                        function getRecomendedProductList(){
                            <?php 
                                if(!$order['order_id']){ $order_id = $_SESSION['user']['new_order'];}
                                else{$order_id = $order['order_id'];}
                            ?>
                            var id = '<?=$order_id?>';
                            $('#recomended-product-container').load('/template/include/recomended_product_in_order.php?order_id='+id);
                        }
                        $(document).ready(function(){  
                            getRecomendedProductList(); 
                        });
                    </script>           
                    <div id="recomended-product-container"></div>
                </td>
                <td rowspan="10" colspan="2" valign="top" style="padding: 0; margin: 0;">
                    <div id="script-text-textarea">
                        
                    </div>
                    <div class="btnLinkContainer">
                        <!-- <div class="btnLink">
                            1
                        </div>
                        <div class="btnLink">
                            2
                        </div>
                        <div class="btnLink">
                            3
                        </div> -->
                    </div>
                </td>
            </tr>
     
           <tr>
                <td align="right" class="td-fixed-height">
                    <div class="npComponentContainer">
                        Город
                    </div>
                    <div class="otherComponentConrainer">
                        Адрес
                    </div>
                </td>
                <td>
                    <div class="npComponentContainer">
                        <select id="city" name="npCity">
                            <?php 
                            if (!empty($npCity)){
                            ?>
                            <option value="<?=$npCity;?>"><?=$npCity;?></option>
                            <option disabled="">----------</option>
                            <?php } else { ?>
                            <option value="">Выберите город</option>
                            <?php } 
                            $npCityList = getNPCityList();
                            foreach ($npCityList as $npCityListElem) { ?>
                            <option value="<?=$npCityListElem['name']?>"><?=$npCityListElem['name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="otherComponentConrainer">
                        <input type="text" name="delivery_adress" size="48" value="<?=$order['delivery_adress']?>" style="width: 270px;">
                    </div>
                </td>
            </tr>
            <tr class="npComponentContainer">
                <td align="right" class="td-fixed-height">
                    <div class="npComponentContainer">
                        Отделение
                    </div>
                </td>
                <td>
                    <div class="npComponentContainer">
                        <select id="warehouse" name="npWarehouse">
                            <?php 
                            if (!empty($npWarehouse)){
                            ?>
                            <option value="<?=$npWarehouse;?>"><?=$npWarehouse;?></option>
                            <option disabled="">----------</option>
                            <?php } else { ?>
                            <option value="">Выберите отделение</option>
                            <?php } 
                            $npWarehouseList = getNPWarehouseList($npCity);
                            foreach ($npWarehouseList as $npWarehouseListElem) { 
                                echo '<option value="'.str_replace('"', '&quot;', $npWarehouseListElem['name']).'">'.$npWarehouseListElem['name'].'</option>';
                            } ?>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right" class="td-fixed-height">
                    <div>
                        ТТН
                    </div>  
                </td>
                <td>
                    <div>
                        <input type="text" name="ttn" size="15" value="<?=$order['ttn']?>" style="width: 270px">
                    </div>
                </td>
            </tr>
            <tr class="otherComponentConrainer">
                <td class="td-fixed-height">
                </td>
            </tr>
            <tr>
                <td align="left" colspan="1" class="td-fixed-height" style="text-align: right;"><b>Задание</b></td>
                <td align="left" colspan="1" class="td-fixed-height">
                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                        <tr>
                            <td align="left" style="padding: 0;" valign="middle">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td style="padding: 0;">
                                            <input id="datetimepicker" type="text" name="call-date-time" value="<?=$task['date_time']?>" style="text-align: right; width: 115px;">
                                        </td>
                                        <td style="padding: 0 0 0 5px;" valign="middle">   
                                            <img class="clear_datetimepicker" src="/image/cancel.png" style=" display: block; width: 18px; cursor: pointer;" title="Очистить время автодозвона">
                                        </td>
                                        <td style="padding: 0 0 0 3px;">
                                            <img class="add_time_to_comment" src="/image/arrow_down.png" style=" display: block; width: 18px; cursor: pointer;" title="Добавить время в коммент">
                                        </td>
                                        <td>
                                            <input type="checkbox" class="task-high-priority" <?=$taskHighPriorityChecked?>>Приоритетно
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="left" colspan="1" class="td-fixed-height"><b>Комментарий</b></td>
                <td align="left"  style="padding: 0;">
                    <input type="button" value="НД" class="no-answer" style="cursor: pointer;" title="Недозвон">
                </td>
            </tr>
            <!-- <tr align="left">
                <td>
                </td>
                <td align="right">
                    
                </td>
            </tr> -->
            <tr>
                <td colspan="2" valign="top">
                    <textarea class="comment" style="width: 370px; height: 60px" name="comment" spellcheck="false"><?=$order['comment']?></textarea>            
                </td>
            </tr>
            <!-- <tr>
                <td colspan="3">
                    <hr>
                </td>
            </tr> -->
            <tr>
                <td colspan="3" valign="top" style="height: 53px;">
                    <div id="result-np"></div> <div id="result-pr"></div>
                </td>
            </tr>

           <!--  <tr>
                <td colspan="3">
                    <hr>
                </td>
            </tr> -->
        </tbody>
    </table>
</form>
<table cellpadding="0" cellspacing="0" border="0" width="55%">
    <tbody>
        <tr>
                <td colspan="3">
                    <p style="text-align:center; margin-top: 0px; margin-bottom: 10px;">
                    <?php if($_GET['id']){ ?>
                        <button class="button order-save" onclick="ajax_zakazy('edit');">Сохранить</button>
                    <?php }else{ ?>
                        <button class="button order-save" onclick="ajax_zakazy('add');">Сохранить</button>
                    <?php } ?>
                        <button class="disabled order-cancel" onclick="CloseModal(); UnsetSession('new_order'); setCallTaskState('<?=$order['id']?>',0)">Отмена</button>  
                </td>
            </tr>
    </tbody>
</table>
