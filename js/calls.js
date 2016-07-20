function guid() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
      .toString(16)
      .substring(1);
  }
  return s4() + s4();
}

var uuid = guid();

var openedOrderId;
var taskTimerId;

$(document).ready(function(){
    window.onbeforeunload = function() {
        setCallTaskState(openedOrderId, 0);
    }

    $('.form-order-container').mouseover(function() {
        deleteCookie("inOrderPage"+uuid);
        document.cookie = "inOrderPage"+uuid+"=true";
    });

    $('.form-order-container').mouseleave(function() {
        deleteCookie("inOrderPage"+uuid);
        document.cookie = "inOrderPage"+uuid+"=false";
    });

    $(window).focus(function() {
        deleteCookie("inWindow"+uuid);
        document.cookie = "inWindow"+uuid+"=true";
    });

    $(window).blur(function() {
        deleteCookie('inWindow'+uuid);
        document.cookie = "inWindow"+uuid+"=false";
    });
});

function setDefaultCookie(){
    if (getCookie('afk') != 'true'){
        deleteCookie('afk');
        document.cookie = "afk=false";
    }
    if (getCookie('dgt') != 'true'){
        deleteCookie('dgt');
        document.cookie = "dgt=false";
    }

    deleteCookie('inWindow'+uuid);
    document.cookie = "inWindow"+uuid+"=true";
    deleteCookie('inOrderPage'+uuid);
    document.cookie = "inOrderPage"+uuid+"=false";
}


function checkAFKState() {
    if (getCookie('afk') == 'true'){
        $('.awayFromKeyboard').prop('checked', 'checked');  
    }
}

function checkDGTState() {
    if (getCookie('dgt') == 'true'){
        $('.dontGetTask').prop('checked', 'checked');  

    }
}

function catchLazyManagers(param, state){
    $.ajax({
        type: "POST",
        url: "/modules/catch_lazy_managers.php",
        data:{
            param: param,
            state: state
        }
    });
}


$(document).ready(function(){

    setDefaultCookie();
    checkAFKState();
    checkDGTState();

    $('.call-settings-container').click(function(){
        $('.call-settings').fadeIn(200);   
    });
    $('.call-setting-close').click(function(){
        $('.call-settings').fadeOut(200);
    });

    $('.awayFromKeyboard').click(function(){
        deleteCookie('afk');
        document.cookie = "afk="+$('.awayFromKeyboard').prop('checked');
        catchLazyManagers('awayFromKeyboard', $('.awayFromKeyboard').prop('checked'));
    });

    $('.dontGetTask').click(function(){
        deleteCookie('dgt');
        document.cookie = "dgt="+$('.dontGetTask').prop('checked');
        catchLazyManagers('dontGetTask', $('.dontGetTask').prop('checked'));
    })
});

function setCallTaskState(orderId, state) {
    $.ajax({
        type: "POST",
        url: "/modules/callTask/callTask.php",
        data: {
            operation: 'setCallTaskState',
            state: state,
            orderId: orderId
        },
        success: function(response){
            response = $.parseJSON(response);
            if (response.success == 'false')
                alert('Error! '+response.error);
        }
    });    
}

function manualCall(phone) {
    if(phone != ''){
        callNotification('Исходящий звонок!<br>Номер '+phone);
        window.location = "tel: "+phone;
    }
}

function autoCall(orderId, phone) {
    console.log('autoCall');
        setCallTaskState(orderId, 1);

        $('.tab-status-active').removeClass('tab-status-active');
        $('#all-tab').addClass('tab-status-active');
        $('#input-ajax-search-in-table').val(phone);
        setOrderFilter();
        $('#input-ajax-search-in-table').val('');
        
        setTimeout(function() {
                edit_zakaz(orderId);
            if(phone != ''){
                callNotification('Автоматический звонок!<br>Заказ №'+orderId+'<br>Телефон: '+phone);
                window.location = "tel: "+phone;
            }
            else
                callNotification('Не указан номер телефона!');
        }, 5000);
}

function getCallTask(){
    // console.log("--------------------------------");
    // console.log("isOpenOverlay "+isOpenOverlay);
    // console.log("afk: "+getCookie('afk'));
    // console.log("dgt: "+getCookie('dgt'));
    // console.log("inWindow"+uuid+" : "+getCookie('inWindow'+uuid));
    // console.log("inOrderPage"+uuid+" : "+getCookie('inOrderPage'+uuid));
    // console.log("--------------------------------");
    //если нет активнх открытых окон
    if (!isOpenOverlay && getCookie('afk') != 'true' && getCookie('dgt') != 'true' && getCookie('inWindow'+uuid) == 'true' && getCookie('inOrderPage'+uuid) == 'true'){
        $.ajax({
            type: "POST",
            url: "/modules/callTasks/callTask.php",
            async: false,
            data: {
                operation: 'getCallTask',
                login: login
            },
            success: function(response){ 
                response = $.parseJSON(response);
                console.log(response);
                if (response.success == true)
                    autoCall(response.id, response.phone);
            },
            error: function(response){
                alert('js error function getCallTask');
            }
        });
    }
}


function autoTaskRequest(login){
    //один раз вызвали, дальше - через каждые 30 секунд
    taskTimerId = setInterval(function(){ getCallTask(login); }, 10000);
}


function openOrderListByTask(){
    var orderId = getUrlVars()["order"];
    var phone = getUrlVars()["phone"];
    if (orderId)
    {
        setCallTaskState(orderId, 1);
        $('#all-tab').addClass('tab-status-active');
        $('#input-ajax-search-in-table').val(phone);
        setOrderFilter();
        // $('#input-ajax-search-in-table').val('');
        setTimeout(function(){
                // callNotification('Исходящий вызов!<br>Заказ №'+orderId+'<br>Телефон: '+phone);
                edit_zakaz(orderId);
                // window.location = "tel: "+phone;
            }, 1);
        // А вот так просто меняется ссылка
        var url = "/?action=zakazy";
        if(window.location != url){
            window.history.pushState(null, null, url);
        }
        // Предотвращаем дефолтное поведение
        return false;
    }
    else{
        $('#3-tab').addClass('tab-status-active');
         setOrderFilter();
    }
}
