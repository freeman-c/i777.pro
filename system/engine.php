<?php
function Render(){    
    /*session_start();
    if(!isset($_SESSION['user'])) {
        $_SESSION['user'] = array();
        $_SESSION['user']['access'] = '';
        //$_SESSION['user']['token'] = '';
    }  */   
    //************************************************************************
    $action = empty($_GET['action']) ? 'index' : $_GET['action'];

    switch ($action){
        case('index'):
            header('Location: '.SITE_URL.'/?action=zakazy');
            break;        
        case('logout'):
            logout();
            header('Location: '.SITE_URL.'');
            break;
        case('ajax_groups'):
            ajax_groups();
            break; 
        case('ajax_clients'):
            ajax_clients();
            break;
        case('ajax_group_clients'):
            ajax_group_clients();
            break;        
        case('ajax_users'):
            ajax_users();
            break;
        case('ajax_offices'):
            ajax_offices();
            break;
        case('ajax_statusy'):
            ajax_statusy();
            break;
        case('ajax_zakazy'):
            ajax_zakazy();
            break;
        case('ajax_sender_sms'):
            ajax_sender_sms();
            break;
        case('ajax_sender_email'):
            ajax_sender_email();
            break;
        case('ajax_template_email'):
            ajax_template_email();
            break;
        case('ajax_template_sms'):
            ajax_template_sms();
            break;
        case('ajax_template_script'):
            ajax_template_script();
            return;
            break;
        case('ajax_category'):
            ajax_category();
            break;
        case('ajax_product'):
            ajax_product();
            break;
        case('ajax_manufacturer'):
            ajax_manufacturer();
            break;
        case('ajax_valuta'):
            ajax_valuta();
            break;
        case('ajax_access'):
            ajax_access();
            break;
        case('ajax_access_group'):
            ajax_access_group();
            break;
        //***
        case('load_image_product'):
            load_image_product();
            break;
        case('delete_image_product'):
            delete_image_product();
            break;
        case('ajax_delivery'):
            ajax_delivery();
            break;
        case('print_ttn'):
            require_once $_SERVER['DOCUMENT_ROOT']."/modules/np/createTTN.php";
            printTTN();
            return;
            break;
            
        case('send_sms_sended_tmp'):
            send_sms_sended_tmp();
            return;
        case('send_sms_arrive_tmp'):
            send_sms_arrive_tmp();
            return;

        case('ajax_dropship'):
            ajax_dropship();
            break;   
        //************ 
        case('error'):
            break;
    }
    $arr = array('index','logout',
                    'users',
                    'groups_users',
                    'clients',
                    'offices',
                    'statusy','delivery',
                    'zakazy',
                    'dropshipping',
                    'template_email','template_sms','senders_mail','senders_sms','template_script',
                    'curier',
                    'statistic','manager_stat',
                    'newsletter','sms',
                    'category','products','manufacturer','valuta', 'recomendedProducts',
                    'history',
                    'access','access_group',
                    'cart',
                    'np','state',
                    'pr','statepr',
                    'faq',
                    'groups_clients','ajax_group_clients',
                    'single_task',
                    'ban_ip', 'ban_phone',
                    "binotel_groups","binotel_phones",
                    "statProducts", "statManagers", "statAdvertising",
                    "deliveryOrders","deliveryOrdersStatuses","stockInTrade",
                    "penalties", "activePenalties");
    
    session_start();
    $access_forbidden = getAccess($action);    
    $access_array = explode(', ', $access_forbidden['users']);
  
    $access_group_array = explode(', ', $access_forbidden['groups']);

    require($_SERVER['DOCUMENT_ROOT'] . '/template/header.php');
    //require($_SERVER['DOCUMENT_ROOT'] . '/views/left_colum.php');
    //********************************** CENTER ******************************
    if(!in_array($action,$arr)){
        require($_SERVER['DOCUMENT_ROOT'] . '/template/error.php');
    }else{ 

        //получаем уровень доступа пользователя
        $user = getUserAccessLevelByLogin($_SESSION['user']['login']);
        $access_level = $user['access'];
        $access_type = GetUsersAccesGroupById($access_level);
        // print_r($access_group_array);

        // print_r($user);
        // print_r($access_level);
        // print_r($access_type);


        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/template/'.$action.'.php')) {
            //заблокирован по группе?
            if (in_array($access_type['group_name'], $access_group_array))
                //есть в исключениях?
                if(in_array($_SESSION['user']['login'],$access_array))
                    require($_SERVER['DOCUMENT_ROOT'] . '/template/'.$action.'.php');        
                else
                    require($_SERVER['DOCUMENT_ROOT'] . '/template/access_locked.php');
            else
                require($_SERVER['DOCUMENT_ROOT'] . '/template/'.$action.'.php');        
        } 
        else{
            echo '<p style="font-size:15px; color:#900;">
                     <b>Fatal Error</b>: Not found require template: "<b>'.$action.'</b>" in CRM!
                         <div>В системных шаблонах отсутствует файл <b>'.$action.'.php</b></div>
                  </p>';
        }


        // if( !in_array($_SESSION['user']['login'],$access_array) !in_array($access_type['group_name'], $access_group_array)){
        //     //require($_SERVER['DOCUMENT_ROOT'] . '/template/'.$action.'.php');        
        //     if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/template/'.$action.'.php')) {
        //         require($_SERVER['DOCUMENT_ROOT'] . '/template/'.$action.'.php');
        //     } else {
        //         echo '<p style="font-size:15px; color:#900;">
        //                  <b>Fatal Error</b>: Not found require template: "<b>'.$action.'</b>" in CRM!
        //                      <div>В системных шаблонах отсутствует файл <b>'.$action.'.php</b></div>
        //               </p>';
        //     }
        // }else{
        //     //echo '<h1>Доступ ограничен!</h1>';
        //     require($_SERVER['DOCUMENT_ROOT'] . '/template/access_locked.php');
        // }
    }
    //********************************** ****** ******************************
    //require($_SERVER['DOCUMENT_ROOT'] . '/views/right_colum.php');
    require($_SERVER['DOCUMENT_ROOT'] . '/template/footer.php');
}
?>
