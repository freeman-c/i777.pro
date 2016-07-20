<?php 
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';

function getStatisticOnAdvertising($productId, $utm_source, $utm_term, $utm_content, $utm_campaign, $dateFrom, $dateTo){
	
	if ($productId){
    	$productIdWhere = "AND PO.product_id = $productId";
    }
    if ($utm_source){
    	$utm_sourceWhere = "AND Z.utm_source LIKE '%$utm_source%'";
    }
    if(true){//if ($utm_term){
    	$utm_termSelect = ",Z.utm_term";
    	$utm_termWhere = "AND Z.utm_term LIKE '%$utm_term%'";
    	$utm_termGroup = ",Z.utm_term";
	}
    if(true){//if ($utm_content){
    	$utm_contentSelect = ",Z.utm_content";
    	$utm_contentWhere = "AND Z.utm_content LIKE '%$utm_content%'";
    	$utm_contentGroup = ",Z.utm_content";
	}
	if(true){//if ($utm_campaign){
		$utm_campaingSelect = ",Z.utm_campaign";
    	$utm_campaignWhere = "AND Z.utm_campaign LIKE '%$utm_campaign%'";
    	$utm_campaignGroup = ",Z.utm_campaign";
	}

	if ($dateFrom){
    	$dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
	}
	if ($dateTo){
    	$dateToWhere = "AND Z.date_stat <= '$dateTo'";
	}
    
    db_connect();
    $query = "SELECT Z.utm_source, PO.product_id, P.name, COUNT( Z.id ) as orderCount, SUM(PO.quantity) as totalSales
    $utm_termSelect $utm_contentSelect $utm_campaingSelect
    FROM zakazy AS Z
    LEFT JOIN product_order AS PO ON PO.order_id = Z.order_id
    LEFT JOIN product AS P ON P.id = PO.product_id
    WHERE Z.status IN (11,14,18,29,30,31,32,33,34,36,37) AND Z.cart = 0 AND PO.status_buy = 1
    $productIdWhere $utm_sourceWhere $utm_termWhere $utm_contentWhere $utm_campaignWhere $dateFromWhere $dateToWhere
    GROUP BY Z.utm_source, PO.product_id $utm_termGroup $utm_contentGroup $utm_campaignGroup
    ORDER BY Z.utm_source, P.name";
	echo $query.PHP_EOL;
    // return $query.PHP_EOL;
    $result = mysql_query($query) or die ('error');
    $result = db_result_to_array($result); 
    return $result;  
}

$productId = $_POST['productId'];
$utm_source = $_POST['utm_source'];
$utm_term = $_POST['utm_term'];
$utm_content = $_POST['utm_content'];
$utm_campaign = $_POST['utm_campaign'];
$dateFrom = $_POST['dateFrom'];
$dateTo = $_POST['dateTo'];
$sort = $_POST['sort'];

$result = getStatisticOnAdvertising($productId, $utm_source, $utm_term, $utm_content, $utm_campaign, $dateFrom, $dateTo); 

//сортировка по количеству проданного товара
if ($sort == 'by-count'){
    foreach ($result as $key => $row) {
        $orderCount[$key] = $row['orderCount'];
        $name[$key]  = $row['name'];
    }
    array_multisort($orderCount, SORT_DESC, $name, SORT_ASC, $result);
}
elseif ($sort == 'by-product'){
    foreach ($result as $key => $row) {
        $name[$key]  = $row['name'];
        $orderCount[$key] = $row['orderCount'];
    }
    array_multisort($name, SORT_ASC, $orderCount, SORT_DESC, $result);
}

if ($utm_source == 'MarketGid'){ 
?>
    <thead>
        <tr>
            <th>Метка</th>
            <th>Кампания</th>
            <th>Объявление</th>
            <th>Ключ</th>
            <th>Товар</th>  
            <th>Заказов</th>   
            <th>Продано,<br>шт.</th>            
        </tr>
    </thead>
    <tbody> 
    <?php
    foreach ($result as $key => $value) 
    { 
        if (!$value['name'])
            continue;
        $totalOrder += $value['orderCount'];
        $totalSales += $value['totalSales'];        
        ?>
		<tr>
		    <td><?=$value['utm_source'];?></td>
		    <td><?=$value['utm_campaign'];?></td>
		    <td><?=$value['utm_content'];?></td>
		    <td><?=$value['utm_term'];?></td>
		    <td style="text-align: left;"><?=$value['name'];?></td>
		    <td><?=$value['orderCount'];?></td>
            <td><?=$value['totalSales'];?></td>
		</tr>
    </tbody>
    <?php 
	}
    ?>
        <tr>
            <td colspan="5" style="text-align: right;"><b>ИТОГО:</b></td>
            <td><b><?=$totalOrder;?></b></td>
            <td><b><?=$totalSales;?></b></td>
        </tr>
        
    <?php
}
else{
	?>
	<thead>
        <tr>
            <th>Метка</th>
            <th>Товар</th>  
            <th>Заказов</th> 
            <th>Продано,<br>шт.</th>      
        </tr>
    </thead>
    <tbody> 
    <?php
	foreach ($result as $key => $value) 
	{ 
        if (!$value['name'])
            continue;
        $totalOrder += $value['orderCount'];
        $totalSales += $value['totalSales'];
        ?>
		<tr>
		    <td><?=$value['utm_source'];?></td>
		    <td style="text-align: left;"><?=$value['name'];?></td>
		    <td><?=$value['orderCount'];?></td>
            <td><?=$value['totalSales'];?></td>
		</tr>
	<?php 
	}
    ?>
        <tr>
            <td colspan="2" style="text-align: right;"><b>ИТОГО:</b></td>
            <td><b><?=$totalOrder;?></b></td>
            <td><b><?=$totalSales;?></b></td>
        </tr>
        
    <?php
}
?>