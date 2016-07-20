<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/system/db.php';

function getOS($userAgent) {
    $oses = array (
        'iPhone' => '(iPhone)',
        'Windows 3.11' => 'Win16',
        'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)', // Используем регулярное выражение
        'Windows 98' => '(Windows 98)|(Win98)',
        'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
        'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
        'Windows 2003' => '(Windows NT 5.2)',
        'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
        'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
        'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
        'Windows ME' => 'Windows ME',
        'Open BSD'=>'OpenBSD',
        'Sun OS'=>'SunOS',
        'Linux'=>'(Linux)|(X11)',
        'Safari' => '(Safari)',
        'Macintosh'=>'(Mac_PowerPC)|(Macintosh)',
        'QNX'=>'QNX',
        'BeOS'=>'BeOS',
        'OS/2'=>'OS/2',
        'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)'
    );  
    error_reporting(E_ALL ^ E_DEPRECATED);
    foreach($oses as $os=>$pattern){
        if(eregi($pattern, $userAgent)) { // проход по массиву $oses для поиска соответствующей операционной системы.
            return $os;
        }
    }
    return 'не определено';
}
function user_browser($agent) {
    preg_match("/(MSIE|Opera|Firefox|Chrome|Version)(?:\/| )([0-9.]+)/", $agent, $browser_info);
    list(,$browser,$version) = $browser_info;
    if ($browser == 'Opera' && $version == '9.80') return 'Opera '.substr($agent,-5);
    if ($browser == 'Version') return 'Safari '.$version;
    if (!$browser && strpos($agent, 'Gecko')) return 'Browser based on Gecko';
    return $browser.' '.$version;
}

function getLogs($date, $str, $logType = ""){
    date_default_timezone_set(TIME_ZONE);
    if (!empty($date))
        $dateWhere = "AND L.date = '{$date}'";
    if (!empty($str))
        $strWhere = "AND (L.text LIKE '%{$str}%' OR L.user LIKE '%{$str}%')";
    if (empty($logType))
        $logTypeWhere = "(L.logType IS NULL OR L.logType = '')";
    else
        $logTypeWhere = "L.logType = '{$logType}'";
    db_connect();    
    //id is not null не несет никакой логической нагрузки, просто так проще запрос писать)
    $query = "SELECT * 
            FROM logs AS L
            WHERE 
            $logTypeWhere
            $dateWhere 
            $strWhere 
            ORDER BY L.datetime DESC,
            L.id DESC";
    // print_r($query);
    $result = mysql_query($query) or die(mysqlResponseFail($query, mysql_error()));
    
    return dbResultToAssocc($result);
}

//$type - 1 - успех, 0 - ошибка //сам только узнал об этом:)
function AddLog($type, $text, $logType = ""){
    session_start();
    db_connect();
    date_default_timezone_set(TIME_ZONE);
    mysql_query("INSERT INTO logs SET
                ip='".$_SERVER['REMOTE_ADDR']."',
                datetime='".date('Y-m-d H:i:s')."',
                user='".$_SESSION['user']['login']."',
                type='".$type."',
                os='".getOS($_SERVER['HTTP_USER_AGENT'])."',    
                browser='".user_browser($_SERVER['HTTP_USER_AGENT'])."',
                referer='',    
                text='".$text."',
                date='".date('Y-m-d')."',
                time='".date('H:i:s')."',
                logType = '{$logType}'");
}




function logOrderAdd(){
    $row = mysql_fetch_array(mysql_query("SELECT id FROM zakazy WHERE order_id = '{$_SESSION['user']['new_order']}'"));
    $status = getStatus($_POST['status']);
    $logText = '<b>{user} ДОБАВИЛ заказ №'.$row['id'].'&nbsp;&nbsp;&nbsp;
        <b>Статус:</b> '.$status['name'].';&nbsp;&nbsp;&nbsp;
        <b>Сумма: '.$_POST['total'].'грн</b>;&nbsp;&nbsp;&nbsp;
        <b>Покупатель:</b> '.$_POST['bayer_name'].', '.$_POST['phone'].';&nbsp;&nbsp;&nbsp;
        <b>Доставка:</b> '.$_POST['delivery'].';&nbsp;&nbsp;&nbsp;
        <b>Адрес:</b> '.$_POST['delivery_adress'].';&nbsp;&nbsp;&nbsp;
        <b>Оплата:</b> '.getStatusPaymentName($_POST['payment']).';&nbsp;&nbsp;&nbsp;
        <b>Оформил:</b> '.$_POST['user'].';&nbsp;&nbsp;&nbsp;
        <b>Комментарий: </b>'.$_POST['comment'];
    AddLog('1', $logText);
}

function logOrderEdit($previousValues){
    $logText = '<b>{user} ИЗМЕНИЛ заказ №'.$_POST['id'].'&nbsp;&nbsp;&nbsp;';
    if ($previousValues['status'] != $_POST['status']){
        $status = getStatus($_POST['status']);
        $status_old = getStatus($previousValues['status']);
        $logText .= '<b>Статус:</b> '.$status_old['name'].' => '.$status['name'].';&nbsp;&nbsp;&nbsp;';
    }
    if ($previousValues['total'] != $_POST['total'])
        $logText .= '<b>Сумма: '.$previousValues['total'].' => '.$_POST['total'].'грн</b>;&nbsp;&nbsp;&nbsp;';
    if ($previousValues['bayer_name'] != $_POST['bayer_name'])
        $logText .= '<b>Покупатель:</b> '.$previousValues['bayer_name'].' => '.$_POST['bayer_name'].', '.$phone.';&nbsp;&nbsp;&nbsp;';
    if ($previousValues['delivery'] != $_POST['delivery'])
        $logText .= '<b>Доставка:</b> '.$previousValues['delivery'].' => '.$_POST['delivery'].';&nbsp;&nbsp;&nbsp;';
    if (strlen($previousValues['delivery_adress']) != strlen($_POST['delivery_adress'])-1 && $_POST['delivery_adress'] != ', ')
        $logText .= '<b>Адрес:</b> |'.$previousValues['delivery_adress'].' => |'.$_POST['delivery_adress'].';&nbsp;&nbsp;&nbsp;';
    if ($previousValues['payment'] != $_POST['payment'])
        $logText .= '<b>Оплата:</b> '.$previousValues['payment'].' => '.getStatusPaymentName($_POST['payment']).';&nbsp;&nbsp;&nbsp;';
    if ($previousValues['user'] != $_POST['user'])
        $logText .= '<b>Оформил:</b> '.$previousValues['user'].' => '.$_POST['user'].';&nbsp;&nbsp;&nbsp;';
    if ($previousValues['comment'] != $_POST['comment'])
        $logText .= '<b>Комментарий:</b> '.$previousValues['comment'].' => '.$_POST['comment'].';&nbsp;&nbsp;&nbsp;';

    AddLog('1', $logText);     
}

//залогировать новый заказ с сайта
function logOrderFromSite($order){
    $logText = "<b>НОВЫЙ ЗАКАЗ №{$order['id']}. Сайт: </b>{$order['site']}; <b>Клиент: </b>{$order['bayer_name']}, 
        {$order['phone']}; <b>IP: </b>{$order['ip']}";

    AddLog('1', $logText);
}

//залогировать геткол
function logGetCall($order, $type){
    if ($type == 'new')
        $logText = '<b>GetCall</b> от нового клиента! <b>Заказ №</b>'.$order['id'].'. <b>Телефон: </b>'.$order['phone'].'. <b>Сайт</b>: '.$order['site'];
    else
        $logText = '<b>GetCall</b> от существующего клиента! <b>Заказ №</b>'.$order['id'].'. <b>Телефон: </b>'.$order['phone'].'. <b>Сайт</b>: '.$order['site'];
    AddLog('1', $logText, "GC");
}


function getCountOfDays($date){
    db_connect();
    $query = "SELECT COUNT(*) as count
        FROM logs AS L
        WHERE L.date = '{$date}'";
    $result = mysql_query($query);
    $result = mysql_fetch_assoc($result);
    return $result['count']; 
}   

function getLogMinDate($logType = ""){
    db_connect();
    $query = "SELECT L.datetime 
        FROM logs AS L  
        ORDER BY L.datetime ASC 
        LIMIT 1";
    $result = mysql_query($query);
    $result = mysql_fetch_assoc($result);
    return $result;
}

function rdate($param, $time = 0) {
    if(intval($time) == 0)
        $time = time();
    $monthNames=array("Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
    if(strpos($param, "M") === false) 
        return date($param, $time);
    else 
        return date(str_replace("M", $monthNames[date("n", $time) - 1], $param), $time);
}

function getDaysList($logType){
    $days = cal_days_in_month(CAL_GREGORIAN, $_POST['m'], $_POST['y']);

    $week = array('Mon'=>"Пн",'Tue'=>"Вт",'Wed'=>"Ср",'Thu'=>"Чт",'Fri'=>"Пт",'Sat'=>"Сб",'Sun'=>"Вс");

    for($d=1; $d <= $days; $d++){
        $date = $_POST['y'].'-'.$_POST['m'].'-'.$d;
        $w = $week[strftime("%a",strtotime($date))];
        
        $countOfDays = getCountOfDays($date);
        // print_r($countOfDays);
        if($countOfDays > 0)
            $no_rows = '';
        else
            $no_rows = 'no-rows';
        
        if($w=='Вс')
            $margin='margin-right:4px;';
        else
            $margin='';
        ?>
        <div class="logs-day-button <?=$no_rows?>" id="<?=$date;?>" onclick="getLogList('<?=$date;?>',event);" style="<?=$margin;?>">
            <?php 
                $red = "";
                if($w == 'Сб' || $w=='Вс')
                    $red = "color:red;"
                ?>
                    <span style="<?=$red?>"><?=$w?></span><br><?=$d;?>
        </div>    
        <?php 
    }
}

function getLogsList($date, $searchingString, $logType){
    db_connect();
    require_once $_SERVER['DOCUMENT_ROOT']."/template/additionalFiles/users/users.php";
    $logs = getLogs($date, $searchingString, $logType);
    if($logs){
        $count_rows = 0;
        foreach($logs as $log):
            $count_rows++;
        if($log['type']=='0')
            $type = 'error';
        else
            $type = '';
        ?>    
        <div class="<?=$type?>" style="text-align:left; padding:1px 2px; line-height: normal;">            
            <span style="color: #ABABAB; width: 80px; display: inline-block;"><?=$log['ip']?></span>
            <span style="color: #454545;"> 
                [<?=date("d.m.Y H:i:s", strtotime($log['datetime']))?>] 
            </span>
            <img src="/image/opera/panel_collapse_right.png">
            <?php 
            $user_info = getUserByLogin($log['user']);
            $message = str_replace('{user}', '<span class="login">'.$log['user'].'</span> (<span class="imya">'.$user_info['surname'].' '.$user_info['name'].' '.$user_info['lastname'].'</span>)', $log['text']); 
            ?>
            <span style="color: #757575;"><?=$message?></span>
        </div>
        <?php        
        endforeach;
        echo '<input type="hidden" id="count_rows_input" value="'.$count_rows.'">';
    }else{
        echo 'Ничего не найдено';
    }    
}

switch ($_POST['action']){
    case 'getDaysList':
        getDaysList($_POST['logType']);
        die();

    case 'getLogsList':
        getLogsList($_POST['date'], $_POST['searchingString'], $_POST['logType']);
        die();    
}

?>