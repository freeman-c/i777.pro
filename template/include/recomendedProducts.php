<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/recomendedProducts/recomendedProductsController.php');
$product = getProduct_($_GET['productId']);

?>

<script type="text/javascript" src="/template/additionalFiles/recomendedProducts/recomendedProductsIncl.js"></script>

<script type="text/javascript">
    $(document).ready(function(){  
        $('#recomended-product-container').load('/template/additionalFiles/recomendedProducts/recomendedProductsList.php?product_id=<?=$_GET['productId']?>'); 
    });
</script>

<table>
    <tr>
        <td width="125px">
            Бонус за допродажу
        </td>
        <td>
            <input class="bonus" type="number" value="<?=$product['bonus']?>">
        </td>
    </tr>
    <tr>
        <td colspan="2" style="overflow: hidden; max-height: 400px; max-width: 450px; min-width: 450px"valign="top">        
            <div id="recomended-product-container"></div>
            <div style="text-align:right;">
                <span style="float:left;">
                    <button onclick="addRecomendedProduct(); return false;" id="add-recomended-product">
                        <img src="/image/plus_circle.ico" style="margin: 0px 0px -4px 0px;">
                        добавить товар</button>
                </span>
            </div> 
        </td>
    </tr>
</table>
<hr>
<input type="hidden" value="<?=$_GET['productId']?>" class="product-id">
<p style="text-align:center;">
    <button class="button save-product-button">Сохранить</button>
    <button class="disabled" onclick="CloseModal(); return false">Отмена</button>
</p>
