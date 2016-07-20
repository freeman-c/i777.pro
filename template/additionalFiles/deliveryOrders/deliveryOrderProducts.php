<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/deliveryOrders/deliveryOrders.php');
$products = getProductsInDeliveryOrder($_GET['orderId']);

error_reporting(0);
$orderId = $_GET['orderId'];

?>
<style>    
    .new-product-string select {
        width: 60px;
    }
    #table-order-product select {
        width: 60px;
    }

    #table-order-product{
        width: 100%;
        border-top: 1px solid #ABABAB;
        border-right: 1px solid #ABABAB;
        background: #FFF; 
        font-size: 11px;
    }
    #table-order-product td{
        padding: 0px 1px !important;
        margin: 0px !important;
        border-left: 1px solid #ABABAB;
        border-bottom: 1px solid #ABABAB;        
    }
    .title-table-order-product{
        background: #FF6;
        font-weight: bold;
    }
    .title-table-order-product td{
        text-align: center;
    }
    #table-order-product input[type="text"]{
        border:none;
        font-size: 11px;
        padding: 1px 3px;
    }
    #table-order-product input[type="text"]:focus{
        border: none;
        background: #E3F2E1;
        box-shadow: none;
        /*background: #1C79EB;
        color: #FFF;*/
    }
    .del-product-button{
        cursor: pointer;
    }
    /*----- search ------*/
    #search-box{
        position: relative;
    }
    #search-result{
        display: none;
        position: absolute;
        top:18px;
        left:-1px;
        width: 420px;
        background: #FF9;
        border: 1px solid #757575;
        padding: 1px;
        overflow: auto;
        max-height: 60px;
    }
    .search-result-product{
        padding: 0px 4px;        
    }
    .search-result-product:hover{
        background: #FED24E;
        cursor: pointer;
    }
    
</style>
<script>
    var orderId = "<?=$_GET['orderId']?>";

    function showDeliveryOrderProductList(){
        $('#order-product').load('/template/additionalFiles/deliveryOrders/deliveryOrderProducts.php?orderId='+orderId);   
    }

    function addProductToDeliveryOrder(orderId, productId){ 
        $.ajax({
            type: "POST",
            url: "/template/additionalFiles/deliveryOrders/deliveryOrders.php",
            data: {
                operation: "addProductToDeliveryOrder",
                orderId : orderId,
                productId:productId
            },
            beforeSend: function(){
                // WaitingBarShow('Добавление товара в заказ...');
            },
            success: function(data){ 
                showDeliveryOrderProductList();
                // WaitingBarHide();
                MessageTray('Информация в заказе обновлена');
            }
        });
    }

    function deleteProductFromDeliveryOrder(recordId,event){
        t=event.target||event.srcElement;         
        $(t).parent().parent().remove();

        $.ajax({
            type: "POST",
            url: "/template/additionalFiles/deliveryOrders/deliveryOrders.php",
            data: {
                operation: "deleteProductFromDeliveryOrder",
                recordId : recordId
            },
            beforeSend: function(){
            },
            success: function(data){ 
                WaitingBarHide();
                MessageTray('Информация в заказе обновлена');
            }
        });
    }
    
    function changeProductOrdQuantityInDeliveryOrder(recordId, quantity){
        $.ajax({
            type: "POST",
            url: "/template/additionalFiles/deliveryOrders/deliveryOrders.php",
            data: {
                operation: "changeProductOrdQuantityInDeliveryOrder",
                recordId : recordId,
                quantity: quantity
            },
            success: function(data){ 
                MessageTray('Информация в заказе обновлена');
            }
        });
    }

    function changeProductRecdQuantityInDeliveryOrder(recordId, quantity){
        $.ajax({
            type: "POST",
            url: "/template/additionalFiles/deliveryOrders/deliveryOrders.php",
            data: {
                operation: "changeProductRecdQuantityInDeliveryOrder",
                recordId : recordId,
                quantity: quantity
            },
            success: function(data){ 
                MessageTray('Информация в заказе обновлена');
            }
        });
    }
       
    function insertSelectedProduct(id,productId,name){
        addProductToDeliveryOrder(orderId, productId);
    }
    
    function addNewProduct(){
        var count_row = $('#table-order-product tr').length;
        if($("#product").length === 0){            
            $('#table-order-product').append('<tr>'+
                    '<td align="center" style="color:#ABABAB;">'+count_row+'</td>'+
                    '<td>'+                         
                        '<div id="search-box">'+
                            '<input type="text" id="product" style="width: 100%;" placeholder="Введите название товара...">'+
                            '<div id="search-result"></div>'+
                        '</div>'+
                    '</td>'+
                    '<td></td>'+
                    '<td></td>'+
                    '<td> <img class="del-product-button" onclick="deleteProductFromDeliveryOrder(\'\',event);" id="delete-product" src="/image/minus_circle.ico"> </td>'+
                '</tr>');
            $('#product').focus();
            
            $('#product').keyup(function(){
                if( $(this).val().length > 1 ){
                var productName = $(this).val();    
                    $.ajax({
                        type: "POST",
                        url: "/template/additionalFiles/deliveryOrders/searchProducts.php",
                        data: {
                            str:$(this).val()
                        },
                        beforeSend: function(){
                            $('#search-result').show();
                            $('#search-result').html('<img src="/image/loader_big.gif">');
                        },
                        success: function(data){ 
                            $('#search-result').html();
                            $('#search-result').html(data);
                        },
                        error: function() { }
                    });
                }
            });
            
        }else{
            alert('Укажите предыдущий товар!');
        }
    }
</script>
<table id="table-order-product" cellspacing="0" cellpadding="0" border="0">
    <tr class="title-table-order-product">
        <td style="width: 30px;">№</td>
        <td>Товар</td>
        <td style="width: 60px;">Заказано</td> 
        <td style="width: 60px;">Получено</td>
        <td style="width: 30px;"></td>      
    </tr>
<?php 
    $n = 1;
    foreach ($products as $product):
?> 
<script>
$(document).ready(function(){  
    $('#product-ord-quantity-<?=$product['id']?>').change(function(){
        changeProductOrdQuantityInDeliveryOrder('<?=$product['id']?>',$('#product-ord-quantity-<?=$product['id']?>').val());
    }).keyup(function() {
        changeProductOrdQuantityInDeliveryOrder('<?=$product['id']?>',$('#product-ord-quantity-<?=$product['id']?>').val());
    });

    $('#product-recd-quantity-<?=$product['id']?>').change(function(){
        changeProductRecdQuantityInDeliveryOrder('<?=$product['id']?>',$('#product-recd-quantity-<?=$product['id']?>').val());
    }).keyup(function(){
        changeProductRecdQuantityInDeliveryOrder('<?=$product['id']?>',$('#product-recd-quantity-<?=$product['id']?>').val());
    });
});
</script>    
    <tr>
        <td align="center" style="color:#ABABAB;">
            <?=$n++;?>            
        </td>
        <td> 
            <input type="text" id="product-<?=$product['id']?>" name="product" size="40" value="<?=htmlspecialchars($product['name']);?>" readonly style="cursor:no-drop; width: 100%;"> 
        </td>
        <td> 
            <input type="number" id="product-ord-quantity-<?=$product['id']?>" style="width: 100%;" value="<?=$product['ord']?>"> 
        </td>
        <td> 
            <input type="number" id="product-recd-quantity-<?=$product['id']?>" style="width: 100%;" value="<?=$product['recd']?>"> 
        </td>
        <td> 
            <img class="del-product-button" onclick="deleteProductFromDeliveryOrder('<?=$product['id']?>',event);" id="delete-product-<?=$product['id']?>" src="/image/minus_circle.ico">
        </td>
    </tr>
    
<?php endforeach; ?>  
</table>


  