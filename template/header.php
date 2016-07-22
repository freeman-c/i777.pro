<?php 
$info_user = Authorization($_SESSION['user']['login']);
$info_personal = get_user_description_login($_SESSION['user']['login']);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Панель управления</title>
<meta name="keywords" content="">
<link href="favicon.png" rel="shortcut icon" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="/style/dafault.css">

<script type="text/javascript" src="/js/jbone.js"></script>

<script type="text/javascript" src="/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui.js"></script>

<script type="text/javascript" src="/js/main.js"></script>
<script type="text/javascript" src="/js/calls.js"></script>
<script type="text/javascript" src="/js/jquery.tshift.js"></script>

<link rel="stylesheet" type="text/css" href="/js/farbtastic/farbtastic.css">
<script type="text/javascript" src="/js/farbtastic/farbtastic.js"></script>

<script src="/js/mask.js"></script>

<script type="text/javascript" src="/js/highstock/highstock.js"></script>
<!-- <script type="text/javascript" src="/js/highstock/modules/export.js"></script> -->
<!-- <script type="text/javascript" src="/js/highcharts/highcharts.js"></script> -->
<script type="text/javascript" src="/js/highcharts/modules/data.js"></script>

<link type="text/css" rel="stylesheet" href="/js/dataTables/jquery.dataTables_themeroller.css">
<script type="text/javascript" src="/js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/js/dataTables/formatted-numbers.js"></script>

<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>

<link href="/js/upload/uploadfile.css" rel="stylesheet">
<script src="/js/upload/jquery.uploadfile.js"></script>

<script type="text/javascript" src="/js/datepicker/jquery.ui.datepicker-ru.js"></script>
<link rel="stylesheet" type="text/css" href="/js/datepicker/datepicker.css">

<link rel="stylesheet" type="text/css" href="/js/contextMenu/jquery.contextMenu.css">
<script type="text/javascript" src="/js/contextMenu/jquery.contextMenu.js"></script>

<script type="text/javascript" src="/js/jquery.synctranslit.js"></script>

<link rel="stylesheet" type="text/css" href="js/datetimepicker/jquery.datetimepicker.css"/>
<script type="text/javascript" src="/js/datetimepicker/build/jquery.datetimepicker.full.js"></script>
<script type="text/javascript" src="/js/chosen/chosen.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="/style/chosen/chosen.css">
<script>
$(document).ready(function(){
    $('#link-user-info').click(function(){
        $('#popup-box-user-info').toggle();
    });
    $('#close-popup-box-user-info').click(function(){
        $('#popup-box-user-info').fadeOut();
    });     
});
</script>
</head>

<body>
    <div id="header">
        <div class="call-settings-container">
            <img src="/image/settings.png" style="cursor: pointer; width: 30px" title="Настройки звонков">  
        </div>
        <div class="call-settings">
            <div>
                <img class="call-setting-close" src="/image/close_active.png" style="cursor: pointer;" title="Закрыть">
            </div>
            <div>
                <label><input type="checkbox" class="awayFromKeyboard" style="margin: 0" />Нет на месте</label>
            </div>
            <div>
                <label><input type="checkbox" class="dontGetTask" style="margin: 0"/>Не получать задания</label>
            </div>
        </div> 

        <div>
            <span style="float:right; margin-top: 3px; margin-right: 10px;">            
            <!-- <a href="http://www.geoiptool.com/ru/?IP=<?=$info_user['ip'];?>" target="_blank"><b>IP:</b><i class="icon icon-ip"></i> <?=$info_user['ip'];?></a> -->
            <a href="#" id="link-user-info">
                <i class="icon icon-user"></i><b><?=$info_personal['surname']?> <?=$info_personal['name']?> (<?=$info_user['login'];?>)</b>
                <div id="popup-box-user-info">
                    <img id="close-popup-box-user-info" src="/image/close_active.png">
                    <img class="user-logo" src="/image/empty_people_logo_40_40.png">
                    <?=$info_personal['surname']?> 
                    <?=$info_personal['name']?> 
                    <?=$info_personal['lastname']?>
                    <div id="user-info-login"><?=$info_personal['login']?></div>
                    <hr>
                    <table cellspacing="0">
                        <tr>
                            <td align="right" style="color:#757575;" width="50px">Доступ:</td>
                            <td><?=getAccessType($info_user['access']);?></td>
                        </tr>
                        <tr>
                            <td align="right" style="color:#757575;">Офис:</td>
                            <td><?php $working_place = getOffice($info_user['place_work'])?><?=$working_place['name']?></td>
                        </tr>
                        <tr>
                            <td align="right" style="color:#757575;">IP-адрес:</td>
                            <td><?=$_SERVER['REMOTE_ADDR']?></td>
                        </tr>
                    </table>                    
                </div>
            </a>            
            <!--<a href="#"><i class="icon icon-calendar"></i></a>
            <a href="#"><i class="icon icon-bels"></i><b class="tips">1</b></a>
            <a href="#"><i class="icon icon-setting"></i></a>-->
            <!-- <a href="#"><i class="icon icon-info" onclick="getInfo();"></i>&nbsp;</a> -->
            <a href="/?action=logout"><i class="icon icon-exit"></i>Выход</a>          
        </span>  
        </div>
    </div>
    
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="min-width: 1280px;">
        <tr>
            <td width="220px" valign="top">
<div id="menu">                
<ul id="menu-list">
    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander"></span>
        <div>
            <span class="menu-item-icon group"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Контакты</span>
        </div>
        </div>
        <ul class="menu-sub-list">
            <li><a href="/?action=groups_users" class="menu-sub-item">Группы пользователей</a></li>
            <li><a href="/?action=users" class="menu-sub-item">Сотрудники</a></li>
            <li><a href="/?action=offices" class="menu-sub-item">Отделы</a></li>
            <li><a href="/?action=groups_clients" class="menu-sub-item">Группы клиентов</a></li>
            <li><a href="/?action=clients&type=1" class="menu-sub-item">Клиенты</a></li>            
        </ul>
        
    </li>
    
    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander"></span>
        <div>
            <span class="menu-item-icon zakazy"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Заказы</span>
        </div>
        </div>
        <ul class="menu-sub-list">
            <li><a href="/?action=statusy" class="menu-sub-item">Статусы заказов</a></li>
            <li><a href="/?action=zakazy" class="menu-sub-item">Перечень заказов</a></li>  
            <li><a href="/?action=delivery" class="menu-sub-item">Способы доставки</a></li>
        </ul>
    </li>

    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander"></span>
        <div>
            <span class="menu-item-icon zakazy"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Задания</span>
        </div>
        </div>
        <ul class="menu-sub-list">
            <li><a href="/?action=single_task" class="menu-sub-item">Одиночные</a></li>
            <li><a href="/?action=mass_task" class="menu-sub-item">Массовые</a></li>  
        </ul>
    </li>

    
    <!--<li class="menu-item">
        <div class="category-wrapper">
        <span class="expander-empty"></span>
        <a href="/?action=tasks">
            <span class="menu-item-icon tasks"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Задачи</span>
        </a>
        </div>
    </li>
    
    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander-empty"></span>
        <a href="/?action=cases">
            <span class="menu-item-icon cases"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Мероприятия</span>
        </a>
        </div>
    </li>-->
    
    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander"></span>
        <div>
            <span class="menu-item-icon templates"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Шаблоны</span>
        </div>
        <ul class="menu-sub-list">
            <li><a href="/?action=senders_mail" class="menu-sub-item">Отправители Email</a></li>
            <li><a href="/?action=senders_sms" class="menu-sub-item">Отправители CMC</a></li>
            <li><a href="/?action=template_email" class="menu-sub-item">Email-шаблоны</a></li>            
            <li><a href="/?action=template_sms" class="menu-sub-item">СМС-шаблоны</a></li>            
            <li><a href="/?action=template_script" class="menu-sub-item">Скрипты</a></li>            
        </ul>
        </div>
    </li> 
        
    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander"></span>
        <div>
            <span class="menu-item-icon catalog"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Каталог</span>
        </div>
        </div>
        <ul class="menu-sub-list">
            <li><a href="/?action=products" class="menu-sub-item">Товары</a></li>
            <li><a href="/?action=recomendedProducts" class="menu-sub-item">Рекомендуемые товары</a></li>
        </ul>
    </li>
    
    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander"></span>
        <div>
            <span class="menu-item-icon catalog"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Склад</span>
        </div>
        </div>
        <ul class="menu-sub-list">
            <li><a href="/?action=stockInTrade" class="menu-sub-item">Наличие товара</a></li>
            <li><a href="/?action=deliveryOrders" class="menu-sub-item">Заказы</a></li>
        </ul>
    </li>

    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander-empty"></span>
        <a href="/?action=curier">
            <span class="menu-item-icon curier"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Отправка</span>
        </a>
        </div>
    </li>
    
    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander"></span>
        <div>
            <span class="menu-item-icon-np"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Новая Почта</span>
        </div>
        </div>
        <ul class="menu-sub-list">
            <li><a href="/?action=np" class="menu-sub-item">API</a></li>        
            <li><a href="/?action=state" class="menu-sub-item">Состояние</a></li>            
        </ul>
    </li>
<!--     
    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander"></span>
        <div>
            <span class="menu-item-icon-pr"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Почта России</span>
        </div>
        </div>
        <ul class="menu-sub-list">
            <li><a href="/?action=pr" class="menu-sub-item">API</a></li>        
            <li><a href="/?action=statepr" class="menu-sub-item">Состояние</a></li>            
        </ul>
    </li> -->

    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander-empty"></span>
        <a href="/?action=manager_stat">
            <span class="menu-item-icon statistic"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Рейтинг</span>
        </a>
        </div>
    </li>

<!--     <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander-empty"></span>
        <a href="/?action=statistic">
            <span class="menu-item-icon statistic"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Статистика</span>
        </a>
        </div>
    </li> -->

    <li class="menu-item">        
        <div class="category-wrapper">
        <span class="expander"></span>
        <div>
            <span class="menu-item-icon statistic"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Статистика</span>
        </div>
        </div>
        <ul class="menu-sub-list">
            <li><a href="/?action=statProducts" class="menu-sub-item">По товарам</a></li>        
            <li><a href="/?action=statProductsTotal" class="menu-sub-item">По всем товарам</a></li> 
            <li><a href="/?action=statManagers" class="menu-sub-item">По менеджерам</a></li>     
            <li><a href="/?action=statAdvertising" class="menu-sub-item">По рекламе</a></li>        
        </ul>
    </li>

    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander"></span>
        <div>
            <span class="menu-item-icon catalog"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Штрафы</span>
        </div>
        </div>
        <ul class="menu-sub-list">
            <li><a href="/?action=penalties" class="menu-sub-item">Список штрафов</a></li>
            <li><a href="/?action=activePenalties" class="menu-sub-item">Активные штрафы</a></li>
        </ul>
    </li>
    
    <li class="menu-item">        
        <div class="category-wrapper">
        <span class="expander"></span>
        <div>
            <span class="menu-item-icon newsletter"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Рассылка</span>
        </div>
        </div>
        <ul class="menu-sub-list">
            <li><a href="/?action=newsletter" class="menu-sub-item">Email</a></li>        
            <li><a href="/?action=sms" class="menu-sub-item">SMS</a></li>            
        </ul>
    </li>
    
    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander"></span>
        <div>
            <span class="menu-item-icon settings"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Настройки</span>
        </div>
        </div>
        <ul class="menu-sub-list">
            <!-- <li><a href="/?action=access" class="menu-sub-item">Доступ</a></li> -->
            <li><a href="/?action=access_group" class="menu-sub-item">Доступ</a></li> 
            <li><a href="/?action=ban_ip" class="menu-sub-item">Бан по IP</a></li>            
            <li><a href="/?action=ban_phone" class="menu-sub-item">Бан по телефону</a></li>
            <li><a href="/?action=binotel_groups" class="menu-sub-item">Binotel группы</a></li>                   
            <li><a href="/?action=binotel_phones" class="menu-sub-item">Binotel телефоны</a></li>            
        </ul>
    </li>
    
    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander-empty"></span>
        <a href="/?action=history">
            <span class="menu-item-icon history"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">История</span>
        </a>
        </div>
    </li>

    <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander-empty"></span>
        <a href="/?action=cart">
            <span class="menu-item-icon cart"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);">Корзина</span>
        </a>
        </div>
    </li>
    
<!--     <li class="menu-item">
        <div class="category-wrapper">
        <span class="expander-empty"></span>
        <a href="/?action=faq">
            <span class="menu-item-icon faq" id="faq"></span>
            <span class="menu-item-label" onclick="MenuToggle(event);" style="color: #4A8CC7;">FAQ</span>
        </a>
        </div>
    </li>
     -->
</ul>     
</div>
            </td>
            <td valign="top">
<div id="content">      

