<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');


function getRecomendedProductsList(){
    db_connect();

    $query = "SELECT *
        FROM product 
        ORDER BY id DESC";
    $result = mysql_query($query);

    while ($product = mysql_fetch_assoc($result)) {
    ?>           
        <tr ondblclick="editRecomendedProducts('<?=$product['id']?>', '<?=$product['name']?>');">
            <td align="left">
                <?=$product['id']?>
            </td>
            <td align="center">
                <span style="position: relative;">
                <?php
                if(empty($product['image'])){echo '<img class="product-img" src="/image/products/no_image.jpg">';}
                    else{
                    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/image/products/'.$product['image'])) {
                        echo '<img class="product-img" src="/image/products/'.$product['image'].'">';}
                    else {
                        echo '<img class="product-img" src="/image/products/no_image.jpg">';}                            
                        }        
                ?>
                </span>
            </td>
            <td style="font-size: 12px; line-height: 13px;">
                <?=$product['name']?>    
            </td>
            <td style="font-size: 12px; line-height: 13px;">
                <?=$product['bonus']?>    
            </td>
        </tr>
    <?php }
}

function getProduct_($id){
    db_connect();
    
    $result = mysql_query("SELECT *
        FROM product
        WHERE id = {$id}") or die (mysql_error());

    return mysql_fetch_assoc($result);
}

function changeProductBonus($productId, $bonus){
    $query = "UPDATE product
        SET bonus = '{$bonus}'
        WHERE id = {$productId}";

    mysqlExec($query);
}

switch ($_POST['action']) {
    case 'getRecomendedProductsList':
        getRecomendedProductsList();
        die();
    case 'changeProductBonus':
        changeProductBonus($_POST['productId'], $_POST['bonus']);    
        break;
}

?>