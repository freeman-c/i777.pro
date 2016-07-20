<?php
require_once $_SERVER['DOCUMENT_ROOT']."/template/additionalFiles/history/historyController.php";

$min = getLogMinDate();
$currentDate = date("Y-m-d H:i:s");
?>

<link rel="stylesheet" type="text/css" href="/template/additionalFiles/history/history.css">

<script type="text/javascript" src="/template/additionalFiles/history/history.js"></script>

<script type="text/javascript">
    var logType = "";
</script>

<div class="log-types-button-container">
    <button class="button log-type log-type-active" id="" onclick="return false;">Общая</button>
    <button class="button log-type" id="GC" onclick="return false;">Get Call</button>
    <button class="button log-type" id="productInOrder" onclick="return false;">Заказы</button>
    <button class="button log-type" id="productInDeliveryOrder" onclick="return false;">Склад</button>
    <button class="button log-type" id="NP" onclick="return false;">Новая почта</button>
    <button class="button log-type" id="SMS" onclick="return false;">СМС</button>
    <button class="button log-type" id="ban" onclick="return false;">Бан</button>
</div>

<div>   
    Поиск: <input type="text" size="28" id="input-ajax-search-logs">
    <button class="button" id="button-search-logs-ajax" onclick="return false;">Найти</button>
    <button class="button" id="button-clear-search-input" onclick="return false;">Очистить</button>
</div> 

<br>
<br>
<table class="logs-list" border="0" cellpadding="0" sellspacing="0">
    <tr>
        <td valign="top" width="140px">
            <div style="overflow-y:auto; overflow-x:hidden; height:420px; padding:1px 0px; margin:4px -3px 0px 0px; z-index:26;">
            <?php 
               $activate = "month-active";
               
               for ($datm = strtotime($currentDate); $datm > strtotime($min['datetime']); $datm = strtotime("-1 month", $datm)) { 
                   
                   if(rdate("M",$datm) == "Декабрь"){ ?>
                       <div class="h-separator">
                       </div>
                   <?php
                   }
                   ?>
                   <div class="month <?=$activate?>" id="m-<?=rdate("m-Y",$datm)?>" onclick="selected_days_in_month('<?=rdate("m-Y",$datm)?>');"><?=rdate("M",$datm)?>
                       <span><?=rdate("Y", $datm)?></span>
                   </div>
                   <?php 
                   $activate = ''; 
               } 
               ?>   
            </div>
        </td>
        <td valign="top">            
            <div class="box-logs">
                <div id="logs-day-panel"></div>
                <div id="logs-container">
                    <h3>Выберите день недели</h3>
                </div>
                <div id="count_rows_message"></div>
            </div>
        </td>
    </tr>
</table>