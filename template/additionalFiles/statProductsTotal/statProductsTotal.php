<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/system/db.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/system/controller.php');


function getRequestCount(&$productStat, $date, $city)
{
    if ($date)
        $dateWhere = "AND Z.date_stat = '$date'";
    if ($city)
        $cityWhere = "AND Z.delivery_adress LIKE '%$city%'";

    $query = "SELECT PO.product_id, P.name, P.only_profit
        FROM zakazy as Z
        LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
        LEFT JOIN product AS P ON P.id = PO.product_id
        WHERE Z.status NOT IN (23,28)
        $dateWhere 
        $cityWhere
        AND Z.cart = 0
        GROUP BY PO.product_id
        ORDER BY P.name";

    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    while ($row = mysql_fetch_assoc($dbRes)) {
        $productStat[$row['product_id']]['name'] = $row['name'];
        $productStat[$row['product_id']]['only_profit'] = $row['only_profit'];
    }


    $query = "SELECT PO.product_id, COUNT(DISTINCT Z.id) as requestCount
    FROM zakazy as Z
    LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
    LEFT JOIN product AS P ON P.id = PO.product_id
    WHERE Z.status NOT IN (23,28)
    $dateWhere
    $cityWhere
    AND P.only_profit != 'on'
    AND PO.status_buy = 1
    AND Z.cart = 0
    GROUP BY PO.product_id";

    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    while ($row = mysql_fetch_assoc($dbRes))
        $productStat[$row['product_id']]['requestCount'] = $row['requestCount'] ? (int)$row['requestCount'] : 0;

    $query = "SELECT COUNT(DISTINCT Z.id) as requestCount
    FROM zakazy as Z
    LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
    LEFT JOIN product AS P ON P.id = PO.product_id
    WHERE Z.status NOT IN (23,28)
    $dateWhere 
    $cityWhere
    AND PO.status_buy = 1
    AND P.only_profit != 'on'
    AND Z.cart = 0";

    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    $row = mysql_fetch_assoc($dbRes);
    $productStat['summury']['requestCount'] = (int)$row['requestCount'];
}

function getCountOfNewOrder(&$productStat, $date, $city)
{
    if ($date)
        $dateWhere = "AND Z.date_stat = '$date'";
    if ($city)
        $cityWhere = "AND Z.delivery_adress LIKE '%$city%'";

    $query = "SELECT PO.product_id, COUNT(DISTINCT Z.id) as countOfNew
    FROM zakazy as Z
    LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
    LEFT JOIN product AS P ON P.id = PO.product_id
    WHERE Z.status NOT IN (23,28)
    $dateWhere 
    $cityWhere
    AND P.only_profit != 'on'
    AND PO.status_buy = 1
    AND Z.cart = 0
    AND Z.new = 1
    GROUP BY PO.product_id";

    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    while ($row = mysql_fetch_assoc($dbRes))
        $productStat[$row['product_id']]['countOfNew'] = $row['countOfNew'] ? $row['countOfNew'] : 0;


}

function getOrderCount(&$productStat, $date, $city)
{
    if ($date)
        $dateWhere = "AND Z.date_stat = '$date'";
    if ($city)
        $cityWhere = "AND Z.delivery_adress LIKE '%$city%'";

    $query = "SELECT PO.product_id, COUNT(DISTINCT Z.id) as orderCount
        FROM zakazy as Z
        LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
        LEFT JOIN product AS P ON P.id = PO.product_id
        WHERE Z.status NOT IN (3,13,23,28)
        $dateWhere 
        $cityWhere
        AND PO.status_buy = 1
        AND P.only_profit != 'on'
        AND Z.cart = 0
        GROUP BY PO.product_id";

    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    while ($row = mysql_fetch_assoc($dbRes))
        $productStat[$row['product_id']]['orderCount'] = (int)$row['orderCount'];

    $query = "SELECT COUNT(DISTINCT Z.id) as orderCount
    FROM zakazy as Z
    LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
    LEFT JOIN product AS P ON P.id = PO.product_id
    WHERE Z.status NOT IN (3,13,23,28)
    $dateWhere 
    $cityWhere
    AND PO.status_buy = 1
    AND P.only_profit != 'on'
    AND Z.cart = 0";

    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    $row = mysql_fetch_assoc($dbRes);
    $productStat['summury']['orderCount'] = (int)$row['orderCount'];
}

function getSaledProductCount(&$productStat, $date, $city)
{
    if ($date)
        $dateWhere = "AND Z.date_stat = '$date'";
    if ($city)
        $cityWhere = "AND Z.delivery_adress LIKE '%$city%'";

    $query = "SELECT PO.product_id, SUM(PO.quantity) AS saledProductCount, 
    SUM(PO.quantity * PO.price) as profit 
    FROM zakazy as Z
    LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
    WHERE Z.status NOT IN (3,13,23,28)
    $dateWhere 
    $cityWhere
    AND Z.cart = 0
    GROUP BY PO.product_id";

    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    while ($row = mysql_fetch_assoc($dbRes)) {
        $productStat[$row['product_id']]['saledProductCount'] = $row['saledProductCount'];
        $productStat[$row['product_id']]['profit'] = (int)$row['profit'];
    }
}

function getAdditonalSaledProductCount(&$productStat, $date, $city)
{
    if ($date)
        $dateWhere = "AND Z.date_stat = '$date'";
    if ($city)
        $cityWhere = "AND Z.delivery_adress LIKE '%$city%'";

    $query = "SELECT PO.product_id, SUM(PO.quantity) AS addSaledProductCount
    FROM zakazy as Z
    LEFT JOIN product_order as PO ON Z.order_id = PO.order_id
    WHERE PO.status_buy IN (2,3) 
    AND Z.status NOT IN (3,13,23,28)
    $cityWhere
    $dateWhere 
    AND Z.cart = 0
    GROUP BY PO.product_id";

    $dbRes = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    while ($row = mysql_fetch_assoc($dbRes)) {
        $productStat[$row['product_id']]['addSaledProductCount'] = $row['addSaledProductCount'];
    }
}

function getStatProductsTotal() {
    
    db_connect();

    $dateFrom = $_POST['dateFrom'];
    $dateTo = $_POST['dateTo'];
    $dateTo = strtotime("+1 day", strtotime($dateTo));
    $dateTo = date("Y-m-d", $dateTo);
    $city = $_POST['city'];
    
    $productStat = array();

    $begin = new DateTime( $dateFrom );
    $end = new DateTime( $dateTo );
    
    $interval = DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($begin, $interval, $end);

    $summury = array();
    foreach ( $period as $dt ) {
        getStatProducts($productStat, $dt->format( "Y-m-d" ), $city);
        $summury[$dt->format( "d.m.Y" )] = $productStat['summury'];
        $productStat = array();
    }

    $response = array("success" => true, "total" => $summury);
    echo json_encode($response);
}


function getStatProducts(&$productStat, $date, $city)
{
    db_connect();
        
    getRequestCount($productStat, $date, $city);
    getCountOfNewOrder($productStat, $date, $city);
    getOrderCount($productStat, $date, $city);
    getSaledProductCount($productStat, $date, $city);
    getAdditonalSaledProductCount($productStat, $date, $city);

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
        $productStat[$key]['avgProfit'] = round($value['profit'] / $value['orderCount'], 2);
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
    $productStat['summury']['avgProfit'] = round($productStat['summury']['profit'] / $productStat['summury']['orderCount'], 2);    
    
    $productStat['summury']['addSaledProductCount'] /= 2;
    $productStat['summury']['saledProductCount'] /= 2;
    $productStat['summury']['addAvgCheck'] = round($productStat['summury']['addAvgCheck'] / 2.0, 3);
    $productStat['summury']['avgCheck'] = round($productStat['summury']['avgCheck'] / 2.0, 3);
    $productStat['summury']['avgProfit'] = round($productStat['summury']['avgProfit'] / 2.0, 2);
    $productStat['summury']['profit'] = round($productStat['summury']['profit'] / 2.0, 2);
}



switch ($_POST['operation']) {
    case 'getStatProductsTotal':
        getStatProductsTotal();
        die();
        break;
}

?>