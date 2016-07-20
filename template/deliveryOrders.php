<?php 

require_once ($_SERVER['DOCUMENT_ROOT']."/template/additionalFiles/deliveryOrders/deliveryOrders.php");

$statuses = getDeliveryOrdersStatuses();

?>  

<link rel="stylesheet" type="text/css" href="/template/additionalFiles/deliveryOrders/deliveryOrdersTempl.css">
<link rel="stylesheet" href="/template/additionalFiles/deliveryOrders/deliveryOrdersIncl.css">

<script type="text/javascript" src="/template/additionalFiles/deliveryOrders/deliveryOrdersTempl.js"></script>

<div style="height: 40px;">
    <span id="panel-button-operation">
        <button class="button add-delivery-order">+ Добавить</button>
        <button class="button-error delete-delivery-orders" id="button-operation-delete">Удалить <span id="count-elements-delete"></span>
        </button>
    </span>
</div>

<div style="font-size: 11px;">

<div class="statuses-tabs-container">
    <!-- <button class="btn tabs-arrow" id="button-arrow-left-tabs">◄</button> -->
    <div id="tabs-panel-statusy">
        <ul id="ul-statusy">
            <?php foreach ($statuses as $tab): ?>
                <li> <a id="status-<?=$tab['id']?>" class="button but-tab" style="background: <?=$tab['color']?>; color:#333; text-shadow:none;"><?=$tab['name']?></a></li>
            <?php endforeach; ?>  
        </ul>
    </div>
    <!-- <button class="btn tabs-arrow" id="button-arrow-right-tabs">►</button> -->
</div>
<form id="form-delivery-orders">    
    <table id="table-list" class="delivery-orders-table" border="0" cellspacing="0">
    <thead>
        <tr class="column-heading">
            <th style="width: 40px"> 
                <div id="box-input-select-all">
                    <input type="checkbox" id="select-all-checkbox">
                    <div class="box-arrow-down"></div>
                </div>                 
            </th>
            <th></th>
            <th width="24px"></th>
            <th>ТТН</th>  
            <th>Товар</th>
            <th>Коментарий</th>         
            <th>Оформил</th>
            <th>Принял</th>
            <th>Добавлен</th>
            <th>Завершен</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table> 
</form>

</div>
</div>

