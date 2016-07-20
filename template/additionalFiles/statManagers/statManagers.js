var searchStr = '';
var period = 'p-today';

function getStatManagers(){

    $.ajax({
        url: '/template/additionalFiles/statManagers/statManagers.php',
        method: 'POST',
        data: {
            operation: 'getStatManagers',
            dateFrom: $('#between-start-mng').val(),
            dateTo: $('#between-end-mng').val()
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...'); 
        },
        success: function(data){
            response = $.parseJSON(data);
            console.log(response);

            $(".stat-managers-table tbody tr").remove();
            $(".stat-total-table tbody tr").remove();
            
            statManagersTable.fnClearTable();
            statManagersTable.fnDraw();
            statManagersTable.fnDestroy();

            if (response.success && response.table != "")
                $(".stat-managers-table tbody").append(response.table);
             
            if (response.success){
                $(".stat-total-table tbody").append(response.managerSummury);
                $(".stat-total-table tbody").append(response.traineeSummury);
                $(".stat-total-table tbody").append(response.total);
            }

            statManagersTable = $('.stat-managers-table').dataTable({
                "aLengthMenu": [ 10,25,50,100,200,500,1000 ],
                "iDisplayLength": 1000,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers",
                "order": [[10,"desc"]]});
        },
        complete: function(){
            WaitingBarHide();
            $('.dataTables_filter').find($("input[type='text']")).val(searchStr).keyup();
            $('.rating-col-head').find(".DataTables_sort_wrapper").click().click();

            switch(period){
                case 'p-today':
                    break;
                case 'p-yesterday': 
                    break;
                case 'p-week':
                    $('.week-bonus-animation').show();
                    break;
                case 'p-month':
                    $('.month-bonus-animation').show();
                    break;
            }
        }
    }); 
}

function getStatTrainees(){

    $.ajax({
        url: '/template/additionalFiles/statManagers/statManagers.php',
        method: 'POST',
        data: {
            operation: 'getStatTrainees',
            dateFrom: $('#between-start-mng').val(),
            dateTo: $('#between-end-mng').val()
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...'); 
        },
        success: function(data){
            // console.log(data);
            response = $.parseJSON(data);
            console.log(response);
            $(".stat-trainees-table tbody tr").remove();
            statTraineesTable.fnClearTable();
            statTraineesTable.fnDraw();
            statTraineesTable.fnDestroy();

            if (response.success && response.table != "")
                $(".stat-trainees-table tbody").append(response.table);

            statTraineesTable = $('.stat-trainees-table').dataTable({
                    "aLengthMenu": [ 10,25,50,100,200,500,1000 ],
                    "iDisplayLength": 1000,
                    "bJQueryUI" : true,
                    "sPaginationType": "full_numbers"});
        },
        complete: function(){
            WaitingBarHide();
            $('.dataTables_filter').find($("input[type='text']")).val(searchStr).keyup();
            $('.rating-col2-head').find(".DataTables_sort_wrapper").click().click();

            switch(period){
                case 'p-today':
                    break;
                case 'p-yesterday': 
                    break;
                case 'p-week':
                    $('.week-bonus-animation').show();
                    break;
                case 'p-month':
                    $('.month-bonus-animation').show();
                    break;
            }
        }
    }); 
}


var statManagersTable;
var statTraineesTable;
$(document).ready(function(){

    statManagersTable = $('.stat-managers-table').dataTable();
    statTraineesTable = $('.stat-trainees-table').dataTable();

    $('.mngStatDateInp').datepicker({
        onSelect: function () {
           getStatManagers();
           getStatTrainees();
        }
    });
});   
