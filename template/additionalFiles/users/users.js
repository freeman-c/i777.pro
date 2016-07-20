//******************************* users **************************
function getUsersList(){
    $.ajax({
        url: '/template/additionalFiles/users/users.php',
        type: 'POST',
        data: {
            operation: 'getUsersList'
        },
    })
    .done(function(response) {
        $('.users-table').html(response);
        
        $('.selected').unbind('click');
        $('.selected').bind('click', function(){ 
            SELECT_SHIFT(); 
        });

        $('#table-list tbody tr').hover(function(){
            $(this).find(".option-button").show();
        }, function() {
            $(this).find(".option-button").hide();
        });
    });
    
}

function add_new_user(){
    var title = 'Создание нового пользователя';
    var content = '<div id="box_add_new_user"></div>';    
    modal(title,content);
    $('#box_add_new_user').load('/template/include/users.php');
}
function edit_user(id){
    var title = 'Редактирование пользователя';
    var content = '<div id="box_edit_user"></div>';    
    modal(title,content);
    $('#box_edit_user').load('/template/include/users.php?id='+id);
}

function delete_user(){
    if(confirm('Удаляем выделенные элементы?')){  
        $.ajax({
            url: "/template/additionalFiles/users/users.php",
            method: 'POST',
            data : $('#form-users').serialize() + "&operation=deleteUsers",
            beforeSend: function(){
                WaitingBarShow('Удаление сотрудника...');
            },
            success: function(response){
                console.log(response);
                WaitingBarHide();
                getUsersList();
            }                   
        });
    }
    $('#button-operation-delete').fadeOut();
}

function addUser(){    
    $.ajax({
        type: "POST",
        url: "/template/additionalFiles/users/users.php",
        data: {
            operation: 'addUser',
            login:$('input[name="login_new_user"]').val(),
            line: $('input[name="line"]').val(),
            pass:$('input[name="pass_new_user"]').val(),
            access:$('select[name="access_new_user"]').val(),
            surname:$('input[name="surname_new_user"]').val(),
            name:$('input[name="name_new_user"]').val(),
            description:$('textarea[name="description_new_user"]').val(),
        },
        beforeSend: function(){
            CloseModal();
            WaitingBarShow('Добавление сотрудника...');
        },
        success: function(response){
            console.log(response);
            WaitingBarHide();
            getUsersList();
        }
    }); 
}

function editUser(){    
    $.ajax({
        type: "POST",
        url: "/template/additionalFiles/users/users.php",
        data: {
            operation: 'editUser',
            id:$('input[name="id_new_user"]').val(),
            login:$('input[name="login_new_user"]').val(),
            line: $('input[name="line"]').val(),
            pass:$('input[name="pass_new_user"]').val(),
            access:$('select[name="access_new_user"]').val(),
            surname:$('input[name="surname_new_user"]').val(),
            name:$('input[name="name_new_user"]').val(),
            description:$('textarea[name="description_new_user"]').val(),
        },
        beforeSend: function(){
            CloseModal();
            WaitingBarShow('Редактирование сотрудника...');
        },
        success: function(response){
            console.log(response);
            WaitingBarHide();
            getUsersList();
        }
    }); 
}


$(document).ready(function() {
    getUsersList();
});