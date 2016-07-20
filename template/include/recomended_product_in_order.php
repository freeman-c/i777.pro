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

<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';

$recomendedProductsList = getRecomendedProductListInOrder($_GET['order_id']);

?>

<table id="table-order-product" cellspacing="0" cellpadding="0" border="0">
    <tr class="title-table-order-product">
        <td></td>
        <td>?</td>
        <td>Товар</td>
        <td>Скл</td>
        <td>$</td>
    </tr>
<?php 
    $n = 1;
    foreach ($recomendedProductsList as $recomendedProduct):
        $product = getProduct($recomendedProduct['recomended_product_id']);
        $product['reserve'] = getProductStock($product['id']);
        
        if(empty($product['reserve']))
            $product['reserve'] = "НД";
        if(empty($product['bonus']))
            $product['bonus'] = "НД";
?> 
<script>
$(document).ready(function(){

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


        $('.tooltip-img').attr('src', '<?=SITE_URL?>'+'/image/products/'+$('#'+product_id+'-image').val());
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

function addRecomendedProductToOrder(recomendedProductId){
    $.ajax({
        url : "/modules/add_recomended_product_to_order.php",
        type : "POST",
        data : {
            orderId: "<?=$_GET['order_id']?>",
            recomendedProductId: recomendedProductId
        },
        beforeSend: function(){
            WaitingBarShow('Добавление товара в заказ...');
        },
        success: function(response){
            response = JSON.parse(response);
            if (response.success == 'true'){
                var total = parseFloat($('input[name="total"]').val());
                total += parseFloat(response.price) * parseFloat(response.quantity);
                $('input[name="total"]').val((total).toFixed(2));
                Product_Order();
                getRecomendedProductList();
                WaitingBarHide();
                MessageTray('Информация в заказе обновлена');
            }
        }
    });
}
</script>    
    <tr>
        <td> 
            <img src="/image/plus_circle.ico" onclick="addRecomendedProductToOrder('<?=$recomendedProduct['recomended_product_id']?>');" id="<?=$product['id']?>-tooltip-btn" style="margin: 0px 0px -4px 0px;">
        </td>
        <td> 
            <img src="/image/help-icons.png" id="<?=$product['id']?>-tooltip-btn" class="tooltip"  style="margin:-2px 0px -2px 2px; cursor: help;"> 
        </td>
        <td> 
            <input type="text" id="product-<?=$product['id']?>" name="product" size="40" value="<?=htmlspecialchars($product['name']);?>" readonly style="cursor:no-drop; width: 200px;"> 
        </td>
        <td>
            <input type="text" size="3" value="<?=htmlspecialchars($product['reserve']);?>" readonly style="cursor:no-drop; width: 35px; text-align: center;"> 
        </td>
        <td>
            <input type="text" size="3" value="<?=htmlspecialchars($product['bonus']);?>" readonly style="cursor:no-drop; width: 35px; text-align: center;"> 
        </td>

<!-- следующие три поля нужны для подсказки, на экран не выводяться -->
        <td style="border: 0; padding: 0px !important;">
            <div style="display: none;">
                <textarea id="<?=$product['id']?>-full-description"><?=$product['full_description']?></textarea>                
            </div> 
        </td>

        <td style="border: 0; padding: 0px !important;">
            <div style="display: none;">
                <textarea id="<?=$product['id']?>-link"><?=$product['link']?></textarea>                
            </div> 
        </td>

        <td style="border: 0; padding: 0px !important;">
            <div style="display: none;">
                <textarea id="<?=$product['id']?>-image"><?=$product['image']?></textarea>                
            </div> 
        </td>
    </tr>
    
<?php endforeach; ?>  
</table>


  
