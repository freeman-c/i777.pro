var searchStr = '';
function getRecomendedProductsList(){
    $('.delete-product').fadeOut();
    $('#select-all-checkbox').removeAttr('checked');

    $.ajax({
        url: '/template/additionalFiles/recomendedProducts/recomendedProductsController.php',
        method: 'POST',
        data: {
            action : 'getRecomendedProductsList',
        },
        beforeSend: function() {
            searchStr = $('.dataTables_filter').find($("input[type='text']")).val();
            WaitingBarShow('Обработка запроса...');
        },
        success: function(data){
            // console.log(data);

            $(".recomended-products-table tbody tr").remove();
            
            recomendedProductsTable.fnClearTable();
            recomendedProductsTable.fnDraw();
            recomendedProductsTable.fnDestroy();

            $(".recomended-products-table tbody").append(data);

            recomendedProductsTable = $('.recomended-products-table').dataTable({
                "aLengthMenu": [ 10,25,50,100,200,500,1000 ],
                "iDisplayLength": 10,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

            // $('.recomended-products-table tbody tr').dblclick(function(event) {
            //     $(this).find('.option-button').click();
            // });  

        },
        complete: function(){
            WaitingBarHide();
            $('.dataTables_filter').find($("input[type='text']")).val(searchStr).keyup();
        }
    }); 
}


function editRecomendedProducts(productId, productName){
    var title = productName+': рекомендуемые товары';
    var content = '<div id="box-edit-recomended-product"></div>';    
    modal(title,content);
    $('#box-edit-recomended-product').load('/template/include/recomendedProducts.php?productId='+productId);
}

var recomendedProductsTable;
$(document).ready(function(){

    recomendedProductsTable = $('.recomended-products-table').dataTable({
                "aLengthMenu": [ 2,10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

    getRecomendedProductsList();
});   