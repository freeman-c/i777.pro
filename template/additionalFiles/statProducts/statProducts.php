<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/controller.php');


function getRequestCount(&$productStat, $dateFrom, $dateTo, $city, $additionalFilters, $noUtmFilter){
    if ($dateFrom)
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    if ($dateTo)
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";
    if ($city)
        $cityWhere = "AND Z.delivery_adress LIKE '%$city%'";  
   
    if($noUtmFilter == "true") {
        $noUtmWhere = "AND Z.utm_source = ''";
    }

    if ($additionalFilters){
        $additionalFiltersStr = "";
        foreach ($additionalFilters as $key => $filter) {
            $additionalFiltersStr .= "AND Z.".$filter->param." LIKE '%".$filter->value."%'
            "; 
        }
    }

// 3,11,13,14,18,29,30,31,32,33,34,36,37
// 11,14,18,,29,30,31,32,33,34,36,37
    $query = "SELECT PO.product_id, P.name, P.only_profit
    FROM zakazy as Z
    LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
    LEFT JOIN product AS P ON P.id = PO.product_id
    WHERE Z.status NOT IN (23,28)
    $dateFromWhere 
    $dateToWhere
    $cityWhere
    $additionalFiltersStr
    $noUtmWhere
    AND Z.cart = 0
    GROUP BY PO.product_id
    ORDER BY P.name";


    // echo $query.PHP_EOL.PHP_EOL;
    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    while ($row = mysql_fetch_assoc($dbRes)){
        $productStat[$row['product_id']]['name'] = $row['name'];
        $productStat[$row['product_id']]['only_profit'] = $row['only_profit'];
    }


    $query = "SELECT PO.product_id, COUNT(DISTINCT Z.id) as requestCount
    FROM zakazy as Z
    LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
    LEFT JOIN product AS P ON P.id = PO.product_id
    WHERE Z.status NOT IN (23,28)
    $dateFromWhere 
    $dateToWhere
    $cityWhere
    $additionalFiltersStr
    $noUtmWhere
    AND P.only_profit != 'on'
    AND PO.status_buy = 1
    AND Z.cart = 0
    GROUP BY PO.product_id";


    // echo $query.PHP_EOL.PHP_EOL;
    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    while ($row = mysql_fetch_assoc($dbRes))
        $productStat[$row['product_id']]['requestCount'] = $row['requestCount'] ? $row['requestCount'] : 0;
    

    $query = "SELECT COUNT(DISTINCT Z.id) as requestCount
    FROM zakazy as Z
    LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
    LEFT JOIN product AS P ON P.id = PO.product_id
    WHERE Z.status NOT IN (23,28)
    $dateFromWhere 
    $dateToWhere
    $cityWhere
    $additionalFiltersStr
    $noUtmWhere
    AND PO.status_buy = 1
    AND P.only_profit != 'on'
    AND Z.cart = 0";
    // echo $query.PHP_EOL.PHP_EOL;
    
    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    $row = mysql_fetch_assoc($dbRes);
    $productStat['summury']['requestCount'] = $row['requestCount'];
}

function getCountOfNewOrder(&$productStat, $dateFrom, $dateTo, $city, $additionalFilters, $noUtmFilter){
    if ($dateFrom)
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    if ($dateTo)
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";
    if ($city)
        $cityWhere = "AND Z.delivery_adress LIKE '%$city%'";  
   
    if($noUtmFilter == "true") {
        $noUtmWhere = "AND Z.utm_source = ''";
    }

    if ($additionalFilters){
        $additionalFiltersStr = "";
        foreach ($additionalFilters as $key => $filter) {
            $additionalFiltersStr .= "AND Z.".$filter->param." LIKE '%".$filter->value."%'
            "; 
        }
    }


    $query = "SELECT PO.product_id, COUNT(DISTINCT Z.id) as countOfNew
    FROM zakazy as Z
    LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
    LEFT JOIN product AS P ON P.id = PO.product_id
    WHERE Z.status NOT IN (23,28)
    $dateFromWhere 
    $dateToWhere
    $cityWhere
    $additionalFiltersStr
    $noUtmWhere
    AND P.only_profit != 'on'
    AND PO.status_buy = 1
    AND Z.cart = 0
    AND Z.new = 1
    GROUP BY PO.product_id";


    // echo $query.PHP_EOL.PHP_EOL;
    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    while ($row = mysql_fetch_assoc($dbRes))
        $productStat[$row['product_id']]['countOfNew'] = $row['countOfNew'] ? $row['countOfNew'] : 0;
    

}

function getOrderCount(&$productStat, $dateFrom, $dateTo, $city, $additionalFilters, $noUtmFilter){
    if ($dateFrom)
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    if ($dateTo)
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";
    if($city)
        $cityWhere = "AND Z.delivery_adress LIKE '%$city%'";
    if($noUtmFilter == "true") {
        $noUtmWhere = "AND Z.utm_source = ''";
    }
    if ($additionalFilters){
        $additionalFiltersStr = "";
        foreach ($additionalFilters as $key => $filter) {
            $additionalFiltersStr .= "AND Z.".$filter->param." LIKE '%".$filter->value."%'
            "; 
        }
    }

    $query = "SELECT PO.product_id, COUNT(DISTINCT Z.id) as orderCount
    FROM zakazy as Z
    LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
    LEFT JOIN product AS P ON P.id = PO.product_id
    WHERE Z.status NOT IN (3,13,23,28)
    $dateFromWhere 
    $dateToWhere
    $cityWhere
    $additionalFiltersStr
    $noUtmWhere
    AND PO.status_buy = 1
    AND P.only_profit != 'on'
    AND Z.cart = 0
    GROUP BY PO.product_id";
    // echo $query.PHP_EOL.PHP_EOL;
    
    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    while ($row = mysql_fetch_assoc($dbRes))
        $productStat[$row['product_id']]['orderCount'] = $row['orderCount'];

    $query = "SELECT COUNT(DISTINCT Z.id) as orderCount
    FROM zakazy as Z
    LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
    LEFT JOIN product AS P ON P.id = PO.product_id
    WHERE Z.status NOT IN (3,13,23,28)
    $dateFromWhere 
    $dateToWhere
    $cityWhere
    $additionalFiltersStr
    $noUtmWhere
    AND PO.status_buy = 1
    AND P.only_profit != 'on'
    AND Z.cart = 0";
    // echo $query.PHP_EOL.PHP_EOL;
    
    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    $row = mysql_fetch_assoc($dbRes);
    $productStat['summury']['orderCount'] = $row['orderCount'];    
}

function getSaledProductCount(&$productStat, $dateFrom, $dateTo, $city, $additionalFilters, $noUtmFilter){    
    if ($dateFrom)
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    if ($dateTo)
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";
    if($city)
        $cityWhere = "AND Z.delivery_adress LIKE '%$city%'";
    if($noUtmFilter == "true") {
        $noUtmWhere = "AND Z.utm_source = ''";
    }
    if ($additionalFilters){
        $additionalFiltersStr = "";
       foreach ($additionalFilters as $key => $filter) {
            $additionalFiltersStr .= "AND Z.".$filter->param." LIKE '%".$filter->value."%'
            "; 
        }
    }

    $query = "SELECT PO.product_id, SUM(PO.quantity) AS saledProductCount, 
    SUM(PO.quantity * PO.price) as profit 
    FROM zakazy as Z
    LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
    WHERE Z.status NOT IN (3,13,23,28)
    $dateFromWhere 
    $dateToWhere
    $cityWhere
    $additionalFiltersStr
    $noUtmWhere
    AND Z.cart = 0
    GROUP BY PO.product_id";
    // echo $query.PHP_EOL.PHP_EOL;

    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    while ($row = mysql_fetch_assoc($dbRes)){
        $productStat[$row['product_id']]['saledProductCount'] = $row['saledProductCount'];
        $productStat[$row['product_id']]['profit'] = (int)$row['profit'];        
    }
}

function getAdditonalSaledProductCount(&$productStat, $dateFrom, $dateTo, $city, $additionalFilters, $noUtmFilter){    
    if ($dateFrom)
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    if ($dateTo)
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";
    if($city)
        $cityWhere = "AND Z.delivery_adress LIKE '%$city%'";
    if($noUtmFilter == "true") {
        $noUtmWhere = "AND Z.utm_source = ''";
    }
    if ($additionalFilters){
        $additionalFiltersStr = "";
        foreach ($additionalFilters as $key => $filter) {
            $additionalFiltersStr .= "AND Z.".$filter->param." LIKE '%".$filter->value."%'
            "; 
        }
    }

    $query = "SELECT PO.product_id, SUM(PO.quantity) AS addSaledProductCount
    FROM zakazy as Z
    LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
    WHERE PO.status_buy IN (2,3) 
    AND Z.status NOT IN (3,13,23,28)
    $cityWhere
    $dateFromWhere 
    $dateToWhere
    $additionalFiltersStr
    $noUtmWhere
    AND Z.cart = 0
    GROUP BY PO.product_id";
    // echo $query.PHP_EOL.PHP_EOL;

    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    while ($row = mysql_fetch_assoc($dbRes)){
        $productStat[$row['product_id']]['addSaledProductCount'] = $row['addSaledProductCount'];
    }
}


function getStatProducts(){
    db_connect();

    $dateFrom = $_POST['dateFrom'];
    $dateTo = $_POST['dateTo'];
    $city = $_POST['city'];
    $noUtmFilter = $_POST['no_utm'];

    $productStat = array();

    $additionalFilters = json_decode($_POST["filters"]);

    getRequestCount($productStat, $dateFrom, $dateTo, $city, $additionalFilters, $noUtmFilter);
    getCountOfNewOrder($productStat, $dateFrom, $dateTo, $city, $additionalFilters, $noUtmFilter);
    getOrderCount($productStat, $dateFrom, $dateTo, $city, $additionalFilters, $noUtmFilter);
    getSaledProductCount($productStat, $dateFrom, $dateTo, $city, $additionalFilters, $noUtmFilter);
    getAdditonalSaledProductCount($productStat, $dateFrom, $dateTo, $city, $additionalFilters, $noUtmFilter);

    foreach ($productStat as $key => $value) {
        if (!$value['requestCount']) $value['requestCount'] = 0;
        if (!$value['orderCount']) $value['orderCount'] = 0;
        if (!$value['profit']) $value['profit'] = 0;
        if (!$value['saledProductCount']) $value['saledProductCount'] = 0;
        if (!$value['addSaledProductCount']) $value['addSaledProductCount'] = 0;

        if (!$productStat[$key]['requestCount']) $productStat[$key]['requestCount'] = 0;
        if (!$productStat[$key]['orderCount']) $productStat[$key]['orderCount'] = 0;
        if (!$productStat[$key]['saledProductCount']) $productStat[$key]['saledProductCount'] = 0;
        if (!$productStat[$key]['profit']) $productStat[$key]['profit'] = 0;
        
        if (!$productStat[$key]['countOfNew']) $productStat[$key]['countOfNew'] = 0;

        $productStat[$key]['cv2'] = round($value['orderCount'] / $value['requestCount'] * 100, 2);
        $productStat[$key]['avgCheck'] = round($value['saledProductCount'] / $value['orderCount'], 2);
        $productStat[$key]['addAvgCheck'] = round($value['addSaledProductCount'] / $value['orderCount'], 2);
        $productStat[$key]['avgProfit'] = round($value['profit'] /$value['orderCount'], 2);
    }

    foreach ($productStat as $key => $value) {
        if ($productStat[$key]['only_profit'] != 'on')
            $productStat['summury']['saledProductCount'] += $value['saledProductCount']; 
        if ($productStat[$key]['only_profit'] != 'on')
            $productStat['summury']['addSaledProductCount'] += $value['addSaledProductCount'];   
        $productStat['summury']['profit'] += $value['profit'];   
        $productStat['summury']['countOfNew'] += $value['countOfNew'];
    }

    if (!$productStat['summury']['requestCount']) $productStat['summury']['requestCount'] = 0;
    if (!$productStat['summury']['orderCount']) $productStat['summury']['orderCount'] = 0;
    if (!$productStat['summury']['profit']) $productStat['summury']['profit'] = 0;
    if (!$productStat['summury']['saledProductCount']) $productStat['summury']['saledProductCount'] = 0;
    if (!$productStat['summury']['addSaledProductCount']) $productStat['summury']['addSaledProductCount'] = 0;
    
    if (!$productStat['summury']['countOfNew']) $productStat['summury']['countOfNew'] = 0;


    $productStat['summury']['cv2'] = round($productStat['summury']['orderCount'] / $productStat['summury']['requestCount'] * 100, 2);
    $productStat['summury']['avgCheck'] = round($productStat['summury']['saledProductCount'] / $productStat['summury']['orderCount'], 3);

    $productStat['summury']['addAvgCheck'] = round($productStat['summury']['addSaledProductCount'] / $productStat['summury']['orderCount'], 3);

    $productStat['summury']['avgProfit'] = round($productStat['summury']['profit'] / $productStat['summury']['orderCount'],2);
    
    $table = "";
    foreach ($productStat as $key => $value) 
    { 
        if ($key == 'summury') continue;
        if (empty($value['name'])) continue;
        $table .= "<tr>
        <td style=\"text-align: left;\">".$value['name']."</td>
        <td>".$value['requestCount']."</td>
        <td>".$value['orderCount']."</td>
        <td>".$value['countOfNew']."</td>
        <td>".$value['cv2']."%</td>
        <td>".$value['saledProductCount']."</td>
        <td>".$value['avgCheck']."</td>
        <td>".$value['addAvgCheck']."</td>
        <td>".$value['profit']."</td>
        <td>".$value['avgProfit']."</td>
    </tr>";
    unset($bgc);
}

$summury .= "<tr style=\"font-weight: bold\">
<td></td>
<td>".$productStat['summury']['requestCount']."</td>
<td>".$productStat['summury']['orderCount']."</td>
<td>".$productStat['summury']['countOfNew']."</td>
<td>".$productStat['summury']['cv2']."%</td>
<td>".$productStat['summury']['saledProductCount']."</td>
<td>".$productStat['summury']['avgCheck']."</td>
<td>".$productStat['summury']['addAvgCheck']."</td>
<td>".$productStat['summury']['profit']."</td>
<td>".$productStat['summury']['avgProfit']."</td>
</tr>";

$response = array("success" => true, "productStat" => $table, "total" => $summury, "no_utm" => $noUtmFilter);
echo json_encode($response); 
}

switch ($_POST['operation']) {
    case 'getStatProducts':
    getStatProducts();
    die();
    break;
}

?>