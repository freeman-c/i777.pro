<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/controller.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/stockInTrade/stockInTrade.php');
require_once $_SERVER['DOCUMENT_ROOT']."/template/additionalFiles/history/historyController.php";


function getDeliveryOrdersList(){
    $query = "SELECT DO.*, DOS.name, DOS.color
        FROM deliveryOrders AS DO
        LEFT JOIN deliveryOrdersStatuses AS DOS ON DO.statusId = DOS.id
        WHERE DO.statusId = {$_POST['status']}
        ORDER BY DO.id DESC";

    $result = mysql_query($query) or die (mysqlResponseFail($query, mysql_error()));

    while ($deliveryOrder = mysql_fetch_assoc($result)){
        $creator = getUserDescById($deliveryOrder['creatorId']);
        $acceptor = getUserDescById($deliveryOrder['acceptorId']);

        $deliveryOrder['dateStart'] = date("d.m.Y", strtotime($deliveryOrder['dateStart']));
        if (isset($deliveryOrder['dateComplete']) && $deliveryOrder['dateComplete'] != "0000-00-00")
            $deliveryOrder['dateComplete'] = date("d.m.Y", strtotime($deliveryOrder['dateComplete']));
        else
             $deliveryOrder['dateComplete'] = "";

        ?>
    
        <tr style="background-color: <?=$deliveryOrder['color']?>;">
            <td> 
                <input type="checkbox" class="selected" name="need_delete[<?=$deliveryOrder['id']?>]" id="checkbox<?=$deliveryOrder['id']?>" id="<?=$deliveryOrder['id']?>" title="<?=$deliveryOrder['id']?>"> 
            </td>
            <td><?=$deliveryOrder['id']?></td>
            <td style="display: block; width: 16px !important; height: 0; padding: 0; margin: -1px;">
                <img src="/image/edit.png" class="option-button" onclick="editDeliveryOrder('<?=$deliveryOrder['id']?>')">
            </td>
            <td><?=$deliveryOrder['ttn']?></td>
            <td>
                <div style="width: 100px;">
                    <div style="overflow:hidden; text-overflow:ellipsis; width:80px; float:left;">
                        <?php 
                            $productsInOrder = getProductsInDeliveryOrder($deliveryOrder['id']);
                            $t = 0;
                            $sum = 0;
                            foreach ($productsInOrder as $product):
                                if(!empty($product['name'])){
                                    echo $product['name'].', ';
                                    $i++; 
                                    $sum += $product['ord'];
                                }else
                                    echo '- ? ? ? ? ? ? ? ? -';
                            endforeach;
                        ?>
                    </div> 
                </div>
                <span class="count-tovary">
                <?php   if($sum > 0){
                            echo $sum;
                        }else{
                            echo '<span style="color:#FFF; font-weight:bold; font-size:11px; margin: 0px -3px; padding: 0px 3px; background:#F00;">0</span>';
                        }                
                    if($sum > 0){    
                    ?>
                        <div class="popup-box-count-tovary">
                            <div style="min-height:50px; max-height:110px; overflow: auto;">
                                <table width="100%" border="0" cellspacing="0" style="font-family: 'tooltip';">
                                    <?php 
                                    $num = 1;
                                    foreach ($productsInOrder as $product): ?>   
                                    <tr>
                                        <td align="center" style="color:#ABABAB; padding: 1px 2px;"><?=$num++;?></td>
                                        <td align="left" style="padding: 1px 2px;"> <div style="width:180px; overflow:hidden; text-overflow:ellipsis;"><?=htmlspecialchars($product['name'])?></div> </td>
                                        <td align="right" style="padding: 1px 8px;"> 
                                            <?=$product['ord']?>
                                        </td>
                                        <td align="right" style="padding: 1px 8px;"> 
                                            <?=$product['recd']?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </table>                
                            </div>
                    </div>
                    <?php } ?>
                </span>
            </td>
            <td>
                <?php if(!empty($deliveryOrder['comment'])){ ?>
                <div style="width:80px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    <img src="/image/info.png" class="tooltip" title="<?=$deliveryOrder['comment']?>" style="margin: 0px 3px -3px 0px; background:#FF9; border:1px solid #F98A15; cursor:help;"><?=$deliveryOrder['comment']?>
                </div>
                <?php } ?>
            </td>
            <td><?=$creator['surname']?> <?=$creator['name']?></td>
            <td><?=$acceptor['surname']?> <?=$acceptor['name']?></td>
            <td><?=$deliveryOrder['dateStart']?></td>
            <td><?=$deliveryOrder['dateComplete']?></td>
        </tr>
        <?php
    }
}

function saveDeliveryOrder($orderId, $statusId, $ttn, $comment){
    $user = GetUserDescription();
    print_r($user);
    $userId = $user[0]['id'];
    $date = date("Y-m-d");

    if ($statusId == 2){ $setComplete = "
        , 
        acceptorId = {$userId}, 
        dateComplete = '{$date}'"; }

    $query = "UPDATE deliveryOrders
        SET statusId = {$statusId},
        ttn = '{$ttn}',
        comment = '{$comment}'
        $setComplete
        WHERE id = {$orderId}";
    mysql_query($query) or die(mysqlResponseFail($query,mysql_error()));

    $query = "SELECT name
        FROM deliveryOrdersStatuses
        WHERE id = {$statusId}";
    $result = mysql_query($query);
    $status = mysql_fetch_assoc($result);

    $logText = "<b>{user}   |   СОХРАНЕН  заказ №{$_POST['orderId']}   |  ТТН: {$ttn}   |   Статус: {$status['name']}   |   Комментарий: {$comment}</b>";
    AddLog("1", $logText, "productInDeliveryOrder");
}

function deleteDeliveryOrders(){
    db_connect();
    foreach ($_POST['need_delete'] as $id => $value)
        mysql_query("UPDATE deliveryOrders 
            SET statusId = 3
            WHERE id= {$id}") or die(mysqlResponseFail($query, mysql_error()));
    echo mysqlResponseDone($query);

    $logText = "<b>{user}   |   ПЕРЕМЕЩЕН В КОРЗИНУ заказ №{$id}</b>";
    AddLog("0", $logText, "productInDeliveryOrder");
}

function getDeliveryOrder($orderId){
    $query = "SELECT DO.*, DOS.id as s_id, DOS.name as s_name 
        FROM deliveryOrders AS DO
        LEFT JOIN deliveryOrdersStatuses AS DOS ON DOS.id = DO.statusId
        WHERE DO.id = {$orderId}";
    $result = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));

    return simpleResultToAssoc($result);
}

function getDeliveryOrdersStatuses(){
    db_connect();
    $query = "SELECT *
        FROM deliveryOrdersStatuses";
    $result = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));

    return dbResultToAssocc($result);
}

function getProductsInDeliveryOrder($orderId){
    $query = "SELECT DOP.*, P.name 
        FROM deliveryOrders_product AS DOP
        LEFT JOIN product as P ON P.id = DOP.productId
        WHERE deliveryOrderId = {$orderId}";
    $result = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));

    return dbResultToAssocc($result);
}

function createTmpDeliveryOrder(){
    db_connect();
    $creator = GetUserDescription();
    $creatorId = $creator[0]['id'];

    $query = "INSERT INTO deliveryOrders
            SET creatorId = {$creatorId}, 
            dateStart = CURDATE()";
    mysql_query($query) or die(mysql_error());
    $orderId = mysql_insert_id();
    echo $orderId;

    $logText = "<b>{user}   |   СОЗДАН временный заказ №{$orderId}</b>";
    AddLog("1", $logText, "productInDeliveryOrder");
}

function deleteTmpDeliveryOrder(){
    db_connect();
    $query = "DELETE 
        FROM deliveryOrders
        WHERE id = {$_POST['orderId']}
        AND statusId = 0";
    mysql_query($query) or die(mysql_error());

    $query = "SELECT statusId 
        FROM deliveryOrders
        WHERE id = {$_POST['orderId']}";
    $result = mysql_fetch_assoc(mysql_query($query) or die(mysql_error()));

    if ($result['statusId'] == 0){
        $logText = "<b>{user}   |   УДАЛЕН временный заказ №{$_POST['orderId']}</b>";
        AddLog("0", $logText, "productInDeliveryOrder");
    }
}

function addProductToDeliveryOrder($deliveryOrderId, $productId){
    $ord = 1;
    $query = "INSERT INTO  deliveryOrders_product
        SET deliveryOrderId = {$deliveryOrderId},
        productId = {$productId},
        ord = {$ord},
        recd = 0";
    mysql_query($query) or die(mysql_error());

    $query = "SELECT name
            FROM product
            WHERE id = '{$productId}'";
    $result = mysql_query($query);
    $product = mysql_fetch_assoc($result);

    $logText = "<b>{user}   |   Заказ №{$deliveryOrderId}   |   ДОБАВЛЕН товар \"{$product['name']}\"   |   Заказано: {$ord}</b>";
    AddLog("1", $logText, "productInDeliveryOrder");
}

function deleteProductFromDeliveryOrder($recordId){
    $query = "DELETE
        FROM deliveryOrders_product
        WHERE id = {$recordId}";
    mysql_query($query) or die(mysql_error());

    $query = "SELECT DOP.id, P.name, DOP.ord, DOP.recd
            FROM product AS P
            LEFT JOIN deliveryOrders_product DOP ON DOP.productId = P.id
            WHERE DOP.id = {$recordId}";
    $result = mysql_query($query);
    $record = mysql_fetch_assoc($result);

    $logText = "<b>{user}   |   Заказ №{$record['id']}   |   УДАЛЕН товар \"{$record['name']}\"   |   Заказано: {$record['ord']}   |   Получено: {$record['recd']}</b>";
    AddLog("1", $logText, "productInDeliveryOrder");
}

function changeProductOrdQuantityInDeliveryOrder($recordId, $quantity){
    if ($quantity){
        $query = "SELECT DOP.id, P.name, DOP.ord
                FROM product AS P
                LEFT JOIN deliveryOrders_product DOP ON DOP.productId = P.id
                WHERE DOP.id = {$recordId}";
        $result = mysql_query($query);
        $record = mysql_fetch_assoc($result);

        $query = "UPDATE deliveryOrders_product
            SET ord = '{$quantity}'
            WHERE id = {$recordId}";
        mysql_query($query) or die(mysql_error());

        $logText = "<b>{user}   |   Заказ №{$record['id']}   |   ИЗМЕНЕНО количество товара \"{$record['name']}\"   |   Заказано: {$record['ord']} => {$quantity}</b>";
        if ($record['ord'] != $quantity)
            AddLog("1", $logText, "productInDeliveryOrder"); 
    }
}

function changeProductRecdQuantityInDeliveryOrder($recordId, $quantity){
    if ($quantity){
        $query = "SELECT DOP.id, P.name, DOP.recd
                FROM product AS P
                LEFT JOIN deliveryOrders_product DOP ON DOP.productId = P.id
                WHERE DOP.id = {$recordId}";
        $result = mysql_query($query);
        $record = mysql_fetch_assoc($result);

        $query = "UPDATE deliveryOrders_product
            SET recd = '{$quantity}'
            WHERE id = {$recordId}";
        mysql_query($query) or die(mysql_error());

        $logText = "<b>{user}   |   Заказ №{$record['id']}   |   ИЗМЕНЕНО количество товара \"{$record['name']}\"   |   Получено: {$record['recd']} => {$quantity}</b>";
        //тут короче какая то дыра, чейндж вызывается два раза и потому в логах пишется типа изменено количество 10=>10
        if ($record['recd'] != $quantity)
            AddLog("1", $logText, "productInDeliveryOrder"); 
    }
}

db_connect();
switch ($_POST['operation']) {
    case 'saveDeliveryOrder':
        //нужно сначала внести изменения по количеству товара
        //а уже потом сохранять заказ
        //поскольку в функции changeProductStock проверяеться предыдущий с устанавливаемый статус
        changeProductStock($_POST['orderId'], $_POST['statusId'], "warehouse");
        saveDeliveryOrder($_POST['orderId'], $_POST['statusId'], $_POST['ttn'],$_POST['comment']);   
        die();

    case 'getDeliveryOrdersList':
        getDeliveryOrdersList();
        die();

    case 'deleteDeliveryOrders':
        deleteDeliveryOrders();
        die();

    case "createTmpDeliveryOrder":
        createTmpDeliveryOrder();
        die();

    case "deleteTmpDeliveryOrder":
        deleteTmpDeliveryOrder();
        die();

    case "addProductToDeliveryOrder":
        addProductToDeliveryOrder($_POST['orderId'],$_POST['productId']);
        die();

    case "deleteProductFromDeliveryOrder":
        deleteProductFromDeliveryOrder($_POST['recordId']);
        die();

    case "changeProductOrdQuantityInDeliveryOrder":
        changeProductOrdQuantityInDeliveryOrder($_POST['recordId'],$_POST['quantity']);
        die();

    case "changeProductRecdQuantityInDeliveryOrder":
        changeProductRecdQuantityInDeliveryOrder($_POST['recordId'],$_POST['quantity']);
        die();


}

?>