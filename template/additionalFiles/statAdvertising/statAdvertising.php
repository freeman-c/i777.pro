<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/controller.php');

function getStatisticOnAdvertising($dateFrom, $dateTo, $city){ 
    if ($dateFrom)
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    if ($dateTo)
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";
    if ($city)
        $cityWhere = "AND Z.delivery_adress LIKE '%$city%'";
    
    db_connect();

    $query = "SELECT Z.utm_source, Z.utm_medium, Z.utm_term, Z.utm_content, Z.utm_campaign, PO.product_id, P.name, COUNT( Z.id ) as orderCount, SUM(PO.quantity) as totalSales
    FROM zakazy AS Z
    LEFT JOIN product_order AS PO ON PO.order_id = Z.order_id
    LEFT JOIN product AS P ON P.id = PO.product_id
    WHERE Z.status IN (11,14,18,29,30,31,32,33,34,36,37) 
        AND Z.cart = 0 
        AND PO.status_buy = 1
        $dateFromWhere 
        $dateToWhere
        $cityWhere
    GROUP BY Z.utm_source, Z.utm_medium, Z.utm_term, Z.utm_content, Z.utm_campaign, PO.product_id
    ORDER BY Z.utm_source, Z.utm_medium, Z.utm_term, Z.utm_content, Z.utm_campaign, P.name";
    // echo $query.PHP_EOL;
    $result = mysql_query($query) or die (mysqlResponseFail($query, mysql_error()));
    $result = dbResultToAssocc($result); 
    return $result;  
}

function getStatAdvertising() {

    $dateFrom = $_POST['dateFrom'];
    $dateTo = $_POST['dateTo'];
    $city = $_POST['city'];

    $result = getStatisticOnAdvertising($dateFrom, $dateTo, $city); 
    $table = "";
    foreach ($result as $key => $value) 
    { 
        if (!$value['name'])
            continue;
        $totalOrder += $value['orderCount'];
        $totalSales += $value['totalSales'];
        $table .= "<tr>
            <td>".$value['utm_source']."</td>
            <td>".$value['utm_medium']."</td>
            <td>".$value['utm_term']."</td>
            <td>".$value['utm_content']."</td>
            <td>".$value['utm_campaign']."</td>
            <td>".$value['name']."</td>
            <td>".$value['orderCount']."</td>
            <td>".$value['totalSales']."</td>
        </tr>";
    }
    if (!$totalOrder) $totalOrder = 0;
    if (!$totalSales) $totalSales = 0;
    $total = "<tr>
            <td></td>
            <td><b>{$totalOrder}</b></td>
            <td><b>{$totalSales}</b></td>
        </tr>";    

    $response = array("success" => true, "table" => $table, "total" => $total);
    echo json_encode($response);
}


switch ($_POST['operation']) {
    case 'getStatAdvertising':
        getStatAdvertising();
        die();
        break;
}

?>