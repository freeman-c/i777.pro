var searchStr = '';
function getStatProducts(){

    $.ajax({
        url: '/template/additionalFiles/statProductsTotal/statProductsTotal.php',
        method: 'POST',
        data: {
            operation: 'getStatProductsTotal',
            dateFrom: $('#between-start-pr').val(),
            dateTo: $('#between-end-pr').val(),
            city: $('#city').val()
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...'); 
        },
        success: function(data){
            response = $.parseJSON(data);
            $(".stat-total-table tbody tr").remove();
            statProductTable.fnClearTable();
            statProductTable.fnDraw();
            statProductTable.fnDestroy();
            
            for(var key in response.total)
                addTotalRow(key, response.total[key]);

            statProductTable = $('.stat-total-table').dataTable({
                "aLengthMenu": [ 10,25,50,100,200,500,1000, 10000 ],
                "iDisplayLength": 10000,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"
            });
        },
        complete: function(){
            WaitingBarHide();
            $('.dataTables_filter').find($("input[type='text']")).val(searchStr).keyup();
        }
    }); 
}

function addTotalRow(date, row){
    console.log(date, row);
    var row = $("<tr>").append(
        $("<td>").text(date),
        $("<td>").text(row.requestCount),
        $("<td>").text(row.countOfNew),
        $("<td>").text(row.orderCount),
        $("<td>").text(row.cv2),
        $("<td>").text(row.saledProductCount),
        $("<td>").text(row.avgCheck),
        $("<td>").text(row.addAvgCheck),
        $("<td>").text(row.profit),
        $("<td>").text(row.avgProfit)
    );
    
    $(".stat-total-table").append(row);
}

var statProductTable;
$(document).ready(function(){

    $('#city').chosen({no_result_text: 'Не удалось найти город', allow_single_deselect: true});

    $('#city').change(function(){
        getStatProducts();
    });

    statProductTable = $('.stat-total-table').dataTable();

        $('.prStatDateInp').datepicker({
            onSelect: function () {
                getStatProducts();
            }
        });

        $('.prSelect').chosen();
        $('.prSelect').change(function(){
            getStatProducts();
        });

    getStatProducts();    
});   