<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/mail/mail.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/callTask/callTask.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/sms/sms.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/history/historyController.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/stockInTrade/stockInTrade.php');


function Authorization($login){
    db_connect();
    $query = "SELECT * FROM users WHERE login='$login'" or die('ошибка контролер 61');
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function logout(){
    session_start();
    db_connect();
    AddLog('1','Пользователь {user} выполнил выход из системы.');
    unset($_SESSION['user']);    
}
//******************************* User *********************************
function GetUserDescription(){
    db_connect();
    session_start();
    $query = "SELECT * 
        FROM users 
        WHERE login = '{$_SESSION['user']['login']}'";
    $result = mysql_query($query);
    $result = dbResultToAssocc($result);
    return $result;
}

function GetUsersAccesGroup(){
    db_connect();
    $query = "SELECT * FROM `access_type`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}

function GetUsersAccesGroupById($id){
    db_connect();
    $query = "SELECT * FROM `access_type` WHERE id = ".$id;
    $result = mysql_query($query);
    $result = mysql_fetch_array($result);
    return $result;
}

function getUsersCountGroup($access){
    db_connect();
    $query = "SELECT COUNT(*) FROM `users` WHERE access='$access'";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    echo $sum[0]; 
}
//*************************** clients ********************************
function getClientsToEmail(){
    db_connect();
    $query = "SELECT * FROM `clients` WHERE status='1' ORDER BY id DESC";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function GetClientsGroups(){
    db_connect();
    $query = "SELECT * FROM `clients_group`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function GetClientsGroup($id){
    db_connect();
    $query = "SELECT * FROM `clients_group` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function getCountClientsGroup($type){
    db_connect();
    $query = "SELECT COUNT(*) FROM `clients` WHERE type='$type'";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    echo $sum[0]; 
}
function getCountAllClientsInGroup(){
    db_connect();
    $query = "SELECT COUNT(*) FROM `clients`";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    echo $sum[0]; 
}

function getClients(){
    db_connect();
    $num = 100;     
    $page = $_GET['page'];    
    $sort = ""; 
    $between = "";
    if($_GET['type']){ $sort = "AND type='".$_GET['type']."'";} 
    if($_GET['complete']){ $sort = "AND date_complete > 0";}
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date <= '".$_GET['d_end']."'";}    
        
        $result0 = mysql_query("SELECT COUNT(*) FROM `clients` WHERE status='1' $sort $between");
        $sum = mysql_fetch_array($result0);
        $posts = $sum[0];
        
    if(empty($_GET['type'])){
        $query = "SELECT * FROM `clients` WHERE status='1' $sort $between ORDER BY id DESC";
    }else{        
        //$query = "SELECT * FROM `clients` WHERE status='1' $sort $between ORDER BY id DESC LIMIT $start, $num";
        //$query = "SELECT * FROM `clients` WHERE status='1' $sort $between ORDER BY id DESC LIMIT $start, $num";
        //mysql_query("DELETE t1 FROM `clients` AS t1, `clients` AS t2 WHERE t1.phone = t2.phone AND t1.id > t2.id");          
    }    
    //***-----------------------------------------------------------    
        $result0 = mysql_query("SELECT COUNT(*) FROM `clients`");
        $sum = mysql_fetch_array($result0);
        $posts = $sum[0];  
    $total = intval(($posts - 1) / $num) + 1; 
    $page = intval($page); 
    if(empty($page) or $page < 0) $page = 1; 
    if($page > $total) $page = $total;
    $start = $page * $num - $num;
    //***---------------------------    
    $query = "SELECT * FROM `clients` WHERE status='1' $sort $between ORDER BY id DESC LIMIT $start, $num";
    //$query = "SELECT * FROM `clients` WHERE status='1' ORDER BY id DESC LIMIT $start, $num";
    
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function navigationClients(){
     db_connect();
    $num = 100;     
    $page = $_GET['page'];  
    
    $sort = ""; 
    $between = "";
    if($_GET['type']){ $sort = "AND type='".$_GET['type']."'";} 
    if($_GET['complete']){ $sort = "AND date_complete > 0";}
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date <= '".$_GET['d_end']."'";}    
     
        $result0 = mysql_query("SELECT COUNT(*) FROM `clients` WHERE status='1' $sort $between");
        $sum = mysql_fetch_array($result0);
        $posts = $sum[0];
        /*$result0 = mysql_query("SELECT COUNT(*) FROM `clients`");
        $sum = mysql_fetch_array($result0);
        $posts = $sum[0];*/
    
    $total = intval(($posts - 1) / $num) + 1; 
    $page = intval($page); 
    if(empty($page) or $page < 0) $page = 1; 
    if($page > $total) $page = $total;
    
    $url = $_SERVER['REQUEST_URI'];
        $q = parse_url($url);        
        $str = $q['query'];
        $array = array();
        $elems = explode("&", $str);
        foreach($elems as $elem){
            $items = explode("=", $elem);
            $array[$items[0]] = $items[1];
        }
        foreach ($array as $key => $value){  
            if($key!=='page'){
                $new_url_string .= $key.'='.$value.'&';
            }else{
                unset($key);
            }            
        }        
        if($_GET['complete']==1){
            $st_name['name']='Cдано';            
        }else{
            $st_name = getStatus($_GET['status']);
            if(!$st_name){
                $st_name['name']='Все';
            }            
        }
        $L = SITE_URL.'/?'.$new_url_string;
        if ($page != 1) $pervpage = '<a class="btn" href="'.$L.'page=1">начало</a> 
                                        <a class="btn" href="'.$L.'page='. ($page - 1) .'">←</a> ';         // Проверяем нужны ли стрелки вперед 
        if ($page != $total) $nextpage = ' <a class="btn" href="'.$L.'page='. ($page + 1) .'">→</a> 
                                            <a class="btn" href="'.$L.'page=' .$total. '">конец</a>'; 
        // Находим две ближайшие страницы с обоих краев, если они есть 
         if($page - 2 > 0) $page2left = ' <a class="btn" href="'.$L.'page='. ($page - 2) .'">'. ($page - 2) .'</a> '; 
         if($page - 1 > 0) $page1left = '<a class="btn" href="'.$L.'page='. ($page - 1) .'">'. ($page - 1) .'</a> '; 
         if($page + 2 <= $total) $page2right = ' <a class="btn" href="'.$L.'page='. ($page + 2) .'">'. ($page + 2) .'</a>'; 
         if($page + 1 <= $total) $page1right = ' <a class="btn" href="'.$L.'page='. ($page + 1) .'">'. ($page + 1) .'</a>';
        // Вывод меню 
        echo "<span class='count-zakazy'>Всего клиентов - ".$posts."</span> &nbsp &nbsp &nbsp";
        echo $pervpage.$page2left.$page1left.'<b class="btn btn-active">'.$page.'</b>'.$page1right.$page2right.$nextpage;
        
    /*$L = SITE_URL.'/?action=clients&';
        // Проверяем нужны ли стрелки назад 
        if ($page != 1) $pervpage = '<a class="btn" href="'.$L.'page=1">начало</a> 
                                        <a class="btn" href="'.$L.'page='. ($page - 1) .'">←</a> ';         // Проверяем нужны ли стрелки вперед 
        if ($page != $total) $nextpage = ' <a class="btn" href="'.$L.'page='. ($page + 1) .'">→</a> 
                                            <a class="btn" href="'.$L.'page=' .$total. '">конец</a>'; 
        // Находим две ближайшие страницы с обоих краев, если они есть 
         if($page - 2 > 0) $page2left = ' <a class="btn" href="'.$L.'page='. ($page - 2) .'">'. ($page - 2) .'</a> '; 
         if($page - 1 > 0) $page1left = '<a class="btn" href="'.$L.'page='. ($page - 1) .'">'. ($page - 1) .'</a> '; 
         if($page + 2 <= $total) $page2right = ' <a class="btn" href="'.$L.'page='. ($page + 2) .'">'. ($page + 2) .'</a>'; 
         if($page + 1 <= $total) $page1right = ' <a class="btn" href="'.$L.'page='. ($page + 1) .'">'. ($page + 1) .'</a>';
        // Вывод меню 
        echo "<span class='count-zakazy'>Всего клиентов - ".$posts."</span> &nbsp &nbsp &nbsp";
        echo $pervpage.$page2left.$page1left.'<b class="btn btn-active">'.$page.'</b>'.$page1right.$page2right.$nextpage; 
        */
} 
 
function getClient($id){
    db_connect();
    $query = "SELECT * FROM `clients` WHERE id={$id}";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}

function ajax_groups(){ 
    $op = $_POST['op'];
    switch ($op){
        case('add'):
            db_connect();
            $add = mysql_query("INSERT INTO `access_type` SET name='".$_POST['name']."'; ");                       
        break;
        
        case('edit'):
            db_connect();
            $edit = mysql_query("UPDATE `access_type` SET name='".$_POST['name']."' WHERE id='".$_POST['id']."' ");
        break;
    
        case('delete'):            
            //$del = mysql_query("DELETE FROM `access_type` WHERE id='".$_POST['id']."' ");
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $delete = 'DELETE FROM `access_type` WHERE id='.(int)$id;
                    mysql_query($delete);
                }
        break;
    }
}

function ajax_group_clients(){ 
    $op = $_POST['op'];
    switch ($op){
        case('add'):
            db_connect();
            $add = mysql_query("INSERT INTO `clients_group` SET name='".$_POST['name']."'; ");                       
        break;
        
        case('edit'):
            db_connect();
            $edit = mysql_query("UPDATE `clients_group` SET name='".$_POST['name']."' WHERE id='".$_POST['id']."' ");
        break;
    
        case('delete'):            
            //$del = mysql_query("DELETE FROM `access_type` WHERE id='".$_POST['id']."' ");
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $delete = 'DELETE FROM `clients_group` WHERE id='.(int)$id;
                    mysql_query($delete);
                }
        break;
    }
}

function ajax_clients(){ 
    $op = $_POST['op'];
    switch ($op){
        case('add'):
            db_connect();
            $phone = preg_replace('/[^0-9]/', '', $_POST['phone']);
            $add = mysql_query("INSERT INTO `clients` SET   
                                    name='".$_POST['name']."',      
                                    phone='".$phone."', 
                                    type='".$_POST['type']."',    
                                    email='".$_POST['email']."',    
                                    description='".$_POST['description']."',    
                                    status='1' ");
            AddLog('1','Добавлен новый клиент пользователем {user}.');
        break;
        
        case('edit'):
            db_connect();
            $phone = preg_replace('/[^0-9]/', '', $_POST['phone']);
            $edit = mysql_query("UPDATE `clients` SET       
                                    name='".$_POST['name']."',      
                                    phone='".$phone."',
                                    type='".$_POST['type']."',
                                    email='".$_POST['email']."',    
                                    description='".$_POST['description']."',    
                                    status='1' 
                                        WHERE id='".$_POST['id']."' ");
            AddLog('1','Обновлены данные клиента пользователем {user}.');
        break;
    
        case('delete'):            
            //$del = mysql_query("DELETE FROM `access_type` WHERE id='".$_POST['id']."' ");
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $delete = 'DELETE FROM `clients` WHERE id='.(int)$id;
                    mysql_query($delete);
                }
                AddLog('1','Удалён клиент пользователем {user}.');
        break;
    }
}
//*************************** users ********************************
function getUsers(){
    db_connect();
    $query = "SELECT * FROM `users` JOIN `users_description` WHERE users.login=users_description.login
    ORDER BY users.access, users_description.surname, users_description.name";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}

function getUsersOne($id){
    db_connect();
    $query = "SELECT * FROM `users` JOIN `users_description` WHERE users.login=users_description.login AND users.id='$id'";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}

function getUser($id){
    db_connect();
    $query = "SELECT * FROM `users` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function getAccessByLogin($login){
    db_connect();
    $query = "SELECT access FROM `users` WHERE login='$login'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function getUserDesc($id){
    db_connect();
    $query = "SELECT * FROM `users_description` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function getUserDescById($id){
    db_connect();
    $query = "SELECT * 
        FROM users_description AS UD
        LEFT JOIN users AS U ON U.login = UD.login
        WHERE U.id={$id}";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function get_user_description_login($login){
    db_connect();
    $query = "SELECT * FROM `users_description` WHERE login='$login'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function getAccessType($id){
    db_connect();
    $query = "SELECT * FROM `access_type` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row['name'];
}
function getPlaceWorkName($id){
    db_connect();
    $query = "SELECT * FROM `place_work` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row['name'];
}
function getPlaceWorkAdress($id){
    db_connect();
    $query = "SELECT * FROM `place_work` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row['adress'];
}
function getPlaceWorkList(){
    db_connect();
    $query = "SELECT * FROM `place_work`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
//***************************** Offices ****************************
function getOffices(){
    db_connect();
    $query = "SELECT * FROM `place_work`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getOffice($id){
    db_connect();
    $query = "SELECT * FROM `place_work` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function ajax_offices(){ 
    $op = $_POST['op'];
    switch ($op){
        case('add'):
            db_connect();
            $add = mysql_query("INSERT INTO `place_work` SET 
                                    name='".$_POST['name']."', 
                                    email='".$_POST['email']."',
                                    adress='".$_POST['adress']."' ");
        break;
        
        case('edit'):
            db_connect();
            $edit = mysql_query("UPDATE `place_work` SET
                                    name='".$_POST['name']."',
                                    email='".$_POST['email']."',
                                    adress='".$_POST['adress']."' WHERE id='".$_POST['id']."' ");
        break;
    
        case('delete'):            
            //$del = mysql_query("DELETE FROM `access_type` WHERE id='".$_POST['id']."' ");
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $delete = 'DELETE FROM `place_work` WHERE id='.(int)$id;
                    mysql_query($delete);
                }
        break;
    }
}
//***************************** Statusy ****************************
function getStatusy(){
    db_connect();
    //$query = "SELECT * FROM `statusy` ORDER BY CASE WHEN sort IS NULL THEN 1 ELSE 0 END, sort ASC";
    $query = "SELECT * FROM `statusy` ORDER BY sort ASC";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getStatusyPayment(){
    db_connect();
    $query = "SELECT * FROM `statusy_payment` ORDER BY CASE WHEN sort IS NULL THEN 1 ELSE 0 END, sort ASC";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getStatusPaymentName($id){
    db_connect();
    $query = "SELECT * FROM `statusy_payment` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row['name'];
}

function getStatus($id){
    db_connect();
    $query = "SELECT * FROM `statusy` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function getStatusPayment($id){
    db_connect();
    $query = "SELECT * FROM `statusy_payment` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function ajax_statusy(){ 
    $op = $_POST['op'];
    switch ($op){
        case('add'):
            db_connect();
            $add = mysql_query("INSERT INTO `statusy` SET 
                                    name='".$_POST['name']."',  
                                    color='".$_POST['color']."' ");
        break;
        
        case('edit'):
            db_connect();
            $edit = mysql_query("UPDATE `statusy` SET
                                    name='".$_POST['name']."',  
                                    color='".$_POST['color']."' WHERE id='".$_POST['id']."' ");
        break;
    
        case('delete'):            
            //$del = mysql_query("DELETE FROM `access_type` WHERE id='".$_POST['id']."' ");
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $delete = 'DELETE FROM `statusy` WHERE id='.(int)$id;
                    mysql_query($delete);
                }
        break;
    }
}
//***************************** Zakazy ****************************
function getOrders(){
    db_connect();
    $sort = ""; 
    $between = "";
    if($_GET['status']){ $sort = "AND status='".$_GET['status']."'";}
    if($_GET['complete']){ $sort = "AND date_complete > 0";}
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date <= '".$_GET['d_end']."'";} // AND date_update >= '2014-06-13' AND date_update <= '2014-06-15'
$session_office = "";
$session_payment = "";
$session_delivery = "";
$session_manager = "";
if(isset($_SESSION['user']['office'])){$session_office = "AND office='".$_SESSION['user']['office']."'";} 
if(isset($_SESSION['user']['payment'])){$session_payment = "AND payment= {$_SESSION['user']['payment']}"; } 
if(isset($_SESSION['user']['delivery'])){$session_delivery = "AND delivery='".$_SESSION['user']['delivery']."'";} 
if(isset($_SESSION['user']['manager'])){$session_manager = "AND user='".$_SESSION['user']['manager']."'";} 
//***----------------------------
    $num = 300;
    if($_SESSION['user']['orders']){$num = $_SESSION['user']['orders'];}    
    $page = $_GET['page']; 
    
        $result0 = mysql_query("SELECT COUNT(*) FROM `zakazy` WHERE cart < 1 $session_office $session_payment $session_delivery $session_manager $sort $between");
        $sum = mysql_fetch_array($result0);
        $posts = $sum[0];
    
    $total = intval(($posts - 1) / $num) + 1; 
    $page = intval($page); 
    if(empty($page) or $page < 0) $page = 1; 
    if($page > $total) $page = $total;
    $start = $page * $num - $num;
    //***---------------------------
    
    if(empty($_GET['status']) && empty($_GET['complete']) && empty($_GET['between'])){
        $query = "SELECT * FROM `zakazy` WHERE cart < 1 $session_office $session_payment $session_delivery $session_manager $sort $between ORDER BY id DESC LIMIT $start, $num";
    }else{        
        $query = "SELECT * FROM `zakazy` WHERE cart < 1 $session_office $session_payment $session_delivery $session_manager $sort $between ORDER BY id DESC LIMIT $start, $num";
    } 
    
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function navigationZakazy(){
     db_connect();
     $sort = ""; 
    $between = "";
    if($_GET['status']){ $sort = "AND status='".$_GET['status']."'";}
    if($_GET['complete']){ $sort = "AND date_complete > 0";}
    if($_GET['between']){ $between = "AND date_update >= '".$_GET['d_start']."' AND date_update <= '".$_GET['d_end']."'";}
$session_office = "";
$session_payment = "";
$session_delivery = "";
$session_manager = "";
if(isset($_SESSION['user']['office'])){$session_office = "AND office='".$_SESSION['user']['office']."'";} 
if(isset($_SESSION['user']['payment'])){$session_payment = "AND payment= {$_SESSION['user']['payment']}"; } 
if(isset($_SESSION['user']['delivery'])){$session_delivery = "AND delivery='".$_SESSION['user']['delivery']."'";} 
if(isset($_SESSION['user']['manager'])){$session_manager = "AND user='".$_SESSION['user']['manager']."'";} 

    $num = 300;
    if($_SESSION['user']['orders']){$num = $_SESSION['user']['orders'];} 
    $page = $_GET['page'];  
    
        $result0 = mysql_query("SELECT COUNT(*) FROM `zakazy` WHERE cart < 1 $session_office $session_payment $session_delivery $session_manager $sort $between");
        $sum = mysql_fetch_array($result0);
        $posts = $sum[0];
        $result_all = mysql_query("SELECT COUNT(*) FROM `zakazy` WHERE cart < 1 $session_office $session_payment $session_delivery $session_manager");
        $suma = mysql_fetch_array($result_all);
        $all = $suma[0];
    
    $total = intval(($posts - 1) / $num) + 1; 
    $page = intval($page); 
    if(empty($page) or $page < 0) $page = 1; 
    if($page > $total) $page = $total;
    
    
    $url = $_SERVER['REQUEST_URI'];
        $q = parse_url($url);        
        $str = $q['query'];
        $array = array();
        $elems = explode("&", $str);
        foreach($elems as $elem){
            $items = explode("=", $elem);
            $array[$items[0]] = $items[1];
        }
        //array_pop($array);
        foreach ($array as $key => $value){  
            if($key!=='page'){
                $new_url_string .= $key.'='.$value.'&';
            }else{
                unset($key);
            }            
        }        
        if($_GET['complete']==1){
            $st_name['name']='Cдано';            
        }else{
            $st_name = getStatus($_GET['status']);
            if(!$st_name){
                $st_name['name']='Все';
            }            
        }
        //echo $new_url_string;
        $L = SITE_URL.'/?'.$new_url_string;
        //echo $L;
        // Проверяем нужны ли стрелки назад 
        // '<a class="btn" href="'.$L.'page=1">начало</a>
    //$uri = SITE_URL.$_SERVER['REQUEST_URI'];
        if ($page != 1) $pervpage = '<a class="btn" href="'.$L.'page=1">начало</a> 
                                        <a class="btn" href="'.$L.'page='. ($page - 1) .'">←</a> ';         // Проверяем нужны ли стрелки вперед 
        if ($page != $total) $nextpage = ' <a class="btn" href="'.$L.'page='. ($page + 1) .'">→</a> 
                                            <a class="btn" href="'.$L.'page=' .$total. '">конец</a>'; 
        // Находим две ближайшие страницы с обоих краев, если они есть 
         if($page - 2 > 0) $page2left = ' <a class="btn" href="'.$L.'page='. ($page - 2) .'">'. ($page - 2) .'</a> '; 
         if($page - 1 > 0) $page1left = '<a class="btn" href="'.$L.'page='. ($page - 1) .'">'. ($page - 1) .'</a> '; 
         if($page + 2 <= $total) $page2right = ' <a class="btn" href="'.$L.'page='. ($page + 2) .'">'. ($page + 2) .'</a>'; 
         if($page + 1 <= $total) $page1right = ' <a class="btn" href="'.$L.'page='. ($page + 1) .'">'. ($page + 1) .'</a>';
        // Вывод меню 
        echo "<span class='count-zakazy'>Всего заказов - ".$all." &nbsp &nbsp &nbsp &nbsp ".$st_name['name']." - ".$posts."</span> &nbsp &nbsp &nbsp";
        echo $pervpage.$page2left.$page1left.'<b class="btn btn-active">'.$page.'</b>'.$page1right.$page2right.$nextpage;         
} 

/*function getOrdersMore(){
    db_connect();
    $sort = ""; 
    $between = "";
    if($_GET['status']){ $sort = "AND status='".$_GET['status']."'";}
    if($_GET['complete']){ $sort = "AND date_complete > 0";}
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date <= '".$_GET['d_end']."'";} // AND date >= '2014-06-13' AND date <= '2014-06-15'
    
    $startFrom = $_POST['startFrom'];
    $query = "SELECT * FROM `zakazy` WHERE cart < 1 $sort $between ORDER BY id DESC LIMIT {$startFrom}, 15";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}*/
function getOrdersOnlyManager($place_work){
    db_connect();
    $sort = ""; 
    $between = "";
    if($_GET['status']){ $sort = "AND status='".$_GET['status']."'";}
    if($_GET['complete']){ $sort = "AND date_complete > 0";}
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date <= '".$_GET['d_end']."'";} // AND date >= '2014-06-13' AND date <= '2014-06-15'
//***----------------------------
$session_payment = "";
$session_delivery = "";
$session_manager = "";
if(isset($_SESSION['user']['payment'])){$session_payment = "AND payment= {$_SESSION['user']['payment']}"; } 
if(isset($_SESSION['user']['delivery'])){$session_delivery = "AND delivery='".$_SESSION['user']['delivery']."'";} 
if(isset($_SESSION['user']['manager'])){$session_manager = "AND user='".$_SESSION['user']['manager']."'";} 

    $num = 300;
    if($_SESSION['user']['orders']){$num = $_SESSION['user']['orders'];}    
        $page = $_GET['page']; 

            $result0 = mysql_query("SELECT COUNT(*) FROM `zakazy` WHERE cart < 1 $session_payment $session_delivery $session_manager $sort $between");
            $sum = mysql_fetch_array($result0);
            $posts = $sum[0];

        $total = intval(($posts - 1) / $num) + 1; 
        $page = intval($page); 
        if(empty($page) or $page < 0) $page = 1; 
        if($page > $total) $page = $total;
        $start = $page * $num - $num;
        //***---------------------------
    
    if(empty($_GET['status']) && empty($_GET['complete']) && empty($_GET['between'])){
        $query = "SELECT * FROM `zakazy` WHERE cart < 1 $session_payment $session_delivery $session_manager $sort $between AND office IN (0,'$place_work') ORDER BY id DESC LIMIT $start, $num";
    }else{        
        $query = "SELECT * FROM `zakazy` WHERE cart < 1 $session_payment $session_delivery $session_manager $sort $between AND office IN (0,'$place_work') ORDER BY id DESC LIMIT $start, $num";
    }
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
    /*$query = "SELECT * FROM `zakazy` WHERE cart < 1 $sort $between AND office IN (0,'$place_work') ORDER BY id DESC";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;*/
}
function navigationZakazyOnlyManager($place_work){
     db_connect();
     $sort = ""; 
    $between = "";
    if($_GET['status']){ $sort = "AND status='".$_GET['status']."'";}
    if($_GET['complete']){ $sort = "AND date_complete > 0";}
    if($_GET['between']){ $between = "AND date_update >= '".$_GET['d_start']."' AND date_update <= '".$_GET['d_end']."'";}

$session_payment = "";
$session_delivery = "";
$session_manager = "";
if(isset($_SESSION['user']['payment'])){$session_payment = "AND payment= {$_SESSION['user']['payment']}"; } 
if(isset($_SESSION['user']['delivery'])){$session_delivery = "AND delivery='".$_SESSION['user']['delivery']."'";} 
if(isset($_SESSION['user']['manager'])){$session_manager = "AND user='".$_SESSION['user']['manager']."'";} 
    
    $num = 300;
    if($_SESSION['user']['orders']){$num = $_SESSION['user']['orders'];} 
    $page = $_GET['page'];  
    
        $result0 = mysql_query("SELECT COUNT(*) FROM `zakazy` WHERE cart < 1 $session_payment $session_delivery $session_manager $sort $between AND office IN (0,'$place_work')");
        $sum = mysql_fetch_array($result0);
        $posts = $sum[0];
    
    $total = intval(($posts - 1) / $num) + 1; 
    $page = intval($page); 
    if(empty($page) or $page < 0) $page = 1; 
    if($page > $total) $page = $total;
    
    
    $url = $_SERVER['REQUEST_URI'];
        $q = parse_url($url);        
        $str = $q['query'];
        $array = array();
        $elems = explode("&", $str);
        foreach($elems as $elem){
            $items = explode("=", $elem);
            $array[$items[0]] = $items[1];
        }
        foreach ($array as $key => $value){  
            if($key!=='page'){
                $new_url_string .= $key.'='.$value.'&';
            }else{
                unset($key);
            }            
        }        
        if($_GET['complete']==1){
            $st_name['name']='Cдано';            
        }else{
            $st_name = getStatus($_GET['status']);
            if(!$st_name){
                $st_name['name']='Все';
            }            
        }
        $L = SITE_URL.'/?'.$new_url_string;
        if ($page != 1) $pervpage = '<a class="btn" href="'.$L.'page=1">начало</a> 
                                        <a class="btn" href="'.$L.'page='. ($page - 1) .'">←</a> ';         // Проверяем нужны ли стрелки вперед 
        if ($page != $total) $nextpage = ' <a class="btn" href="'.$L.'page='. ($page + 1) .'">→</a> 
                                            <a class="btn" href="'.$L.'page=' .$total. '">конец</a>'; 
        // Находим две ближайшие страницы с обоих краев, если они есть 
         if($page - 2 > 0) $page2left = ' <a class="btn" href="'.$L.'page='. ($page - 2) .'">'. ($page - 2) .'</a> '; 
         if($page - 1 > 0) $page1left = '<a class="btn" href="'.$L.'page='. ($page - 1) .'">'. ($page - 1) .'</a> '; 
         if($page + 2 <= $total) $page2right = ' <a class="btn" href="'.$L.'page='. ($page + 2) .'">'. ($page + 2) .'</a>'; 
         if($page + 1 <= $total) $page1right = ' <a class="btn" href="'.$L.'page='. ($page + 1) .'">'. ($page + 1) .'</a>';
        // Вывод меню 
        echo "<span class='count-zakazy'>".$st_name['name']." - ".$posts."</span> &nbsp &nbsp &nbsp";
        echo $pervpage.$page2left.$page1left.'<b class="btn btn-active">'.$page.'</b>'.$page1right.$page2right.$nextpage;         
 } 

//****************************** Orders TTN ********************************
 //**************************************************************************
function getOrdersTTN_NP(){
    db_connect();    
    $status = "status='14'";
    if($_GET['status']){ $status = "status='".$_GET['status']."'";}
    
    $num = 20;     
    $page = $_GET['page']; 
    
        $result0 = mysql_query("SELECT COUNT(*) FROM `zakazy` WHERE `delivery` LIKE 'Новая Почта' AND $status AND ttn > 0"); //WHERE ttn NOT LIKE ''
        $sum = mysql_fetch_array($result0);
        $posts = $sum[0];
    
    $total = intval(($posts - 1) / $num) + 1; 
    $page = intval($page); 
    if(empty($page) or $page < 0) $page = 1; 
    if($page > $total) $page = $total;
    $start = $page * $num - $num;
    //***---------------------------       
        $query = "SELECT * FROM `zakazy` WHERE `delivery` LIKE 'Новая Почта' AND $status AND ttn > 0 ORDER BY date DESC LIMIT $start, $num";
    
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function navigationZakazyTTN_NP(){
    db_connect();
     
    $status = "status='14'";
    if($_GET['status']){ $status = "status='".$_GET['status']."'";} 
    
    $num = 20;     
    $page = $_GET['page'];  
    
        $result0 = mysql_query("SELECT COUNT(*) FROM `zakazy` WHERE `delivery` LIKE 'Новая Почта' AND $status AND ttn > 0");
        $sum = mysql_fetch_array($result0);
        $posts = $sum[0];
    
    $total = intval(($posts - 1) / $num) + 1; 
    $page = intval($page); 
    if(empty($page) or $page < 0) $page = 1; 
    if($page > $total) $page = $total;
    
        $L = SITE_URL.'/?action=state&';
        if($_GET['status']){
            $L = SITE_URL.'/?action=state&status='.$_GET['status'].'&';
        }
        
        if ($page != 1) $pervpage = '<a class="btn" href="'.$L.'page=1">начало</a> 
                                        <a class="btn" href="'.$L.'page='. ($page - 1) .'">←</a> ';         // Проверяем нужны ли стрелки вперед 
        if ($page != $total) $nextpage = ' <a class="btn" href="'.$L.'page='. ($page + 1) .'">→</a> 
                                            <a class="btn" href="'.$L.'page=' .$total. '">конец</a>'; 
        // Находим две ближайшие страницы с обоих краев, если они есть 
         if($page - 2 > 0) $page2left = ' <a class="btn" href="'.$L.'page='. ($page - 2) .'">'. ($page - 2) .'</a> '; 
         if($page - 1 > 0) $page1left = '<a class="btn" href="'.$L.'page='. ($page - 1) .'">'. ($page - 1) .'</a> '; 
         if($page + 2 <= $total) $page2right = ' <a class="btn" href="'.$L.'page='. ($page + 2) .'">'. ($page + 2) .'</a>'; 
         if($page + 1 <= $total) $page1right = ' <a class="btn" href="'.$L.'page='. ($page + 1) .'">'. ($page + 1) .'</a>';
        // Вывод меню 
        echo "<span class='count-zakazy'>Всего: ".$posts." &nbsp &nbsp &nbsp &nbsp Отображено: ".$num."</span> &nbsp &nbsp &nbsp";
        echo $pervpage.$page2left.$page1left.'<b class="btn btn-active">'.$page.'</b>'.$page1right.$page2right.$nextpage;         
 }  
function getOrdersTTN_PR(){
    db_connect();    
    $status = "status='14'";
    if($_GET['status']){ $status = "status='".$_GET['status']."'";}
    
    $num = 10;     
    $page = $_GET['page']; 
    
        $result0 = mysql_query("SELECT COUNT(*) FROM `zakazy` WHERE `delivery` LIKE 'Почта Росии' AND $status AND ttn > 0"); //WHERE ttn NOT LIKE ''
        $sum = mysql_fetch_array($result0);
        $posts = $sum[0];
    
    $total = intval(($posts - 1) / $num) + 1; 
    $page = intval($page); 
    if(empty($page) or $page < 0) $page = 1; 
    if($page > $total) $page = $total;
    $start = $page * $num - $num;
    //***---------------------------       
        $query = "SELECT * FROM `zakazy` WHERE `delivery` LIKE 'Почта Росии' AND $status AND ttn > 0 ORDER BY date DESC LIMIT $start, $num";
    
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function navigationZakazyTTN_PR(){
    db_connect();
     
    $status = "status='14'";
    if($_GET['status']){ $status = "status='".$_GET['status']."'";} 
    
    $num = 10;     
    $page = $_GET['page'];  
    
        $result0 = mysql_query("SELECT COUNT(*) FROM `zakazy` WHERE `delivery` LIKE 'Почта Росии' AND $status AND ttn > 0");
        $sum = mysql_fetch_array($result0);
        $posts = $sum[0];
    
    $total = intval(($posts - 1) / $num) + 1; 
    $page = intval($page); 
    if(empty($page) or $page < 0) $page = 1; 
    if($page > $total) $page = $total;
    
        $L = SITE_URL.'/?action=statepr&';
        if($_GET['status']){
            $L = SITE_URL.'/?action=statepr&status='.$_GET['status'].'&';
        }
        
        if ($page != 1) $pervpage = '<a class="btn" href="'.$L.'page=1">начало</a> 
                                        <a class="btn" href="'.$L.'page='. ($page - 1) .'">←</a> ';         // Проверяем нужны ли стрелки вперед 
        if ($page != $total) $nextpage = ' <a class="btn" href="'.$L.'page='. ($page + 1) .'">→</a> 
                                            <a class="btn" href="'.$L.'page=' .$total. '">конец</a>'; 
        // Находим две ближайшие страницы с обоих краев, если они есть 
         if($page - 2 > 0) $page2left = ' <a class="btn" href="'.$L.'page='. ($page - 2) .'">'. ($page - 2) .'</a> '; 
         if($page - 1 > 0) $page1left = '<a class="btn" href="'.$L.'page='. ($page - 1) .'">'. ($page - 1) .'</a> '; 
         if($page + 2 <= $total) $page2right = ' <a class="btn" href="'.$L.'page='. ($page + 2) .'">'. ($page + 2) .'</a>'; 
         if($page + 1 <= $total) $page1right = ' <a class="btn" href="'.$L.'page='. ($page + 1) .'">'. ($page + 1) .'</a>';
        // Вывод меню 
        echo "<span class='count-zakazy'>Всего: ".$posts." &nbsp &nbsp &nbsp &nbsp Отображено: ".$num."</span> &nbsp &nbsp &nbsp";
        echo $pervpage.$page2left.$page1left.'<b class="btn btn-active">'.$page.'</b>'.$page1right.$page2right.$nextpage;         
 }  

function countOrdersToStatus($status){
    $between = "";
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date <= '".$_GET['d_end']."'";}
    
    db_connect();
    $result = mysql_query("SELECT COUNT(*) FROM `zakazy` WHERE status='$status' AND cart='0' $between");
    $sum = mysql_fetch_array($result);
    return $sum[0];
}
function countOrdersToStatusPayment($payment){
    $between = "";
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date <= '".$_GET['d_end']."'";}
    
    db_connect();
    $result = mysql_query("SELECT COUNT(*) FROM `zakazy` WHERE payment='$payment' AND cart='0' $between");
    $sum = mysql_fetch_array($result);
    return $sum[0];
}
function countTotalOrdersToStatusPayment($payment){
    $between = "";
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date <= '".$_GET['d_end']."'";}
    
    db_connect();
    $result = mysql_query("SELECT SUM(total) as summa FROM `zakazy` WHERE payment='$payment' AND cart='0' $between");
    $sum = mysql_fetch_array($result);
    return $sum['summa'];
}

function countOrdersToUsers($login){
    db_connect();
    $result = mysql_query("SELECT COUNT(*) FROM `zakazy` WHERE user='$login' ");
    $sum = mysql_fetch_array($result);
    return $sum[0];
}
function countAllOrders(){
    db_connect();    
    $result = mysql_query("SELECT COUNT(*) FROM `zakazy` ");
    $sum = mysql_fetch_array($result);
    return $sum[0];
}
function getOrder($id){
    db_connect();
    $query = "SELECT * FROM `zakazy` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function getOrdersInCart(){
    $query = "SELECT * FROM `zakazy` WHERE cart > 0 ORDER BY id DESC";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}

function ajax_zakazy(){ 
    db_connect();
    date_default_timezone_set(TIME_ZONE);
    if ($_POST['delivery'] == 'Новая Почта')
        $_POST['delivery_adress'] = $_POST['npCity'].', '.$_POST['npWarehouse'];

    if(empty($_POST['user']))
        $_POST['user'] = $_SESSION['user']['login'];                    
    else
        $_POST['user'] = $_POST['user'];
    $_POST['phone'] = preg_replace('/[^0-9]/', '', $_POST['phone']);
    switch ($_POST['op']){
        case('add'):          
            
            mysql_query("INSERT INTO `zakazy` SET 
                                    order_id='".$_SESSION['user']['new_order']."',   
                                    bayer_name='".$_POST['bayer_name']."', 
                                    gender = '".$_POST['gender']."',
                                    age = '".$_POST['age']."',     
                                    phone='".$_POST['phone']."',     
                                    email='".$_POST['email']."',                                        
                                    total='".$_POST['total']."',  
                                    date='".date('Y-m-d')."',
                                    date_update='".date('Y-m-d')."',
                                    date_stat='".date('Y-m-d')."',                                        
                                    status='".$_POST['status']."',
                                    ip='".$_SERVER['REMOTE_ADDR']."',   
                                    delivery='".$_POST['delivery']."',      
                                    delivery_adress='".$_POST['delivery_adress']."',                                        
                                    ttn='".trim($_POST['ttn'])."',                                          
                                    user='{$_POST['user']}',
                                    office='".$_POST['office']."',
                                    payment='".$_POST['payment']."',
                                    date_complete='".$_POST['date_complete']."',
                                    new='0',
                                    cart='0',
                                    comment='".$_POST['comment']."' ") or die(mysql_error());
            
            logOrderAdd();

            $currentOrder = mysql_query("SELECT id, status
                FROM zakazy
                WHERE phone = {$_POST['phone']}
                AND user='{$_POST['user']}'
                AND bayer_name = '{$_POST['bayer_name']}'") or die(mysql_error());
            $currentOrder = mysql_fetch_assoc($currentOrder);

            if (!empty($_POST['call-date-time'])){
                $_POST['call-date-time'] = date('Y-m-d H:i:s', strtotime($_POST['call-date-time']));
                if ($_POST['tim'] == 'true')
                    $priority = 60;
                else
                    $priority = 20;
                addCallTask($currentOrder['id'], $_POST['call-date-time'], $priority, 0);
            }
            else
                deleteCallTask($currentOrder['id']);

            unset($_SESSION['user']['new_order']);


        break;
        
        case('edit'):
            
            changeProductStock($_POST['id'], $_POST['status'], "order");

            $before = mysql_fetch_array(mysql_query('SELECT * FROM zakazy WHERE id = '.$_POST['id']));
            $phone = preg_replace('/[^0-9]/', '', $_POST['phone']);
            $edit = mysql_query("UPDATE `zakazy` SET 
                                    bayer_name='".$_POST['bayer_name']."',   
                                    gender = '".$_POST['gender']."',
                                    age = '".$_POST['age']."',   
                                    phone='".$phone."',     
                                    email='".$_POST['email']."',    
                                    total='".$_POST['total']."',
                                    date_update='".date('Y-m-d')."',                                        
                                    status='".$_POST['status']."', 
                                    cancel_description='".$_POST['cancel_description']."',  
                                    delivery='".$_POST['delivery']."',      
                                    delivery_adress='".$_POST['delivery_adress']."',
                                    ttn='".trim($_POST['ttn'])."',                                          
                                    user='{$_POST['user']}', 
                                    office='".$_POST['office']."',
                                    payment='".$_POST['payment']."',
                                    date_complete='".$_POST['date_complete']."',
                                    new='0',
                                    cart='0',
                                    comment='".$_POST['comment']."'
                                        WHERE id='".$_POST['id']."' ") or die(mysql_error());
            
            //если поле времени не пустое и статус заказа не изменился - добавить задание
            if (!empty($_POST['call-date-time']) && $_POST['status'] == $before['status']){
                $_POST['call-date-time'] = date('Y-m-d H:i:s', strtotime($_POST['call-date-time']));
                if ($_POST['tim'] == 'true')
                    $priority = 60;
                else
                    $priority = 20;
                addCallTask($_POST['id'], $_POST['call-date-time'], $priority, 0);
            }
            else 
                deleteCallTask($_POST['id']);
                // addCallTask($_POST['id'], date('Y-m-d H:i:s'), 20, 2);

            logOrderEdit($before);

        break;
    
        case('delete'):            
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    
                    $delete = "UPDATE `zakazy` SET cart='1' WHERE id='".(int)$id."' ";
                    mysql_query($delete);
                    deleteCallTask($id);

            AddLog('1','Заказ №'.$id.' удалён пользователем {user}. Перемещён в корзину.');
                }                
        break;
        
        case('restore'):
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $query = "SELECT * FROM `zakazy` WHERE id='".$id."' ";
                    $result = mysql_query($query);
                    $row = mysql_fetch_array($result);
                    
                    $restore = "UPDATE `zakazy` SET cart='0' WHERE id='".(int)$id."' ";
                    mysql_query($restore);
            AddLog('1','Заказ №'.$row['order_id'].' восстановлен пользователем {user}.');
                }                
        break;
        
        case('destroy'):
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();                    
                    $query = "SELECT * FROM `zakazy` WHERE id='".$id."' ";
                    $result = mysql_query($query);
                    $row = mysql_fetch_array($result);
                    
                    $del = "DELETE FROM `product_order` WHERE order_id='".$row['order_id']."' ";
                    mysql_query($del);
            
                    $delete = 'DELETE FROM `zakazy` WHERE id='.(int)$id;
                    mysql_query($delete);

            AddLog('1','Заказ №'.$row['order_id'].' удалён пользователем {user} без возможности восстановления.');
                }                
        break;
    }
}

//***************************** Template Email ****************************
function getTemplatesEmail(){
    db_connect();
    $query = "SELECT * FROM `templates_email`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getTemplateEmail($id){
    db_connect();
    $query = "SELECT * FROM `templates_email` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function ajax_template_email(){ 
    $op = $_POST['op'];
    switch ($op){
        case('add'):
            db_connect();
            //$text = nl2br(addslashes($_POST['text']));
            //$text = nl2br($_POST['text']);
            $add = mysql_query("INSERT INTO `templates_email` SET
                                    title='".$_POST['title']."',    
                                    text='".$_POST['text_editor']."',
                                    sender='".$_POST['sender']."' ");
        break;
        
        case('edit'):
            //print_r($_POST);
            db_connect();
            $edit = mysql_query("UPDATE `templates_email` SET
                                    title='".$_POST['title']."',    
                                    text='".$_POST['text_editor']."',
                                    sender='".$_POST['sender']."' WHERE id='".$_POST['id']."' ");
        break;
    
        case('delete'): 
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $delete = 'DELETE FROM `templates_email` WHERE id='.(int)$id;
                    mysql_query($delete);
                }
        break;
    }
}
//***************************** Template SMS ****************************
function getTemplatesSMS(){
    db_connect();
    $query = "SELECT * FROM `templates_sms`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getTemplateSMS($id){
    db_connect();
    $query = "SELECT * FROM `templates_sms` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function ajax_template_sms(){ 
    $op = $_POST['op'];
    switch ($op){
        case('add'):
            db_connect();
            //$text = nl2br(addslashes($_POST['text']));
            $text = nl2br($_POST['text']);
            $add = mysql_query("INSERT INTO `templates_sms` SET
                                    title='".$_POST['title']."',    
                                    text='".$text."',
                                    sender='".$_POST['sender']."' ");
        break;
        
        case('edit'):
            db_connect();
            $edit = mysql_query("UPDATE `templates_sms` SET
                                    title='".$_POST['title']."',    
                                    text='".$_POST['text']."',
                                    sender='".$_POST['sender']."' WHERE id='".$_POST['id']."' ");
        break;
    
        case('delete'): 
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $delete = 'DELETE FROM `templates_sms` WHERE id='.(int)$id;
                    mysql_query($delete);
                }
        break;
    }
}


//***************************** Template Script ****************************
function getTemplatesScript(){
    db_connect();
    $query = "SELECT TSC.*, TSP.name AS p_name, TSP.title AS p_title, TSP.text as p_text FROM templates_script AS TSC
            LEFT JOIN templates_script AS TSP ON TSP.name = TSC.parent_name";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getTemplateScript($id){
    db_connect();
    // $query = "SELECT * FROM templates_script WHERE id = '$id'";

    $query = "SELECT TSC.*, TSP.name AS p_name, TSP.title AS p_title, TSP.text as p_text FROM templates_script AS TSC
            LEFT JOIN templates_script AS TSP ON TSP.name = TSC.parent_name
            WHERE TSC.id='$id'";

    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function ajax_template_script(){ 
    date_default_timezone_set(TIME_ZONE);
    $op = $_POST['op'];
    switch ($op){
        case('add'):
            db_connect();
            //$text = nl2br(addslashes($_POST['text']));
            // $text = nl2br($_POST['text']);
            $add = mysql_query("INSERT INTO `templates_script` SET
                                    type = ".$_POST['type'].",
                                    name = 'scr-".date('YmdHis')."',
                                    parent_name = '".$_POST['parent_name']."',   
                                    title='".$_POST['title']."',    
                                    text='".$text."' ") or die(mysql_errno());
        break;
        
        case('edit'):
            db_connect();
            // $text = nl2br($_POST['text']);
            $edit = mysql_query("UPDATE `templates_script` SET
                                    type = ".$_POST['type'].",
                                    parent_name = '".$_POST['parent_name']."',
                                    title='".$_POST['title']."',    
                                    text='".$_POST['text']."'
                                    WHERE id=".$_POST['id']) or die(mysql_errno());
        break;
    
        case('delete'): 
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $delete = 'DELETE FROM `templates_script` WHERE id='.(int)$id;
                    mysql_query($delete);
                }
        break;
    }
}

//***************************** Sender Email ****************************
function getSendersEmail(){
    db_connect();
    $query = "SELECT * FROM `sender_email`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getSenderEmail($id){
    db_connect();
    $query = "SELECT * FROM `sender_email` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function ajax_sender_email(){ 
    $op = $_POST['op'];
    date_default_timezone_set(TIME_ZONE);
    switch ($op){
        case('add'):
            db_connect();
            $add = mysql_query("INSERT INTO `sender_email` SET 
                                    name='".$_POST['name']."',  
                                    email='".$_POST['email']."' ");
        break;
        
        case('edit'):
            db_connect();
            $edit = mysql_query("UPDATE `sender_email` SET
                                    name='".$_POST['name']."',  
                                    email='".$_POST['email']."' WHERE id='".$_POST['id']."' ");
        break;
    
        case('delete'): 
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $delete = 'DELETE FROM `sender_email` WHERE id='.(int)$id;
                    mysql_query($delete);
                }
        break;
    }
}

//***************************** Sender SMS ****************************
function getSendersSMS(){
    db_connect();
    $query = "SELECT * FROM `sender_sms`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getSenderSMS($id){
    db_connect();
    $query = "SELECT * FROM `sender_sms` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function ajax_sender_sms(){ 
    $op = $_POST['op'];
    date_default_timezone_set(TIME_ZONE);
    switch ($op){
        case('add'):
            db_connect();
            $add = mysql_query("INSERT INTO `sender_sms` SET 
                                    name='".$_POST['name']."',  
                                    turbosms='".$_POST['turbosms']."' ");
        break;
        
        case('edit'):
            db_connect();
            $edit = mysql_query("UPDATE `sender_sms` SET
                                    name='".$_POST['name']."',  
                                    turbosms='".$_POST['turbosms']."' WHERE id='".$_POST['id']."' ");
        break;
    
        case('delete'): 
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $delete = 'DELETE FROM `sender_sms` WHERE id='.(int)$id;
                    mysql_query($delete);
                }
        break;
    }
}
//*************************** Orders for Curier **************************
function getOrdersForCurier(){
    $session_office = "";
    $session_payment = "";
    $session_delivery = "WHERE delivery !='Самовывоз'";
    if(isset($_SESSION['user']['office'])){$session_office = "AND office='".$_SESSION['user']['office']."'";} 
    if(isset($_SESSION['user']['payment'])){$session_payment = "AND payment='".$_SESSION['user']['payment']."'";} 
    if(isset($_SESSION['user']['delivery'])){$session_delivery = "WHERE delivery='".$_SESSION['user']['delivery']."'";} 
    db_connect();
    $query = "SELECT * FROM `zakazy` $session_delivery $session_office $session_payment AND status='11' AND cart='0' ORDER BY date DESC";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getProductsInOrder($order_id){
    db_connect();
    $query = "SELECT * FROM `product_order` WHERE order_id='$order_id' ";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
//***************************** Category ****************************
function getParentCategories(){
    db_connect();
    $query = "SELECT * FROM `category` WHERE parent_id='0'";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getCategories($parent_id){
    db_connect();
    $query = "SELECT * FROM `category` WHERE parent_id='$parent_id'";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getCategory($id){
    db_connect();
    $query = "SELECT * FROM `category` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}

function ajax_category(){ 
    $op = $_POST['op'];
    date_default_timezone_set(TIME_ZONE);
    switch ($op){
        case('add'):
            db_connect();
            $add = mysql_query("INSERT INTO `category` SET 
                                    name='".$_POST['name']."',
                                    parent_id='".$_POST['parent_id']."',
                                    icon='',
                                    status='1',
                                    date='".date('Y-m-d')."' ");
            AddLog('1','Добавлена новая категория \"'.$_POST['name'].'\" пользователем {user}.');
        break;
        
        case('edit'):
            db_connect();
            $edit = mysql_query("UPDATE `category` SET
                                    name='".$_POST['name']."',
                                    parent_id='".$_POST['parent_id']."',
                                    icon='',
                                    date='".date('Y-m-d')."' WHERE id='".$_POST['id']."' ");
            AddLog('1','Внесены изменения категории \"'.$_POST['name'].'\" пользователем {user}.');
        break;
    
        case('change_status'):
            db_connect();
            $edit = mysql_query("UPDATE `category` SET
                                    status='".$_POST['status']."' WHERE id='".$_POST['id']."' ");
        break;
    
        case('delete'): 
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $query = "SELECT * FROM `category` WHERE id='".$id."' ";
                    $result = mysql_query($query);
                    $row = mysql_fetch_array($result);
                    
                    $delete = 'DELETE FROM `category` WHERE id='.(int)$id;
                    mysql_query($delete);
            AddLog('0','Категория \"'.$row['name'].'\" (id:'.$id.') удалена пользователем {user}.');
                }
        break;
    }
}
//***************************** Product ****************************
function getCountOrdersProductOne($id, $status){
    $between = "";
    if($_GET['between']){ $between = "AND zakazy.date >= '".$_GET['d_start']."' AND zakazy.date <= '".$_GET['d_end']."'";}    
    db_connect();
    $query = "SELECT COUNT(*) FROM `product_order` INNER JOIN `zakazy` ON product_order.order_id = zakazy.order_id WHERE product_order.product_id='$id' AND zakazy.status='$status' $between AND zakazy.cart < 1 AND product_order.status_buy = 1 ";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}

function getCountOrdersProductAll($id, $status){
    $between = "";
    if($_GET['between']){ $between = "AND zakazy.date >= '".$_GET['d_start']."' AND zakazy.date <= '".$_GET['d_end']."'";}    
    db_connect();
    $query = "SELECT COUNT(*) FROM `product_order` INNER JOIN `zakazy` ON product_order.order_id = zakazy.order_id WHERE product_order.product_id='$id' AND zakazy.status='$status' $between AND zakazy.cart < 1 ";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}

function ProductAllPrice($id, $status){
    $between = "";
    if($_GET['between']){ $between = "AND zakazy.date >= '".$_GET['d_start']."' AND zakazy.date <= '".$_GET['d_end']."'";}    
    db_connect();
    $query = "SELECT * FROM `product_order` INNER JOIN `zakazy` ON product_order.order_id = zakazy.order_id WHERE product_order.product_id='$id' AND zakazy.status='$status' $between AND zakazy.cart < 1 ";
    $result = mysql_query($query);
    //$result = db_result_to_array($result);
    return $result;
    //$row = mysql_fetch_array($result);
    //return $result; 
}

function getProducts(){
    db_connect();
    $query = "SELECT * FROM `product` ORDER BY id DESC";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}

function getProduct($id){
    db_connect();
    $query = "SELECT P.*
        FROM product AS P
        WHERE P.id = {$id}";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}

function getProductsOrderQ($product_id, $status){
    $between = "";
    if($_GET['between']){ $between = "AND zakazy.date >= '".$_GET['d_start']."' AND zakazy.date <= '".$_GET['d_end']."'";}
    db_connect();
    $query = "SELECT * FROM `product_order` INNER JOIN `zakazy` ON product_order.order_id = zakazy.order_id WHERE product_order.product_id='$product_id' AND zakazy.status='$status' $between AND zakazy.cart < 1";
    //$query = "SELECT * FROM `product_order` WHERE product_id='$product_id' ";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}

function getProductValuteSymbol($id){
    db_connect();
    $query = "SELECT * FROM `product` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row['valuta'];
}

function getProductsDoprodaja(){
    db_connect();
    $query = "SELECT * FROM `product` WHERE parent_id > 0";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getProductsOrder($order_id){
    db_connect();
    $query = "SELECT *
    FROM product_order AS PO
    WHERE order_id='$order_id' ";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}

//*************************** Manufacturer **************************
function getManufacturers(){
    db_connect();
    $query = "SELECT * FROM `manufacturer`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getManufacturer($id){
    db_connect();
    $query = "SELECT * FROM `manufacturer` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function ajax_manufacturer(){ 
    $op = $_POST['op'];
    switch ($op){
        case('add'):
            db_connect();
            $add = mysql_query("INSERT INTO `manufacturer` SET 
                                    name='".$_POST['name']."',
                                    image='',
                                    description='".$_POST['description']."',
                                    status='1' ");
            AddLog('1','Добавлен новый производитель '.$_POST['name'].' пользователем {user}.');
        break;
        
        case('edit'):
            db_connect();
            $edit = mysql_query("UPDATE `manufacturer` SET
                                    name='".$_POST['name']."',
                                    image='',
                                    description='".$_POST['description']."'    
                                WHERE id='".$_POST['id']."' ");
            AddLog('1','Обновлена информация о производителе '.$_POST['name'].' пользователем {user}.');
        break;
        
        case('change_status'):
            db_connect();
            $edit = mysql_query("UPDATE `manufacturer` SET
                                    status='".$_POST['status']."' WHERE id='".$_POST['id']."' ");
        break;
    
        case('delete'): 
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $delete = 'DELETE FROM `manufacturer` WHERE id='.(int)$id;
                    mysql_query($delete);
                }
                AddLog('1','Удалён производитель id: '.$id.' пользователем {user}.');
        break;
    }
}
//*************************** Valuta **************************
function getValuts(){
    db_connect();
    $query = "SELECT * FROM `valuta`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getValuta($id){
    db_connect();
    $query = "SELECT * FROM `valuta` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function getValut($symbol){
    db_connect();
    $query = "SELECT * FROM `valuta` WHERE symbol='$symbol'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function ajax_valuta(){ 
    $op = $_POST['op'];
    switch ($op){
        case('add'):
            db_connect();
            $add = mysql_query("INSERT INTO `valuta` SET 
                                    name='".$_POST['name']."',
                                    symbol='".$_POST['symbol']."' ");
            AddLog('1','Добавлена валюта '.$_POST['name'].' ('.$_POST['symbol'].') пользователем {user}.');
        break;
        
        case('edit'):
            db_connect();
            $edit = mysql_query("UPDATE `valuta` SET
                                    name='".$_POST['name']."',
                                    symbol='".$_POST['symbol']."'    
                                WHERE id='".$_POST['id']."' ");
            AddLog('1','Обновлена информация о валюте '.$_POST['name'].' ('.$_POST['symbol'].') пользователем {user}.');
        break;
    
        case('delete'): 
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $delete = 'DELETE FROM `valuta` WHERE id='.(int)$id;
                    mysql_query($delete);
                }
                AddLog('1','Удалёна валюта id: '.$id.' пользователем {user}.');
        break;
    }
}

//*************************** Access **************************
function getAccessList(){
    db_connect();
    $query = "SELECT * FROM `access`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getAccess($link){
    db_connect();
    $query = "SELECT * FROM `access` WHERE link='$link' ";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
// function getAccessGroupList(){
//     db_connect();
//     $query = "SELECT * FROM `access`";
//     $result = mysql_query($query);
//     $result = db_result_to_array($result);
//     return $result;
// }
// function getAccessGroup($link){
//     db_connect();
//     $query = "SELECT * FROM `access_group` WHERE link='$link' ";
//     $result = mysql_query($query);
//     $row = mysql_fetch_array($result);
//     return $row;
// }

function ajax_access(){ 
    $op = $_POST['op'];
    switch ($op){        
        case('edit'):
            db_connect();
            $edit = mysql_query("UPDATE `access` SET users='".$_POST['users']."' WHERE link='".$_POST['link']."' ");
        break;
    }
}

function ajax_access_group(){ 
    $op = $_POST['op'];
    switch ($op){        
        case('edit'):
            db_connect();
            $edit = mysql_query("UPDATE `access` SET groups='".$_POST['groups']."' WHERE link='".$_POST['link']."' ");
        break;
    }
}

//************************** Statistic ****************************
function getCountOrdersUser($user,$status){
    $between = "";
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date_update <= '".$_GET['d_end']."'";}    
    db_connect();
    $query = "SELECT COUNT(*) FROM `zakazy` WHERE user='$user' AND status='$status' $between AND cart < 1";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}


function getCountOrdersUserOrderStatusOne($user,$status){
    $between = "";
    if($_GET['between']){ $between = "AND zakazy.date >= '".$_GET['d_start']."' AND zakazy.date_update <= '".$_GET['d_end']."'";}    
    db_connect();
    $query = "SELECT COUNT(*) FROM `zakazy` INNER JOIN `product_order` ON product_order.order_id = zakazy.order_id WHERE zakazy.user='$user' AND zakazy.status='$status' $between AND zakazy.cart < 1 AND product_order.status_buy = 1 ";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}

function getCountOrdersUserOrderStatusTwo($user,$status){
    $between = "";
    if($_GET['between']){ $between = "AND zakazy.date >= '".$_GET['d_start']."' AND zakazy.date_update <= '".$_GET['d_end']."'";}    
    db_connect();
    $query = "SELECT COUNT(*) FROM `zakazy` INNER JOIN `product_order` ON product_order.order_id = zakazy.order_id WHERE zakazy.user='$user' AND zakazy.status='$status' $between AND zakazy.cart < 1 AND product_order.status_buy = 2 ";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}

function getCountOrdersUserOrderStatusThree($user,$status){
    $between = "";
    if($_GET['between']){ $between = "AND zakazy.date >= '".$_GET['d_start']."' AND zakazy.date_update <= '".$_GET['d_end']."'";}    
    db_connect();
    $query = "SELECT COUNT(*) FROM `zakazy` INNER JOIN `product_order` ON product_order.order_id = zakazy.order_id WHERE zakazy.user='$user' AND zakazy.status='$status' $between AND zakazy.cart < 1 AND product_order.status_buy = 3 ";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}

function getCountOrdersUserOrderStatusBuyThree($user,$status){
    $between = "";
    if($_GET['between']){ $between = "AND zakazy.date >= '".$_GET['d_start']."' AND zakazy.date_update <= '".$_GET['d_end']."'";}    
    db_connect();
    $query = "SELECT COUNT(`status_buy`) FROM `product_order` LEFT JOIN `zakazy` ON product_order.order_id = zakazy.order_id WHERE zakazy.user='$user' AND zakazy.status='$status' $between AND zakazy.cart < 1 AND product_order.status_buy=3";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}

function getCountOrdersUserOrderStatusBuyTwo($user,$status){
    $between = "";
    if($_GET['between']){ $between = "AND zakazy.date >= '".$_GET['d_start']."' AND zakazy.date_update <= '".$_GET['d_end']."'";}    
    db_connect();
    $query = "SELECT COUNT(`status_buy`) FROM `product_order` LEFT JOIN `zakazy` ON product_order.order_id = zakazy.order_id WHERE zakazy.user='$user' AND zakazy.status='$status' $between AND zakazy.cart < 1 AND product_order.status_buy=2";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}

function getCountOrdersUserOrderStatusBuyOne($user,$status){
    $between = "";
    if($_GET['between']){ $between = "AND zakazy.date >= '".$_GET['d_start']."' AND zakazy.date_update <= '".$_GET['d_end']."'";}    
    db_connect();
    $query = "SELECT COUNT(`status_buy`) FROM `product_order` LEFT JOIN `zakazy` ON product_order.order_id = zakazy.order_id WHERE zakazy.user='$user' AND zakazy.status='$status' $between AND zakazy.cart < 1 AND product_order.status_buy=1";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}

function getCountAllOrdersUser($user){
    $between = "";
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date_update <= '".$_GET['d_end']."' AND cart < 1";}    
    db_connect();
    $query = "SELECT COUNT(*) FROM `zakazy` WHERE user='$user' $between";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}
function getCountAllOrdersUserStatusBuyOne($user){
    $between = "";
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date_update <= '".$_GET['d_end']."' AND cart < 1";}    
    db_connect();
    $query = "SELECT COUNT(`status_buy`) FROM `product_order` LEFT JOIN `zakazy` ON product_order.order_id = zakazy.order_id WHERE zakazy.user='$user' AND zakazy.status=18 $between AND zakazy.cart < 1 ";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}
/*function getCountAllOrdersUserStatusBuyTwo($user){
    $between = "";
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date_update <= '".$_GET['d_end']."' AND cart < 1";}    
    db_connect();
    $query = "SELECT COUNT(*) FROM `zakazy` WHERE user='$user' $between";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}
function getCountAllOrdersUserStatusBuyThree($user){
    $between = "";
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date_update <= '".$_GET['d_end']."' AND cart < 1";}    
    db_connect();
    $query = "SELECT COUNT(*) FROM `zakazy` WHERE user='$user' $between";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}*/
function getCountOrdersOffice($office,$status){
    $between = "";
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date_update <= '".$_GET['d_end']."' AND cart < 1";}    
    db_connect();
    $query = "SELECT COUNT(*) FROM `zakazy` WHERE office='$office' AND status='$status' $between";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}
function getCountAllOrdersOffice($office){
    $between = "";
    if($_GET['between']){ $between = "AND date >= '".$_GET['d_start']."' AND date_update <= '".$_GET['d_end']."' AND cart < 1";}    
    db_connect();
    $query = "SELECT COUNT(*) FROM `zakazy` WHERE office='$office' $between";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}
function CountStatus($id){
    db_connect();
    $query = "SELECT COUNT(*) FROM `zakazy` WHERE status='$id' AND cart < 1 ";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}
function getMinDateInOrders(){
    date_default_timezone_set(TIME_ZONE);
    db_connect();
    $query = "SELECT date FROM `zakazy` ORDER BY date ASC LIMIT 1";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function getStatisticDiagram($status,$date){
    db_connect();
//if(!$_GET['status']){$status = '18';}else{$status = $_GET['status'];}
    $query = "SELECT COUNT(*) FROM `zakazy` WHERE status='$status' AND date_update='$date'";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0];
    //$i=0;    
    /*foreach($result as $row) {
        echo "{";
        echo "name: '".$row['name']."',";
        echo "data: [";
        echo "[".strtotime($row['date'])*1000,(int)$row['id']."]";
        echo "]},";
    }*/
    
    /*while($row = mysql_fetch_array($data)) {
        $rows[$i]=array(strtotime($row['date'])*1000,(int)$row['total']);
        $i++;
    }    
    $stat = getStatus($status);
    echo "{";
    echo "name: '".$stat['name']."',";
    echo "data: ";
    print_r(json_encode($rows)).'';
    echo "},";*/
}
//***************************** Dropshipping ****************************
function getDropshippingDocumentList(){
    db_connect();
    $query = "SELECT * FROM `dropshipping`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getDropshippingTableTitle($id,$column){
    db_connect();
    $query = "SELECT * FROM `dropshipping_table_".$id."_desc` WHERE name='$column'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row['title'];
}
/*function getContentDropshippingTable($id){
    db_connect();
    $query = "SELECT * FROM `dropshipping_table_".$id."`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function showColumnsTableDropshipping($id){
    db_connect();
    $query = "SHOW COLUMNS FROM `dropshipping_table_".$id."`";
    $result = mysql_query($query); 
    $result = db_result_to_array($result);
    return $result;
}*/
function getDropshippingTableName($id){
    db_connect();
    $query = "SELECT * FROM `dropshipping_table_".$id."_desc` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row['name'];
}
function getLastIDinDropship(){
    db_connect();            
    $query = "SELECT * FROM `dropshipping` ORDER BY id DESC LIMIT 1";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function ajax_dropship(){
    error_reporting();
    $op = $_POST['op'];
    switch ($op){        
        // создание новой таблицы
        case('create'):
            db_connect(); 
        //********** step 1 ********** 
            $last_id = getLastIDinDropship();
            $new_id = $last_id['id'] + 1;
            mysql_query("INSERT INTO `dropshipping` SET id='$new_id', name='".$_POST['name_table']."' ");            
            
            array_pop($_POST); //удаляет последний элемент в массиве (op = create)
            array_shift($_POST); //удаляет первый элемент в массиве (name_table)
            //print_r($_POST);           
        //********** step 2 **********
            foreach($_POST as $key => $value){
                $column_name[] = $key.' TEXT';
            }
            mysql_query("CREATE TABLE `dropshipping_table_".$new_id."`
                            (
                                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                ".implode(", ", $column_name).",
                                date DATE
                            )");
            
        //********** step 3 **********            
            mysql_query("CREATE TABLE `dropshipping_table_".$new_id."_desc` 
                            (
                                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                name VARCHAR(255),
                                title VARCHAR(255),
                                sort VARCHAR(3) 
                            )");
            mysql_query("INSERT INTO `dropshipping_table_".$new_id."_desc` SET
                            name='id',
                            title='id',
                            sort='1' ");
            $d_keys = array();
            $d_values = array();
            $n=0; $t=0; $s=2;
            foreach($_POST as $key => $value) {
                $d_keys[] = $key;
                $d_values[] = $value;
                mysql_query("INSERT INTO `dropshipping_table_".$new_id."_desc` SET
                            name='".$d_keys[$n++]."',
                            title='".$d_values[$t++]."',
                            sort='".$s++."' ");
            }
            $query = "SELECT COUNT(*) FROM `dropshipping_table_".$new_id."_desc`";
            $result = mysql_query($query);
            $sum = mysql_fetch_array($result);
            $d_sort = $sum[0] + 1;
            mysql_query("INSERT INTO `dropshipping_table_".$new_id."_desc` SET
                            name='date',
                            title='Дата',
                            sort='".$d_sort."' ");
            //header('Location: http://'.SITE_NAME.'/?action=dropshipping&table='.$new_id.'');
        break;
    
        // добавление записи в таблицу
        case('add'):
            db_connect();
            foreach(array_keys($_POST) as $key){
                $arr[$key] = mysql_real_escape_string($_POST[$key]);
            }
            // удаляем POST['op'] в массиве $arr
            $last1 = array_search($arr['op'], $arr);
                if($last1 !== false){ unset($arr[$last1]); }
            array_pop($arr); //удаляет последний элемент в массиве (POST['table'])
            //print_r($arr);
            $d_keys = array();
            $d_values = array();
            foreach($arr as $key => $value) {
                $d_keys[] = $key;
                $d_values[] = $value;
            }
            $insert = "INSERT INTO `dropshipping_table_".$_POST['table']."` 
                         (`".implode("`, `", $d_keys)."`) 
                         VALUES 
                         ('".implode("', '", $d_values)."')";
            echo $insert;
            mysql_query($insert) or die(mysql_error());
            AddLog('1','Добавлена запись '.$_POST['id'].' в таблицу '.$_POST['table'].' пользователем {user}.');
            //echo 'Данные успешно добавлены.';
        break;
        
        // обновление данных в таблице
        case('update'):
            db_connect();
            $edit = mysql_query("UPDATE `dropshipping_table_".$_POST['table']."` SET
                                    ".$_POST['name']."='".$_POST['text']."'    
                                WHERE id='".$_POST['id']."' ");
            AddLog('1','Обновлена информация поля '.$_POST['id'].' в таблице '.$_POST['table'].' пользователем {user}.');
        break;
        
        // переименование таблицы
        case('rename'):
            db_connect();
            $edit = mysql_query("UPDATE `dropshipping` SET
                                    name='".$_POST['name']."'    
                                WHERE id='".$_POST['id']."' ");
            AddLog('1','Таблица '.$_POST['id'].' переименована пользователем {user}.');
        break;
        
        // изменение структуры таблицы
        /*case('edit'):
            db_connect();
            $edit = mysql_query("UPDATE `dropshipping_table_".$_POST['id']."` SET
                                    name='".$_POST['name']."',
                                    image='',
                                    description='".$_POST['description']."'    
                                WHERE id='".$_POST['id']."' ");
            AddLog('1','Обновлена информация '.$_POST['name'].' в таблице '.$_POST['table'].' пользователем {user}.');
        break;*/
    
        // удаление строки в таблице    
        case('delete'):
                foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $delete = "DELETE FROM `dropshipping_table_".$_POST['table']."` WHERE id='$id' ";
                    mysql_query($delete);
                }
            AddLog('0','Удалёна запись '.$id.' в таблице '.$_POST['table'].' пользователем {user}.');
        break;
        
        // удаление таблицы
        case('destroy'):
                    db_connect();
                    $delete1 = "DELETE FROM `dropshipping` WHERE id='".$_POST['table']."' ";                    
                    $delete2 = "DROP TABLE `dropshipping_table_".$_POST['table']."`";
                    $delete3 = "DROP TABLE `dropshipping_table_".$_POST['table']."_desc`"; 
                    mysql_query($delete1);
                    mysql_query($delete2);
                    mysql_query($delete3);
            AddLog('0','Удалёна таблица '.$_POST['table'].' пользователем {user}.');
        break;
    }
}


//***************************** Email **************************
function getLimitEmailSetting(){
    db_connect();
    $query = "SELECT * FROM `email_limit` WHERE id='1'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function UpdateLimitCurrentEmailZeroing(){
    db_connect();
    $query = mysql_query("UPDATE `email_limit` SET
                            date_today=NOW(),
                            current='0' 
                          WHERE id='1'");
}
function UpdateLimitCurrentEmailValue(){
    db_connect();
    $query = mysql_query("UPDATE `email_limit` SET
                            date_today=NOW(),
                            current=current 
                          WHERE id='1'");
}
//********************* Delivery ************************
function getDeliverys(){
    db_connect();
    //$query = "SELECT * FROM `statusy` ORDER BY CASE WHEN sort IS NULL THEN 1 ELSE 0 END, sort ASC";
    $query = "SELECT * FROM `delivery` ORDER BY sort ASC";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getDelivery($id){
    db_connect();
    $query = "SELECT * FROM `delivery` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}
function CountDeliveryInOrders($delivery){
    db_connect();
    $query = "SELECT COUNT(*) FROM `zakazy` WHERE delivery='$delivery' AND cart < 1 ";
    $result = mysql_query($query);
    $sum = mysql_fetch_array($result);
    return $sum[0]; 
}
function ajax_delivery(){ 
    $op = $_POST['op'];
    switch ($op){
        case('add'):
            db_connect();
            $add = mysql_query("INSERT INTO `delivery` SET 
                                    name='".$_POST['name']."' ");
        break;
        
        case('edit'):
            db_connect();
            $edit = mysql_query("UPDATE `delivery` SET
                                    name='".$_POST['name']."' WHERE id='".$_POST['id']."' ");
        break;
    
        case('delete'):            
            //$del = mysql_query("DELETE FROM `access_type` WHERE id='".$_POST['id']."' ");
            foreach ($_POST['need_delete'] as $id => $value) {
                    db_connect();
                    $delete = 'DELETE FROM `delivery` WHERE id='.(int)$id;
                    mysql_query($delete);
                }
        break;
    }
}

function getAdvertise(){
    db_connect();
    $query = "SELECT utm_source FROM zakazy WHERE utm_source != '' AND cart = 0 ORDER BY utm_source";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    for ($i=0; $i < count($result); $i++) { 
        $utm_source[$i] = $result[$i]['utm_source'];
    }
    $result = array();
    $result['utm_source'] = array_unique($utm_source);
    return $result;
}

function getMarketGidParam(){
      db_connect();
    $query = "SELECT utm_term, utm_content, utm_campaign FROM zakazy WHERE utm_source LIKE '%MarketGid%' AND utm_source IS NOT NULL AND cart = 0";
    $result = mysql_query($query);
    $result = db_result_to_array($result);

    for ($i=0; $i < count($result); $i++) { 
        $utm_term[$i] = $result[$i]['utm_term'];
        $utm_content[$i] = $result[$i]['utm_content'];
        $utm_campaign[$i] = $result[$i]['utm_campaign'];
    }
    $result = array();
    $result['utm_term'] = array_unique($utm_term);
    $result['utm_content'] = array_unique($utm_content);
    $result['utm_campaign'] = array_unique($utm_campaign);
    return $result;
}

function getSaleManagers(){
    db_connect();
    $query = "SELECT ud.name, ud.surname, ud.login FROM users_description as ud, users as u WHERE u.login = ud.login AND u.access = 3 ORDER BY ud.surname, ud.name";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}

function getUserAccessLevelByLogin($login){
    db_connect();
    $query = "SELECT access FROM `users` WHERE login='$login'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row;
}


function getRecomendedProductList($product_id){
    db_connect();
    $query = "SELECT * FROM recomended_products WHERE product_id = {$product_id}";
    $result = mysql_query($query);
    $result = dbResultToAssocc($result);
    return $result;
}

function getRecomendedProductListInOrder($order_id){
    db_connect();
    $query = "SELECT DISTINCT recomended_product_id 
        FROM recomended_products AS RP
        LEFT JOIN product_order AS PO ON PO.product_id = RP.product_id
        WHERE PO.order_id = '{$order_id}'";
    $result = mysql_query($query);
    $result = dbResultToAssocc($result);
    return $result;
}

function checkOrderDublicate($param)
{
    // ПРОВЕРКА НА ДУБЛИ
    //возвращает false если дубль
    switch ($param['type']) {
        case 'OrderFromSite':
            $result = mysql_query("SELECT id FROM zakazy WHERE phone = '".$param['phone']."' AND ip = '".$param['ip']."' AND site = '".$param['site']."' AND status = 3 AND new = 1 AND cart = 0") or die(mysql_error());
            $row = db_result_to_array($result);
            if (count($row) == 0 || empty($row))
                return true;
            break;

        case 'GetCall':
            $result = mysql_query("SELECT id FROM zakazy WHERE phone = '".$param['phone']."' AND site = '".$param['site']."' AND status = 3 AND new = 1 AND cart = 0") or die(mysql_error());
            $row = db_result_to_array($result);
            if (count($row) == 0 || empty($row))
                return true;
            break;
        default:
            return true;
    }
    return false;
}

function isNewClient($phone){
    db_connect();
    $query = "SELECT id FROM zakazy 
        WHERE phone = '{$phone}' 
        AND cart = 0
        LIMIT 1";
    $result = mysql_query($query) or die(mysql_error());
    $result = mysql_fetch_assoc($result);    
    if (empty($result))
        return true;
    else
        return false;
}


//это блядь две временных функции, написанных левой ногой на коленке
//но нет же сука ничего более постоянного чем временное (26.05.16)
function send_sms_sended_tmp(){
    db_connect();
    $turboSMSConnect = turboSMSAuth();
    foreach ($_POST['need_delete'] as $id => $value) {
        $query = "SELECT delivery FROM zakazy WHERE id = {$id}";
        print_r($query);
        $result = mysql_query($query) or die(mysql_error());
        $row = mysql_fetch_array($result);
        print_r($row);
        if ($row['delivery'] == 'Новая Почта'){  
            sendSMS($id, $turboSMSConnect);
        }
    }
}

function send_sms_arrive_tmp(){
    db_connect();
    $turboSMSConnect = turboSMSAuth();
    foreach ($_POST['need_delete'] as $id => $value) {
        $query = "SELECT delivery FROM zakazy WHERE id = {$id}";
        print_r($query);
        $result = mysql_query($query) or die(mysql_error());
        $row = mysql_fetch_array($result);
        print_r($row);
        if ($row['delivery'] == 'Новая Почта'){  
            sendSMS($id, $turboSMSConnect);
        }
    }
}
?>
