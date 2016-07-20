<?php
header('Content-Type: text/html; charset=utf-8'); 
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/engine.php';

session_start();
if($_SESSION['user']){
    //echo '<meta http-equiv="refresh" content="1; url='.SITE_URL.'/">';
    header('Location: '.SITE_URL.'');
    exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=SITE_NAME?></title>
<link href="/image/lock.ico" rel="shortcut icon" type="image/x-icon">
<script type="text/javascript" src="/js/jquery-1.11.0.min.js"></script>
<style>
    @font-face{
        font-family: "web";
        src: url('/fonts/PT_Sans.woff') format('truetype'); 
    }
    @font-face{
        font-family: "magistral";
        src: url('/fonts/magistralc-bold-webfont.ttf') format('truetype'); 
    }
    body{
        font-family: 'web';
        font-size: 13px;
        color: #333;
    }
    #head{
        font-family: 'magistral';
        font-size: 20px;
        color: #D5D5FD;
    }
    #head-text-2{
        font-size: 13px;
        line-height: 8px;
        color: #DADAFD;
    }
    button{
        padding: 4px 24px;
    }
    .footer-login{
        /*border: 1px solid #CCC;*/
        font-family: 'magistral';
        font-size: 12px;
        padding: 14px;
        color: #ABABAB;
        background: url('/image/line-shadow-2.png') no-repeat;
    }
    input{
        border: 1px solid #CCC;
        padding: 3px 5px;
        color: #599CFF;
    }
    input:focus{
        border:1px solid #599CFF;
        box-shadow: 0px 0px 5px #8CCAD9;
        background: #F3F9FB;
    }
    .button, input[type="submit"]{
        background: linear-gradient(to bottom, #F7FBFC 0px, #D9EDF2 40%, #ADD9E4 100%) transparent;
        border: 1px solid #6A9FD0;
        border-radius: 8px;
        -moz-border-radius: 8px;
        -webkit-border-radius: 8px;
        color: #3F80C0;
        cursor: pointer;
        font-family: "magistral";
        font-size: 17px;
        margin: 0px 1px;
        padding: 4px 14px;
        text-shadow: 0px 1px 1px #F6F6F6;
    }
    .button:hover{
        box-shadow: 0px 0px 5px #8CCAD9;
    }
    #message-ajax{
        background: url('/image/line-shadow.png') no-repeat;        
        padding: 4px 2px 2px 2px;
        height: 20px;
        color: #F00;
    }
</style>
<script>
    function Enter(){
        var login = $('input[name="login"]').val();
        var pass = $('input[name="pass"]').val();
        $.ajax({
            type: "POST",
            url: "/admin.php",
            data: {login:login,pass:pass},
            beforeSend: function(){
                $('#message-ajax').html('<img src="/image/ajax-load.gif">');
            },
            success: function(data){ 
                $('#message-ajax').html(data);
            },
            error: function() { alert('Ошибка запроса admin.php!'); }
        });
    }
$(document).ready(function(){
    $('#enter-button').click(function(){
        Enter();
    });
    $("body").keypress(function(e){
        if(e.keyCode==13){
            Enter();
        }
    });
});
</script>
</head>

<body>   
    <table id="table-login-page" width="260px" align="center" border="0" cellpadding="4" style="margin-top:100px;">
        <tr>
            <!--<td width="60px"> <img src="/image/icon-crm.png"> </td>-->
            <td colspan="2"> 
                <!--<div id="head">BA-CRM Engine<br><span id="head-text-2" >Панель управления</span></div> -->
                <!-- <img src="/image/CRM-logo_small.png" style="width: 236px;"> -->
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center"> <div id="message-ajax">&nbsp</div> </td>
        </tr>
        <tr>
            <td align="right">Логин:</td>
            <td> <input type="text" name="login"> </td>
        </tr>
        <tr>
            <td align="right">Пароль:</td>
            <td> <input type="password" name="pass"> </td>
        </tr>
        <tr>
            <td colspan="2" align="center"> <p> <button class="button" id="enter-button">Войти</button> </p> </td>
        </tr>
        <tr>
            <td colspan="2" align="center"><p class="footer-login"></p></td>
        </tr>
    </table>
      
    
</body>
</html>