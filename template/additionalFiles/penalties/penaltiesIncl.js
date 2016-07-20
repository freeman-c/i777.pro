$(document).ready(function() {
    $('.button-save-penalty').click(function() {
        if ($('#penalty-id').val() == "")
            operation = "addPenalty";
        else
            operation = "editPenalty";
        var only_profit = '';
        if ($('.only_profit').prop('checked') == true)
            only_profit = 'on';
        $.ajax({
            url: '/template/additionalFiles/penalties/penaltiesController.php',
            type: 'POST',
            data: {
                operation: operation,
                id: $('#penalty-id').val(),
                name: $('.name').val(),
                price: $('.price').val(),
                description: $('.description').val()
            },
        })
        .always(function(response) {
            console.log(response);
        });
        searchStr = $('.dataTables_filter').find($("input[type='text']")).val();
        CloseModal();
        getPenaltiesList();        
        $('.delete-penalty').fadeOut();
    });

    $('.close-modal').click(function(event) {
        CloseModal();
    });
});