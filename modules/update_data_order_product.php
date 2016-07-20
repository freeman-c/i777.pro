<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$op = $_POST['op'];

switch ($op){
    case('add'):
        db_connect();
        $query = "SELECT id
            FROM zakazy
            WHERE order_id = '{$_POST['order_id']}'";
        $result = mysql_query($query);
        $order = mysql_fetch_assoc($result);

        $query = "SELECT name
            FROM product
            WHERE id = '{$_POST['product_id']}'";
        $result = mysql_query($query);
        $product = mysql_fetch_assoc($result);

        mysql_query("INSERT INTO product_order SET  
                    order_id='".$_POST['order_id']."',  	
                    product_id='".$_POST['product_id']."',  	
                    price='".$_POST['price']."',  	
                    quantity='".$_POST['quantity']."',
                    date='".date('Y-m-d')."',
                    status_buy='1'");

        $logText = "<b>{user}   |   Заказ №{$order['id']}   |   ДОБАВЛЕН товар \"{$product['name']}\"   |   Количество: {$_POST['quantity']}   |   Стоимость: {$_POST['price']}</b>";
        AddLog("1", $logText, "productInOrder");

        break;
    
    case('update'):
        db_connect();

        $query = "SELECT id
            FROM zakazy
            WHERE order_id = '{$_POST['order_id']}'";
        $result = mysql_query($query);
        $order = mysql_fetch_assoc($result);

        $query = "SELECT name
            FROM product AS P
            LEFT JOIN product_order AS PO ON P.id = PO.product_id
            WHERE PO.id = '{$_POST['id']}'";
        $result = mysql_query($query);
        $product = mysql_fetch_assoc($result);

        $query = "SELECT quantity, price
            FROM product_order
            WHERE id = {$_POST['id']}";
        $result = mysql_query($query);
        $productInOrder = mysql_fetch_assoc($result);

        mysql_query("UPDATE product_order SET  	
                    price='".$_POST['price']."',  	
                    quantity='".$_POST['quantity']."', 	
                    date='".date('Y-m-d')."' 
                    WHERE order_id='".$_POST['order_id']."' 
                    AND id='".$_POST['id']."'");

        if ($productInOrder['price'] != $_POST['price'])
            $logText = "<b>{user}  |   Заказ №{$order['id']}   |   ИЗМЕНЕНА стоимость товара \"{$product['name']}\" | {$productInOrder['price']} => {$_POST['price']}</b>";
        elseif ($productInOrder['quantity'] != $_POST['quantity'])
            $logText = "<b>{user}   |   Заказ №{$order['id']}   |   ИЗМЕНЕНО количество товара \"{$product['name']}\" | {$productInOrder['quantity']} => {$_POST['quantity']}</b>";
        
        AddLog("1", $logText, "productInOrder");

        break;

    case('updatestatus'):
        db_connect();
        $query = "SELECT id
            FROM zakazy
            WHERE order_id = '{$_POST['order_id']}'";
        $result = mysql_query($query);
        $order = mysql_fetch_assoc($result);

        $query = "SELECT name
            FROM product AS P
            LEFT JOIN product_order AS PO ON P.id = PO.product_id
            WHERE PO.id = '{$_POST['id']}'";
        $result = mysql_query($query);
        $product = mysql_fetch_assoc($result);

        $query = "SELECT status_buy
            FROM product_order
            WHERE id = {$_POST['id']}";
        $result = mysql_query($query);
        $productInOrder = mysql_fetch_assoc($result);

        $edit = mysql_query("UPDATE product_order SET  
                            status_buy='".$_POST['status_buy']."' 
                            WHERE order_id='".$_POST['order_id']."' 
                            AND id='".$_POST['id']."' ");

        if ($_POST['status_buy'] == 1) $newStatus = "ОС";
        elseif ($_POST['status_buy'] == 2) $newStatus = "ДП";
        elseif ($_POST['status_buy'] == 3) $newStatus = "ПП";

        if ($productInOrder['status_buy'] == 1) $oldStatus = "ОС";
        elseif ($productInOrder['status_buy'] == 2) $oldStatus = "ДП";
        elseif ($productInOrder['status_buy'] == 3) $oldStatus = "ПП";

        $logText = "<b>{user}   |   Заказ №{$order['id']}   |   ИЗМЕНЁН статус продажи товара \"{$product['name']}\" | {$oldStatus} => {$newStatus}</b>";
        AddLog("1", $logText, "productInOrder");

        break;
    
    case('delete'):
            db_connect();  
         $query = "SELECT id
            FROM zakazy
            WHERE order_id = '{$_POST['order_id']}'";
        $result = mysql_query($query);
        $order = mysql_fetch_assoc($result);

        $query = "SELECT name
            FROM product AS P
            LEFT JOIN product_order AS PO ON P.id = PO.product_id
            WHERE PO.id = '{$_POST['id']}'";
        $result = mysql_query($query);
        $product = mysql_fetch_assoc($result);

        $query = "SELECT quantity, price
            FROM product_order
            WHERE id = {$_POST['id']}";
        $result = mysql_query($query);
        $productInOrder = mysql_fetch_assoc($result);

        $delete = "DELETE 
            FROM product_order 
            WHERE id='".$_POST['id']."' ";
            mysql_query($delete) or die(mysql_error());

        $logText = "<b>{user}   |   Заказ №{$order['id']}   |   УДАЛЁН товар \"{$product['name']}\" |   Количество: {$productInOrder['quantity']}   |   Стоимость: {$productInOrder['price']}</b>";
        AddLog("1", $logText, "productInOrder");

        break;
}
?>