<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/controller.php');


function getProductsList(){
    db_connect();

    
    $result = mysql_query("SELECT *
    	FROM product
        ORDER BY id") or die (mysql_error());

    while ($product = mysql_fetch_assoc($result)){       
        if($product['quantity'] == 0){
            $background = '#FFDFE0';
        }else{
            if($product['status'] == 0){
                $background = '#EEE';
            }else{
                $background = '#FFF';
            }
        }    
        ?>           
        <tr style="background: <?=$background?>;">
            <td>                    
                <input type="checkbox" class="selected" name="need_delete[<?=$product['id']?>]" id="checkbox<?=$product['id']?>" title="<?=$product['id']?>">
            </td>
            <td align="left">
                <?=$product['id']?>
            </td>
            <td align="center" width="24px">
                <img src="/image/edit.png" class="option-button" onclick="editProduct('<?=$product['id']?>');">
            </td>
            <td align="center">
                <span style="position: relative;">
                    <?php
                    echo '<img class="product-img" src="/image/products/no_image.jpg" style="width: 50px">';       
                            ?>
                        </span>
                    </td>
                    <td style="font-size: 12px; line-height: 13px;" width="240px">
                        <?=$product['name']?>
                        <?php 
                        $doprodaji = getProductsDoprodaja();
                        foreach ($doprodaji as $doprodaja):
                            if($doprodaja['parent_id'] == $product['id']){?>
                        <br>                    
                        <span class="arrow-category">
                            &nbsp; <img class="doprodaja" src="/image/add_small.ico"><?=$doprodaja['name']?></span>   
                            <?php } endforeach;?>    
                        </td>
                        <td>
                            <span style="color:#900;"><?=$product['price']?></span>     
                        </td>
                        <td><?=$product['weight']?></td>
                        <td>
                            <?php 
                            if($product['quantity'] < 1){echo '<span style="color:#F00; background:#FFC0CB; padding:0px 6px;">'.$product['quantity'].'</span>';}
                            elseif ($product['quantity'] <= 2){echo '<span style="color:#C60; background:#EED39D; padding:0px 6px;">'.$product['quantity'].'</span>';}
                            elseif ($product['quantity'] > 2){echo '<span style="color:green; background:#E3F2E1; padding:0px 6px;">'.$product['quantity'].'</span>';}
                            ?>
                        </td>
                        <td>
                            <?php if($product['status'] > 0){?>
                            <img src="/image/on.png" class="on-off" onclick="changeProductState('<?=$product['id']?>','0');">
                            <?php }else{?>
                            <img src="/image/off.png" class="on-off" onclick="changeProductState('<?=$product['id']?>','1');">
                            <?php } ?>
                        </td>
                        <td>
                            <?php if($product['only_profit']){?>
                            <img src="/image/on.png" class="on-off" onclick="changeProductOnlyProfitState('<?=$product['id']?>','0');">
                            <?php }else{?>
                            <img src="/image/off.png" class="on-off" onclick="changeProductOnlyProfitState('<?=$product['id']?>','1');">
                            <?php } ?>
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

function addProduct(){
    db_connect();

    mysql_query("INSERT INTO product
        SET name = '{$_POST['name']}',
        price = '{$_POST['price']}',
        weight = '{$_POST['weight']}',
        width = '{$_POST['width']}',
        height = '{$_POST['height']}',
        length = '{$_POST['length']}',
        quantity = '{$_POST['quantity']}',
        full_description = '{$_POST['full_description']}',
        description = '{$_POST['description']}',
        only_profit = '{$_POST['only_profit']}',
        link = '{$_POST['link']}',
        valuta = 'грн.'") or die(mysql_error());

}

function editProduct(){
    db_connect();

    mysql_query("UPDATE product
        SET name = '{$_POST['name']}',
        price = '{$_POST['price']}',
        weight = '{$_POST['weight']}',
        width = '{$_POST['width']}',
        height = '{$_POST['height']}',
        length = '{$_POST['length']}',
        quantity = '{$_POST['quantity']}',
        full_description = '{$_POST['full_description']}',
        description = '{$_POST['description']}',
        only_profit = '{$_POST['only_profit']}',
        link = '{$_POST['link']}'
        WHERE id = {$_POST['id']}") or die(mysql_error());

}

function deleteProduct(){
    db_connect();
    foreach ($_POST['need_delete'] as $id => $value)
        mysql_query("DELETE FROM product 
            WHERE id= {$id}") or die(json_encode(array('success' => false, 'error' => mysql_error())));
    echo json_encode(array('success' => true));
}

function changeProductState($productId, $state){
    db_connect();

    mysql_query("UPDATE product
        SET status = {$state}
        WHERE id = {$productId}") or die(mysql_error());
}

function changeProductOnlyProfitState($productId, $state){
    db_connect();
    $state == 1 ? $state = 'on' : $state = '';
    mysql_query("UPDATE product
        SET only_profit = '{$state}'
        WHERE id = {$productId}") or die(mysql_error());
}

switch ($_POST['operation']) {
    case 'addProduct':
        addProduct();
        die();
    case 'editProduct':
        editProduct();
        break;
    case 'getProductsList':
        getProductsList();
        die();
    case 'deleteProduct':
        deleteProduct();
        die();
    case 'changeProductState':
        changeProductState($_POST['productId'],$_POST['state']);    
        break;
    case 'changeProductOnlyProfitState':
        changeProductOnlyProfitState($_POST['productId'],$_POST['state']);    
        break;
}

?>