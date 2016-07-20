<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
db_connect();
//echo '[';
    $min_d = getMinDateInOrders();
    $current_date = date('Y-m-d G:i:s');
    $status = getStatus($_GET['status']);
    //$statusy = getStatusy();
    //foreach ($statusy as $status):
echo '{';
    echo "name: '".$status['name']."' ";
    echo 'data: ['; 
            for ($date = strtotime($current_date); $date > strtotime($min_d['date']); $date=strtotime("-1 day",$date)) {
                $count_d = getStatisticDiagram($status['id'], date('Y-m-d',$date));
                $d = $date * 1000;
                echo '['.$d.','.$count_d.'],';
            }             
        echo ']';
echo '},'; 
            /*echo "{";
            echo "name: '".$status['name']."',";
            echo "data: ";
            echo '[';
                for ($date = strtotime($current_date); $date > strtotime($min_d['date']); $date=strtotime("-1 day",$date)) {
                    $count_d = getStatisticDiagram($status['id'], date('Y-m-d',$date));
                    $d = $date * 1000;
                    echo '['.$d.','.$count_d.'],';
                }
            echo ']';
            echo "}";*/
    //endforeach;
//echo ']';
            ?>


