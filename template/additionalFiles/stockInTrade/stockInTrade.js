var searchStr = '';
var currentProductId;
var currentProductStock;
function getStockInTradeList(){
    WaitingBarShow("Обработка запроса...");

    $.ajax({
        url: '/template/additionalFiles/stockInTrade/stockInTrade.php',
        method: 'POST',
        data: {
            operation: 'getStockInTradeList',
        },
        beforeSend: function() {
            $('.navigation').hide();   
        },
        success: function(data){
            // data = $.parseJSON(data);
            // console.log("data:", data);
            $(".stock-in-trade-table tbody tr").remove();
            
            warehouseProductsTable.fnClearTable();
            warehouseProductsTable.fnDraw();
            warehouseProductsTable.fnDestroy();

            $(".stock-in-trade-table tbody").append(data);

            $('.stock-in-trade-table tbody tr').dblclick(function(event) { 
                $('#product-reserve-'+currentProductId).fadeIn();
                $('.product-reserve-input').hide();
                $('.product-reserve-button').hide();

                currentProductId = $(this).find('td.product-id')[0].innerText;
                console.log("currentProductId: ", currentProductId);
                currentProductStock = $('.product-reserve-input-'+currentProductId).val() != "" ? $('.product-reserve-input-'+currentProductId).val() : 0;

                $('#product-reserve-'+currentProductId).hide();
                $('#product-reserve-input-'+currentProductId).fadeIn();
                $('#product-reserve-button-save-'+currentProductId).fadeIn();
                $('#product-reserve-button-cancel-'+currentProductId).fadeIn();

            });

            $('.product-reserve-button-save').click(function(event) {
                $.ajax({
                    url: '/template/additionalFiles/stockInTrade/stockInTrade.php',
                    type: 'POST',
                    data: {
                        operation: 'manualChangeProductStock',
                        productId: currentProductId,
                        reserve: $('#product-reserve-input-'+currentProductId).val()
                    },
                })
                .done(function(response) {
                    // console.log(response);

                    document.getElementById("product-reserve-"+currentProductId).innerHTML = $('#product-reserve-input-'+currentProductId).val();
                    $('#product-reserve-'+currentProductId).fadeIn();
                    $('.product-reserve-input').hide();
                    $('.product-reserve-button').hide();
                });
                
            });

            $('.product-reserve-button-cancel').click(function(event) {
                    $('#product-reserve-'+currentProductId).fadeIn();
                    $('.product-reserve-input').hide();
                    $('.product-reserve-button').hide();                
            });

            warehouseProductsTable = $('.stock-in-trade-table').dataTable({
                "aLengthMenu": [ 10,25,50,100,200,500,1000 ],
                "iDisplayLength": 10,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});
        },
        complete: function(){
            WaitingBarHide();
            $('.dataTables_filter').find($("input[type='text']")).val(searchStr).keyup();
        }
    }); 
}


var warehouseProductsTable;
$(document).ready(function(){

    warehouseProductsTable = $('.stock-in-trade-table').dataTable({
                "aLengthMenu": [ 10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

    getStockInTradeList(); 

    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            $('.product-reserve-input').hide();
            $('.product-reserve-button').hide();
            $('#product-reserve-'+currentProductId).fadeIn();
    }

    $('.update-stock-in-trade').click(function(event) {
        getStockInTradeList();
        searchStr = $('.dataTables_filter').find($("input[type='text']")).val();
    });
});
});   