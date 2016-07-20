<script>
    function Add_Data_OrderProduct(product_id, recomended_product_id){ 
    $.ajax({
        type: "POST",
        url: "/modules/update_recomended_products.php",
        data: {
            product_id:product_id,
            recomended_product_id:recomended_product_id,
            op:'add'
        },
        beforeSend: function(){
            //alert('<?=$order_id?>, '+product_id+', '+price+', '+quantity+'' );
            WaitingBarShow('Добавление товара...');
        },
        success: function(data){ 
            // alert(data);
            WaitingBarHide();
            MessageTray('Информация обновлена.');
            getRecomendedProductList();
        },
        error: function() { alert('Ошибка ajax(update)! cod: add_data_order_product.php'); }
    });
}

function Delete_Data_OrderProduct(id){
    $.ajax({
        type: "POST",
        url: "/modules/update_recomended_products.php",
        data: {
            id:id,
            op:'delete'
        },
        beforeSend: function(){
            WaitingBarShow('Удаление товара...');
        },
        success: function(data){ 
            //alert(data);
            WaitingBarHide();
            MessageTray('Информация обновлена.');
        }
    });
}
    
    function close_add_product(){
        $('#overlay-order-product').remove();
        $('#add-product-to-order').show();
    }

    function del_row_product(order_id,event){
        t=event.target||event.srcElement;
        Delete_Data_OrderProduct(order_id);           
        $(t).parent().parent().remove();
    }
       
    function insert_search_product(id,recomended_product_id,name){
        $('#product').attr('id','product-'+id+'').val(name);
        $('#delete-product').attr("onclick","del_row_product('"+id+"',event);");
        $('#delete-product').attr('id','delete-product-'+id+'');
        $('#search-result').remove();
        $('#search-box').attr('id','serch-box-'+id+'');        
        Add_Data_OrderProduct('<?=$_GET['product_id']?>', recomended_product_id);        
    }
    
    function addRecomendedProduct(){
        var count_row = $('#table-order-product tr').length;
        var n = (count_row - 1) + 1;
        if($("#product").length === 0){            
            $('#table-order-product').append('<tr>'+
                    '<td align="center" style="color:#ABABAB;">'+n+'</td>'+
                    '<td>'+                         
                        '<div id="search-box">'+
                            '<input type="text" id="product" name="product" size="40" placeholder="Выберите товар...начните писать">'+
                            '<div id="search-result"></div>'+
                        '</div>'+
                    '</td>'+
                    '<td> <img class="del-product-button" onclick="del_row_product(\'\',event);" id="delete-product" src="/image/minus_circle.ico"> </td>'+
                '</tr>');
            $('#product').focus();
            
            $('#product').keyup(function(){
                if( $(this).val().length > 1 ){
                    //alert('Go search!');
                var ProductName = $(this).val();    
                    $.ajax({
                        type: "POST",
                        url: "/modules/search_product.php",
                        data: {
                            ProductName:ProductName
                        },
                        beforeSend: function(){
                            $('#search-result').show();
                            $('#search-result').html('<img src="<?=SITE_URL?>/image/loader_big.gif">');
                        },
                        success: function(data){ 
                            //alert(data);
                            $('#search-result').html();
                            $('#search-result').html(data);
                        },
                        error: function() { alert('Ошибка ajax! cod: search_product.php'); }
                    });
                }
                if( $(this).val().length < 2 ){
                    $('#search-result').hide();
                }
            });
            
        }else{
            alert('Укажите товар № '+(n - 1)+'!');
            }
    }
</script>

<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$recomendedProductsList = getRecomendedProductList($_GET['product_id']);

?>

<table id="table-order-product" cellspacing="0" cellpadding="0" border="0">
    <tr class="title-table-order-product">
        <td>№</td>
        <td>Товар</td>
        <th></th>
    </tr>
<?php 
    $n = 1;
    foreach ($recomendedProductsList as $recomendedProduct):
        $product = getProduct($recomendedProduct['recomended_product_id']);
?> 

    <tr>
        <td align="center">
            <?=$n++;?>
        </td>
        <td> 
            <input type="text" id="product-<?=$product['id']?>" name="product" size="40" value="<?=htmlspecialchars($product['name']);?>" readonly style="cursor:no-drop; width: 200px;"> 
        </td>
        
        <td> <img class="del-product-button" onclick="del_row_product('<?=$recomendedProduct['id']?>',event);" id="delete-product-<?=$product['id']?>" src="/image/minus_circle.ico"> 
        </td>
    </tr>
    
<?php endforeach; ?>  
</table>


  