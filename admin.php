<?php header('Content-Type: text/html; charset=utf-8'); ?>
<style>    
</style>
<?php 
error_reporting(0);
//sleep(1);
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/engine.php';

$auth = Authorization($_POST['login']);
$user = $auth['login'];
$pass = $auth['password'];

    if($user == $_POST['login'] && $pass == md5($_POST['pass'])){        
        session_start();
        $_SESSION['user']['login'] = $user;
    //*** token ***
        if (!$_SESSION['user']['token']){
         $_SESSION['user']['token'] = uniqid();
        }
    db_connect();
    mysql_query("UPDATE users SET ip='".$_SERVER['REMOTE_ADDR']."', online='".date('Y-m-d G:i:s')."' WHERE login='".$user."' ") or die('ошибка выполнения запроса');
    //*** log ****    
    AddLog('1','Пользователь {user} выполнил вход - авторизован.');
        //echo '<meta http-equiv="refresh" content="0; url='.SITE_URL.'/">';
    ?>
    <script>
    $(document).ready(function() {
        window.location.href = '<?=SITE_URL?>/?action=zakazy';
    });
    </script>
    <?php
        exit;
    }else{ 
        echo '<div id="message">Ошибка логина или пароля!!!</div>'; 
        AddLog('0','Попытка входа под именем "'.$_POST['login'].'" - авторизация не пройдена.');
        }
?>
<script type="text/javascript">
$(document).ready(function() {
    setTimeout($('#message').fadeOut(6000), 2000);
});
</script>