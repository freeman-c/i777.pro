$(document).ready(function() {
    $('.button-save-active-penalty').click(function() {
        
        if ($('#active-penalty-id').val() == "")
            operation = "addActivePenalty";
        else
            operation = "editActivePenalty";
        var only_profit = '';
        if ($('.only_profit').prop('checked') == true)
            only_profit = 'on';
        $.ajax({
            url: '/template/additionalFiles/activePenalties/activePenaltiesController.php',
            type: 'POST',
            data: {
                operation: operation,
                id: $('#active-penalty-id').val(),
                userId: $('#users').val(),
                penaltyId: $('#penalty').val(),
                creationDate: $('#penalty-date').val(),
                commentary: $('#commentary').val()
            },
        })
        .always(function(response) {
            console.log(response);
        });
        searchStr = $('.dataTables_filter').find($("input[type='text']")).val();
        CloseModal();
        getActivePenaltiesList();        
        $('.delete-active-penalty').fadeOut();
    });

    $('.penaltyDate').datepicker({
        onSelect: function () {
            getActivePenaltiesList();
        }
    });

    $('.close-modal').click(function(event) {
        CloseModal();
    });
});