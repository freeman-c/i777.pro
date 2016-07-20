function getSingleTasks(){
    $.ajax({
        url: '/modules/get_single_tasks.php',
        method: 'POST',
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...');
            proces = true;  
            $('.navigation').hide();   
        },
        success: function(data){
            // console.log(data);
            $(".single-tasks-table tbody tr").remove();
            $(".single-tasks-table tbody").append(data);

            $('.single-tasks-table').dataTable( {
                "aLengthMenu": [ 10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers",
                "aoColumnDefs": [{'bSortable': false, 'aTargets':[0] }],
                "aaSorting": [[ 17, "desc" ]],
                "columnDefs": [{ type: 'formatted-num', targets: 11 }]
            } );

            $('.fg-toolbar').click(function(){
                setTableListListeners();
            }).keyup(function(){
                setTableListListeners();
            });

            $('.selected').unbind('click');
            $('.selected').bind('click', function(){ 
                SELECT_SHIFT(); 
            });;
        },
        complete: function(){
            proces = false;                                                     

            $('#table-list tbody tr').hover(function(){
                $(this).find(".option-button").show();
            }, function() {
                $(this).find(".option-button").hide();
            });  
            WaitingBarHide();
        }
    }); 
}

// function add_new_template_script(login){
//     var title = 'Создание нового скрипта';
//     var content = '<div id="box_add_new_template_script"></div>';    
//     modal(title,content);
//     $('#box_add_new_template_script').load('/template/include/template_script.php');
// }
// function edit_template_script(id){
//     var title = 'Редактирование скрипта';
//     var content = '<div id="box_edit_template_script"></div>';    
//     modal(title,content);
//     $('#box_edit_template_script').load('/template/include/template_script.php?id='+id+'');
// }
// function ajax_template_script(op){    
//         var data = $('#forma-template-script').serialize();
//         // console.log(data);
//         $.ajax({
//             type: "POST",
//             url: "/index.php?action=ajax_template_script",
//             data: data + '&op='+op+'',
//             beforeSend: function(){
//             },
//             success: function(res){ 
//                 location.reload();
//             },
//             error: function() { alert('Ошибка ajax! cod: ajax_template_script'); }
//         }); 
// }

function deleteSingleTasts(){
    if(confirm('Удаляем выделенные элементы?')){  
        $.ajax({
            url: "/modules/singleTask/deleteSingleTask.php",
            method: 'POST',
            data : $('#form-single-tasks').serialize(),
            beforeSend: function(){
                WaitingBarShow('Удаление заданий...');
            },
            success: function(data){
                // alert(data);
                // location.reload();
                getSingleTasks();
            }
        });
    }
}


$(document).ready(function(){ 
    getSingleTasks();
    
    $('#table-list tbody td').click(function(event){
        $('#table-list tbody tr').removeClass('selected-row-in-table');
        t=event.target||event.srcElement;
        $(t).parent('tr').addClass('selected-row-in-table');
    });    
});    
