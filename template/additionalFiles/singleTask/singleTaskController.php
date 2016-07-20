<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');

function getSingleCallTaskList(){
    db_connect();

    $callTaskStates = json_decode($_POST['call_task_states'], true);
    //статус 1 - одновременно показывается и статус 3
    if($callTaskStates['1'])
        $callTaskStates[] = 3;
    if ($callTaskStates)
        $displayingStatesWhere = ' WHERE CT.state in ('.implode($callTaskStates, ', ').') ';
    else
        die();
    
    $result = mysql_query("SELECT CT.id, CT.order_id, CT.date_time, CT.priority, CT.state, Z.phone, Z.bayer_name, UD.name, UD.surname
        FROM call_tasks AS CT 
        LEFT JOIN zakazy AS Z ON Z.id = CT.order_id
        LEFT JOIN users_description AS UD ON UD.line = CT.line
        {$displayingStatesWhere}
        ORDER BY id DESC") or die (mysql_error());

    while ($task = mysql_fetch_assoc($result)){
        $task['date_time'] = date("d.m H:i", strtotime($task['date_time']));
        $bacgroundColor = "#fff";
        if ($task['state'] == 1 || $task['state'] == 3)
            $bacgroundColor = "#FED24E";
        if ($task['state'] == 2 )
            $bacgroundColor = "#AD7";
        if ($task['state'] == 5 )
            $bacgroundColor = "#F99";
    ?>           
       <tr style="background-color: <?=$bacgroundColor?>;">
            <td> 
                <input type="checkbox" class="selected" name="need_delete[<?=$task['id']?>]" id="checkbox<?=$task['id']?>" id="<?=$task['id']?>" title="<?=$task['id']?>"> 
            </td>
            <td style="display: block; width: 16px !important; height: 0; padding: 0; margin: -1px;">
                <img src="/image/edit.png" class="option-button" onclick="editSingleCallTask('<?=$task['id']?>');">
            </td>
            <td>
                <?=$task['order_id']?>
            </td>
            <td style="display: block; width: 16px !important; height: 0; padding: 0; margin: -1px;">
                <img src="/image/edit.png" class="option-button" onclick="edit_zakaz('<?=$task['order_id']?>');">
            </td>
            <td>
                <?=$task['bayer_name']?>
            </td>
            <td>
                <?=$task['phone']?>
            </td>
            <td>
                <?=$task['date_time']?>
            </td>
            <td>
                <?php 
                if (!empty($task['line'])){ ?>
                    (<?=$task['line']?>) <?=$task['name'].' '.$task['surname']?>
                <?php
                }
                else{
                    echo "Все";
                }
                ?>
            </td>
            <td>
                <?=$task['priority']?>
            </td>
            <td>
            	<?php if($task['state'] == 5){?>
            			<img src="/image/off.png" class="on-off" onclick="setCallTaskState('<?=$task['order_id']?>','0');">
                <?php }elseif($task['state'] == 0){?>
                		<img src="/image/on.png" class="on-off" onclick="setCallTaskState('<?=$task['order_id']?>','5');">
                <?php } ?>
            </td>
        </tr>
    <?php }
}

function deleteSingleCallTask(){
    db_connect();
    foreach ($_POST['need_delete'] as $id => $value)
        mysql_query("DELETE FROM call_tasks WHERE id= {$id}") or die(json_encode(array('success' => false, 'error' => mysql_error())));
    echo json_encode(array('success' => true));
}

switch ($_POST['operation']) {
    case 'getSingleCallTaskList':
        getSingleCallTaskList();
        die();
        break;
    case 'deleteSingleCallTask':
        deleteSingleCallTask();
        break;
}

?>