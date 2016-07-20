$(document).ready(function() {
    $('.button-save-product').click(function() {
        if ($('#product-id').val() == "")
            operation = "addProduct";
        else
            operation = "editProduct";
        var only_profit = '';
        if ($('.only_profit').prop('checked') == true)
            only_profit = 'on';
        $.ajax({
            url: '/template/additionalFiles/products/products.php',
            type: 'POST',
            data: {
                operation: operation,
                id: $('#product-id').val(),
                name: $('.name').val(),
                price: $('.price').val(),
                weight: $('.weight').val(),
                width: $('.width').val(),
                height: $('.height').val(),
                length: $('.length').val(),
                quantity: $('.quantity').val(),
                full_description: $('.full_description').val(),
                description: $('.description').val(),
                only_profit: only_profit,
                link: $('.link').val()
            },
        })
        .always(function(response) {
            console.log(response);
        });
        searchStr = $('.dataTables_filter').find($("input[type='text']")).val();
        CloseModal();
        getProductsList();        
        $('.delete-product').fadeOut();
    });

    $('.close-modal').click(function(event) {
        CloseModal();
    });
});