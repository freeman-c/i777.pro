<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
db_connect();

switch ($_POST['op']){
    case('add'):
            $add = mysql_query("INSERT INTO recomended_products SET   	
                product_id='".$_POST['product_id']."',  	
                recomended_product_id='".$_POST['recomended_product_id']."'");
        break;
    
    case('delete'):      
            $delete = "DELETE FROM recomended_products WHERE id=".$_POST['id']."";
            mysql_query($delete) or die(mysql_error());
        break;
}

?>