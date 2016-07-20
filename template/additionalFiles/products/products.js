var searchStr = '';
function getProductsList(){
    $('.delete-product').fadeOut();
    $('#select-all-checkbox').removeAttr('checked');

    $.ajax({
        url: '/template/additionalFiles/products/products.php',
        method: 'POST',
        data: {
            operation: 'getProductsList',
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...');
            $('.navigation').hide();   
        },
        success: function(data){
            // console.log(data);

            $(".product-table tbody tr").remove();
            
            productTable.fnClearTable();
            productTable.fnDraw();
            productTable.fnDestroy();

            $(".product-table tbody").append(data);

            productTable = $('.product-table').dataTable({
                "aLengthMenu": [ 10,25,50,100,200,500,1000 ],
                "iDisplayLength": 10,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

            $('.fg-toolbar').click(function(){
                setTableListListeners();
            }).keyup(function(){
                setTableListListeners();
            });

            $('.selected').unbind('click');
            $('.selected').bind('click', function(){ 
                SELECT_SHIFT(); 
            });

            $('#table-list tbody tr').hover(function(){
                $(this).find(".option-button").show();
            }, function() {
                $(this).find(".option-button").hide();
            });  
        },
        complete: function(){
            WaitingBarHide();
            $('.dataTables_filter').find($("input[type='text']")).val(searchStr).keyup();
        }
    }); 
}

function addProduct(){
    var title = 'Добавление товара';
    var content = '<div id="box_add_product"></div>';    
    modal(title,content);
    $('#box_add_product').load('/template/include/products.php');
}

function editProduct(id){
    var title = 'Редактирование товара';
    var content = '<div id="box_edit_product"></div>';    
    modal(title,content);
    $('#box_edit_product').load('/template/include/products.php?id='+id);
}

function deleteProduct(){
    if(confirm('Удаляем выделенные элементы?')){  
        $.ajax({
            url: "/template/additionalFiles/products/products.php",
            method: 'POST',
            data : $('#form-product').serialize()+'&operation=deleteProduct',
            beforeSend: function(){
                WaitingBarShow('Удаление товаров...');
            },
            success: function(response){
                WaitingBarHide();
                response = $.parseJSON(response);
                if (response.success == 'false')
                    alert('Error! '+response.error);
                getProductsList();                
            }
        });
    }
    $('#select-all-checkbox').removeAttr('checked');
}

function changeProductState(productId, state){
    $.ajax({
        url: '/template/additionalFiles/products/products.php',
        type: 'POST',
        data: {
            operation: 'changeProductState',
            productId: productId,
            state: state 
        },
        beforeSend: function(){
            searchStr = $('.dataTables_filter').find($("input[type='text']")).val();
        },
        success: function(){
            WaitingBarHide();
            getProductsList();                
            $('.dataTables_filter').find($("input[type='text']")).val(searchStr).keyup();
        }
    });    
}

function changeProductOnlyProfitState(productId, state){
    $.ajax({
        url: '/template/additionalFiles/products/products.php',
        type: 'POST',
        data: {
            operation: 'changeProductOnlyProfitState',
            productId: productId,
            state: state 
        },
        beforeSend: function(){
            searchStr = $('.dataTables_filter').find($("input[type='text']")).val();
        },
        success: function(){
            getProductsList();                
            $('.dataTables_filter').find($("input[type='text']")).val(searchStr).keyup();
        }
    });    
}

var productTable;
$(document).ready(function(){

    productTable = $('.product-table').dataTable({
                "aLengthMenu": [ 2,10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

    getProductsList();

    $('#table-list tbody td').click(function(event){
        $('#table-list tbody tr').removeClass('selected-row-in-table');
        t=event.target||event.srcElement;
        $(t).parent('tr').addClass('selected-row-in-table');
    });   

    $('.add-product').click(function() {
        addProduct();
    });

    $('.edit-product').click(function() {
        editProduct();
    });

    $('.delete-product').click(function() {
        deleteProduct();
        getProductsList();
    });
});   