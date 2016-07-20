<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');


function getManagerStat(&$managerStat, $dateFrom, $dateTo){
    if ($dateFrom)
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    if ($dateTo)
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";
    
    $query = "SELECT U.login, UD.surname, UD.name, U.access, COUNT(DISTINCT Z.id) as requestCount, 
                (SELECT COUNT(Z.id) 
                FROM zakazy AS Z 
                WHERE Z.user = U.login 
                AND Z.cart = 0
                LIMIT 5000) AS SOC 
            FROM zakazy as Z
            LEFT JOIN product_order as PO ON PO.order_id = Z.order_id
            LEFT JOIN product AS P ON P.id = PO.product_id
            LEFT JOIN users AS U ON U.login = Z.user
            LEFT JOIN users_description AS UD ON UD.login = Z.user
            WHERE Z.status NOT IN (23,28)
            $dateFromWhere
            $dateToWhere
            AND PO.status_buy IN (0,1)
            AND Z.cart = 0
            AND P.only_profit != 'on'
            GROUP BY U.login
            ORDER BY UD.surname, UD.name";
    //echo $query.PHP_EOL;
    $dbRes = mysql_query($query) or die (mysql_error());
    
    while ($row = mysql_fetch_assoc($dbRes)){
        $managerStat[$row['login']]['name'] = $row['surname'].' '.$row['name'];
        $managerStat[$row['login']]['SOC'] = $row['SOC'];
        $managerStat[$row['login']]['access'] = $row['access'];
        $managerStat[$row['login']]['requestCount'] = $row['requestCount'];
    }



    $query = "SELECT U.login, COUNT(DISTINCT Z.id) as orderCount
            FROM zakazy as Z
            LEFT JOIN product_order as PO ON PO.order_id = Z.order_id
            LEFT JOIN product AS P ON P.id = PO.product_id
            LEFT JOIN users AS U ON U.login = Z.user
            WHERE Z.status NOT IN (3,13,23,28)
            $managerIdWhere
            $dateFromWhere
            $dateToWhere
            AND PO.status_buy IN (0,1)
            AND Z.cart = 0
            AND P.only_profit != 'on'
            GROUP BY U.login";
    //echo $query.PHP_EOL;
    $dbRes = mysql_query($query) or die (mysql_error());
    while ($row = mysql_fetch_assoc($dbRes))
        $managerStat[$row['login']]['orderCount'] = $row['orderCount'];



    $query = "SELECT U.login, COUNT(DISTINCT Z.id) as orderCountEffective
            FROM zakazy as Z
            LEFT JOIN product_order as PO ON PO.order_id = Z.order_id
            LEFT JOIN product AS P ON P.id = PO.product_id
            LEFT JOIN users AS U ON U.login = Z.user
            WHERE Z.status NOT IN (3,13,23,28)
            $managerIdWhere
            $dateFromWhere
            $dateToWhere
            AND PO.status_buy IN (2,3)
            AND Z.cart = 0
            AND P.only_profit != 'on'
            GROUP BY U.login";
    //echo $query.PHP_EOL;
    $dbRes = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_assoc($dbRes)){ 
        $managerStat[$row['login']]['orderCountEffective'] = $row['orderCountEffective'];
        if (!$managerStat[$row['login']]['orderCountEffective'])
            $managerStat[$row['login']]['orderCountEffective'] = 0;
    }



    $query = "SELECT U.login, SUM(PO.quantity) AS totalSales
            FROM zakazy as Z
            LEFT JOIN product_order as PO ON PO.order_id = Z.order_id
            LEFT JOIN product AS P ON P.id = PO.product_id
            LEFT JOIN users AS U ON U.login = Z.user
            WHERE Z.status NOT IN (3,13,23,28)
            $managerIdWhere
            $dateFromWhere
            $dateToWhere
            AND Z.cart = 0
            AND P.only_profit != 'on'
            GROUP BY U.login";
    //echo $query.PHP_EOL;
    $dbRes = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_assoc($dbRes))
        $managerStat[$row['login']]['totalSales'] = $row['totalSales'];



    $query = "SELECT U.login, SUM(PO.quantity) AS addSalesProductCount
            FROM zakazy as Z
            LEFT JOIN product_order as PO ON PO.order_id = Z.order_id
            LEFT JOIN product AS P ON P.id = PO.product_id
            LEFT JOIN users AS U ON U.login = Z.user
            WHERE Z.status NOT IN (3,13,23,28)
            $managerIdWhere
            $dateFromWhere
            $dateToWhere
            AND PO.status_buy IN (2)
            AND Z.cart = 0
            -- AND P.only_profit != 'on'
            GROUP BY U.login";
    //echo $query.PHP_EOL;
    $dbRes = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_assoc($dbRes))
        $managerStat[$row['login']]['addSalesProductCount'] = $row['addSalesProductCount'];


    $query = "SELECT U.login, SUM(PO.quantity) AS crossSalesProductCount
            FROM zakazy as Z
            LEFT JOIN product_order as PO ON PO.order_id = Z.order_id
            LEFT JOIN product AS P ON P.id = PO.product_id
            LEFT JOIN users AS U ON U.login = Z.user
            WHERE Z.status NOT IN (3,13,23,28)
            $managerIdWhere
            $dateFromWhere
            $dateToWhere
            AND PO.status_buy IN (3)
            AND Z.cart = 0
            -- AND P.only_profit != 'on'
            GROUP BY U.login";
    // //echo $query.PHP_EOL;
    $dbRes = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_assoc($dbRes))
        $managerStat[$row['login']]['crossSalesProductCount'] = $row['crossSalesProductCount'];

    $managerStat['']['SOC'] = 100;

}

function getStatManagers() {

    db_connect();

    $dateFrom = $_POST['dateFrom'];
    $dateTo = $_POST['dateTo'];

    $managerStat = array();
    getManagerStat($managerStat, $dateFrom, $dateTo);
    // print_r($managerStat);
    foreach ($managerStat as $key => $value) {
        if (!$value['orderCount']) $value['orderCount'] = 0;
        if (!$value['orderCountEffective']) $value['orderCountEffective'] = 0;
        if (!$value['addSalesProductCount']) $value['addSalesProductCount'] = 0;
        if (!$value['crossSalesProductCount']) $value['crossSalesProductCount'] = 0;
        if (!$value['totalSales']) $value['totalSales'] = 0;

        $managerStat[$key]['cv2'] = round($value['orderCount'] / $value['requestCount'] * 100, 2);
        $managerStat[$key]['asop'] = round($value['orderCountEffective'] / $value['orderCount'] * 100, 2);
        $managerStat[$key]['avgCheck'] = round($value['totalSales'] / $value['orderCount'], 3);
        $managerStat[$key]['avgAddCheck'] = round(($value['addSalesProductCount'] + $value['crossSalesProductCount']) / $managerStat[$key]['orderCount'], 3);
        $managerStat[$key]['bonus'] = $value['addSalesProductCount'] * 8 + $value['crossSalesProductCount'] * 16; 
        $managerStat[$key]['rating'] = round(($managerStat[$key]['asop']/60 + $managerStat[$key]['cv2']/90)/2, 3);  

        if ($managerStat[$key]['SOC'] >= 50){
            $managerStat['managerSummury']['requestCount'] += $value['requestCount'];
            $managerStat['managerSummury']['orderCount'] += $value['orderCount'];
            $managerStat['managerSummury']['totalSales'] += $value['totalSales'];
            $managerStat['managerSummury']['orderCountEffective'] += $value['orderCountEffective'];
            $managerStat['managerSummury']['addSalesProductCount'] += $value['addSalesProductCount'];
            $managerStat['managerSummury']['crossSalesProductCount'] += $value['crossSalesProductCount'];
        }
        else{
            $managerStat['traineeSummury']['requestCount'] += $value['requestCount'];
            $managerStat['traineeSummury']['orderCount'] += $value['orderCount'];
            $managerStat['traineeSummury']['totalSales'] += $value['totalSales'];
            $managerStat['traineeSummury']['orderCountEffective'] += $value['orderCountEffective'];
            $managerStat['traineeSummury']['addSalesProductCount'] += $value['addSalesProductCount'];
            $managerStat['traineeSummury']['crossSalesProductCount'] += $value['crossSalesProductCount'];
        }

        $managerStat['total']['requestCount'] += $value['requestCount'];
        $managerStat['total']['orderCount'] += $value['orderCount'];
        $managerStat['total']['totalSales'] += $value['totalSales'];
        $managerStat['total']['orderCountEffective'] += $value['orderCountEffective'];
        $managerStat['total']['addSalesProductCount'] += $value['addSalesProductCount'];
        $managerStat['total']['crossSalesProductCount'] += $value['crossSalesProductCount'];
    }

    $managerStat['managerSummury']['cv2'] = round((float)$managerStat['managerSummury']['orderCount'] / (float)$managerStat['managerSummury']['requestCount']  * 100, 2);
    $managerStat['managerSummury']['asop'] = round((float)$managerStat['managerSummury']['orderCountEffective'] / (float)$managerStat['managerSummury']['orderCount']  * 100, 2);
    $managerStat['managerSummury']['avgCheck'] = round((float)$managerStat['managerSummury']['totalSales'] / (float)$managerStat['managerSummury']['orderCount'], 3);
    $managerStat['managerSummury']['avgAddCheck'] = round(((float)$managerStat['managerSummury']['addSalesProductCount'] + (float)$managerStat['managerSummury']['crossSalesProductCount']) / (float)$managerStat['managerSummury']['orderCount'], 3);


    //просто обнулили значения для красоты вывода у клиента (в случае, если не было стажеров - была пустота в таблице)
    if(!$managerStat['traineeSummury']['requestCount']) $managerStat['traineeSummury']['requestCount'] = 0;
    if(!$managerStat['traineeSummury']['orderCount']) $managerStat['traineeSummury']['orderCount'] = 0;
    if(!$managerStat['traineeSummury']['totalSales']) $managerStat['traineeSummury']['totalSales'] = 0;
    if(!$managerStat['traineeSummury']['orderCountEffective']) $managerStat['traineeSummury']['orderCountEffective'] = 0;
    if(!$managerStat['traineeSummury']['addSalesProductCount']) $managerStat['traineeSummury']['addSalesProductCount'] = 0;
    if(!$managerStat['traineeSummury']['crossSalesProductCount']) $managerStat['traineeSummury']['crossSalesProductCount'] = 0;


    $managerStat['traineeSummury']['cv2'] = round((float)$managerStat['traineeSummury']['orderCount'] / (float)$managerStat['traineeSummury']['requestCount']  * 100, 2);
    $managerStat['traineeSummury']['asop'] = round((float)$managerStat['traineeSummury']['orderCountEffective'] / (float)$managerStat['traineeSummury']['orderCount']  * 100, 2);
    $managerStat['traineeSummury']['avgCheck'] = round((float)$managerStat['traineeSummury']['totalSales'] / (float)$managerStat['traineeSummury']['orderCount'], 3);
    $managerStat['traineeSummury']['avgAddCheck'] = round(((float)$managerStat['traineeSummury']['addSalesProductCount'] + (float)$managerStat['traineeSummury']['crossSalesProductCount']) / (float)$managerStat['traineeSummury']['orderCount'], 3);


    $managerStat['total']['cv2'] = round((float)$managerStat['total']['orderCount'] / (float)$managerStat['total']['requestCount']  * 100, 2);
    $managerStat['total']['asop'] = round((float)$managerStat['total']['orderCountEffective'] / (float)$managerStat['total']['orderCount']  * 100, 2);
    $managerStat['total']['avgCheck'] = round((float)$managerStat['total']['totalSales'] / (float)$managerStat['total']['orderCount'], 3);
    $managerStat['total']['avgAddCheck'] = round(((float)$managerStat['total']['addSalesProductCount'] + (float)$managerStat['total']['crossSalesProductCount']) / (float)$managerStat['total']['orderCount'], 3);


    foreach ($managerStat as $key => $value) {
        if ($value['SOC']>= 50) {
            $ratingArr[$key] = $managerStat[$key]['rating'];
        }
    }

    $max = max($ratingArr);
    $min = min($ratingArr);


    $table = "";
    foreach ($managerStat as $key => $value) 
    { 
        if ($value['SOC'] >= 50) {
            if ($value['requestCount'] == 0) continue;
            if ($key == 'managerSummury') continue;
            if ($key == 'traineeSummury') continue;
            if ($key == 'total') continue;
            if ($value['access'] != 3) continue;
            $table .= "<tr>
                <td style=\"text-align: left;\">".$value['surname'].' '.$value['name']."</td>
                <td>".$value['requestCount']."</td>
                <td>".$value['orderCount']."</td>
                <td>".$value['cv2']."%</td>
                <td>".$value['orderCountEffective']."</td>
                <td>".$value['asop']."%</td>
                <td>".$value['addSalesProductCount']."</td>
                <td>".$value['crossSalesProductCount']."</td>
                <td>".$value['avgAddCheck']."</td>
                <td>".$value['bonus']."</td>
                <td>".$value['rating']."</td>";
            
            if($value['rating'] == $max)
                $table .= "<td style=\"border: 0px; background-color: #fff;\">
                    <div class=\"month-bonus-animation\">
                        Лидер месяца<br>
                        500 грн.
                    </div>
                    <div class=\"week-bonus-animation\">
                        Лидер недели<br>
                        200 грн.
                    </div>
                </td>";
            elseif ($value['rating'] == $min)
                $table .= "<td style=\"border: 0px; background-color: #fff;\">
                        <div class=\"month-bonus-animation week-bonus-animation\">
                            Кандидат на увольнение
                        </div>
                    </td>";
            else 
                $table .= "<td style=\"border: 0px; background-color: #fff;\"></td>";
            
            $table .= "</tr>";
        }
    }

    $managerSummury .= "<tr style=\"font-weight: bold\">
            <td style=\"text-align: right;\">МЕНЕДЖЕРЫ</td>
            <td>".$managerStat['managerSummury']['requestCount']."</td>
            <td>".$managerStat['managerSummury']['orderCount']."</td>
            <td>".$managerStat['managerSummury']['cv2']."%</td>
            <td>".$managerStat['managerSummury']['orderCountEffective']."</td>
            <td>".$managerStat['managerSummury']['asop']."%</td>
            <td>".$managerStat['managerSummury']['addSalesProductCount']."</td>
            <td>".$managerStat['managerSummury']['crossSalesProductCount']."</td>
            <td>".$managerStat['managerSummury']['avgAddCheck']."</td>
            <td style=\"border: 0px\"></td>
            <td style=\"border: 0px\"></td>
        </tr>";

    $traineeSummury .= "<tr style=\"font-weight: bold\">
            <td style=\"text-align: right;\">CТАЖЕРЫ</td>
            <td>".$managerStat['traineeSummury']['requestCount']."</td>
            <td>".$managerStat['traineeSummury']['orderCount']."</td>
            <td>".$managerStat['traineeSummury']['cv2']."%</td>
            <td>".$managerStat['traineeSummury']['orderCountEffective']."</td>
            <td>".$managerStat['traineeSummury']['asop']."%</td>
            <td>".$managerStat['traineeSummury']['addSalesProductCount']."</td>
            <td>".$managerStat['traineeSummury']['crossSalesProductCount']."</td>
            <td>".$managerStat['traineeSummury']['avgAddCheck']."</td>
            <td style=\"border: 0px\"></td>
            <td style=\"border: 0px\"></td>
        </tr>";

     $total .= "<tr style=\"font-weight: bold\">
            <td style=\"text-align: right;\">ВСЕГО</td>
            <td>".$managerStat['total']['requestCount']."</td>
            <td>".$managerStat['total']['orderCount']."</td>
            <td>".$managerStat['total']['cv2']."%</td>
            <td>".$managerStat['total']['orderCountEffective']."</td>
            <td>".$managerStat['total']['asop']."%</td>
            <td>".$managerStat['total']['addSalesProductCount']."</td>
            <td>".$managerStat['total']['crossSalesProductCount']."</td>
            <td>".$managerStat['total']['avgAddCheck']."</td>
            <td style=\"border: 0px\"></td>
            <td style=\"border: 0px\"></td>
        </tr>";

    $response = array("success" => true, "table" => $table, "managerSummury" => $managerSummury, "traineeSummury" => $traineeSummury, "total" => $total);
    echo json_encode($response);
}


function getStatTrainees() {

    db_connect();

    $dateFrom = $_POST['dateFrom'];
    $dateTo = $_POST['dateTo'];

    $managerStat = array();
    getManagerStat($managerStat, $dateFrom, $dateTo);

    foreach ($managerStat as $key => $value) {
        // $skill = $managerStat[$key]['SOC'] >= 50 ? 'manager' : 'trainee';
        if (!$managerStat[$key]['orderCount']) $managerStat[$key]['orderCount'] = 0;

        $managerStat[$key]['cv2'] = round($managerStat[$key]['orderCount'] / $managerStat[$key]['requestCount'] * 100, 2);
        $managerStat[$key]['asop'] = round($managerStat[$key]['orderCountEffective'] / $managerStat[$key]['orderCount'] * 100, 2);
        $managerStat[$key]['avgCheck'] = round($managerStat[$key]['totalSales'] / $managerStat[$key]['orderCount'], 3);
        $managerStat[$key]['avgAddCheck'] = round(($value['addSalesProductCount'] + $value['crossSalesProductCount']) / $managerStat[$key]['orderCount'], 3);
        $managerStat[$key]['bonus'] = $value['addSalesProductCount'] * 8 + $value['crossSalesProductCount'] * 16; 
        $managerStat[$key]['rating'] = round(($managerStat[$key]['asop']/60 + $managerStat[$key]['cv2']/90)/2, 3);  

    }
    // print_r($managerStat);

    foreach ($result as $key => $value) {
        $ratingArr[$key] = $value['rating'];
    }

    $max = max($ratingArr);
    $table = "";
    foreach ($managerStat as $key => $value) 
    { 
        if ($value['SOC'] < 50) {
            if ($value['requestCount'] == 0)
                continue;
            if ($value['access'] != 3) continue;
            if ($key == 'summury') continue;
            $table .= "<tr>
                <td style=\"text-align: left;\">".$value['surname'].' '.$value['name']."</td>
                <td>".$value['requestCount']."</td>
                <td>".$value['orderCount']."</td>
                <td>".$value['cv2']."%</td>
                <td>".$value['orderCountEffective']."</td>
                <td>".$value['asop']."%</td>
                <td>".$value['addSalesProductCount']."</td>
                <td>".$value['crossSalesProductCount']."</td>
                <td>".$value['avgAddCheck']."</td>
                <td>".$value['bonus']."</td>
                <td>".$value['rating']."</td>
                <td style=\"border: none; width: 92px !important;  background-color: #fff; \"></td>
            </tr>";
        }
    }

    $response = array("success" => true, "table" => $table);
    echo json_encode($response);
}

switch ($_POST['operation']) {
    case 'getStatManagers':
        getStatManagers();
        die();
        break;
     case 'getStatTrainees':
        getStatTrainees();
        die();
        break;
}
?>


