<?php 
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';

function getManagerStat($managerId){
	
	if ($managerId){
    	$managerIdWhere = "AND UD.id = $managerId";
    }
    
    db_connect();
    $query = "SELECT UD.login, UD.surname, UD.name FROM users_description AS UD
    LEFT JOIN users AS U ON UD.login = U.login
    WHERE U.access = 3 $managerIdWhere
    ORDER BY UD.surname, UD.name";
    // return $query.PHP_EOL;
    $result = mysql_query($query) or die ('error');
    $result = db_result_to_array($result); 
    return $result;  
}

function getRequestCount($login, $dateFrom, $dateTo){
	db_connect();

    if ($dateFrom){
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    }
    if ($dateTo){
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";
    }

    $query = "SELECT COUNT(DISTINCT Z.id) as requestCount FROM zakazy as Z
    LEFT JOIN users as U ON U.login = Z.user
    WHERE Z.status IN (3,11,13,14,18,29,30,31,32,33,34,36,37) AND Z.cart = 0 
    AND U.login = '$login' 
    $dateFromWhere $dateToWhere";

    $result = mysql_query($query);
    $result = mysql_fetch_array($result);
    return $result;
}

function getOrderCount($login, $dateFrom, $dateTo){
	db_connect();

    if ($dateFrom){
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    }
    if ($dateTo){
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";
    }

    $query = "SELECT COUNT(DISTINCT Z.id) as orderCount FROM zakazy as Z
    LEFT JOIN users as U ON U.login = Z.user
    WHERE Z.status IN (11,14,18,29,30,31,32,33,34,36,37) AND Z.cart = 0 
    AND U.login = '$login' 
    $dateFromWhere $dateToWhere";

    $result = mysql_query($query);
    $result = mysql_fetch_array($result);
    return $result;
}

function getSalesStat($login, $dateFrom, $dateTo){
    db_connect();

    if ($dateFrom){
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    }
    if ($dateTo){
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";
    }

    //Получить количество заказов с допродажами
    $query = "SELECT COUNT(DISTINCT Z.id) as orderCountEffective FROM zakazy as Z
    LEFT JOIN users as U ON U.login = Z.user
    LEFT JOIN product_order AS PO ON Z.order_id = PO.order_id
    WHERE Z.status IN (11,14,18,29,30,31,32,33,34,36,37) 
    AND Z.cart = 0 
    AND U.login = '$login'
    AND 0 != (SELECT COUNT(_po.id) from product_order as _po WHERE _po.status_buy IN (2,3) AND _po.order_id = Z.order_id)
    $dateFromWhere $dateToWhere";
    $result = mysql_query($query) or die ('ошибка запроса строка 84');
    $result = mysql_fetch_array($result);

    //Получить количество товаров Допродажа
    $query = "SELECT SUM(PO.quantity) as addSalesProductCount FROM zakazy as Z
    LEFT JOIN users as U ON U.login = Z.user
    LEFT JOIN product_order AS PO ON Z.order_id = PO.order_id
    WHERE Z.status IN (11,14,18,29,30,31,32,33,34,36,37) 
    AND Z.cart = 0
    AND U.login = '$login'
    AND PO.status_buy IN (2)
    $dateFromWhere $dateToWhere";
    $result2 = mysql_query($query) or die ('ошибка запроса строка 98');
    $result2 = mysql_fetch_array($result2);
    $result['addSalesProductCount'] = $result2['addSalesProductCount'];

    //Получить количество товаров Перекрестная
    $query = "SELECT SUM(PO.quantity) as crossSalesProductCount FROM zakazy as Z
    LEFT JOIN users as U ON U.login = Z.user
    LEFT JOIN product_order AS PO ON Z.order_id = PO.order_id
    WHERE Z.status IN (11,14,18,29,30,31,32,33,34,36,37) 
    AND Z.cart = 0
    AND U.login = '$login'
    AND PO.status_buy IN (3)
    $dateFromWhere $dateToWhere";
    $result3 = mysql_query($query) or die ('ошибка запроса строка 113');
    $result3 = mysql_fetch_array($result3);
    $result['crossSalesProductCount'] = $result3['crossSalesProductCount'];

    return $result;
}

$managerId = $_POST['managerId'];
$dateFrom = $_POST['dateFrom'];
$dateTo = $_POST['dateTo'];
$sort = $_POST['sort'];

$result = getManagerStat($managerId); 
$summury = array();
foreach ($result as $key => $value) {
    $requestArr = getRequestCount($value['login'], $dateFrom, $dateTo);
    $orderArr = getOrderCount($value['login'], $dateFrom, $dateTo); 
    $salesStatArr = getSalesStat($value['login'], $dateFrom, $dateTo);

    $result[$key]['requestCount'] = $requestArr['requestCount'];
    $summury['requestCount'] += $result[$key]['requestCount'];

    $result[$key]['orderCount'] = (int)$orderArr['orderCount'];
    $summury['orderCount'] += $result[$key]['orderCount'];

    $result[$key]['cv2'] =  round((float)$orderArr['orderCount'] / (float)$requestArr['requestCount'] * 100, 2);

    $result[$key]['orderCountNotEffective'] = $orderArr['orderCount'] - $salesStatArr['orderCountEffective'];
    $summury['orderCountNotEffective'] += $result[$key]['orderCountNotEffective'];

    $result[$key]['orderCountEffective'] = $salesStatArr['orderCountEffective'];
    $summury['orderCountEffective'] += $result[$key]['orderCountEffective'];

    $result[$key]['cv3'] =  round((float)$salesStatArr['orderCountEffective'] / $orderArr['orderCount'] * 100, 2);

    $result[$key]['addSalesProductCount'] = $salesStatArr['addSalesProductCount'];
    $summury['addSalesProductCount'] += $result[$key]['addSalesProductCount'];

    $result[$key]['crossSalesProductCount'] = $salesStatArr['crossSalesProductCount'];
    $summury['crossSalesProductCount'] += $result[$key]['crossSalesProductCount'];
}

$summury['cv2'] = round((float)$summury['orderCount'] / (float)$summury['requestCount']  * 100, 2);
$summury['cv3'] = round((float)$summury['orderCountEffective'] / (float)$summury['orderCount']  * 100, 2);

if ($sort == 'by-orderCount'){
    foreach ($result as $key => $row) {
        $orderCount[$key] = $row['orderCount'];
        $surname[$key]  = $row['surname'];
        $name[$key]  = $row['name'];
    }
    array_multisort($orderCount, SORT_DESC, $surname, SORT_ASC, $name, SORT_ASC, $result);
}
elseif ($sort == 'by-cv2'){
    foreach ($result as $key => $row) {
        $cv2[$key] = $row['cv2'];
        $surname[$key]  = $row['surname'];
        $name[$key]  = $row['name'];
    }
    array_multisort($cv2, SORT_DESC, $surname, SORT_ASC, $name, SORT_ASC, $result);
}
elseif ($sort == 'by-effective'){
    foreach ($result as $key => $row) {
        $orderCountEffective[$key] = $row['orderCountEffective'];
        $surname[$key]  = $row['surname'];
        $name[$key]  = $row['name'];
    }
    array_multisort($orderCountEffective, SORT_DESC, $surname, SORT_ASC, $name, SORT_ASC, $result);
}
elseif ($sort == 'by-not-effective'){
    foreach ($result as $key => $row) {
        $orderCountNotEffective[$key] = $row['orderCountNotEffective'];
        $surname[$key]  = $row['surname'];
        $name[$key]  = $row['name'];
    }
    array_multisort($orderCountNotEffective, SORT_ASC, $surname, SORT_ASC, $name, SORT_ASC, $result);
}
elseif ($sort == 'by-cv3'){
    foreach ($result as $key => $row) {
        $cv3[$key] = $row['cv3'];
        $surname[$key]  = $row['surname'];
        $name[$key]  = $row['name'];
    }
    array_multisort($cv3, SORT_DESC, $surname, SORT_ASC, $name, SORT_ASC, $result);
}
elseif ($sort == 'by-asc'){
    foreach ($result as $key => $row) {
        $addSalesProductCount[$key] = $row['addSalesProductCount'];
        $surname[$key]  = $row['surname'];
        $name[$key]  = $row['name'];
    }
    array_multisort($addSalesProductCount, SORT_DESC, $surname, SORT_ASC, $name, SORT_ASC, $result);
}
elseif ($sort == 'by-csc'){
    foreach ($result as $key => $row) {
        $crossSalesProductCount[$key] = $row['crossSalesProductCount'];
        $surname[$key]  = $row['surname'];
        $name[$key]  = $row['name'];
    }
    array_multisort($crossSalesProductCount, SORT_DESC, $surname, SORT_ASC, $name, SORT_ASC, $result);
}




// var_dump($result);

?>
<thead>
    <tr>
        <th>Менеджер</th>  
        <th>Заявок</th> 
        <th>Заказов</th>
        <th>CV2</th>
        <th>Н/Э</th>
        <th>Э</th>
        <th>% доп.</th>
        <th>Допродажи</th>
        <th>Перекрестные<br>продажи</th>
    </tr>
</thead>
<tbody> 
<?php
foreach ($result as $key => $value) 
{ 
    if ($value['requestCount'] == 0)
        continue;
    ?>
    <tr>
        <td style="text-align: left;"><?=$value['surname'].' '.$value['name']?></td>
        <td><?=$value['requestCount']?></td>
        <td><?=$value['orderCount']?></td>
        <td><?=$value['cv2']?>%</td>
        <td><?=$value['orderCountNotEffective']?></td>
        <td><?=$value['orderCountEffective']?></td>
        <td><?=$value['cv3']?>%</td>
        <td><?=$value['addSalesProductCount']?></td>
        <td><?=$value['crossSalesProductCount']?></td>
    </tr>
<?php 
}
?>
    <tr style="font-weight: bold">
        <td style="text-align: right;">ИТОГО:</td>
        <td><?=$summury['requestCount']?></td>
        <td><?=$summury['orderCount']?></td>
        <td><?=$summury['cv2']?>%</td>
        <td><?=$summury['orderCountNotEffective']?></td>
        <td><?=$summury['orderCountEffective']?></td>
        <td><?=$summury['cv3']?>%</td>
        <td><?=$summury['addSalesProductCount']?></td>
        <td><?=$summury['crossSalesProductCount']?></td>
    </tr>