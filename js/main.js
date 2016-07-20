function setCookie(name, value, options) {
  options = options || {};

  var expires = options.expires;

  if (typeof expires == "number" && expires) {
    var d = new Date();
    d.setTime(d.getTime() + expires * 1000);
    expires = options.expires = d;
  }
  if (expires && expires.toUTCString) {
    options.expires = expires.toUTCString();
  }

  value = encodeURIComponent(value);

  var updatedCookie = name + "=" + value;

  for (var propName in options) {
    updatedCookie += "; " + propName;
    var propValue = options[propName];
    if (propValue !== true) {
      updatedCookie += "=" + propValue;
    }
  }

  document.cookie = updatedCookie;
}

// возвращает cookie с именем name, если есть, если нет, то undefined
function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}

function deleteCookie(name) {
  setCookie(name, "", {
    expires: -1
  })
}

function getUrlVars() {
 	var vars = {};
 	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
	return vars;
}


function isValidJSON(src) {
   var filtered = src;
   filtered = filtered.replace(/\\["\\\/bfnrtu]/g, '@');
   filtered = filtered.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']');
   filtered = filtered.replace(/(?:^|:|,)(?:\s*\[)+/g, '');
 
   return (/^[\],:{}\s]*$/.test(filtered));
}


$(document).ready(function(){
       
    var url=document.location.href;
        $("#menu a").each(function(){
            if(this.href==url){
                $(this).addClass('selected-menu');
                $(this).parent().parent().find('.category-wrapper').addClass("selected-menu-cat");
                $(this).parent().parent().show();
                $(this).parent().parent().parent().find('.expander').addClass('expander-active');                
            }
        });
    
    SELECT_ALL();
    

    $('.selected').unbind('click');
    $('.selected').bind('click', function(){ 
        SELECT_SHIFT(); 
    });
    
    $('#table-list tbody tr').hover(function(){
        $(this).find(".option-button").show();
    }, function() {
        $(this).find(".option-button").hide();
    });
    $('#table-tnn tbody tr').hover(function(){
        $(this).find(".option-button").show();
    }, function() {
        $(this).find(".option-button").hide();
    });
    
});

var isOpenOverlay = false;
var dgt = false;
var afk = false;
var login;
var orderId;

//******************************* FUNCTION *******************************
function CloseModal(){
    isOpenOverlay = false;
    openedOrderId = '';
    taskTimerId = setInterval(function(){ getCallTask(login); }, 10000);
    $('#overlay').remove();
    $('body').css('overflow', 'visible');
}
function modal(title,content){
    isOpenOverlay = true;
    clearTimeout(taskTimerId);
    $('#content').append('<div id="overlay">'+ 
                            '<div id="modal-window">'+                            
                            '</div>'+
                        '</div>');
    $('body').css('overflow', 'hidden');            
    $('#modal-window').html('<div id="modal-title">'+title+''+
                                '<span id="modal-close" onclick="CloseModal();"></span>'+
                                '</div>'+
                                '<div id="modal-content">'+content+'</div>');
}
function UnsetSession(element){
        $.ajax({
            type: "POST",
            url: "/modules/unset_session_element.php",
            data: {element:element},
            beforeSend: function(){},
            success: function(res){ 
                //alert(res);
                //location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: unset_session_element.php'); }
        });
}
//*********************************** SMS **********************************
function SendSMS(phone,ttn,event){
    //t=event.target||event.srcElement;
    //$(t).parent().addClass('selected-row-in-table');    
    /*var col = phone.length;    
    if (col == 10) {$number = '%2B38'+phone+'';} // символ "+" заменяем "%2B"
else if (col == 11){$number = '%2B3'+phone+'';} // символ "+" заменяем "%2B"
else if (col == 12){$number = '%2B'+phone+'';} // символ "+" заменяем "%2B"
    else{
        //var repl = phone.replace("+", "%2B"); // символ "+" заменяем "%2B"
        //$number = repl;
        $number = phone;
    }*/
    var first_symbol = phone.slice(0,1);
        if(first_symbol=='0'){$number = '%2B38'+phone+''; $strana='UA';} // 0 => +38
   else if(first_symbol=='8'){$number = '%2B3'+phone+''; $strana='UA';} // 8 => +3
   else if(first_symbol=='3'){$number = '%2B'+phone+''; $strana='UA';} // 3 => +
   
   else if(first_symbol=='7'){$number = '%2B'+phone+''; $strana='RU';} // 7 => +
   else if(first_symbol=='9' || first_symbol=='6' || first_symbol=='5' || first_symbol=='4' || first_symbol=='2' || first_symbol=='1'){
       $number = '%2B'+phone+''; $strana='';} // 9 => +
   
    else{
        //var repl = phone.replace("+", "%2B"); // символ "+" заменяем "%2B"
        //$number = repl;
        $number = phone;
    }
    //var nomer = $.trim($number); // убираются пробелы в начале и конце
    var title = 'Отправка СМС сообщения';
    var content = '<div id="box_sms"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'800px','margin-left':'-400px'});
    $('#box_sms').load('/template/include/sms.php?phone='+$number+'&ttn='+ttn+'&strana='+$strana+'');
}
//********************************** EMAIL *********************************
function SendMail(fio,email){
    //alert('Отправка Email: '+email+'');
    var repl = fio.replace(" ", "%20"); // символ "пробел" заменяем "%20"
    $client = repl;
    var title = 'Отправка письма клиенту на Email';
    var content = '<div id="box_mail"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'980px','margin-left':'-490px'});
    $('#box_mail').load('/template/include/email.php?fio='+$client+'&email='+email+'');
}
//********************************* PRINT **********************************
function pageCleaner(){
     $('body').removeClass('printSelected');//убираем класс у body
     $('.printSelection').remove();//убиваем наш только что созданный блок для печати
}
function printBlock(id_element){
     $content = $(''+id_element+'').html();//забираем контент нужного нам блока (в моем случае ссылка на печать находится внути его)
     $('body').addClass('printSelected');//добавляем класс к body
     $('body').append('<div class="printSelection">' + $content + '</div>');//создаем новый блок внутри body
     window.print();//печатаем     
     window.setTimeout(pageCleaner, 0); //очищаем нашу страницу от "мусора"     
     return false;//баним переход по ссылке, чтобы она не пыталась перейти по адресу, указанному внутри аттрибута href
}

//******************************* Waiting Bar ******************************
function WaitingBarShow(text_message){
    $('#content').append('<div id="waiting-bar"></div>');
    $('#waiting-bar').text(text_message);
    $('#waiting-bar').css('top','-36px');
    $('#waiting-bar').show();
    $('#waiting-bar').animate({top: "-6px"},{duration: 500});      
}
function WaitingBarHide(){
    $('#waiting-bar').animate({top: "-36px"},{duration: 500, complete:function(){$('#waiting-bar').remove();}});
    //$('#waiting-bar').remove();    
}
//******************************* Message Tray *****************************
function CloseMessageTray(){
    $('#message-tray').animate({top: "-36px"},{duration: 500, complete:function(){$('#waiting-bar').remove();}});  
}
function MessageTray(text_message){
    $('#content').append('<div id="message-tray"></div>');
    $('#message-tray').html(text_message +'<span id="close-message-tray" onclick="CloseMessageTray();">&nbsp</span>');
    $('#message-tray').css('top','-40px');
    $('#message-tray').show();
    $('#message-tray').animate({top: "-1px"},{duration: 500}); 
    setTimeout(function(){
        //$('#message-tray').remove();
        $('#message-tray').animate({top: "-40px"},{duration: 1500, complete:function(){$('#message-tray').remove();}});
    },3000);
}

//******************************* Message Tray *****************************
function callNotification(text_message){
    $('#content').append('<div id="message-tray"></div>');
    $('.call_notification').html(text_message);
    $('.call_notification').fadeIn();
    setTimeout(function(){$('.call_notification').fadeOut()},5000);
}
//**************************************************************************
function getInfo(){  
    modal('О программе',content);
    $('#modal-content').load('/template/include/version.php');
}

function MenuToggle(event){
    t=event.target||event.srcElement;    
    $('.menu-sub-list').slideUp();
    $('.expander').removeClass('expander-active');
    $(t).parent().parent().parent().find('.expander').addClass('expander-active');
    //$(t).parent().parent().parent().find('.expander').toggleClass('expander-active');
    $(t).parent().parent().parent().find('.menu-sub-list').slideToggle();
}

function ScriptToggle(event){
    t=event.target||event.srcElement;   
    $(t).parent().parent().parent().find('.expander:first').toggleClass('expander-active');
    var prevState = $(t).parent().parent().parent().find('.script-view').css('display');
    // $('.script-view').slideUp();
    // console.log($(t).parent().parent().parent().find('.script-view:first').css('display'));
    if (prevState == 'block'){
        $(t).parent().parent().parent().find('.script-view').slideUp();
    }
    else{
        $(t).parent().parent().parent().find('.script-view:first').slideDown();
    }
}

function showScriptText(event) {
    t=event.target||event.srcElement; 
    var text = $(t).parent().parent().find('.script-text:first').val();
    $('#script-text-textarea').empty();
    $('#script-text-textarea').append(text);
}

//*************************************************************************
function SELECTED_ROW(){
    $(".selected").each(function(){
        if( $(this).is(":checked") ){
            $(this).closest("tr").addClass("selected_row");
        }else{
            $(this).closest("tr").removeClass("selected_row");
        }
    });
}
function COUNT_SELECTED_CHECKBOX(){
    var count = $(".selected:checked").length;
    var status = $('.tab-status-active').attr('id');
    if (status)
      status = status.substr(0, status.indexOf('-'));
    if(count > 0){
        $('#button-operation-export').fadeIn();
        if (status == 11 || status == 3)
            $('#button-operation-print-ttn').fadeIn();
        $('.button-operation-send-sms-tmp').fadeIn();
        $('#button-operation-delete').fadeIn();
        $('#button-restore').fadeIn();
        $('#table-message').html('Выделено элементов: <b>'+count+'</b>');
        $('#count-elements-delete').text('('+count+')');
    }else{
        $('#button-operation-export').hide();
        $('#button-operation-delete').hide();
        $('#button-restore').hide();
        $('#table-message').html('');
        $('#count-elements-delete').text('');
        $('#button-operation-create-ttn').hide();
        $('#button-operation-print-ttn').hide();
        $('.button-operation-send-sms-tmp').hide();
    }
}
function SELECT_ALL(){
     /*$('#select-all-checkbox').click(function(event) {
         if(this.checked) {
             $('.selected').each(function() { 
                 this.checked = true; 
                 SELECTED_ROW();
                 COUNT_SELECTED_CHECKBOX();
             });
         }else{
             $('.selected').each(function() {
                 this.checked = false;
                 SELECTED_ROW();
                 COUNT_SELECTED_CHECKBOX();
             });         
         }
     });*/
    $('#select-all-checkbox').change(function() {
        var checkboxes = $('input[type="checkbox"].selected');
        if($(this).is(':checked')) {
            checkboxes.prop('checked', true);
            COUNT_SELECTED_CHECKBOX();
        } else {
            checkboxes.prop('checked', false);
            COUNT_SELECTED_CHECKBOX();
        }
        $('input[type="checkbox"].selected').each(function(){
				if($(this).is(":checked")) {
					$(this).closest("tr").addClass("selected_row"); //$(this).closest("tr").css({background:'#CDE6F7'});
				}else {
					$(this).closest("tr").removeClass("selected_row"); //$(this).closest("tr").css({background:'transparent'});
				}
			}
		);
        });
}
function SELECT_SHIFT() {
    var _last_selected = null, checkboxes = $( ".selected" );
    checkboxes.click( function( e ) {
        var ix = checkboxes.index( this ), checked = this.checked;
        if ( e.shiftKey && ix != _last_selected ) {
          checkboxes.slice( Math.min( _last_selected, ix ), Math.max( _last_selected, ix ) )
          .each( function() { 
             this.checked = checked
         });       
          _last_selected = null;
      } else { _last_selected = ix }
    });
    SELECTED_ROW();
    COUNT_SELECTED_CHECKBOX();
}
//******************************** group users ***************************
function ajax_groups_user(op){
    var name = $('input[name="name_new_group_user"]').val();
    var id = $('input[name="id_group_user"]').val();    
    if(name.length > 0){
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_groups",
            data: {
                id:id,
                name:name,
                op:op
            },
            beforeSend: function(){

            },
            success: function(res){ 
                //alert(res);
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_groups_user'); }
        });
    }else{
        alert('Поле не может быть пустым!');
    }    
}
function add_new_group_user(){
    var title = 'Создание новой группы пользователей';
    var content = 'Название группы: <input type="text" name="name_new_group_user" size="36">'+
                  '<hr>'+
                  '<p style="text-align:center;">'+
                  '<button class="button" onclick="ajax_groups_user(\'add\');">Сохранить</button>'+
                  '<button class="disabled" onclick="CloseModal();">Отмена</button>'+
                  '</p>';
    modal(title,content);
    $('input[name="name_new_group_user"]').focus();
}
function edit_group_user(id,name){
    var title = 'Редактирование группы пользователей';
    var content = 'Название группы: <input type="text" name="name_new_group_user" value="'+name+'" size="36">'+
                  '<input type="hidden" name="id_group_user" value="'+id+'">'+
                  '<hr>'+
                  '<p style="text-align:center;">'+
                  '<button class="button" onclick="ajax_groups_user(\'edit\');">Сохранить</button>'+
                  '<button class="disabled" onclick="CloseModal();">Отмена</button>'+
                  '</p>';
    modal(title,content);
    $('input[name="name_new_group_user"]').focus();
}
function delete_group_user(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_groups",
                    method: 'POST',
                    data : $('#form-group-user').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление групп...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE group users'); }                    
                });
    }
}
//***************************** group clients ***************************
function ajax_groups_clients(op){
    var name = $('input[name="name_new_group_clients"]').val();
    var id = $('input[name="id_group_clients"]').val();    
    if(name.length > 0){
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_group_clients",
            data: {
                id:id,
                name:name,
                op:op
            },
            beforeSend: function(){

            },
            success: function(res){ 
                //alert(res);
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_groups_clients'); }
        });
    }else{
        alert('Поле не может быть пустым!');
    }    
}
function add_new_group_clients(){
    var title = 'Создание новой группы клиентов';
    var content = 'Название группы: <input type="text" name="name_new_group_clients" size="36">'+
                  '<hr>'+
                  '<p style="text-align:center;">'+
                  '<button class="button" onclick="ajax_groups_clients(\'add\');">Сохранить</button>'+
                  '<button class="disabled" onclick="CloseModal();">Отмена</button>'+
                  '</p>';
    modal(title,content);
    $('input[name="name_new_group_clients"]').focus();
}
function edit_group_clients(id,name){
    var title = 'Редактирование группы клиентов';
    var content = 'Название группы: <input type="text" name="name_new_group_clients" value="'+name+'" size="36">'+
                  '<input type="hidden" name="id_group_clients" value="'+id+'">'+
                  '<hr>'+
                  '<p style="text-align:center;">'+
                  '<button class="button" onclick="ajax_groups_clients(\'edit\');">Сохранить</button>'+
                  '<button class="disabled" onclick="CloseModal();">Отмена</button>'+
                  '</p>';
    modal(title,content);
    $('input[name="name_new_group_clients"]').focus();
}
function delete_group_clients(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_group_clients",
                    method: 'POST',
                    data : $('#form-group-user').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление групп...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE group clients'); }                    
                });
    }
}
//*************************** clients ****************************
function add_new_client(){
    var title = 'Создание нового клиента';
    var content = '<div id="box_add_new_client"></div>';    
    modal(title,content);
    $('#box_add_new_client').load('/template/include/clients.php');
}
function edit_client(id){
    var title = 'Редактирование данных клиента';
    var content = '<div id="box_edit_client"></div>';    
    modal(title,content);
    $('#box_edit_client').load('/template/include/clients.php?id='+id+'');
}
function delete_client(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_clients",
                    method: 'POST',
                    data : $('#form-clients').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление клиентов...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE clients'); }                    
                });
    }
}
function ajax_clients(op){    
    var id = $('input[name="id_new_client"]').val();  
    var surname = $('input[name="surname_new_client"]').val();
    var name = $('input[name="name_new_client"]').val();
    var lastname = $('input[name="lastname_new_client"]').val();
    var phone = $('input[name="phone_new_client"]').val();
    var email = $('input[name="email_new_client"]').val();  
    var description = $('textarea[name="description_new_client"]').val();
    var type = $('select[name="type"]').val();
    if(phone.length > 0){
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_clients",
            data: {
                id:id,
                surname:surname,
                name:name,
                lastname:lastname,
                phone:phone,
                email:email,
                description:description,
                type:type,
                op:op
            },
            beforeSend: function(){
            },
            success: function(res){ 
                //alert(res);
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_clients'); }
        }); 
    }else{
        alert('Забылы телефон указать!');
    }
}
//******************************* users **************************
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
    $('#box_edit_user').load('/template/include/users.php?id='+id+'');
}
function delete_user(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_users",
                    method: 'POST',
                    data : $('#form-users').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление сотрудника...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE clients'); }                    
                });
    }
}
function ajax_users(op){    
    var id = $('input[name="id_new_user"]').val();
    var login = $('input[name="login_new_user"]').val();
    var line = $('input[name="line"]').val();
    var pass = $('input[name="pass_new_user"]').val();
    var access = $('select[name="access_new_user"]').val();
    var surname = $('input[name="surname_new_user"]').val();
    var name = $('input[name="name_new_user"]').val();
    var lastname = $('input[name="lastname_new_user"]').val();
    var email = $('input[name="email_new_user"]').val();
    var place_work = $('select[name="place_work_new_user"]').val();
    var description = $('textarea[name="description_new_user"]').val();
    
    if(login.length > 0){
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_users",
            data: {
                id:id,
                login:login,
                line: line,
                pass:pass,
                access:access,
                surname:surname,
                name:name,
                lastname:lastname,
                email:email,
                place_work:place_work,
                description:description,
                op:op
            },
            beforeSend: function(){
            },
            success: function(res){ 
                //alert(res);
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_users'); }
        }); 
    }else{
        alert('Заполните обязательные поля!');
    }
}
//*************************** clients ****************************
function add_new_office(){
    var title = 'Создание нового офиса';
    var content = '<div id="box_add_new_office"></div>';    
    modal(title,content);
    $('#box_add_new_office').load('/template/include/offices.php');
}
function edit_office(id){
    var title = 'Редактирование данных офиса';
    var content = '<div id="box_edit_office"></div>';    
    modal(title,content);
    $('#box_edit_office').load('/template/include/offices.php?id='+id+'');
}
function delete_office(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_offices",
                    method: 'POST',
                    data : $('#form-offices').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление офисов...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE offices'); }                    
                });
    }
}
function ajax_offices(op){    
    /*var id = $('input[name="id_new_office"]').val();
    var name = $('input[name="name_new_office"]').val();
    var adress = $('input[name="adress_new_office"]').val();*/    
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_offices",
            data: $('#forma-offices').serialize() + '&op='+op+'',
            /*data: {
                id:id,
                name:name,
                adress:adress,
                op:op
            },*/
            beforeSend: function(){
            },
            success: function(res){ 
                //alert(res);
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_offices'); }
        }); 
}
//*************************** statusy ****************************
function add_new_status(){
    var title = 'Создание нового статуса';
    var content = '<div id="box_add_new_status"></div>';    
    modal(title,content);
    $('#box_add_new_status').load('/template/include/statusy.php');
}
function edit_status(id){
    var title = 'Редактирование статуса';
    var content = '<div id="box_edit_status"></div>';    
    modal(title,content);
    $('#box_edit_status').load('/template/include/statusy.php?id='+id+'');
}
function delete_status(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_statusy",
                    method: 'POST',
                    data : $('#form-statusy').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление статусов...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE statusy'); }                    
                });
    }
}
function ajax_statusy(op){    
    var id = $('input[name="id_new_status"]').val();
    var name = $('input[name="name_new_status"]').val();
    var color = $('input[name="color_new_status"]').val();    
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_statusy",
            data: {
                id:id,
                name:name,
                color:color,
                op:op
            },
            beforeSend: function(){
            },
            success: function(res){ 
                //alert(res);
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_offices'); }
        }); 
}
//********************* zakazy ********************
function add_new_zakaz(login){
    var title = 'Создание нового заказа';
    var content = '<div id="box_add_new_status"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'1200px','margin-left':'-600px', 'top':'0px'});
    $('#box_add_new_status').load('/template/include/zakazy.php?user='+login+'&status='+'0');
}
function edit_zakaz(id){
    setCallTaskState(id, 3);
    openedOrderId = id;
    var title = 'Редактирование заказа';
    var content = '<div id="box_edit_status"></div>';    
    modal(title,content);
    $('#box_edit_status').html('<img src="image/loader_big.gif" style="margin-bottom: -6px;"> &nbsp Подождите, пожалуйста...');
    $('#box_edit_status').load('/template/include/zakazy.php?id='+id);


}
function ajax_zakazy(op){ 
    if ($('.task-high-priority').prop('checked'))
      taskIsManual = true;
    else
      taskIsManual = false;
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_zakazy",
            data: $('#forma-zakazy').serialize() + '&op='+op+'&tim='+taskIsManual,
            beforeSend: function(){
            },
            success: function(res){ 
                //alert(res);
                // location.reload();
                CloseModal();
                setOrderFilter();
                // updateSingleTasksList();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_zakazy'); }
        }); 
}
function delete_zakaz(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_zakazy",
                    method: 'POST',
                    data : $('#form-zakazy').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление заказов...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE zakazy'); }                    
                });
    }
}
function destroy_zakaz(){
    if(confirm('Удаляем выделенные элементы? восстановление будет не возможно.')){  
                $.ajax({
                    url: "/index.php?action=ajax_zakazy",
                    method: 'POST',
                    data : $('#form-zakazy').serialize() + "&op=destroy",
                    beforeSend: function(){
                        WaitingBarShow('Удаление заказов...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE (destroy) zakazy'); }                    
                });
    }
}
//********************* sender SMS ********************
function add_new_sender_sms(login){
    var title = 'Создание нового отправителя СМС';
    var content = '<div id="box_add_new_sender_sms"></div>';    
    modal(title,content);
    $('#box_add_new_sender_sms').load('/template/include/sender_sms.php?user='+login+'');
}
function edit_sender_sms(id){
    var title = 'Редактирование отправителя СМС';
    var content = '<div id="box_edit_sender_sms"></div>';    
    modal(title,content);
    $('#box_edit_sender_sms').load('/template/include/sender_sms.php?id='+id+'');
}
function ajax_sender_sms(op){      
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_sender_sms",
            data: $('#forma-sender-sms').serialize() + '&op='+op+'',
            beforeSend: function(){
                WaitingBarShow('Отправка СМС сообщения...');
            },
            success: function(res){
                WaitingBarHide();
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_sender_sms'); }
        }); 
}
function delete_sender_sms(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_sender_sms",
                    method: 'POST',
                    data : $('#form-sender-sms').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление отправителей СМС...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE sender_sms'); }                    
                });
    }
}
//********************* sender Email ********************
function add_new_sender_email(login){
    var title = 'Создание нового отправителя СМС';
    var content = '<div id="box_add_new_sender_email"></div>';    
    modal(title,content);
    $('#box_add_new_sender_email').load('/template/include/sender_email.php?user='+login+'');
}
function edit_sender_email(id){
    var title = 'Редактирование отправителя СМС';
    var content = '<div id="box_edit_sender_email"></div>';    
    modal(title,content);
    $('#box_edit_sender_email').load('/template/include/sender_email.php?id='+id+'');
}
function ajax_sender_email(op){      
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_sender_email",
            data: $('#forma-sender-email').serialize() + '&op='+op+'',
            beforeSend: function(){
            },
            success: function(res){ 
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_sender_email'); }
        }); 
}
function delete_sender_email(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_sender_email",
                    method: 'POST',
                    data : $('#form-sender-email').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление отправителей Email...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE ajax_sender_email'); }                    
                });
    }
}
//********************* template email ********************
function add_new_template_email(login){
    var title = 'Создание нового шаблона Электронной Почты';
    var content = '<div id="box_add_new_template_email"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'800px','margin-left':'-400px'});
    $('#box_add_new_template_email').load('/template/include/template_email.php?user='+login+'');
}
function edit_template_email(id){
    var title = 'Редактирование шаблона Электронной Почты';
    var content = '<div id="box_edit_template_email"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'800px','margin-left':'-400px'});
    $('#box_edit_template_email').load('/template/include/template_email.php?id='+id+'');
}
function ajax_template_email(op){ 
            var editor_data = CKEDITOR.instances.text.getData();
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_template_email",
            data: $('#forma-template-email').serialize() + '&op='+op+'&text_editor='+editor_data+'',
            beforeSend: function(){
            },
            success: function(res){ 
                //alert(res);
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_template_email'); }
        }); 
}
function delete_template_email(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_template_email",
                    method: 'POST',
                    data : $('#form-template-email').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление шаблонов Email...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE ajax_template_email'); }                    
                });
    }
}
//********************* template sms ********************
function add_new_template_sms(login){
    var title = 'Создание нового шаблона СМС сообщения';
    var content = '<div id="box_add_new_template_sms"></div>';    
    modal(title,content);
    $('#box_add_new_template_sms').load('/template/include/template_sms.php?user='+login+'');
}
function edit_template_sms(id){
    var title = 'Редактирование шаблона СМС сообщения';
    var content = '<div id="box_edit_template_sms"></div>';    
    modal(title,content);
    $('#box_edit_template_sms').load('/template/include/template_sms.php?id='+id+'');
}
function ajax_template_sms(op){      
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_template_sms",
            data: $('#forma-template-sms').serialize() + '&op='+op+'',
            beforeSend: function(){
            },
            success: function(res){ 
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_template_sms'); }
        }); 
}
function delete_template_sms(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_template_sms",
                    method: 'POST',
                    data : $('#form-template-sms').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление шаблонов SMS...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE ajax_template_sms'); }                    
                });
    }
}

//********************* template Script ********************
function add_new_template_script(login){
    var title = 'Создание нового скрипта';
    var content = '<div id="box_add_new_template_script"></div>';    
    modal(title,content);
    $('#box_add_new_template_script').load('/template/include/template_script.php');
}
function edit_template_script(id){
    var title = 'Редактирование скрипта';
    var content = '<div id="box_edit_template_script"></div>';    
    modal(title,content);
    $('#box_edit_template_script').load('/template/include/template_script.php?id='+id+'');
}
function ajax_template_script(op){    
        var data = $('#forma-template-script').serialize();
        // console.log(data);
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_template_script",
            data: data + '&op='+op+'',
            beforeSend: function(){
            },
            success: function(res){ 
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_template_script'); }
        }); 
}

function delete_template_script(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_template_script",
                    method: 'POST',
                    data : $('#form-template-script').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление шаблонов скриптов...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE ajax_template_sms'); }                    
                });
    }
}

//********************* Category ********************
function add_new_category(login){
    var title = 'Создание новой категории';
    var content = '<div id="box_add_new_category"></div>';    
    modal(title,content);
    $('#box_add_new_category').load('/template/include/category.php?user='+login+'');
}
function edit_category(id){
    var title = 'Редактирование категории';
    var content = '<div id="box_edit_category"></div>';    
    modal(title,content);
    $('#box_edit_category').load('/template/include/category.php?id='+id+'');
}
function change_status_category(id,status){
    $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_category",
            data: {
                id:id,
                status:status,
                op:'change_status'
            },
            beforeSend: function(){},
            success: function(res){ 
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_manufacturer'); }
        });
}
function ajax_category(op){      
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_category",
            data: $('#forma-category').serialize() + '&op='+op+'',
            beforeSend: function(){
            },
            success: function(res){ 
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_category'); }
        }); 
}
function delete_category(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_category",
                    method: 'POST',
                    data : $('#form-category').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление категорий...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE ajax_category'); }                    
                });
    }
}
//********************* Product ********************
function add_new_product(login){
    var title = 'Добавление нового товара';
    var content = '<div id="box_add_new_product"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'800px','margin-left':'-400px'});
    $('#box_add_new_product').load('/template/include/product.php?user='+login+'');
}
function edit_product(id){
    var title = 'Редактирование товара';
    var content = '<div id="box_edit_product"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'800px','margin-left':'-400px'});
    $('#box_edit_product').load('/template/include/product.php?id='+id+'');
}

function ajax_product(op){      
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_product",
            data: $('#forma-product').serialize() + '&op='+op+'',
            beforeSend: function(){
            },
            success: function(res){ 
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_product(add-edit)'); }
        }); 
}
function delete_product(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_product",
                    method: 'POST',
                    data : $('#form-product').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление товаров...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE ajax_product'); }                    
                });
    }
}



//********************* Manufacturer ********************
function add_new_manufacturer(login){
    var title = 'Добавление нового производителя';
    var content = '<div id="box_add_new_manufacturer"></div>';    
    modal(title,content);
    $('#box_add_new_manufacturer').load('/template/include/manufacturer.php?user='+login+'');
}
function edit_manufacturer(id){
    var title = 'Редактирование производителя';
    var content = '<div id="box_edit_manufacturer"></div>';    
    modal(title,content);
    $('#box_edit_manufacturer').load('/template/include/manufacturer.php?id='+id+'');
}
function change_status_brand(id,status){
    $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_manufacturer",
            data: {
                id:id,
                status:status,
                op:'change_status'
            },
            beforeSend: function(){},
            success: function(res){ 
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_manufacturer'); }
        });
}
function ajax_manufacturer(op){      
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_manufacturer",
            data: $('#forma-manufacturer').serialize() + '&op='+op+'',
            beforeSend: function(){
            },
            success: function(res){ 
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_manufacturer'); }
        }); 
}
function delete_manufacturer(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_manufacturer",
                    method: 'POST',
                    data : $('#form-manufacturer').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление производителей...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE ajax_manufacturer'); }                    
                });
    }
}
//********************* Valuta ********************
function add_new_valuta(login){
    var title = 'Добавление новой валюты';
    var content = '<div id="box_add_new_valuta"></div>';    
    modal(title,content);
    $('#box_add_new_valuta').load('/template/include/valuta.php?user='+login+'');
}
function edit_valuta(id){
    var title = 'Редактирование валюты';
    var content = '<div id="box_edit_valuta"></div>';    
    modal(title,content);
    $('#box_edit_valuta').load('/template/include/valuta.php?id='+id+'');
}
function ajax_valuta(op){      
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_valuta",
            data: $('#forma-valuta').serialize() + '&op='+op+'',
            beforeSend: function(){
            },
            success: function(res){ 
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_valuta'); }
        }); 
}
function delete_valuta(){
    if(confirm('Удаляем выделенные элементы?')){  
                $.ajax({
                    url: "/index.php?action=ajax_valuta",
                    method: 'POST',
                    data : $('#form-valuta').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление валюты...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE ajax_valuta'); }                    
                });
    }
}
//***************************** Access ********************************
function get_access(link,name){
    var title = 'Исключения - '+name+'';
    var content = '<div id="box_access"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'600px','margin-left':'-300px'});
    $('#box_access').load('/template/include/access.php?link='+link+'');
}
function get_access_group(link,name){
    var title = 'Изменение доступа для групп - '+name+'';
    var content = '<div id="box_access"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'600px','margin-left':'-300px'});
    $('#box_access').load('/template/include/access_group.php?link='+link+'');
}

function info_order(id){
    var title = 'Информация о заказе';
    var content = '<div id="box_info_order"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'700px','margin-left':'-350px'});
    $('#box_info_order').load('/template/include/cart.php?id='+id+'');
}
function restore_zakazy(){ 
                $.ajax({
                    url: "/index.php?action=ajax_zakazy",
                    method: 'POST',
                    data : $('#form-zakazy').serialize() + "&op=restore",
                    beforeSend: function(){
                        WaitingBarShow('Восстановление заказов...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error RESTORE ajax_zakazy'); }                    
                });
}
function ajax_access(op){      
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_access",
            data: $('#forma-access').serialize() + '&op='+op+'',
            beforeSend: function(){
            },
            success: function(res){ 
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_access'); }
        }); 
}

function ajax_access_group(op){      
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_access_group",
            data: $('#forma-access-group').serialize() + '&op='+op+'',
            beforeSend: function(){
            },
            success: function(res){ 
                // CloseModal();
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_access'); }
        }); 
}

//*********************** Export Exel **************************
function get_file_exel(site_url,type){    
    var data = $('#form-zakazy').serialize();    
    window.location.href = ''+site_url+'/modules/export_exel.php?'+data+'&type='+type+'';
}
function export_exel(site_url){    
    var title = 'Экспорт данных в Exel';
    var content = '<div id="box_export"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'480px','margin-left':'-240px'});
    $('#box_export').html('<div style="padding: 20px 50px;">'+
'<p><img src="/image/list_accept.ico" style="margin: 0px 2px -3px;"> <a href="javascript:void(0);" onclick="get_file_exel(\''+site_url+'\',\'xls\');">В файл xls (Microsoft Office 2003)</a></p>'+
'<p><img src="/image/list_accept.ico" style="margin: 0px 2px -3px;"> <a href="javascript:void(0);" onclick="get_file_exel(\''+site_url+'\',\'xlsx\');">В файл xlsx (Microsoft Office 2007-2010)</a></p>'+
                          '</div>');   
}
function get_file_exel_statistic(site_url,type){    
    var data = $('#stat-datatable-orders-users-statistic').serialize(); 
    var start = $('#between-start').val(); 
    var end = $('#between-end').val();  
    if(!start || !end ){
        window.location.href = ''+site_url+'/modules/export_exel_statistic.php?'+data+'&type='+type+'';
    }else{
        window.location.href = ''+site_url+'/modules/export_exel_statistic.php?'+data+'&type='+type+'&between=1&d_start='+start+'&d_end='+end+'';
    }
    
}
function export_exel_statistic(site_url){    
    var title = 'Экспорт данных в Exel';
    var content = '<div id="box_export"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'480px','margin-left':'-240px'});
    $('#box_export').html('<div style="padding: 20px 50px;">'+
'<p><img src="/image/list_accept.ico" style="margin: 0px 2px -3px;"> <a href="javascript:void(0);" onclick="get_file_exel_statistic(\''+site_url+'\',\'xls\');">В файл xls (Microsoft Office 2003)</a></p>'+
'<p><img src="/image/list_accept.ico" style="margin: 0px 2px -3px;"> <a href="javascript:void(0);" onclick="get_file_exel_statistic(\''+site_url+'\',\'xlsx\');">В файл xlsx (Microsoft Office 2007-2010)</a></p>'+
                          '</div>');   
}

//********************** SESSION_SELCTS ************************
function SESSION_SELCTS(event){
    t=event.target||event.srcElement;
    var op = $(t).attr('id');
    var select = $(t).val();
    if(op=='session_orders'){
            $.ajax({
                url: "/modules/session_selects.php",
                method: 'POST',
                data : {op:op,select:select},
                beforeSend: function(){
                    WaitingBarShow('Изменение сортировки...');
                },
                success: function(data){
                    location.reload();
                },
                error: function() { alert('Error session_orders'); }                    
            });
    }
    if(op=='session_office'){
            $.ajax({
                url: "/modules/session_selects.php",
                method: 'POST',
                data : {op:op,select:select},
                beforeSend: function(){
                    WaitingBarShow('Изменение сортировки...');
                },
                success: function(data){
                    location.reload();
                },
                error: function() { alert('Error session_office'); }                    
            });
    }
    if(op=='session_payment'){
            $.ajax({
                url: "/modules/session_selects.php",
                method: 'POST',
                data : {op:op,select:select},
                beforeSend: function(){
                    WaitingBarShow('Изменение сортировки...');
                },
                success: function(data){
                    location.reload();
                },
                error: function() { alert('Error session_payment'); }                    
            });
    }
    if(op=='session_delivery'){
            $.ajax({
                url: "/modules/session_selects.php",
                method: 'POST',
                data : {op:op,select:select},
                beforeSend: function(){
                    WaitingBarShow('Изменение сортировки...');
                },
                success: function(data){
                    location.reload();
                },
                error: function() { alert('Error session_delivery'); }                    
            });
    }
    if(op=='session_manager'){
            $.ajax({
                url: "/modules/session_selects.php",
                method: 'POST',
                data : {op:op,select:select},
                beforeSend: function(){
                    WaitingBarShow('Изменение сортировки...');
                },
                success: function(data){
                    location.reload();
                },
                error: function() { alert('Error session_manager'); }                    
            });
    }
}

//*********************** DropShipping *************************
function insert_data_dropship(id_table){
    var title = 'Вставка данных';
    var content = '<div id="box_new_dropship"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'600px','margin-left':'-300px'});
    $('#box_new_dropship').load('/template/include/add_data_dropship.php?table='+id_table+'');
}
function ajax_dropship(op){
            $.ajax({
                url: "/index.php?action=ajax_dropship",
                method: 'POST',
                data : $('#forma-dropshipping').serialize() + "&op="+op+"",
                beforeSend: function(){
                    WaitingBarShow('Добавление данных в таблицу...');
                },
                success: function(data){
                    //alert(data);                    
                    //WaitingBarHide();
                    //MessageTray('Данные успешно добавлены.');
                    location.reload();
                },
                error: function() { alert('Error ajax_dropship'); }                    
            });
}
function add_dropshipping_table(){
    var title = 'Cоздание новой таблицы';
    var content = '<div id="box_new_dropship"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'680px','margin-left':'-340px'});
    $('#box_new_dropship').load('/template/include/add_table_dropship.php');
}
function edit_dropshipping_table(id_table){
    var title = 'Редактирование структуры таблицы';
    var content = '<div id="box_new_dropship"></div>';    
    modal(title,content);
    $('#modal-window').css({'width':'680px','margin-left':'-340px'});
    $('#box_new_dropship').load('/template/include/add_table_dropship.php?table='+id_table+'');
}
function delete_dropship(id_table){
    if(confirm('Вы действительно хотите УДАЛИТЬ ЭТУ ТАБЛИЦУ?\nВСЕ ДАННЫЕ в таблице будут уничтожены без возможности восстановления.')){
            
            $.ajax({
                url: "/index.php?action=ajax_dropship",
                method: 'POST',
                data : {op:'destroy', table:id_table},
                beforeSend: function(){
                    WaitingBarShow('Удаление таблицы...');
                },
                success: function(data){
                    //alert(data);
                    MessageTray('Таблица '+id_table+' удалена.');
                    //location.reload();
                    window.location.href = '/?action=dropshipping';
                },
                error: function() { alert('Error DELETE table ajax_dropship'); }                    
            });
    }
}
function delete_data_drop(){
        if(confirm('Вы действительно хотите удалить отмеченные записи?\nДанные в таблице будут уничтожены без возможности восстановления.')){
            $.ajax({
                url: "/index.php?action=ajax_dropship",
                method: 'POST',
                data : $('#form-dropy').serialize() + "&op=delete",
                beforeSend: function(){
                    WaitingBarShow('Удаление записей в таблице...');
                },
                success: function(data){
                    //alert(data);
                    location.reload();
                },
                error: function() { alert('Error DELETE data ajax_dropship'); }                    
            });
        }
}
function create_dropship_table(){
            $('input[type="text"]', '#form-new-table').each(function(){
                if($(this).val() == ""){
                    //alert('Название столбца таблицы не может быть пустым!');
                    //$(this).remove();
                }
            });
            if( $('#form-new-table').find('input').not('[value=""]')){
                alert('Error create table!');
            }else{                
                alert('Создана!');
            }
            
            
                    /*$.ajax({
                        url: "/index.php?action=ajax_dropship",
                        method: 'POST',
                        data : $('#form-new-table').serialize() + "&op=create",
                        beforeSend: function(){
                            WaitingBarShow('Создание новой таблицы...');
                        },
                        success: function(data){
                            //alert(data);
                            location.reload();
                        },
                        error: function() { alert('Error CREATE Table ajax_dropship'); }                    
                    });*/
            
}
function change_dropship_table(){
    
}

//*************************** delivery ****************************
function add_new_delivery(){
    var title = 'Создание нового способа доставки';
    var content = '<div id="box_add_new_delivery"></div>';    
    modal(title,content);
    $('#box_add_new_delivery').load('/template/include/delivery.php');
}
function edit_delivery(id){
    var title = 'Редактирование способа доставки';
    var content = '<div id="box_edit_delivery"></div>';    
    modal(title,content);
    $('#box_edit_delivery').load('/template/include/delivery.php?id='+id+'');
}
function delete_delivery(){
    if(confirm('Удаляем выделенные элементы?')){
        
                $.ajax({
                    url: "/index.php?action=ajax_delivery",
                    method: 'POST',
                    data : $('#form-delivery').serialize() + "&op=delete",
                    beforeSend: function(){
                        WaitingBarShow('Удаление способов доставки...');
                    },
                    success: function(data){
                        //alert(data);
                        location.reload();
                    },
                    error: function() { alert('Error DELETE delivery'); }                    
                });
    }
}
function ajax_delivery(op){    
    var id = $('input[name="id_new_delivery"]').val();
    var name = $('input[name="name_new_delivery"]').val();    
        $.ajax({
            type: "POST",
            url: "/index.php?action=ajax_delivery",
            data: {
                id:id,
                name:name,
                op:op
            },
            beforeSend: function(){
            },
            success: function(res){ 
                //alert(res);
                location.reload();
            },
            error: function() { alert('Ошибка ajax! cod: ajax_delivery'); }
        }); 
}


function printTTN(){
    data = $('#form-zakazy').serialize();
    $.ajax({
        type: "POST",
        url: "/index.php?action=print_ttn",
        data: data,
        async: true,
        beforeSend: function(){
            WaitingBarShow('Идёт создание ТТН...');
        },
        success: function(resp){
            WaitingBarHide();
            console.log(resp);
            var link = resp.slice(resp.indexOf('http:'));
            if (link.indexOf('[]') == -1)
                alert("При создании ТТН произошла ошибка\nПроверьте корректность заполнения выбранных заказов и попробуйте снова");
            else{
                alert("Созданные ТТН будут открыты в новом окне\nЕсли некоторые ТТН не были созданы - \nпроверь корректность заполнения выбранных заказов");
                window.open(link, '_blank');
            	// location.reload();
            }
        },
        error: function() { 
            WaitingBarHide();
            alert("При создании ТТН произошла ошибка\nПроверьте корректность заполнения выбранных заказов и попробуйте снова"); 
        }
    });
}

function updateOrderStatus(){
    $.ajax({
        type: "POST",
        url: "/index.php?action=update_order_status",
        async: true,
        beforeSend: function(){
            WaitingBarShow('Обновление статусов заказов...');
        },
        success: function(data){
            // console.log(data);
            location.reload();
            WaitingBarHide();
        },
        error: function(data) {
            // console.log(data);
            WaitingBarHide();            
            alert('Ошибка ajax! cod: update_order_status'); 
        }
    });
}

var page = 0;
function setOrderFilter(){
    var status, complete;
    status = $('.tab-status-active').attr('id');
    if (status == 'all-tab'){
        status = '';
        complete = '';
    }
    else
    if (status == 'complete-tab') {
        status = ''; 
        complete = true;
    }
    else{   
        status = status.substr(0, status.indexOf('-'));
        complete = '';
    }

    $.ajax({
        url: '/modules/ajax_order_set_filter.php',
        method: 'POST',
        data: {
            productId:$('#select-filter-product').val(),
            status:status,
            complete:complete,
            page: page,
            orderPerPage: $('#session_orders').val(),
            dateFrom:$('#between-start').val(),
            dateTo:$('#between-end').val(),
            searchingStr:$('#input-ajax-search-in-table').val(),
            manager: $('#session_manager').val(),
            // office: $('#session_office').val(),
            payment: $('#session_payment').val(),
            delivery: $('#session_delivery').val()
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...');
            proces = true;  
            $('.navigation').hide();   
        },
        success: function(data){
            // console.log(data);
            $("#table-list tbody tr").remove();
            $("#table-list tbody").append(data);

            if (((page+1) * $('#session_orders').val()) > $('#order-count').val())
                $('#nextPage').attr('disabled', 'disabled');
            else
                $('#nextPage').removeAttr('disabled');

            if (page == 0)
                $('#prePage').attr('disabled', 'disabled');
            else
                $('#prePage').removeAttr('disabled');

            if ($('#order-count').val() != 0)
                $('.navigation').show();
            else
                $('.navigation').hide();

            $('.selected').unbind('click');
            $('.selected').bind('click', function(){ 
                SELECT_SHIFT(); 
            });

        },
        complete: function(){
            proces = false;                                                     

            $('#table-list tbody tr').hover(function(){
                $(this).find(".option-button").show();
            }, function() {
                $(this).find(".option-button").hide();
            });  
            $('.tooltip').tooltip({
                track: false, //true включает "привязку" подсказки к движущемуся указателю мыши
                content: function() {
                    return $(this).attr('title');
                }        
            });
            WaitingBarHide();
        }
    });
}

function setTableListListeners(){
    $('.odd').mouseover(function(event){
        t=event.target||event.srcElement; 
        $(t).parent().find('.option-button').css('display','inline');
    }).mouseout(function(event){
        t=event.target||event.srcElement; 
        $(t).parent().find('.option-button').css('display','none');
    });

    $('.even').mouseover(function(event){
        t=event.target||event.srcElement; 
        $(t).parent().find('.option-button').css('display','inline');
    }).mouseout(function(event){
        t=event.target||event.srcElement; 
        $(t).parent().find('.option-button').css('display','none');
    });
}

function getProducts(){
    $.ajax({
        url: '/modules/get_recomended_product.php',
        method: 'POST',
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...');
            proces = true;  
            $('.navigation').hide();   
        },
        success: function(data){
            // console.log(data);
            $(".recomended-product-table tbody tr").remove();
            $(".recomended-product-table tbody").append(data);

            $('.recomended-product-table').dataTable( {
                "aLengthMenu": [ 10,25,50,100,200,500,1000 ],
                "iDisplayLength": 10,
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
            })
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

function getGeoIPInfo(client_ip, site){
  console.log("getGeoIPInfo");
    if (client_ip != "" && site != "" && client_ip != "185.65.244.18"){
        $.ajax({
            url: "/modules/get_geo_ip_info.php",
            type: "POST",
            data: {
                client_ip: client_ip
            },
            success: function(response){
              console.log("response:", response);
              response = response.slice(response.indexOf("{"));
              if(isValidJSON(response)){
                response = JSON.parse(response);
                $('#addres-by-ip').html(response.city_rus);
              }
            }
        });
    }
}


function printTTN_tmpfnc(){
  if(confirm('Отправить сообщения "Заказ отправлен"?')){  
                $.ajax({
                    url: "/index.php?action=send_sms_sended_tmp",
                    method: 'POST',
                    data : $('#form-zakazy').serialize(),
                    beforeSend: function(){
                        WaitingBarShow('Отправка СМС...');
                    },
                    success: function(response){
                        alert("Сообщения отправлены");
                        WaitingBarHide();
                        console.log(response);
                    }                
      });
    }
}


function sendSMSArrive_tmpfnc(){
  if(confirm('Отправить сообщения "Заказ прибыл"?')){  
                $.ajax({
                    url: "/index.php?action=send_sms_arrive_tmp",
                    method: 'POST',
                    data : $('#form-zakazy').serialize(),
                    beforeSend: function(){
                        WaitingBarShow('Отправка СМС...');
                    },
                    success: function(response){
                        alert("Сообщения отправлены");
                        WaitingBarHide();
                        console.log(response);
                    }                
      });
    }
}