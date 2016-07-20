<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$products = getProductsOrder($_GET['order_id']);

error_reporting(0);
session_start();
$order_id = $_GET['order_id'];

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
    input[name="price"],input[name="quantity"],input[name="total_price"]{
        text-align: right;
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
        max-height: 120px;
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
//     $(document).ready(function(){
//         alert('<?=$_GET['order_id']?>');

// });
    function Product_Order(){
        <?php !empty($_GET['order_id']) ? $order_id = $_GET['order_id'] : $order_id = $_SESSION['user']['new_order']; ?>
        $('#order-product').load('/template/include/order_product.php?order_id=<?=$order_id?>');   
    }

    function getRecomendedProductList(){
        <?php !empty($_GET['order_id']) ? $order_id = $_GET['order_id'] : $order_id = $_SESSION['user']['new_order']; ?>
        $('#recomended-product-container').load('/template/include/recomended_product_in_order.php?order_id=<?=$order_id?>');
    }
    
    function Add_Data_OrderProduct(product_id,price,quantity){    
    $.ajax({
        type: "POST",
        url: "/modules/update_data_order_product.php",
        data: {
            order_id:'<?=$_GET['order_id']?>',
            product_id:product_id,
            price:price,
            quantity:quantity,
            op:'add'
        },
        success: function(data){ 
            Product_Order();
            getRecomendedProductList();
            MessageTray('Информация в заказе обновлена.');
        },
        error: function() { alert('Ошибка ajax(update)! cod: add_data_order_product.php'); }
    });
}
function Update_Data_OrderProduct(id,price,quantity){
    $.ajax({
        type: "POST",
        url: "/modules/update_data_order_product.php",
        data: {
            order_id:'<?=$_GET['order_id']?>',
            id:id,
            price:price,
            quantity:quantity,
            op:'update'
        },
        success: function(data){ 
            MessageTray('Информация в заказе обновлена.');
        },
        error: function() { alert('Ошибка ajax(update)! cod: update_data_order_product.php'); }
    });
}

function Update_Data_OrderProduct_Status(id,status_buy){
    $.ajax({
        type: "POST",
        url: "/modules/update_data_order_product.php",
        data: {
            order_id:'<?=$_GET['order_id']?>',
            id:id,
            status_buy:status_buy,
            op:'updatestatus'
        },
        beforeSend: function(){
            WaitingBarShow('Добавление товара в заказ...');
        },
        success: function(data){ 
            WaitingBarHide();
            MessageTray('Информация в заказе обновлена.');
        },
        error: function() { alert('Ошибка ajax(update)! cod: update_data_order_product.php'); }
    });
}

function Delete_Data_OrderProduct(id){
    $.ajax({
        type: "POST",
        url: "/modules/update_data_order_product.php",
        data: {
            order_id:'<?=$_GET['order_id']?>',
            id:id,
            //price:price,
            //quantity:quantity,
            op:'delete'
        },
        beforeSend: function(){
            WaitingBarShow('Удаление товара в заказе...');
        },
        success: function(data){ 
            WaitingBarHide();
            MessageTray('Информация в заказе обновлена.');
            getRecomendedProductList();

        },
        error: function() { alert('Ошибка ajax(update)! cod: delete_data_order_product.php'); }
    });
}
    
    function close_add_product(){
        $('#overlay-order-product').remove();
        $('#add-product-to-order').show();
    }
    function del_row_product(id,event){
        t=event.target||event.srcElement;
        Delete_Data_OrderProduct(id);           
        $(t).parent().parent().remove();
        var total=0;
            $('input[name="total_price"]').each(function(){
                total += parseFloat($(this).val());
            });
        $('input[name="total"]').val((total).toFixed(2)); 
    }
       
    function insert_search_product(id,product_id,name,price){
        $('#product').attr('id','product-'+id+'').val(name);
        $('#product-price').attr('id','product-price-'+id+'').val(price);
        $('#product-quantity').attr('id','product-quantity-'+id+'').val(1);
        $('#product-total-price').attr('id','product-total-price-'+id+'');
        $('#delete-product').attr("onclick","del_row_product('"+id+"',event);");
        $('#delete-product').attr('id','delete-product-'+id+'');
            $total_price = price * 1;
            $('#product-total-price-'+id+'').val( ($total_price).toFixed(2));   
            var total=0;
            $('input[name="total_price"]').each(function(){
                total += parseFloat($(this).val());
            });
            $('input[name="total"]').val((total).toFixed(2));
        $('#search-result').remove();
        $('#search-box').attr('id','serch-box-'+id+'');        
        var quantity = 1;
        Add_Data_OrderProduct(product_id,price,quantity);        
    }
    
    function CheckNumericTR(){
        var count_row = $('#table-order-product tr').length;
        var n = (count_row - 1) + 1;
        if($("#product").length === 0){            
            $('#table-order-product').append('<tr>'+
                    '<td align="center" style="color:#ABABAB;">'+n+'</td>'+
                    '<td> <img src="/image/help-icons.png" class="tooltip" style="margin:-2px 0px -2px 2px; cursor: help;"> </td>'+
                    '<td>'+                         
                        '<div id="search-box">'+
                            '<input type="text" id="product" name="product" size="40" placeholder="Выберите товар...начните писать">'+
                            '<div id="search-result"></div>'+
                        '</div>'+
                    '</td>'+
                    '<td></td>'+
                    '<td> <input type="text" id="product-price" name="price" size="6" value=""> </td>'+
                    '<td> <input type="text" id="product-quantity" name="quantity" size="2" value=""> </td>'+
                    '<td> <input type="text" id="product-total-price" name="total_price" size="7" readonly style="cursor:no-drop; font-weight: bold;"></td>'+
                    '<td> <select id="product-status_buy" name="status_buy"><option value="1">ОП</option><option value="2">ДП</option><option value="3">ПП</option></select> </td>'+
                    '<td> <img class="del-product-button" onclick="del_row_product(\'\',event);" id="delete-product" src="/image/minus_circle.ico"> </td>'+
                '</tr>');
            $('#product').focus();
            
            $('#product').keyup(function(){
                if( $(this).val().length > 1 ){
                var ProductName = $(this).val();    
                    $.ajax({
                        type: "POST",
                        url: "/modules/search_product.php",
                        data: {
                            ProductName:ProductName
                        },
                        beforeSend: function(){
                            $('#search-result').show();
                            $('#search-result').html('<img src="/image/loader_big.gif">');
                        },
                        success: function(data){ 
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
<table id="table-order-product" cellspacing="0" cellpadding="0" border="0">
    <tr class="title-table-order-product">
        <td>№</td>
        <td>?</td>
        <td>Товар</td>
        <td>Скл</td>
        <td>Цена</td>
        <td>К-во</td>        
        <td>Итого</td>
        <td width="20px"></td>
        <td></td>
    </tr>
<?php 
    $n = 1;
    foreach ($products as $product):
        $tovar = getProduct($product['product_id']);
        $tovar['reserve'] = getProductStock($product['product_id']);

        if ($tovar['reserve'] < 0)
            $tovar['reserve'] = "НД";
?> 
<script>
$(document).ready(function(){
    $price = $('#product-price-<?=$product['id']?>').val();
    $quantity = $('#product-quantity-<?=$product['id']?>').val();
    $total_price = $price * $quantity;
    $('#product-total-price-<?=$product['id']?>').val( ($total_price).toFixed(2));
    
    $('#product-price-<?=$product['id']?>').keyup(function(){
        $price = $('#product-price-<?=$product['id']?>').val();
        $quantity = $('#product-quantity-<?=$product['id']?>').val();
        $total_price = $price * $quantity;
        $('#product-total-price-<?=$product['id']?>').val( ($total_price).toFixed(2));
            var total=0;
            $('input[name="total_price"]').each(function(){
                total += parseFloat($(this).val());
            });
            $('input[name="total"]').val((total).toFixed(2));
            $cena = $('#product-price-<?=$product['id']?>').val();
            $kolishestvo = $('#product-quantity-<?=$product['id']?>').val();            
            Update_Data_OrderProduct('<?=$product['id']?>',$cena,$kolishestvo);
        
    });
    $('#product-quantity-<?=$product['id']?>').keyup(function(){
        $price = $('#product-price-<?=$product['id']?>').val();
        $quantity = $('#product-quantity-<?=$product['id']?>').val();
        $total_price = $price * $quantity;
        $('#product-total-price-<?=$product['id']?>').val(($total_price).toFixed(2)); 
            var total=0;
            $('input[name="total_price"]').each(function(){
                total += parseFloat($(this).val());
            });
            $('input[name="total"]').val((total).toFixed(2));            
            $cena = $('#product-price-<?=$product['id']?>').val();
            $kolishestvo = $('#product-quantity-<?=$product['id']?>').val();            
            Update_Data_OrderProduct('<?=$product['id']?>',$cena,$kolishestvo);
    });
    $('#product-status_buy-<?=$product['id']?>').change(function(){
        $status_buy = $('#product-status_buy-<?=$product['id']?>').val(); 
            Update_Data_OrderProduct_Status('<?=$product['id']?>',$status_buy);
        
    });

    $('.tooltip').tooltip({
        track: false, //true включает "привязку" подсказки к движущемуся указателю мыши
        content: function() {
            return $(this).attr('title');
        }        
    });

function getPosition(e) {
  var posx = 0;
  var posy = 0;
  if (!e) var e = window.event;
  if (e.pageX || e.pageY) {
    posx = e.pageX;
    posy = e.pageY;
  }
  else if (e.clientX || e.clientY) {
    posx = e.clientX + document.body.scrollLeft
      + document.documentElement.scrollLeft;
    posy = e.clientY + document.body.scrollTop
      + document.documentElement.scrollTop;
  }
  return {
    x: posx,
    y: posy
  }
}

    function showTooltip(e, product_id){
        var x = getPosition(e).x;
        var y = getPosition(e).y;

        $('.div-tooltip').css('top', y+'px');
        $('.div-tooltip').css('left', x+'px');
        $('.div-tooltip').fadeIn();

        $('.tooltip-text').val($('#'+product_id+'-full-description').val());
        $('.tooltip-link').attr('href', $('#'+product_id+'-link').val());
        $('.tooltip-link').text($('#'+product_id+'-link').val());


        $('.tooltip-img').attr('src', ''+'/image/products/'+$('#'+product_id+'-image').val());
    }

    $('.tooltip').click(function(e){
        var product_id = $(this).attr('id');
        product_id = product_id.substring(0, product_id.indexOf('-'));

        setTimeout(showTooltip(e, product_id), 1000);
    });

    $(function(){
        $(document).click(function(event) {
            if ($(event.target).closest(".div-tooltip").length || $(event.target).closest(".tooltip").length) return;
            $('.div-tooltip').fadeOut();
            event.stopPropagation();
        });
    });

});
</script>    
    <tr>
        <td align="center" style="color:#ABABAB;"><?=$n++;?></td>
        <td> <img src="/image/help-icons.png" id="<?=$tovar['id']?>-tooltip-btn" class="tooltip"  style="margin:-2px 0px -2px 2px; cursor: help;"> </td>
        <td> 
            <input type="text" id="product-<?=$product['id']?>" name="product" size="40" value="<?=htmlspecialchars($tovar['name']);?>" readonly style="cursor:no-drop; width: 200px;"> 
        </td>
        <td>
            <input type="text" size="3" value="<?=htmlspecialchars($tovar['reserve']);?>" readonly style="cursor:no-drop; width: 35px; text-align: center;"> 
        </td>
        <td> <input type="text" id="product-price-<?=$product['id']?>" name="price" size="6" value="<?=$product['price']?>"></td>
        <td> <input type="text" id="product-quantity-<?=$product['id']?>" name="quantity" size="2" value="<?=$product['quantity']?>"> </td>
        <td> <input type="text" id="product-total-price-<?=$product['id']?>" name="total_price" size="7" readonly style="cursor:no-drop; font-weight: bold;"></td>
        <td style="padding: 0 !important;">
            <select style="width: 52px;" id="product-status_buy-<?=$product['id']?>" name="status_buy"><?php
             if($product['status_buy']==1){  
                    echo '<option value="1">ОП</option>';
              }
              elseif ($product['status_buy']==2) {  
                 echo '<option value="2">ДП</option>';
              }
              elseif ($product['status_buy']==3) { 
                 echo '<option value="3">ПП</option>';
              } ?>
              <option value="" disabled>----</option>
              <option value="1">ОП</option>
              <option value="2">ДП</option>
              <option value="3">ПП</option>
              </select>
        </td>
        <td> <img class="del-product-button" onclick="del_row_product('<?=$product['id']?>',event);" id="delete-product-<?=$product['id']?>" src="/image/minus_circle.ico"> </td>
        <td style="border: 0; padding: 0px !important;">
            <div style="display: none;">
                <textarea id="<?=$tovar['id']?>-full-description"><?=$tovar['full_description']?></textarea>                
            </div> 
        </td>
        <td style="border: 0; padding: 0px !important;">
            <div style="display: none;">
                <textarea id="<?=$tovar['id']?>-link"><?=$tovar['link']?></textarea>                
            </div> 
        </td>
        <td style="border: 0; padding: 0px !important;">
            <div style="display: none;">
                <textarea id="<?=$tovar['id']?>-image"><?=$tovar['image']?></textarea>                
            </div> 
        </td>
    </tr>
    
<?php endforeach; ?>  
</table>


  