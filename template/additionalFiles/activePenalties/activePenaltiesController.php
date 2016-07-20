<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/controller.php');



function getActivePenaltiesList(){
    db_connect();

    
    $result = mysql_query("SELECT * FROM                           
                           (SELECT id as activePenaltyId, penaltyId, userId, commentary, creationDate FROM activePenalties) AS AP
                           INNER JOIN (SELECT id as penaltyId, name as penaltyName, price FROM penalties) AS P ON P.penaltyId = AP.penaltyId
                           INNER JOIN users_description ON AP.userId = users_description.id
                           ORDER BY AP.activePenaltyId") or die (mysql_error());

    while ($penalty = mysql_fetch_assoc($result)){       
        ?>           
        <tr style="background: <?=$background?>;">
            <td>                    
                <input type="checkbox" class="selected" name="need_delete[<?=$penalty['activePenaltyId']?>]" id="checkbox<?=$penalty['activePenaltyId']?>" title="<?=$penalty['activePenaltyId']?>">
            </td>
            <td align="center" width="24px">
                <img src="/image/edit.png" class="option-button" onclick="editActivePenalty('<?=$penalty['activePenaltyId']?>');">
            </td>
            <td align="left">
                <?=$penalty['surname']?> <?=$penalty['name']?>
            </td>
            <td align="left">
                <?=$penalty['penaltyName']?>
            </td>
            <td align="left">
                <?=$penalty['price']?>
            </td>
            <td align="left">
                <?=$penalty['creationDate']?>
            </td>
            <td align="left">
                <?=$penalty['commentary']?>
            </td>
        </tr>
    <?php }
}

function getActivePenalty_($id){
    db_connect(); 
    
    $result = mysql_query("SELECT * FROM                           
                           (SELECT id as activePenaltyId, penaltyId, userId, commentary, creationDate FROM activePenalties) AS AP
                           INNER JOIN (SELECT id as penaltyId, name as penaltyName, price FROM penalties) AS P ON P.penaltyId = AP.penaltyId
                           INNER JOIN users_description ON AP.userId = users_description.id
                           WHERE AP.activePenaltyId = {$id}
                           ORDER BY AP.activePenaltyId") or die (mysql_error());

    return mysql_fetch_assoc($result);
}

function addActivePenalty(){
    db_connect();

    mysql_query("INSERT INTO activePenalties
        SET penaltyId = {$_POST['penaltyId']},
        userId = {$_POST['userId']},
        commentary = '{$_POST['commentary']}',
        creationDate = '{$_POST['creationDate']}'") or die(mysql_error());

}

function editActivePenalty(){
    db_connect();

    mysql_query("UPDATE activePenalties
        SET penaltyId = {$_POST['penaltyId']},
        userId = {$_POST['userId']},
        commentary = '{$_POST['commentary']}',
        creationDate = '{$_POST['creationDate']}'
        WHERE id = {$_POST['id']}") or die(mysql_error());

}

function deleteActivePenalties(){
    db_connect();
    foreach ($_POST['need_delete'] as $id => $value) {
        mysql_query("DELETE FROM activePenalties
            WHERE id = {$id}") or die(json_encode(array('success' => false, 'error' => mysql_error())));
    }
    echo json_encode(array('success' => true));
}


function getPenalties() {
    db_connect();
    $result = mysql_query("SELECT * FROM penalties") or die (mysql_error());
    return $result;
}


switch ($_POST['operation']) {
    case 'addActivePenalty':
        addActivePenalty();
        die();
    case 'editActivePenalty':
        editActivePenalty();
        break;
    case 'getActivePenaltiesList':
        getActivePenaltiesList();
        die();
    case 'deleteActivePenalties':
        deleteActivePenalties();
        die();
}

?>