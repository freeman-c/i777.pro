$(document).ready(function() {
    $('.button-save-ban-ip-rule').click(function() {
        if ($('#ban-ip-id').val() == "")
            operation = "addBanIpRule";
        else
            operation = "editBanIpRule";
        $.ajax({
            url: '/template/additionalFiles/banIp/banIp.php',
            type: 'POST',
            data: {
                operation: operation,
                id: $('#ban-ip-id').val(),
                ip: $('.ip').val(),
                reason: $('.reason').val(),
            },
        })
        .always(function(response) {
            console.log(response);
        });
        
        CloseModal();
        getBanIpRulesList();
        $('.delete-ban-ip-rule').fadeOut();
    });

    $('.close-modal').click(function(event) {
        CloseModal();
    });
});