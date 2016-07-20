<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');

function getRequestCount(&$productStat, $productId, $dateFrom, $dateTo){
    if ($productId)
        $productIdWhere = "AND PO.product_id = {$productId}";
    if ($dateFrom)
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    if ($dateTo)
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";

// 3,11,13,14,18,29,30,31,32,33,34,36,37
// 11,14,18,,29,30,31,32,33,34,36,37
    $query = "SELECT PO.product_id, P.name, P.only_profit
            FROM zakazy as Z
            LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
            LEFT JOIN product AS P ON P.id = PO.product_id
            WHERE Z.status NOT IN (23,28)
            $productIdWhere
            $dateFromWhere 
            $dateToWhere
            AND Z.cart = 0
            GROUP BY PO.product_id
            ORDER BY P.name";

    echo $query.PHP_EOL.PHP_EOL;
    $dbRes = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_assoc($dbRes)){
        $productStat[$row['product_id']]['name'] = $row['name'];
        $productStat[$row['product_id']]['only_profit'] = $row['only_profit'];
    }


    $query = "SELECT PO.product_id, COUNT(DISTINCT Z.id) as requestCount
            FROM zakazy as Z
            LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
            LEFT JOIN product AS P ON P.id = PO.product_id
            WHERE Z.status NOT IN (23,28)
            $productIdWhere
            $dateFromWhere 
            $dateToWhere
            AND P.only_profit != 'on'
            AND PO.status_buy = 1
            AND Z.cart = 0
            GROUP BY PO.product_id";

    echo $query.PHP_EOL.PHP_EOL;
    $dbRes = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_assoc($dbRes))
        $productStat[$row['product_id']]['requestCount'] = $row['requestCount'] ? $row['requestCount'] : 0;
    

    $query = "SELECT COUNT(DISTINCT Z.id) as requestCount
            FROM zakazy as Z
            LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
            LEFT JOIN product AS P ON P.id = PO.product_id
            WHERE Z.status NOT IN (23,28)
            $productIdWhere
            $dateFromWhere 
            $dateToWhere
            AND PO.status_buy = 1
            AND P.only_profit != 'on'
            AND Z.cart = 0";
    echo $query.PHP_EOL.PHP_EOL;
    
    $dbRes = mysql_query($query) or die(mysql_error());
    $row = mysql_fetch_assoc($dbRes);
    $productStat['summury']['requestCount'] = $row['requestCount'];
}

function getOrderCount(&$productStat, $productId, $dateFrom, $dateTo){
    if ($productId)
        $productIdWhere = "AND PO.product_id = {$productId}";
    if ($dateFrom)
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    if ($dateTo)
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";

    $query = "SELECT PO.product_id, COUNT(DISTINCT Z.id) as orderCount
            FROM zakazy as Z
            LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
            LEFT JOIN product AS P ON P.id = PO.product_id
            WHERE Z.status NOT IN (3,13,23,28)
            $productIdWhere
            $dateFromWhere 
            $dateToWhere
            AND PO.status_buy = 1
            AND P.only_profit != 'on'
            AND Z.cart = 0
            GROUP BY PO.product_id";
    echo $query.PHP_EOL.PHP_EOL;
    
    $dbRes = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_assoc($dbRes))
        $productStat[$row['product_id']]['orderCount'] = $row['orderCount'];

    $query = "SELECT COUNT(DISTINCT Z.id) as orderCount
            FROM zakazy as Z
            LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
            LEFT JOIN product AS P ON P.id = PO.product_id
            WHERE Z.status NOT IN (3,13,23,28)
            $productIdWhere
            $dateFromWhere 
            $dateToWhere
            AND PO.status_buy = 1
            AND P.only_profit != 'on'
            AND Z.cart = 0";
    echo $query.PHP_EOL.PHP_EOL;
    
    $dbRes = mysql_query($query) or die(mysql_error());
    $row = mysql_fetch_assoc($dbRes);
    $productStat['summury']['orderCount'] = $row['orderCount'];    
}

function getSaledProductCount(&$productStat, $productId, $dateFrom, $dateTo){    
    if ($productId)
        $productIdWhere = "AND PO.product_id = {$productId}";
    if ($dateFrom)
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    if ($dateTo)
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";

    $query = "SELECT PO.product_id, SUM(PO.quantity) AS saledProductCount, 
                SUM(PO.quantity * PO.price) as profit 
            FROM zakazy as Z
            LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
            WHERE Z.status NOT IN (3,13,23,28)
            $productIdWhere
            $dateFromWhere 
            $dateToWhere
            AND Z.cart = 0
            GROUP BY PO.product_id";
    echo $query.PHP_EOL.PHP_EOL;

    $dbRes = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_assoc($dbRes)){
        $productStat[$row['product_id']]['saledProductCount'] = $row['saledProductCount'];
        $productStat[$row['product_id']]['profit'] = (int)$row['profit'];        
    }
}

db_connect();

$productId = $_POST['productId'];
$dateFrom = $_POST['dateFrom'];
$dateTo = $_POST['dateTo'];
$sort = $_POST['sort'];

$productStat = array();

getRequestCount($productStat, $productId, $dateFrom, $dateTo);
getOrderCount($productStat, $productId, $dateFrom, $dateTo);
getSaledProductCount($productStat, $productId, $dateFrom, $dateTo);

print_r($productStat);

foreach ($productStat as $key => $value) {
    if (!$productStat[$key]['requestCount']) $productStat[$key]['requestCount'] = 0;
    if (!$productStat[$key]['orderCount']) $productStat[$key]['orderCount'] = 0;
    if (!$productStat[$key]['profit']) $productStat[$key]['profit'] = 0;
    if (!$productStat[$key]['saledProductCount']) $productStat[$key]['saledProductCount'] = 0;

    $productStat[$key]['cv2'] = round($productStat[$key]['orderCount'] / $productStat[$key]['requestCount'] * 100, 2);
    $productStat[$key]['avgCheck'] = round($productStat[$key]['saledProductCount'] / $productStat[$key]['orderCount'], 2);
    $productStat[$key]['avgProfit'] = round($productStat[$key]['profit'] / $productStat[$key]['orderCount'], 2);
}

foreach ($productStat as $key => $value) {
    if ($productStat[$key]['only_profit'] != 'on')
        $productStat['summury']['saledProductCount'] += $value['saledProductCount'];   
    $productStat['summury']['profit'] += $value['profit'];   
}

$productStat['summury']['cv2'] = round($productStat['summury']['orderCount'] / $productStat['summury']['requestCount'] * 100, 2);
$productStat['summury']['avgCheck'] = round($productStat['summury']['saledProductCount'] / $productStat['summury']['orderCount'], 3);

$productStat['summury']['avgProfit'] = round($productStat['summury']['profit'] / $productStat['summury']['orderCount'],2);


if ($sort == 'by-count'){
    foreach ($productStat as $key => $row) {
        $saledProductCount[$key] = $row['saledProductCount'];
        $name[$key]  = $row['name'];
    }
    array_multisort($saledProductCount, SORT_DESC, $name, SORT_ASC, $productStat);
}
elseif ($sort == 'by-profit'){
    foreach ($productStat as $key => $row) {
        $profit[$key] = $row['profit'];
        $name[$key]  = $row['name'];
    }
    array_multisort($profit, SORT_DESC, $name, SORT_ASC, $productStat);
}
elseif ($sort == 'by-mac'){
    foreach ($productStat as $key => $row) {
        $avgProfit[$key] = $row['avgProfit'];
        $name[$key]  = $row['name'];
    }
    array_multisort($avgProfit, SORT_DESC, $name, SORT_ASC, $productStat);
}
elseif ($sort == 'by-orderCount'){
    foreach ($productStat as $key => $row) {
        $orderCount[$key] = $row['orderCount'];
        $name[$key]  = $row['name'];
    }
    array_multisort($orderCount, SORT_DESC, $name, SORT_ASC, $productStat);
}
elseif ($sort == 'by-cv2'){
    foreach ($productStat as $key => $row) {
        $cv2[$key] = $row['cv2'];
        $name[$key]  = $row['name'];
    }
    array_multisort($cv2, SORT_DESC, $name, SORT_ASC, $productStat);
}
elseif ($sort == 'by-nac'){
    foreach ($productStat as $key => $row) {
        $avgCheck[$key] = $row['avgCheck'];
        $name[$key]  = $row['name'];
    }
    array_multisort($avgCheck, SORT_DESC, $name, SORT_ASC, $productStat);
}

?>
<thead>
    <tr>
        <th>Товар</th>  
        <th>Заявок</th>  
        <th>Заказов</th> 
        <th>CV2</th> 
        <th>Товаров<br>продано</th>  
        <th>N СЧ</th> 
        <th>Выручка</th>    
        <th>$ СЧ</th>   
    </tr>
</thead>
<tbody> 
<?php
$i = 0;
foreach ($productStat as $key => $value) 
{ 
    if ($key == 'summury') continue;
    if ($i % 2 != 0) $bgc = "background-color: #DAD7D7";
    if (empty($value['name'])) continue;
    ?>
    <tr style="<?=$bgc?>">
        <td style="text-align: left;"><?=$value['name'];?></td>
        <td><?=$value['requestCount'];?></td>
        <td><?=$value['orderCount'];?></td>
        <td><?=$value['cv2']?>%</td>
        <td><?=$value['saledProductCount'];?></td>
        <td><?=$value['avgCheck'];?></td>
        <td><?=$value['profit'];?></td>
        <td><?=$value['avgProfit'];?></td>
    </tr>
<?php 
    unset($bgc);
    $i++;
}
if (count($productStat) > 10){
?>
    <tr style="font-weight: bold">
        <td></td>  
        <td>Заявок</td>  
        <td>Заказов</td> 
        <td>CV2</td> 
        <td>Товаров<br>продано</td>  
        <td>N СЧ</td> 
        <td>Выручка</td>    
        <td>$ СЧ</td>   
    </tr>
<?php
}
?>
    <tr style="font-weight: bold">
        <td style="text-align: right;">Итого:</td>
        <td><?=$productStat['summury']['requestCount']?></td>
        <td><?=$productStat['summury']['orderCount']?></td>
        <td><?=$productStat['summury']['cv2']?>%</td>
        <td><?=$productStat['summury']['saledProductCount']?></td>
        <td><?=$productStat['summury']['avgCheck']?></td>
        <td><?=$productStat['summury']['profit']?></td>
        <td><?=$productStat['summury']['avgProfit']?></td>
    </tr>
