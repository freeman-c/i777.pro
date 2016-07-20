<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/db.php');

function getAccessType($id){
    db_connect();
    $query = "SELECT * FROM `access_type` WHERE id='$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    return $row['name'];
}

function getUsersList(){
    db_connect();
    $query = "SELECT U.id AS uid, U.*, UD.* 
    FROM users AS U
    LEFT JOIN users_description AS UD ON U.login = UD.login
    WHERE UD.surname !='' OR UD.name != ''
    ORDER BY U.access, UD.surname, UD.name";
    $result = mysql_query($query) or die(mysql_error());
    ?>
    <thead>
    <tr>
        <td width="20px"> 
            <div id="box-input-select-all">
                <input type="checkbox" id="select-all-checkbox">
                <div class="box-arrow-down"></div>
            </div>                         
        </td>
        <td width="40px"></td>
        <td align="center"> <span id="table-message"></span> </td>
        <td>Логин</td>
        <!-- <td>Email</td> -->
        <td>Группа</td>
        <td>Линия<br>Binotel</td>
        <!-- <td>Офис</td> -->
        <td width="22px"></td>
    </tr>
    </thead>
    <tbody>
    <?php
    while ($user = mysql_fetch_assoc($result)){ ?>
         <tr>
            <td> <input type="checkbox" class="selected" name="need_delete[<?=$user['uid']?>]" id="checkbox<?=$user['uid']?>"></td>
            <td> <img class="user-logo" src="/image/empty_people_logo_40_40.png"> </td>
            <td>
                <b><?=$user['surname']?> <?=$user['name']?> <?=$user['lastname']?></b>           
            </td>
            <td><span style="color:#3F80C0;"><?=$user['login']?></span></td>
            <td><span style="color:#3F80C0;"><?=getAccessType($user['access']);?></span></td> 
            <td>
                <?=$user['line']?>
            </td>
            <td>
                <img src="/image/edit.png" class="option-button" onclick="edit_user('<?=$user['uid']?>');">
            </td>
        </tr>
    <?php
    } 
    ?>
    </tbody>
    <?php
}

function addUser(){
    db_connect();
    $addUserQuery = "INSERT INTO users SET 
        login='{$_POST['login']}',    
        password='".md5($_POST['pass'])."',     
        email='{$_POST['email']}',    
        ip='{$_SERVER['REMOTE_ADDR']}',   
        access='{$_POST['access']}'";

    if (empty($_POST['line']))
        $_POST['line'] = "NULL";
    $addUserDescriptionQuery = "INSERT INTO users_description SET
        login='{$_POST['login']}', 
        line = {$_POST['line']},
        surname='{$_POST['surname']}',    
        name='{$_POST['name']}',  
        lastname='{$_POST['lastname']}',  
        description='{$_POST['description']}'";
    mysql_query($addUserQuery) or die ($addUserQuery.PHP_EOL.mysql_error());
    mysql_query($addUserDescriptionQuery) or die ($addUserDescriptionQuery.PHP_EOL.mysql_error());

}

function editUser(){
    db_connect();

    if(!empty($_POST['pass']))
        $passwordSet = "password='".md5($_POST['pass'])."',";
    $currentUser = mysql_query("SELECT login 
                FROM users
                WHERE id = {$_POST['id']}");
    $currentUser = mysql_fetch_assoc($currentUser);

    $editUserQuery = "UPDATE users SET 
            login='{$_POST['login']}',
            {$passwordSet}   
            ip='{$_SERVER['REMOTE_ADDR']}',   
            access='{$_POST['access']}'
            WHERE id = {$_POST['id']}";

    if (empty($_POST['line']))
        $_POST['line'] = "NULL";
    $editUserDescriptionQuery = "UPDATE users_description SET
            login='{$_POST['login']}',
            line={$_POST['line']},  
            surname='{$_POST['surname']}',    
            name='{$_POST['name']}',  
            description='{$_POST['description']}' 
            WHERE login = '{$currentUser['login']}'";
    mysql_query($editUserQuery) or die ($editUserQuery.PHP_EOL.mysql_error());
    mysql_query($editUserDescriptionQuery) or die ($editUserDescriptionQuery.PHP_EOL.mysql_error());
}

function deleteUsers(){
    foreach ($_POST['need_delete'] as $id => $value) {
        db_connect();
        $deleteUsersQuery = "DELETE 
            FROM users 
            WHERE id={$id}";
         $deleteUsersDescQuery = "DELETE 
            FROM users_description 
            WHERE login = (SELECT login
                FROM users AS U
                WHERE U.id = {$id})";

        mysql_query($deleteUsersDescQuery) or die ($deleteUsersDescQuery.PHP_EOL.mysql_error());
        mysql_query($deleteUsersQuery) or die ($deleteUsersQuery.PHP_EOL.mysql_error());
    }
}

function getUserByLogin($login){
    db_connect();
    $query = "SELECT * 
        FROM users_description 
        WHERE login = '{$login}'";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    return $row;
}

switch ($_POST['operation']) {
    case 'getUsersList':
        getUsersList();
        break;
    case 'addUser':
        addUser();
        break;
    case 'editUser':
        editUser();
        break;
    case 'deleteUsers':
        deleteUsers();
        break;
}
?>