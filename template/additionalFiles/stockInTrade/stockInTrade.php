<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/controller.php');

// function getNRecdSubquery($productId, $dateS, $dateE){
//     $query = "SELECT SUM(quantity)
//             FROM product_order AS PO
//             LEFT JOIN zakazy AS Z on PO.order_id = Z.order_id
//             WHERE Z.status = 11 
//             AND PO.product_id = {$productId}
//             AND Z.date_stat >= '{$dateS}'
//             AND Z.date_stat <= '{$dateE}'
//             AND Z.cart = 0"
// }

function getStockInTradeList(){
    db_connect();

    $dateS = date("Y-m-d", time()-7*24*3600);
    $dateE = date("Y-m-d");

    $nRecdSubquery = "SELECT SUM(quantity)
                    FROM product_order AS PO
                    LEFT JOIN zakazy AS Z on PO.order_id = Z.order_id
                    WHERE Z.status = 11 
                    AND PO.product_id = P.id
                    AND Z.cart = 0";

    $nOrderedSubquery = "SELECT SUM(quantity)
                    FROM product_order AS PO
                    LEFT JOIN zakazy AS Z ON PO.order_id = Z.order_id
                    WHERE Z.status IN (11,14,18,29,30,31,33) 
                    AND PO.product_id = P.id
                    AND Z.date_stat >= '{$dateS}'
                    AND Z.date_stat <= '{$dateE}'
                    AND Z.cart = 0";

    $nTransitSubquery = "SELECT SUM(ord)
                    FROM deliveryOrders_product AS DOP
                    LEFT JOIN deliveryOrders AS DO ON DO.id = DOP.deliveryOrderId
                    WHERE DOP.productId = P.id
                    AND DO.statusId = 1"; //только заказы со статусом Приход товара

    $query = "SELECT P.id, P.name, SIT.reserve, 
        ({$nRecdSubquery}) AS nRecd,
        ({$nOrderedSubquery}) AS nOrdered,
        ({$nTransitSubquery}) AS nTransit
        FROM product AS P
        LEFT JOIN stockInTrade AS SIT ON P.id = SIT.productId
        ORDER BY id";

    $result = mysql_query($query) or die (mysqlResponseFail($query, mysql_error()));

    while ($product = mysql_fetch_assoc($result)){       
        // if($product['reserve'] < 10)
            // $background = '#FFDFE0';
        if(empty($product['reserve'])) 
            $product['reserve'] = 0;
        if(empty($product['nRecd'])) 
            $product['nRecd'] = 0;
        if(empty($product['nTransit'])) 
            $product['nTransit'] = 0;
        if(empty($product['nOrdered'])) 
            $product['nOrdered'] = 0;
        $nAvgOrdered = round($product['nOrdered']/7);

        $forecast = $product['reserve'] + $product['nTransit'] - $product['nRecd'] - $nAvgOrdered;
        ?>           
        <tr style="background: <?=$background?>;">
            <td class="product-id">
                <?=$product['id']?>
            </td>
            <td>
                
            </td>
            <td>
                <?=$product['name']?>  
            </td>
            <td class="product-reserve-td">
                <div id="product-reserve-<?=$product['id']?>"><?=$product['reserve']?></div>
                <input type="number" id="product-reserve-input-<?=$product['id']?>" class="product-reserve-input" value="<?=$product['reserve']?>"> 
                <button type="button" id="product-reserve-button-save-<?=$product['id']?>" class="button product-reserve-button product-reserve-button-save">OK</button>
                <button type="button" id="product-reserve-button-cancel-<?=$product['id']?>" class="button-error product-reserve-button product-reserve-button-cancel">X</button>
            </td>
            <td>
                <?=$product['nTransit']?>
            </td>
            <td>
                <?=$product['nRecd']?>
            </td>
            <td>
                <?=$nAvgOrdered?>
            </td>
            <td>
                <?=$forecast?>
            </td>
            <td>
                <?=$forecast*3?>
            </td>
        </tr>
    <?php     
    }
}

function getProductStock($productId){
    $dateS = date("Y-m-d", time()-7*24*3600);
    $dateE = date("Y-m-d");

    $nRecdSubquery = "SELECT SUM(quantity)
                    FROM product_order AS PO
                    LEFT JOIN zakazy AS Z on PO.order_id = Z.order_id
                    WHERE Z.status = 11 
                    AND PO.product_id = P.id
                    AND Z.cart = 0";

    $nOrderedSubquery = "SELECT SUM(quantity)
                    FROM product_order AS PO
                    LEFT JOIN zakazy AS Z ON PO.order_id = Z.order_id
                    WHERE Z.status IN (11,14,18,29,30,31,33) 
                    AND PO.product_id = P.id
                    AND Z.date_stat >= '{$dateS}'
                    AND Z.date_stat <= '{$dateE}'
                    AND Z.cart = 0";

    $nTransitSubquery = "SELECT SUM(ord)
                    FROM deliveryOrders_product AS DOP
                    LEFT JOIN deliveryOrders AS DO ON DO.id = DOP.deliveryOrderId
                    WHERE DOP.productId = P.id
                    AND DO.statusId = 1"; //только заказы со статусом Приход товара

    $query = "SELECT P.id, P.name, SIT.reserve, 
        ({$nRecdSubquery}) AS nRecd,
        ({$nOrderedSubquery}) AS nOrdered,
        ({$nTransitSubquery}) AS nTransit
        FROM product AS P
        LEFT JOIN stockInTrade AS SIT ON P.id = SIT.productId
        WHERE P.id = {$productId}";

    $result = mysql_query($query) or die (mysqlResponseFail($query, mysql_error()));
    $product = mysql_fetch_assoc($result);

    return $product['reserve'] + $product['nTransit'] - $product['nRecd'];
}

function manualChangeProductStock($productId, $reserve){
    db_connect();

    $query = "SELECT id 
        FROM stockInTrade
        WHERE productId = {$productId}";
    $result = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    $result = mysql_fetch_assoc($result);
    if (!empty($result))
        $query = "UPDATE stockInTrade
            SET reserve = {$reserve}
            WHERE productId = {$productId}";
    else
        $query = "INSERT INTO stockInTrade
            SET productId = {$productId},
            reserve = {$reserve}";
    mysqlExec($query);
}

function changeProductStockInDB($productId, $quantity){
    db_connect();

    $query = "SELECT id 
        FROM stockInTrade
        WHERE productId = {$productId}";
    $result = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    $result = mysql_fetch_assoc($result);
    if (!empty($result))
        $query = "UPDATE stockInTrade
            SET reserve = reserve + ({$quantity})
            WHERE productId = {$productId}";
    else
        $query = "INSERT INTO stockInTrade
            SET productId = {$productId},
            reserve = {$quantity}";
    mysqlExec($query);
}

function changeProductStock($orderId, $statusNew, $type){
    echo "changeProductStock";
    if ($type == "warehouse"){
        $query = "SELECT DO.statusId, DOP.productId, DOP.recd AS quantity
            FROM deliveryOrders AS DO
            LEFT JOIN deliveryOrders_product AS DOP ON DOP.deliveryOrderId = DO.id
            WHERE DOP.deliveryOrderId = {$orderId}";

        $result = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));

        $result = dbResultToAssocc($result);
        print_r($result);

        foreach ($result as $row) {
            if ($row['statusId'] == 0 && $statusNew == 2) //если статус "Новый" и устанавливамый "завершено"
                changeProductStockInDB($row['productId'], $row['quantity']);  //увеличить количество товара на складе
            elseif ($row['statusId'] == 1 && $statusNew == 2) //если статус "Приход" и устанавливамый "завершено"
                changeProductStockInDB($row['productId'], $row['quantity']);
            elseif ($row['statusId'] == 3 && $statusNew == 2) //если статус "Корзина" и устанавливамый "завершено"
                changeProductStockInDB($row['productId'], $row['quantity']);

            elseif ($row['statusId'] == 2 && $statusNew == 1) //если статус "Завершено" и устанавливамый "Приход"
                changeProductStockInDB($row['productId'], -$row['quantity']); //уменьшить количество товара на складе
            elseif ($row['statusId'] == 2 && $statusNew == 3) //если статус "Завершено" и устанавливамый "Корзина"
                changeProductStockInDB($row['productId'], -$row['quantity']);
        }
    }
    elseif ($type == "order"){
        //выбрать все товары по указанному заказу
        $query = "SELECT Z.status AS statusId, PO.product_id AS productId, PO.quantity
            FROM zakazy AS Z
            LEFT JOIN product_order AS PO ON PO.order_id = Z.order_id
            WHERE Z.id = {$orderId}";

        $result = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));

        $result = dbResultToAssocc($result);
        print_r($result);

        foreach ($result as $row) {
            if ($row['statusId'] != 36 && $statusNew == 36) //если старый статус не "Убыток" и устанавливамый "Убыток"
                changeProductStockInDB($row['productId'], $row['quantity']);  //увеличить количество товара на складе
            
            elseif ($row['statusId'] != 14 && $statusNew == 14) //если старый статус не "Отправлено" и устанавливамый "Отправлено"
                changeProductStockInDB($row['productId'], -$row['quantity']); //уменьшить количество товара на складе
        }
    }
}

switch ($_POST['operation']) {
    case 'getStockInTradeList':
        getStockInTradeList();
        die();
    case 'manualChangeProductStock':
        manualChangeProductStock($_POST['productId'],$_POST['reserve']);
        die();
}

?>