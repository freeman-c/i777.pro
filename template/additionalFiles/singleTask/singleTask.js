function getSingleCallTasks(){
    $('.delete-single-call-task').fadeOut();
    $('#select-all-checkbox').removeAttr('checked');
    var checkboxes = $('.single-call-task-state-checkbox');

    var call_task_states = new Object();
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked)
            call_task_states[i] = checkboxes[i].value;
    }
    call_task_states = JSON.stringify(call_task_states);

    $.ajax({
        url: '/template/additionalFiles/singleTask/singleTaskController.php',
        method: 'POST',
        data: {
            operation: 'getSingleCallTaskList',
            call_task_states: call_task_states
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...');
            proces = true;  
            $('.navigation').hide();   
        },
        success: function(data){
            // console.log(data);

            $(".single-tasks-table tbody tr").remove();
            
            singleTaskTable.fnClearTable();
            singleTaskTable.fnDraw();
            singleTaskTable.fnDestroy();

            $(".single-tasks-table tbody").append(data);

            singleTaskTable = $('.single-tasks-table').dataTable({
                "aLengthMenu": [ 2,10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

            $('.fg-toolbar').click(function(){
                setTableListListeners();
            }).keyup(function(){
                setTableListListeners();
            });

            $('.selected').unbind('click');
            $('.selected').bind('click', function(){ 
                SELECT_SHIFT(); 
            });

            $('.on-off').unbind('click');
            $('.on-off').click(function() {
                 getSingleCallTasks();
            });
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

function addSingleCallTask(){
    var title = 'Добавление задания';
    var content = '<div id="box_add_single_call_task"></div>';    
    modal(title,content);
    $('#box_add_single_call_task').load('/template/include/single_task.php');
}

function editSingleCallTask(id){
    var title = 'Редактирование задания';
    var content = '<div id="box_edit_single_call_task"></div>';    
    modal(title,content);
    $('#box_edit_single_call_task').load('/template/include/single_task.php?id='+id);
}

function deleteSingleCallTask(){
    if(confirm('Удаляем выделенные элементы?')){  
        $.ajax({
            url: "/template/additionalFiles/singleTask/singleTaskController.php",
            method: 'POST',
            data : $('#form-single-tasks').serialize()+'&operation=deleteSingleCallTask',
            beforeSend: function(){
                WaitingBarShow('Удаление заданий...');
            },
            success: function(response){
                response = $.parseJSON(response);
                if (response.success == 'false')
                    alert('Error! '+response.error);
                getSingleCallTasks();                
            }
        });
    }
    $('#select-all-checkbox').removeAttr('checked');
}
var singleTaskTable;
$(document).ready(function(){

    singleTaskTable = $('.single-tasks-table').dataTable({
                // "bRetrieve": true,
                "aLengthMenu": [ 2,10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

    getSingleCallTasks();

    $('#table-list tbody td').click(function(event){
        $('#table-list tbody tr').removeClass('selected-row-in-table');
        t=event.target||event.srcElement;
        $(t).parent('tr').addClass('selected-row-in-table');
    });   

    $('.add-single-call-task').click(function() {
        addSingleCallTask();
    });

    $('.update-single-call-task-list').click(function() {
        getSingleCallTasks();
    });

    $('.delete-single-call-task').click(function() {
        deleteSingleCallTask();
        getSingleCallTasks();
    });

    $('.single-call-task-state-checkbox').change(function() {
        getSingleCallTasks();
    });
});    
