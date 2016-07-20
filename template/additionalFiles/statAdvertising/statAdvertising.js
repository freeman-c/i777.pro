var searchStr = '';
function getStatAdvertising(){

    $.ajax({
        url: '/template/additionalFiles/statAdvertising/statAdvertising.php',
        method: 'POST',
        data: {
            operation: 'getStatAdvertising',
            dateFrom: $('#between-start-ad').val(),
            dateTo: $('#between-end-ad').val(),
            city: $('#city').val()
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...'); 
        },
        success: function(data){
            response = $.parseJSON(data);
            console.log(response);

            $(".stat-advertising-table tbody tr").remove();
            $(".stat-total-table tbody tr").remove();
            
            statAdvertisingTable.fnClearTable();
            statAdvertisingTable.fnDraw();
            statAdvertisingTable.fnDestroy();

            if (response.success && response.table != "")
                $(".stat-advertising-table tbody").append(response.table);

            if (response.success)
                $(".stat-total-table tbody").append(response.total);
            
            statAdvertisingTable = $('.stat-advertising-table').dataTable({
                "aLengthMenu": [ 10,25,50,100,200,500,1000, 10000 ],
                "iDisplayLength": 10000,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});
        },
        complete: function(){
            WaitingBarHide();
            $('.dataTables_filter').find($("input[type='text']")).val(searchStr).keyup();
        }
    }); 
}


var statAdvertisingTable;
$(document).ready(function(){

    statAdvertisingTable = $('.stat-advertising-table').dataTable();

    $('#city').chosen({no_result_text: 'Не удалось найти город', allow_single_deselect: true});

    $('#city').change(function(){
        getStatAdvertising();
    });

    $('.dateInp').datepicker({
        onSelect: function () {
            getStatAdvertising();
        }
    });

    getStatAdvertising();
});   