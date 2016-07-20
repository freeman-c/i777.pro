function getDeliveryOrdersList(){
    $('.delete-delivery-orders').fadeOut();
    $('#select-all-checkbox').removeAttr('checked');

    var status = $('.tab-status-active').attr('id');  
    status = status.substr(status.indexOf('-')+1);
    console.log(status);

    $.ajax({
        url: '/template/additionalFiles/deliveryOrders/deliveryOrders.php',
        method: 'POST',
        data: {
            operation: 'getDeliveryOrdersList',
            status: status
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...');
            proces = true;  
        },
        success: function(data){
            // console.log(data);

            $(".delivery-orders-table tbody tr").remove();
            
            deliveryOrdersTable.fnClearTable();
            deliveryOrdersTable.fnDraw();
            deliveryOrdersTable.fnDestroy();

            $(".delivery-orders-table tbody").append(data);

            $('.selected').unbind('click');
            $('.selected').bind('click', function(){ 
                SELECT_SHIFT(); 
            });

            $('#table-list tbody tr').hover(function(){
                $(this).find(".option-button").show();
            }, function() {
                $(this).find(".option-button").hide();
            });  

            deliveryOrdersTable = $('.delivery-orders-table').dataTable({
                "aLengthMenu": [ 10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

        },
        complete: function(){
            proces = false;                                                     
            WaitingBarHide();
        }
    }); 
}

function addDeliveryOrder(){
    var title = 'Новый заказ';
    var content = '<div id="box_add_delivery_order"></div>';    
    modal(title,content);
    $('#box_add_delivery_order').load('/template/include/deliveryOrders.php');
}

function editDeliveryOrder(id){
    var title = 'Редактирование заказа';
    var content = '<div id="box_edit_delivery_order"></div>';    
    modal(title,content);
    $('#box_edit_delivery_order').load('/template/include/deliveryOrders.php?orderId='+id);
}

function deleteDeliveryOrders(){
    if(confirm('Удаляем выделенные элементы?')){  
        $.ajax({
            url: "/template/additionalFiles/deliveryOrders/deliveryOrders.php",
            method: 'POST',
            data : $('#form-delivery-orders').serialize()+'&operation=deleteDeliveryOrders',
            beforeSend: function(){
                WaitingBarShow('Удаление заказов...');
            },
            success: function(response){
                console.log("response:", response);
                response = $.parseJSON(response);
                if (response.success == 'false')
                    alert('Error! '+response.error);
                getDeliveryOrdersList();                
            }
        });
    }
    $('#select-all-checkbox').removeAttr('checked');
}

var deliveryOrdersTable;
$(document).ready(function(){

	$('.but-tab').click(function(){
        page = 0;
        $('.tab-status-active').removeClass('tab-status-active');
        $(this).addClass('tab-status-active');
        getDeliveryOrdersList();
    });

    $("#status-1").click();

    deliveryOrdersTable = $('.delivery-orders-table').dataTable({
                "aLengthMenu": [ 2,10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

    getDeliveryOrdersList();

    $('.add-delivery-order').click(function() {
        addDeliveryOrder();
    });

    // $('.edit-ban-ip-rule').click(function() {
    //     editBanIpRule();
    // });

    $('.delete-delivery-orders').click(function() {
        deleteDeliveryOrders();
    });
});   