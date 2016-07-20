<?php
//error_reporting(0);
//require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/modules/pr/russianpost.php';
try{
	$client = new RussianPostAPI();
	$result = $client->getOperationHistory($_POST['ttn']);	
$count = count($result);
$i = $count - 1;
//for($i=$prelast; $i < $count; $i++){     
    if($result[$i]->operationAttribute=='Прибыло в место вручения'){
            $operationAttribute = '<span style="color: #C60">'.$result[$i]->operationAttribute.'</span>';        
    }elseif($result[$i]->operationAttribute=='Покинуло сортировочный центр'){
            $operationAttribute = '<span style="color: #757575">'.$result[$i]->operationAttribute.'</span>';        
    }elseif($result[$i]->operationAttribute=='Истёк срок хранения'){
            $operationAttribute = '<span style="color: #900">'.$result[$i]->operationAttribute.'</span>';        
    }elseif($result[$i]->operationAttribute=='Нероздано'){
            $operationAttribute = '<span style="color: red">'.$result[$i]->operationAttribute.'</span>';        
    }elseif($result[$i]->operationAttribute=='Вручение отправителю' || $result[$i]->operationAttribute=='Вручение адресату'){
            $operationAttribute = '<span style="color: green">'.$result[$i]->operationAttribute.'</span>';        
    }else{$operationAttribute = $result[$i]->operationAttribute;}
    
    echo $operationAttribute;
//}        
        //echo '<pre>';
	//print_r($result);	
}catch(RussianPostException $e) {
    die('ERROR: ' . $e->getMessage() . "\n");
}
?>