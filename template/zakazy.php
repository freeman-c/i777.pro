<div class="call_notification">
</div>

<div class="form-order-container">
<div class="form-order-container1">
<div style="height: 40px;">
    <span id="panel-button-operation" style="float: left;">
        <button class="button-success" id="button-operation-print-ttn" onclick="printTTN();">Напечатать ТТН</button>
        <button class="button-error button-operation-send-sms-tmp" style="display: none;" onclick="printTTN_tmpfnc();">СМС "Отправлен"</button>
        <button class="button-error button-operation-send-sms-tmp" style="display: none;" onclick="sendSMSArrive_tmpfnc();">СМС "Прибыл"</button>
    </span>
    <span id="panel-button-operation">
        <button class="button-success" id="button-operation-export" onclick="export_exel('<?=SITE_URL?>');">Експорт в Exel</button>
        <button class="button" onclick="add_new_zakaz('<?=$_SESSION['user']['login']?>');">+ Добавить</button>
        <button class="button-error" id="button-operation-delete" onclick="delete_zakaz();">Удалить <span id="count-elements-delete"></span>
        </button>
    </span>
</div>
<div style="padding: 4px 8px 8px; font-size: 11px;">
    Отображать по
    <select id="session_orders" class="select-filter">  
        <option value="100">100</option>  
        <option disabled>- - - - -</option>
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="50">50</option>
        <option value="100">100</option>
        <option value="200">200</option>
        <option value="300">300</option>
        <option value="500">500</option>
        <option value="1000">1000</option>
        <option value="1000000">Все</option>
    </select>
   <!--  &nbsp; &nbsp; &nbsp; &nbsp;
    Отдел: 
    <select id="session_office" class="select-filter">
        <option value="">- Все -</option>
        <option disabled>- - - - - - - - - -</option>
        <?php 
            $list = getOffices();
            foreach ($list as $elem){
        ?>
        <option value="<?=$elem['id']?>"><?=$elem['name']?></option>
        <?php } ?>
    </select> -->
    &nbsp; &nbsp; &nbsp; &nbsp;
    Оплата: 
    <select id="session_payment" class="select-filter"> 
        <option value="">- Все -</option>
        <option disabled>- - - - - - - - - -</option>
        <?php 
            $list = getStatusyPayment();
            foreach ($list as $elem){
        ?>
        <option value="<?=$elem['id']?>"><?=$elem['name']?></option>
        <?php } ?>
    </select>
    &nbsp; &nbsp; &nbsp; &nbsp;
    Доставка: 
    <select id="session_delivery" class="select-filter">
        <option value="">- Все -</option>
        <option disabled>- - - - - - - - - -</option>
        <?php 
        $list = getDeliverys();
        foreach ($list as $elem){ ?>
        <option value="<?=$elem['name']?>"><?=$elem['name']?></option>        
        <?php } ?>
    </select>
    &nbsp; &nbsp; &nbsp; &nbsp;
    Сотрудник: 
    <select id="session_manager" class="select-filter">
        <option value="">- Все -</option>
        <option disabled>- - - - - - - - - -</option>
        <?php 
        $list = getUsers();
        foreach ($list as $elem){ ?>         
        <option value="<?=$elem['login']?>"><?=$elem['surname'].' '.$elem['name']?></option>        
        <?php } ?>
    </select>
</div>
<style>
    #session_orders,#session_office,#session_payment,#session_delivery,#session_manager{
        font: 13px 'tooltip';
    }
    #form-zakazy{
        overflow: auto;
        width: 1032px;
        min-height: 380px;
        max-height: 380px;
        padding-bottom: 30px;
        border-top: 1px solid #6A9FD0;
        border-left: 1px solid #B3DCE6;
        border-right: 1px solid #B3DCE6;
        border-bottom: 1px solid #B3DCE6; 
        position: relative;
        z-index: 792;
        margin-top: -1px;
    }
    .table-box-orders{
        font-size: 12px;
        font-family: 'tooltip';
        text-shadow: 0px 1px #FFF;
    }
    #table-list{
        background: #FFF;
    }
    #table-list thead th{
        padding: 5px 8px;
        white-space: nowrap;
        border-bottom: 1px solid #EEE;
    }
    #table-list tbody tr:hover{
        background: #FF9 !important;
        color: #333 !important;
    }
    #table-list tbody td{
        padding: 1px 8px;
        white-space: nowrap;
        text-shadow: none;        
    }
    .fifnish-row{
        background: #C7E16C;
        color: green !important;
    }
    #preloader{
        text-align: center;
        padding: 30px;
    }
    /*------- data Table -----*/
    #table-list_wrapper{
    }
    #table-list_length{
        /*display: none;*/
        margin-right:100px;
    }
    .dataTables_empty{
        color: #F00;
    }
    #table-list_filter input{
        width: 360px;
    }
    #table-list_info{
        display: none;
    }
    
    #table-list_length,#table-list_filter,#table-list_paginate,#table-list_info{
        margin-top: 6px;
        margin-bottom: 10px;
        font-family: 'magistral';
        color: #3F80C0;
    }
    #table-list_paginate .ui-state-default{
        background: linear-gradient(to bottom, #F7FBFC 0px, #D9EDF2 40%, #ADD9E4 100%) transparent;
        border: 1px solid #6A9FD0;
        border-radius: 2px;
        color: #3F80C0;
        margin: 0px 4px;        
    } 
    #table-list_paginate .ui-state-disabled{
        background: linear-gradient(to bottom, #F6F8F9 0px, #E5EBEE 50%, #D7DEE3 51%, #F5F7F9 100%) transparent;
        border: 1px solid #ABABAB; 
        color: #757575 !important;
    }
    /*--------------------*/
    .selected_row{
        /*background: #045694 !important; 
        color: #FFF !important;*/
        background: #FED24E;
    }
    .selected-row-in-table{
        background: #C60 !important;
        color: #DDD !important;
    }
    /*---*/
    #box-input-select-all{
        background: #045694 !important;
    }
    .box-arrow-down { 
        border-top: 9px solid #045694 !important;
    }
    /*---*/
    .count-tovary{
        padding: 0px 4px 1px;
        border: 1px solid #C60;
        background: #FF9;
        cursor: default;
        position: relative;
        /*z-index: 940;*/
    }
    .popup-box-count-tovary{
        display: none;
        position: absolute;
        width: 320px;
        /*min-height: 60px;*/
        max-height: 120px;
        background: #FFF;
        border: 1px solid #C60;
        box-shadow: 0px 1px 5px #999;
        border-radius: 3px;
        top: -20px;
        left: 23px;
        padding: 2px 4px;
        z-index: 940;
    }
    .popup-box-count-tovary:before, .popup-box-count-tovary:after{
        content: ''; 
        position: absolute;
        left: -21px; 
        top: 16px;
        border: 10px solid transparent;
        border-right: 10px solid #C60;
    }
    .popup-box-count-tovary:after {
        border-right: 10px solid #FFF;
        left: -19px; 
    }
    .popup-box-count-tovary table{        
        text-shadow: 0px 1px 1px #FFF;
    }
    .count-tovary:hover > .popup-box-count-tovary{
        display: block;
    }
    /*------*/
    #between{
        padding: 5px 10px;
        /*position: absolute;
        left: 360px;
        top: 4px;
        z-index: 9;*/
    }
    #button-between{
        padding: 2px 10px;
        font-size: 13px;
        border-radius: 5px;
    }
    #search-ajax-button, .page-selector{
        padding: 2px 10px;
        font-size: 13px;
        border-radius: 5px;
    }
    /*--------- date picker arrow ---------*/
    #ui-datepicker-div:before, #ui-datepicker-div:after{
        content: ''; 
        position: absolute;
        left: 17px; 
        top: -20px;
        border: 10px solid transparent;
        border-bottom: 10px solid #045694;
    }
    #ui-datepicker-div:after {
        border-bottom: 12px solid #FFF;
        top: -18px; 
    }
    #select-filter-product{
        width: 260px;
    }
    .slash-separator{
        color: #ABABAB;
    }
    .navigation{
        display: none;
    }
    .form-order-container{
        width: calc(100vw - 220px);
        width: -webkit-calc(100vw - 220px);
        width: -moz-calc(100vw - 220px);
        height: calc(100vh - 50px);
        height: -webkit-calc(100vh - 50px);
        height: -moz-calc(100vh - 50px);        
        position: absolute;
        top: 50px;
        left: 220px;
        padding: 10px;
        -webkit-box-sizing: border-box;
           -moz-box-sizing: border-box;
                box-sizing: border-box;
    }
    .form-order-container1{
        /*position: absolute;*/
    }
</style>
<script type="text/javascript" src="<?=SITE_URL?>/modules/np/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/js/main.js"></script>
<link rel="stylesheet" type="text/css" href="<?=SITE_URL?>/modules/np/chosen/chosen.css"> 
<?php $statusy = getStatusy();?>  
<div style="position: relative; width: 1000px; padding: 0px 16px;">
<button class="btn tabs-arrow" id="button-arrow-left-tabs">◄</button>
<div id="tabs-panel-statusy">
    <ul id="ul-statusy">
        <!--<li><a href="<?=SITE_URL?>/?action=zakazy&nov=1" class="button-edit">Новые!</a></li>-->      
        <?php foreach ($statusy as $tab): 
        ?>
        <li> <a id="<?=$tab['id']?>-tab" class="button but-tab" style="background: <?=$tab['color']?>; color:#333; text-shadow:none;"><?=$tab['name']?></a></li>
        <?php endforeach; ?>  
        <li><a id="complete-tab" class="button-success but-tab">Сдано!</a></li>
        <li><a id="all-tab" class="button but-tab">Все</a></li>          
    </ul>
</div>
<button class="btn tabs-arrow" id="button-arrow-right-tabs">►</button>
</div>
<!-- ****************************************************************** -->
<form id="form-zakazy"> 
    
    <div style="background: #F6F6F6; font-family: 'magistral'; padding: 6px 8px;">
        <span style="position: relative;">    
            Поиск в базе: <input type="text" size="28" id="input-ajax-search-in-table">
            <button class="button" id="search-ajax-button" onclick="return false;">Найти</button>
        </span>
        &nbsp;
        <span id="between">
            с <input type="text" id="between-start" value="<?=$_GET['d_start']?>" size="10"> 
            по <input type="text" id="between-end" value="<?=$_GET['d_end']?>" size="10">
            <input type="hidden" id="get-status" value="<?=$_GET['status']?>" size="2">
        </span>
        &nbsp;
        По товару: 
        <select id="select-filter-product" class="select-filter">
            <option value="">Все</option>
            <?php 
                $spisok_tovarov = getProducts();
                foreach ($spisok_tovarov as $sp_tov):
            ?>        
            <option value="<?=$sp_tov['id']?>"><?=$sp_tov['name']?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <table id="table-list" class="table-box-orders" border="0" cellspacing="0">
    <thead>
        <tr>
            <th style="width: 40px"> 
                <div id="box-input-select-all">
                    <input type="checkbox" id="select-all-checkbox">
                    <div class="box-arrow-down"></div>
                </div>                 
            </th>
            <th width="24px" style="padding: 0">
                <img src="/image/task.jpg" style="width: 24px">
            </th>
            <th width="24px"></th>
            <th>Покупатель</th>
            <th>Телефон</th> 
            <th></th>   
            <th>Коментарий</th>         
            <th>Товар</th>
            <th>Сумма заказа</th>
            <th>Статус оплаты &nbsp;&nbsp;</th>            
            <th>Добавлен</th>
            <th>Изменён</th>
            <th>Способ доставки</th>
            <th>Адрес доставки</th>
            <th>№ ТТН</th>  
            <th>№ ТТН Обратной</th>           
            <th>Источник</th>            
            <th>Статус заказа</th>
            <!-- <th>Электронная почта</th>  -->
            <th>IP</th>           
            <th>Оформил</th>
            <!-- <th>Офис</th>             -->
            <th>&nbsp; Сдано заказ &nbsp;</th>
            <th>Источник / Канал</th>
            <th>Кампания / Объявление / Ключи</th>          
        </tr>
    </thead>
    <tbody>
    </tbody>
</table> 
<!-- begin navigation -->
<div class="navigation">
    <button class="button page-selector" id="prePage"  onclick="return false;">Предыдущая страница</button>
    <button class="button page-selector" id="nextPage" onclick="return false;">Следующая страница</button>
</div>
<!-- end navigation -->
<script>
$(document).ready(function(){
    login = '<?=$_SESSION['user']['login']?>';
    autoTaskRequest();

    openOrderListByTask();

    $('.but-tab').click(function(){
        page = 0;
        $('.tab-status-active').removeClass('tab-status-active');
        $(this).addClass('tab-status-active');
        setOrderFilter();        
    });

    $('#nextPage').click(function(){
        page++;
        setOrderFilter();
    });

    $('#prePage').click(function(){
        page--;
        setOrderFilter();
    });
    
    $('.select-filter').chosen();        
    $('.select-filter').change(function(){
        setOrderFilter();
    });

    $('#search-ajax-button').click(function(){ 
            setOrderFilter();                
    });

    $('#table-list tbody td').click(function(event){
        $('#table-list tbody tr').removeClass('selected-row-in-table');
        t=event.target||event.srcElement;
        $(t).parent('tr').addClass('selected-row-in-table');
    });

    $('#between-start').datepicker({
        onSelect: function () {
                    setOrderFilter();
        }
    });

    $('#between-end').datepicker({
        onSelect: function () {
                    setOrderFilter();
        }
    });
    
    var active = $('.tab-status-active');
    var position = $('#ul-statusy li a').index(active) + 1;

    if(position > 12){
        $('#ul-statusy').animate({scrollLeft: "+1000"});
    }        
    $('#button-arrow-left-tabs').click(function(){
        $('#ul-statusy').animate({scrollLeft: "-=500"});
    });
    $('#button-arrow-right-tabs').click(function(){
        $('#ul-statusy').animate({scrollLeft: "+=500"});
    });
});    
</script> 
</form>

</div>
</div>

