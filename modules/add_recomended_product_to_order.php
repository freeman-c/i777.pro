<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

db_connect();
$row = getProduct($_POST['recomendedProductId']);
$response = array();

$add = mysql_query("INSERT INTO product_order SET  
                        order_id='".$_POST['orderId']."',  	
                        product_id='".$row['id']."',  	
                        price='".$row['price']."',  	
                        quantity='".$row['quantity']."',
                        date='".date('Y-m-d')."',
                        status_buy='2' 	
                         ") or die (json_encode(array('success' => 'false', 'error' => mysql_error())));

$response['success'] = 'true';
$response['price'] = $row['price'];
$response['quantity'] = $row['quantity'];

echo json_encode($response);
?>
