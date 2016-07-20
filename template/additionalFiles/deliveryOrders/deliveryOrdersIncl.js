function createTmpDeliveryOrder(){
    $.ajax({
        url: '/template/additionalFiles/deliveryOrders/deliveryOrders.php',
        type: 'POST',
        async: false,
        data: {
            operation: "createTmpDeliveryOrder"
        },
    })
    .done(function(data) {
        console.log("data:", data);
        orderId = data;
        console.log("orderId4:", orderId);
        showDeliveryOrderProductList();
    })
    .fail(function() {
        console.log("error");
    });    
}

function deleteTmpDeliveryOrder(){
    $.ajax({
        url: '/template/additionalFiles/deliveryOrders/deliveryOrders.php',
        type: 'POST',
        data: {
            operation: "deleteTmpDeliveryOrder",
            orderId: orderId
        },
    })
    .done(function(data) {
        console.log(data);
    })
    .fail(function() {
        console.log("error");
    });    
}

function showDeliveryOrderProductList(){
    $('#order-product').load('/template/additionalFiles/deliveryOrders/deliveryOrderProducts.php?orderId='+orderId);   
}

$(document).ready(function() {
    if (orderId == "")
        createTmpDeliveryOrder();

    showDeliveryOrderProductList();
    
    $('.button-save-delivery-order').click(function() {
         $.ajax({
            url: '/template/additionalFiles/deliveryOrders/deliveryOrders.php',
            type: 'POST',
            data: {
                operation: "saveDeliveryOrder",
                orderId: orderId,
                statusId: $('.delivery-order-status').val(),
                ttn: $('.delivery-order-ttn').val(),
                comment: $('.delivery-order-comment').val()
            },
        })
        .done(function(data) {
            console.log(data);
        })
        .fail(function() {
            console.log("error");
        }); 
        orderId = "";
        CloseModal();
        getDeliveryOrdersList();
        $('.delete-delivery-order').fadeOut();
    });

    $('.close-modal').click(function(event) {
        CloseModal();
        deleteTmpDeliveryOrder();
    });
});