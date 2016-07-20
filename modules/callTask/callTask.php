<?php 	
require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');

function addCallTask($orderId, $dateTime, $priority, $state, $comment, $line){
	db_connect();

	//if ($line) { $lineSet = ", line = {$line}"; }
    deleteCallTask($orderId);
    $query = "INSERT INTO call_tasks SET
            date_time = '{$dateTime}',
            order_id = {$orderId},
            priority = {$priority},
            state = {$state},
            comment = '$comment'";
    mysql_query($query) or die($query.'   '.mysql_error());
}

//получить последнее задание для заказа
function getCallTaskForOrder($orderId){
    db_connect();
    $query = "SELECT * 
        FROM call_tasks 
        WHERE order_id = {$orderId} 
        AND state != 2 
        ORDER BY id DESC LIMIT 1";
    $result = mysql_fetch_assoc(mysql_query($query));
    return $result;
}

function getCallTaskForEditing($id){
    db_connect();
    $query = "SELECT * FROM call_tasks WHERE id = {$id}";
    $result = mysql_fetch_assoc(mysql_query($query));
    return $result;
}

function checkLineState($line){
	// получаем статус линии
    if (!empty($line)){
        $result = mysql_query("SELECT status
            FROM binotel_lines_status
            WHERE line = {$line}") or die (json_encode(array('success' => false, 'query' => $query, 'error' => 'строка 44 '.mysql_error())));
        $binotel_line = mysql_fetch_assoc($result);
    }
    return $binotel_line['status'];
}

function getCallTask(){
    date_default_timezone_set(TIME_ZONE);

	$access = getAccessByLogin($_POST['login']);

	//по логину вытягиваем внутренний номер в бинотеле
    $user = get_user_description_login($_POST['login']);
    $lineState = checkLineState($user['line']);
    //если сотрудник онлайн (и не разговаривает)
    if ($lineState == 'online' && $access['access'] == 3){
    	$date_time = date('Y-m-d H:i:s');
        //получаем задание
        $query = "SELECT * 
            FROM call_tasks 
            WHERE (line IS NULL 
            OR line = {$user['line']})
            AND date_time <= '{$date_time}'
            AND state = 0
            ORDER BY priority DESC,
            id ASC
            LIMIT 1";
        $result = mysql_query($query) or die (json_encode(array('success' => false, 'query' => $query, 'error' => 'строка 59 '.mysql_error())));
        $task = mysql_fetch_assoc($result);
    }

    //если задание получено
    if (!empty($task)){
        //- получаем инфу по этому заданию (номер заказа и номер телефона)
        $query = "SELECT id, phone
            FROM zakazy 
            WHERE id = {$task['order_id']}";
	        $result = mysql_query($query) or die(json_encode(array('success' => false, 'query' => $query, 'error' => 'строка 72 '.mysql_error())));
	        $order = mysql_fetch_assoc($result);

        die(json_encode(array_merge($order, array('success' => true, 'line' => $user['line'], 'access' =>  $access['access']))));
    }
    else{
        die(json_encode(array('success' => false, 'error' => 'Нет заданий для Вас')));
    }
}

function deleteCallTask($orderId){
    mysql_query("DELETE FROM call_tasks WHERE order_id = ".$orderId);
}

function setCallTaskState($orderId, $state){
    db_connect();
    $query = "UPDATE call_tasks 
        SET state = {$state} 
        WHERE order_id = {$orderId} 
        AND state != 2";
    mysql_query($query) or die(json_encode(array('success' => false, 'error' => mysql_error())));
    echo json_encode(array('success' => true));
}


switch ($_POST['operation']) {
    case 'addCallTask':
        if (!empty($_POST['orderId']) && !empty($_POST['dateTime']) && !empty($_POST['priority'])){
            $_POST['dateTime'] = date('Y-m-d H:i:s', strtotime($_POST['dateTime']));
            addCallTask($_POST['orderId'], $_POST['dateTime'], $_POST['priority'], $_POST['state'], $_POST['comment']);
        }
        // addCallTask($orderId, $dateTime, $priority, $state/*, $line = 915*/)
        die();
        break;
    case 'getCallTask':
        getCallTask();
        die();
        break;
    case 'setCallTaskState':
        setCallTaskState($_POST['orderId'], $_POST['state']);
        break;
}

?>
