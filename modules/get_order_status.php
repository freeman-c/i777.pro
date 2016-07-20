<?php 
	require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
	require $_SERVER['DOCUMENT_ROOT'].'/config.php';

	$id = $_POST['id'];

    $connection = db_connect();
    $query = "SELECT status FROM zakazy WHERE id='$id'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($result);
    
    echo json_encode($row);
?>