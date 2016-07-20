$(document).ready(function(){
    var today = new Date();
    getDaysList(today.getMonth() + 1, today.getFullYear());
});

function getDaysList(month, year){
    $.ajax({
        type: "POST",
        url: "/template/additionalFiles/history/historyController.php",
        data: {
            action: "getDaysList",
            m: month,
            y: year,
            logType: logType
        },
        beforeSend: function(){
            $('#logs-day-panel').html('<img src="/image/loader_big.gif">');
        },
        success: function(data){
            // console.log("data:", data);
            $('#logs-day-panel').html(data);
            $('#logs-container').html('<h3>Выберите день недели</h3>');
        }
    });   
}


 function startSearch(){
    if ($('.today').attr('id'))
        getLogList($('.today').attr('id'), event);
    else
        getLogList('', event);
}

$(document).ready(function(){
    $('#button-search-logs-ajax').click(function(){
        startSearch();
    });

    $('#button-clear-search-input').click(function(){
        $('#input-ajax-search-logs').val('');
        clear_selected_today();
        $('#logs-container').html('<h3>Выберите день недели</h3>');
    });


    $('.log-type').click(function(event) {
        //получили тип логов
        logType = $(this).attr('id');

        //подсветили кнопочку желтым
        $('.log-type-active').removeClass('log-type-active');
        $(this).addClass('log-type-active');

        //получили логи за день, если он уже был выбран ранее
        $('.today').click();    
    });
});

function selected_days_in_month(d){
    var id = d;
    var arr = d.split('-');
    //alert(arr[0]+' & '+arr[1]);
    $('.month').each(function(){ 
        $(this).removeClass('month-active'); 
    });

    $('#m-'+id+'').addClass('month-active');  
    
    getDaysList(arr[0], arr[1]);
}

function clear_selected_today(){
    $('.logs-day-button').each(function(){
        $(this).removeClass('today');
    });
}

function getLogList(date,event){
    t=event.target||event.srcElement;
    $('.today').removeClass('today');
    if ($(t).hasClass('logs-day-button'))
        $(t).addClass('today');
    else
        if ($(t).parent().hasClass('logs-day-button'))
            $(t).parent().addClass('today');

    $.ajax({
        type: "POST",
        url: "/template/additionalFiles/history/historyController.php",
        data: {
            action: "getLogsList",
            date: date,
            searchingString: $('#input-ajax-search-logs').val(),
            logType: logType
        },
        beforeSend: function(){
            $('#logs-container').html('<img src="/image/loader_big.gif">');
        },
        success: function(data){
            // console.log(data);
            $('#logs-container').html(data);
        },
        complete: function(){
            var count = $('#count_rows_input').val();
            if(count > 0)
               $('#count_rows_message').html('<span style="color:#757575;">Всего операций:</span> '+count);
           else
               $('#count_rows_message').html('<span style="color:#757575;">Всего операций:</span> <span style="color:#F00;">Ничего не найдено</span>');


       }
   });            
}
