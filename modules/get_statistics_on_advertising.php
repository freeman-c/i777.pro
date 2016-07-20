<?php 
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';

function getStatisticOnAdvertising($productId, $utm_source, $utm_term, $utm_content, $utm_campaign, $dateFrom, $dateTo){
	
	if ($productId){
    	$productIdWhere = "AND PO.product_id = $productId";
    }
    if ($utm_source){
    	$utm_sourceWhere = "AND z.utm_source LIKE '%$utm_source%'";
    }
    if ($utm_term){
    	$utm_termSelect = ",Z.utm_term";
    	$utm_termWhere = "AND z.utm_term LIKE '%$utm_term%'";
    	$utm_termGroup = ",Z.utm_term";
	}
    if ($utm_content){
    	$utm_contentSelect = ",Z.utm_content";
    	$utm_contentWhere = "AND z.utm_content LIKE '%$utm_content%'";
    	$utm_contentGroup = ",Z.utm_content";
	}
	if ($utm_campaign){
		$utm_campaingSelect = ",Z.utm_campaign";
    	$utm_campaignWhere = "AND z.utm_campaign LIKE '%$utm_campaign%'";
    	$utm_campaignGroup = ",Z.utm_campaign";
	}

	if ($dateFrom){
    	$dateFromWhere = "AND z.date_stat >= '$dateFrom'";
	}
	if ($dateTo){
    	$dateToWhere = "AND z.date_stat <= '$dateTo'";
	}
    
    db_connect();
    $query = "SELECT Z.utm_source, PO.product_id, P.name, COUNT( z.id ) as orderCount $utm_termSelect $utm_contentSelect $utm_campaingSelect
FROM zakazy AS Z
LEFT JOIN product_order AS PO ON PO.order_id = Z.order_id
LEFT JOIN product AS P ON P.id = PO.product_id
WHERE z.cart =0 AND Z.status IN (11,14,18,29,30,31,21,33,34,36) 
$productIdWhere $utm_sourceWhere $utm_termWhere $utm_contentWhere $utm_campaignWhere $dateFromWhere $dateToWhere
GROUP BY z.utm_source, PO.product_id $utm_termGroup $utm_contentGroup $utm_campaignGroup";
	echo $query.PHP_EOL;
    // return $query.PHP_EOL;
    $result = mysql_query($query);
    $result = db_result_to_array($result); 
    return $result;  
}

function getRequestCount($product_id){
	db_connect();
    $query = "SELECT SUM(PO.quantity) as requestCount FROM product_order as PO
    LEFT JOIN zakazy as Z ON Z.order_id = PO.order_id
    WHERE Z.status IN (3,11,13,14,18,29,30,31,32,33,34,36)";
    echo $query.PHP_EOL;
    $result = mysql_query($query);
    //$result = db_result_to_array($result); 
    $result = mysql_fetch_array($result);
    return $result['requestCount'];
}

function getOrderCount($product_id){
	db_connect();
    $query = "SELECT SUM(PO.quantity) as orderCount FROM product_order as PO
    LEFT JOIN zakazy as Z ON Z.order_id = PO.order_id
    WHERE Z.status IN (3,11,13,14,18,29,30,31,32,33,34,36)";
    echo $query.PHP_EOL;
    $result = mysql_query($query);
    //$result = db_result_to_array($result); 
    $result = mysql_fetch_array($result);
    return $result['orderCount'];
}

$productId = $_POST['productId'];
$utm_source = $_POST['utm_source'];
$utm_term = $_POST['utm_term'];
$utm_content = $_POST['utm_content'];
$utm_campaign = $_POST['utm_campaign'];
$dateFrom = $_POST['dateFrom'];
$dateTo = $_POST['dateTo'];

$result = getStatisticOnAdvertising($productId, $utm_source, $utm_term, $utm_content, $utm_campaign, $dateFrom, $dateTo); 

if ($utm_source == 'MarketGid'){ ?>
	<thead>
        <tr>
            <th>Метка</th>
            <th>Кампания</th>
            <th>Объявление</th>
            <th>Ключ</th>
            <th>Товар</th>  
            <th>Заказов</th>         
        </tr>
    </thead>
    <tbody> 
    <?php
    foreach ($result as $key => $value) 
    { ?>
		<tr>
		    <td><?=$value['utm_source'];?></td>
		    <td><?=$value['utm_term'];?></td>
		    <td><?=$value['utm_content'];?></td>
		    <td><?=$value['utm_campaign'];?></td>
		    <td><?=$value['name'];?></td>
		    <td><?=$value['orderCount'];?></td>
		</tr>
    </tbody>
    <?php 
	}
}
else{
	?>
	<thead>
        <tr>
            <th>Метка</th>
            <th>Товар</th>  
            <th>Заказов</th>         
        </tr>
    </thead>
    <tbody> 
    <?php
	foreach ($result as $key => $value) 
	{ ?>
		<tr>
		    <td><?=$value['utm_source'];?></td>
		    <td><?=$value['name'];?></td>
		    <td><?=$value['orderCount'];?></td>
		</tr>
	<?php 
	}
}
?>