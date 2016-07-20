<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$op = $_POST['op'];

switch ($op){
    case('add'):
        print_r($_FILES);
            /*db_connect();
            $add = mysql_query("INSERT INTO `product_order` SET  	
                                    product_id='".$_POST['product_id']."' ");*/
        break;
    
    case('update'):
            db_connect();
            $edit = mysql_query("UPDATE `product` SET  	
                                    price='".$_POST['price']."' 
                                        WHERE order_id='".$_POST['order_id']."' AND product_id='".$_POST['product_id']."' ");
        break;
    
    case('delete'):
            db_connect();
            $query = "SELECT * FROM `product_order` WHERE order_id='".$_POST['order_id']."' ";
            $result = mysql_query($query);
            $row = mysql_fetch_array($result);
            //return $row;        
            $delete = "DELETE FROM `product_order` WHERE id='".$row['id']."' ";
            mysql_query($delete) or die(mysql_error());
        break;
}
?>