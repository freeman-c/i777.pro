<?php 
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';


function sortResult(&$result, $sort){
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
    elseif ($sort == 'by-asop'){
        foreach ($result as $key => $row) {
            $asop[$key] = $row['asop'];
            $surname[$key]  = $row['surname'];
            $name[$key]  = $row['name'];
        }
        array_multisort($asop, SORT_DESC, $surname, SORT_ASC, $name, SORT_ASC, $result);
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
    elseif ($sort == 'by-bonus'){
        foreach ($result as $key => $row) {
            $bonus[$key] = $row['bonus'];
            $surname[$key]  = $row['surname'];
            $name[$key]  = $row['name'];
        }
        array_multisort($bonus, SORT_DESC, $surname, SORT_ASC, $name, SORT_ASC, $result);
    }
    elseif ($sort == 'by-rating'){
        foreach ($result as $key => $row) {
            $rating[$key] = $row['rating'];
            $surname[$key]  = $row['surname'];
            $name[$key]  = $row['name'];
        }
        array_multisort($rating, SORT_DESC, $surname, SORT_ASC, $name, SORT_ASC, $result);
    }
}


function getManagerStat(&$managerStat, $managerId, $dateFrom, $dateTo){
    if ($managerId)
        $managerIdWhere = "AND UD.id = $managerId";
    if ($dateFrom)
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    if ($dateTo)
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";
    
    $query = "SELECT U.login, UD.surname, UD.name, U.access, COUNT(DISTINCT Z.id) as requestCount, 
                (SELECT COUNT(Z.id) 
                FROM zakazy AS Z 
                WHERE Z.user = U.login 
                AND Z.cart = 0) AS SOC 
            FROM zakazy as Z
            LEFT JOIN product_order as PO ON PO.order_id = Z.order_id
            LEFT JOIN product AS P ON P.id = PO.product_id
            LEFT JOIN users AS U ON U.login = Z.user
            LEFT JOIN users_description AS UD ON UD.login = Z.user
            WHERE Z.status NOT IN (23,28)
            $managerIdWhere
            $dateFromWhere
            $dateToWhere
            AND PO.status_buy IN (0,1)
            AND Z.cart = 0
            AND P.only_profit != 'on'
            -- AND Z.user != ''
            GROUP BY U.login
            ORDER BY UD.surname, UD.name";
    $dbRes = mysql_query($query) or die (mysql_error());
    echo $query.PHP_EOL.PHP_EOL;
    
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
            -- AND Z.user != ''
            GROUP BY U.login";
    echo $query.PHP_EOL.PHP_EOL;
    
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
            -- AND Z.user != ''
            GROUP BY U.login";
    echo $query.PHP_EOL.PHP_EOL;

    $dbRes = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_assoc($dbRes))
        $managerStat[$row['login']]['orderCountEffective'] = $row['orderCountEffective'];



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
            -- AND Z.user != ''
            AND P.only_profit != 'on'
            GROUP BY U.login";
    echo $query.PHP_EOL.PHP_EOL;

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
            -- AND Z.user != ''
            AND P.only_profit != 'on'
            GROUP BY U.login";
    echo $query.PHP_EOL.PHP_EOL;

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
            -- AND Z.user != ''
            AND P.only_profit != 'on'
            GROUP BY U.login";
    echo $query.PHP_EOL.PHP_EOL;

    $dbRes = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_assoc($dbRes))
        $managerStat[$row['login']]['crossSalesProductCount'] = $row['crossSalesProductCount'];

    $managerStat['']['SOC'] = 100;
}



db_connect();

$managerId = $_POST['managerId'];
$dateFrom = $_POST['dateFrom'];
$dateTo = $_POST['dateTo'];
$sortParam = $_POST['sort'];

$managerStat = array();
getManagerStat($managerStat, $managerId, $dateFrom, $dateTo);

foreach ($managerStat as $key => $value) {
    $skill = $managerStat[$key]['SOC'] >= 50 ? 'manager' : 'trainee';
    if (!$managerStat[$key]['orderCount']) $managerStat[$key]['orderCount'] = 0;

    $managerStat[$key]['cv2'] = round($managerStat[$key]['orderCount'] / $managerStat[$key]['requestCount'] * 100, 2);
    $managerStat[$key]['asop'] = round($managerStat[$key]['orderCountEffective'] / $managerStat[$key]['orderCount'] * 100, 2);
    $managerStat[$key]['avgCheck'] = round($managerStat[$key]['totalSales'] / $managerStat[$key]['orderCount'], 3);
    $managerStat[$key]['bonus'] = $value['addSalesProductCount'] * 8 + $value['crossSalesProductCount'] * 16; 
    $managerStat[$key]['rating'] = round(($managerStat[$key]['asop']/60 + $managerStat[$key]['cv2']/90)/2, 3);  

    $managerStat['summury'][$skill]['requestCount'] += $value['requestCount'];
    $managerStat['summury'][$skill]['orderCount'] += $value['orderCount'];
    $managerStat['summury'][$skill]['totalSales'] += $value['totalSales'];
    $managerStat['summury'][$skill]['orderCountEffective'] += $value['orderCountEffective'];
    $managerStat['summury'][$skill]['addSalesProductCount'] += $value['addSalesProductCount'];
    $managerStat['summury'][$skill]['crossSalesProductCount'] += $value['crossSalesProductCount'];
}

$managerStat['summury']['manager']['cv2'] = round((float)$managerStat['summury']['manager']['orderCount'] / (float)$managerStat['summury']['manager']['requestCount']  * 100, 2);
$managerStat['summury']['manager']['asop'] = round((float)$managerStat['summury']['manager']['orderCountEffective'] / (float)$managerStat['summury']['manager']['orderCount']  * 100, 2);
$managerStat['summury']['manager']['avgCheck'] = round((float)$managerStat['summury']['manager']['totalSales'] / (float)$managerStat['summury']['manager']['orderCount'], 3);


$managerStat['summury']['trainee']['cv2'] = round((float)$managerStat['summury']['trainee']['orderCount'] / (float)$managerStat['summury']['trainee']['requestCount']  * 100, 2);
$managerStat['summury']['trainee']['asop'] = round((float)$managerStat['summury']['trainee']['orderCountEffective'] / (float)$managerStat['summury']['trainee']['orderCount']  * 100, 2);
$managerStat['summury']['trainee']['avgCheck'] = round((float)$managerStat['summury']['trainee']['totalSales'] / (float)$managerStat['summury']['trainee']['orderCount'], 3);

sortResult($managerStat, $sortParam);

print_r($managerStat);

?>
<tbody> 

<!-- БЛОК МЕНЕДЖЕРОВ -->
    <tr>
        <th style="font: bold 16pt Arial; border: 0px"  colspan="10">Рейтинг менеджеров</th>
    </tr>
    <tr>
        <th>Менеджер</th>                               
        <th>Заявок</th>                                 
        <th>Заказов</th>                                
        <th>CV2</th>                                    
        <th>Допродажи</th>                              
        <th>% ДП</th>                                   
        <th>Допродано<br>товаров</th>                   
        <th>Допродано<br>перекрёстных<br>товаров</th>   
        <!-- <th>SUM</th>                                    -->
        <th>N СЧ</th>                                   
        <th>Заработано<br>бонусов,<br>грн.</th>         
        <th>Рейтинг</th>                               
    </tr>
    
<?php
$i = 0;
foreach ($managerStat as $key => $value) {
    if ($value['SOC']>= 50)
        $ratingArr[$key] = $managerStat[$key]['rating'];
}

$max = max($ratingArr);
$min = min($ratingArr);

foreach ($managerStat as $key => $value) 
{ 
if ($value['SOC'] >= 50){
    if ($value['requestCount'] == 0) continue;
    if ($key == 'summury') continue;
    if ($value['access'] != 3 && $value['access'] != 8) continue;
    if ($i % 2 != 0) $bgc = "background-color: #DAD7D7";
    ?>
    <tr style="<?=$bgc?>">
        <td style="text-align: left;"><?=$value['surname'].' '.$value['name']?></td>
        <td><?=$value['requestCount']?></td>
        <td><?=$value['orderCount']?></td>
        <td><?=$value['cv2']?>%</td>
        <td><?=$value['orderCountEffective']?></td>
        <td><?=$value['asop']?>%</td>
        <td><?=$value['addSalesProductCount']?></td>
        <td><?=$value['crossSalesProductCount']?></td>
        <!-- <td><?=$value['totalSales']?></td> -->
        <td><?=$value['avgCheck']?></td>
        <td><?=$value['bonus']?></td>
        <td><?=$value['rating']?></td>
        <?php if($value['rating'] == $max){?>
        <td style="border: 0px; background-color: #fff;">
            <div class="month-bonus-animation">
                Лидер месяца<br>
                500 грн.
            </div>
            <div class="week-bonus-animation">
                Лидер недели<br>
                200 грн.
            </div>
        </td>
        <?php }
        else { 
            if ($value['rating'] == $min){
        ?>
            <td style="border: 0px; background-color: #fff;">
                <div class="month-bonus-animation week-bonus-animation">
                    Кандидат на увольнение
                </div>
            </td>
        <?php }
        else { ?>
            <td style="border: 0px; background-color: #fff;"></td>
        <?php } } ?>
    </tr>
<?php 
    unset($bgc);
    $i++;
}
}
?>
    <tr style="font-weight: bold">
        <td style="text-align: right;">ИТОГО:</td>
        <td><?=$managerStat['summury']['manager']['requestCount']?></td>
        <td><?=$managerStat['summury']['manager']['orderCount']?></td>
        <td><?=$managerStat['summury']['manager']['cv2']?>%</td>
        <td><?=$managerStat['summury']['manager']['orderCountEffective']?></td>
        <td><?=$managerStat['summury']['manager']['asop']?>%</td>
        <td><?=$managerStat['summury']['manager']['addSalesProductCount']?></td>
        <td><?=$managerStat['summury']['manager']['crossSalesProductCount']?></td>
        <!-- <td><?=$managerStat['summury']['manager']['totalSales']?></td> -->
        <td><?=$managerStat['summury']['manager']['avgCheck']?></td>
        <td style="border: 0px"></td>
        <td style="border: 0px"></td>
    </tr>
<!-- КОНЕЦ БЛОКА МЕНЕДЖЕРОВ -->

    <tr>
        <td style="border: 0px">&nbsp;</td>
    </tr>
<!--     <tr>
        <td style="border: 0px">&nbsp;</td>
    </tr> -->

<!-- БЛОК СТАЖЕРОВ -->
    <tr>
        <th style="font: bold 16pt Arial; border: 0px"  colspan="10">Рейтинг стажеров</th>
    </tr>
    <tr>
        <th>Менеджер</th>                               
        <th>Заявок</th>                                 
        <th>Заказов</th>                                
        <th>CV2</th>                                    
        <th>Допродажи</th>                              
        <th>% ДП</th>                                   
        <th>Допродано<br>товаров</th>                   
        <th>Допродано<br>перекрёстных<br>товаров</th>   
        <!-- <th>SUM</th>                                    -->
        <th>N СЧ</th>                                   
        <th>Заработано<br>бонусов,<br>грн.</th>         
        <th>Рейтинг</th>                               
    </tr>
<?php
foreach ($managerStat as $key => $value) 
{ 
if ($value['SOC'] < 50){
    if ($value['requestCount'] == 0) continue;
    if ($key == 'summury') continue;
    if ($value['access'] != 3 && $value['access'] != 8) continue;
    if ($i % 2 != 0) $bgc = "background-color: #DAD7D7";
    ?>
    <tr>
        <td style="text-align: left;"><?=$value['surname'].' '.$value['name']?></td>
        <td><?=$value['requestCount']?></td>
        <td><?=$value['orderCount']?></td>
        <td><?=$value['cv2']?>%</td>
        <td><?=$value['orderCountEffective']?></td>
        <td><?=$value['asop']?>%</td>
        <td><?=$value['addSalesProductCount']?></td>
        <td><?=$value['crossSalesProductCount']?></td>
        <!-- <td><?=$value['totalSales']?></td> -->
        <td><?=$value['avgCheck']?></td>
        <td><?=$value['bonus']?></td>
        <td><?=$value['rating']?></td>
        <td style="border: 0px"></td>
    </tr>
<?php 
}
}
?>
    <tr style="font-weight: bold">
        <td style="text-align: right;">ИТОГО:</td>
        <td><?=$managerStat['summury']['trainee']['requestCount']?></td>
        <td><?=$managerStat['summury']['trainee']['orderCount']?></td>
        <td><?=$managerStat['summury']['trainee']['cv2']?>%</td>
        <td><?=$managerStat['summury']['trainee']['orderCountEffective']?></td>
        <td><?=$managerStat['summury']['trainee']['asop']?>%</td>
        <td><?=$managerStat['summury']['trainee']['addSalesProductCount']?></td>
        <td><?=$managerStat['summury']['trainee']['crossSalesProductCount']?></td>
        <!-- <td><?=$managerStat['summury']['trainee']['totalSales']?></td> -->
        <td><?=$managerStat['summury']['trainee']['avgCheck']?></td>
        <td style="border: 0px"></td>
        <td style="border: 0px"></td>
    </tr>
<!-- КОНЕЦ БЛОКА СТАЖЕРОВ -->


</tbody>
