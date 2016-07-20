<?php

require($_SERVER['DOCUMENT_ROOT'] .'/config.php');
//*******************************************************************
//error_reporting(0);

function db_connect() {
    $host = DB_HOSTNAME;
    $user = DB_USERNAME;
    $password = DB_PASSWORD;
    $db = DB_DATABASE;

    $connection = mysql_connect($host, $user, $password) or die('ошибка конекта db.13');
    mysql_query("SET NAMES utf8");
    if (!$connection || !mysql_select_db($db, $connection)) {
        return false;
    }
    return $connection;
}
//Массив для выводов выборки
function db_result_to_array($result){
    $res_array = array();
    $count = 0;
    while ($row = mysql_fetch_array($result)){
        $res_array[$count] = $row;
        $count++;
    }
    return $res_array;
}

function mysqlResponseFail($query, $error){
    $response = array("success" => false,
        "query" => $query,
        "error" => $error);
    return json_encode($response);
}

function mysqlResponseDone($query){
    $response = array("success" => true, "query" => $query);
    return json_encode($response);
}

function mysqlExec($query){
    db_connect();

    if (mysql_query($query))
        echo mysqlResponseDone($query);
    else
        echo mysqlResponseFail($query, mysql_error());
}

function dbResultToAssocc($result){
    $responseArray = array();
    while ($row = mysql_fetch_assoc($result)) {
        $responseArray[] = $row;
    }

    return $responseArray;
}

function simpleResultToAssoc($result){
    return mysql_fetch_assoc($result);
}


?>
