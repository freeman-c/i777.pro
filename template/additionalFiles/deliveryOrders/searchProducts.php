<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

function searchProducts(){
    db_connect();
    $productName = trim(htmlspecialchars($_POST['str']));

    $query = "SELECT * 
        FROM product
        WHERE name 
        LIKE '%{$productName}%'";
    $result = mysql_query($query);
    return dbResultToAssocc($result);
}

$products = searchProducts();
if($products){
    foreach ($products as $product):
    ?>
        <div class="search-result-product" onclick="insertSelectedProduct('<?=uniqid()?>','<?=$product['id']?>','<?=$product['name']?>')">
            <?=$product['name']?> [<span style="color:#900; font-weight:bold;"><?=$product['price']?></span> грн.] (<?=$product['quantity']?> шт.)
        </div>
    <?php 
    endforeach; 
    } 
else{ ?>
    <div class="search-result-product" style="color:red;">Нет такого товара!</div>
<?php;
}
?>
