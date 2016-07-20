<?php
session_start();
$op = $_POST['op'];
switch ($op){
    case('session_orders'):
        if($_POST['select']==''){
            unset($_SESSION['user']['orders']);
        }else{
            $_SESSION['user']['orders'] = $_POST['select'];
        }        
    break; 
    
    case('session_office'):
        if($_POST['select']==''){
            unset($_SESSION['user']['office']);
        }else{
            $_SESSION['user']['office'] = $_POST['select'];
        }        
    break;
    
    case('session_payment'):
        if($_POST['select']==''){
            unset($_SESSION['user']['payment']);
        }else{
            $_SESSION['user']['payment'] = $_POST['select'];
        }        
    break;
    
    case('session_delivery'):
        if($_POST['select']==''){
            unset($_SESSION['user']['delivery']);
        }else{
            $_SESSION['user']['delivery'] = $_POST['select'];
        }        
    break;
    
    case('session_manager'):
        if($_POST['select']==''){
            unset($_SESSION['user']['manager']);
        }else{
            $_SESSION['user']['manager'] = $_POST['select'];
        }        
    break;
}
?>