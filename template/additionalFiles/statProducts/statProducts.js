var searchStr = '';
function getStatProducts(){

    var filters;
    
    if(!$('.checkbox-no-utm').prop('checked')) {
        filters = getFilters();
    }
    
    filters = JSON.stringify(filters);

    $.ajax({
        url: '/template/additionalFiles/statProducts/statProducts.php',
        method: 'POST',
        data: {
            operation: 'getStatProducts',
            dateFrom: $('#between-start-pr').val(),
            dateTo: $('#between-end-pr').val(),
            city: $('#city').val(),
            filters: filters,
            no_utm: $('.checkbox-no-utm').prop('checked')
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...'); 
        },
        success: function(data){
            response = $.parseJSON(data);
            console.log(response);
            $(".stat-products-table tbody tr").remove();
            $(".stat-total-table tbody tr").remove();
            
            statProductTable.fnClearTable();
            statProductTable.fnDraw();
            statProductTable.fnDestroy();

            if (response.success && response.productStat != "")
                $(".stat-products-table tbody").append(response.productStat);

            if (response.success)
                $(".stat-total-table tbody").append(response.total);

            statProductTable = $('.stat-products-table').dataTable({
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

function addFilter(){
    var div = $("<div>");
    var select = $("<select>",{
        "class" : "filter-param"
    }).append(
        '<option value="utm_campaign">Кампания</option>',
        '<option value="utm_content">Тизер</option>',
        '<option value="utm_term">Площадка</option>');
    
    var input = $("<input>",{
        "type" : "text",
        "class" : "filter-value" 
    }).keyup(function(event) {
        if (event.keyCode == 13)
            getStatProducts();
    });

    var deleteButton = $("<button>",{
        "class" : "delete-filter-button button button-period button-error"
    }).click(function(event) {
        $(this).parent().remove();
        if($('.filter-panel').children().length == 0) {
            $('.apply-filters-button').css('display', 'none');
        }
        else {
            $('.apply-filters-button').css('display', 'block');
        }
    }).text("X");
    
    $(".filter-panel").append(div);
    $(div).append(select, input, deleteButton);
    
    if($('.filter-panel').children().length > 0) {
        $('.apply-filters-button').css('display', 'block');
    }
}

function getFilters(){
    var filters = {};

    var filter_params = $(".filter-param");
    var filter_values = $(".filter-value");

    for(var i = 0; i < filter_params.length; i++){
        filters[i] = {};
        filters[i].param = $(filter_params[i]).val();
        filters[i].value = $(filter_values[i]).val();
    }

    return filters;
}

var statProductTable;
$(document).ready(function(){

    $('#city').chosen({no_result_text: 'Не удалось найти город', allow_single_deselect: true});

    $('#city').change(function(){
        getStatProducts();
    });

    statProductTable = $('.stat-products-table').dataTable();

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

    $(".add-filter-button").click(function(event) {
        addFilter();
    });

    $(".apply-filters-button").click(function() {
        getStatProducts();
    });
    
    $('.checkbox-no-utm').on('change', function() {
        if($(this).prop('checked')) {
            $('.filter-param').prop('disabled', 'true');
            $('.filter-value').prop('disabled', 'true');
            $('.delete-filter-button').prop('disabled', 'true');
            $('.apply-filters-button').prop('disabled', 'true');
        }
        else {
            $('.filter-param').removeAttr('disabled');
            $('.filter-value').removeAttr('disabled');
            $('.delete-filter-button').removeAttr('disabled');
            $('.apply-filters-button').removeAttr('disabled');
        }
        getStatProducts();
    });
    
    $('.apply-filters-button').css('display', 'none');
    
    
});   