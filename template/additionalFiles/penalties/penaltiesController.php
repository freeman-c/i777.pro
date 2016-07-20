<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/controller.php');


function getPenaltiesList(){
    db_connect();

    
    $result = mysql_query("SELECT *
    	FROM penalties
        ORDER BY id") or die (mysql_error());

    while ($penalty = mysql_fetch_assoc($result)){       
        ?>           
        <tr style="background: <?=$background?>;">
            <td>                    
                <input type="checkbox" class="selected" name="need_delete[<?=$penalty['id']?>]" id="checkbox<?=$penalty['id']?>" title="<?=$penalty['id']?>">
            </td>
            <td align="center" width="24px">
                <img src="/image/edit.png" class="option-button" onclick="editPenalty('<?=$penalty['id']?>');">
            </td>
            <td align="left">
                <?=$penalty['name']?>
            </td>
            <td align="left">
                <?=$penalty['price']?>
            </td>
            <td align="left">
                <?=$penalty['description']?>
            </td>
        </tr>
    <?php }
}

function getPenalty_($id){
    db_connect();
    
    $result = mysql_query("SELECT *
        FROM penalties
        WHERE id = {$id}") or die (mysql_error());

    return mysql_fetch_assoc($result);
}

function addPenalty(){
    db_connect();

    mysql_query("INSERT INTO penalties
        SET name = '{$_POST['name']}',
        price = '{$_POST['price']}',
        description = '{$_POST['description']}'") or die(mysql_error());

}

function editPenalty(){
    db_connect();

    mysql_query("UPDATE penalties
        SET name = '{$_POST['name']}',
        price = '{$_POST['price']}',
        description = '{$_POST['description']}'
        WHERE id = {$_POST['id']}") or die(mysql_error());

}

function deletePenalties(){
    db_connect(); echo var_dump($_POST);
    foreach ($_POST['need_delete'] as $id => $value) {
        mysql_query("DELETE FROM penalties
            WHERE id = {$id}") or die(json_encode(array('success' => false, 'error' => mysql_error())));
    }
    echo json_encode(array('success' => true));
}



switch ($_POST['operation']) {
    case 'addPenalty':
        addPenalty();
        die();
    case 'editPenalty':
        editPenalty();
        break;
    case 'getPenaltiesList':
        getPenaltiesList();
        die();
    case 'deletePenalties':
        deletePenalties();
        die();
}

?>