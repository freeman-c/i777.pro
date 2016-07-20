<?php
//error_reporting(0);
//require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/modules/pr/russianpost.php';
try{
	$client = new RussianPostAPI();
	$result = $client->getOperationHistory($_POST['ttn']);	
$count = count($result);
echo '<table id="table-pr" width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr class="title-table-pr">
            <td rowspan="2">Операция</td>
            <td rowspan="2">Дата</td>
            <td colspan="2">Место проведения операции</td>
            <td rowspan="2">Атрибут операции</td>
            <td rowspan="2">Вес (кг.)</td>
            <td rowspan="2">Объявл. ценность (руб.)</td>
            <td rowspan="2">Налож. платёж (руб.)</td>
            <td colspan="2">Адресовано</td>
          </tr>
          <tr class="title-table-pr">
            <td>Индекс</td>
            <td>Название ОПС</td>
            <td>Индекс</td>
            <td>Адрес</td>
          </tr>';
for($i=0; $i < $count; $i++){
    if($result[$i]->itemWeight == '' || $result[$i]->itemWeight == '0'){ $itemWeight = '-'; }else{ $itemWeight = $result[$i]->itemWeight; }    
    if($result[$i]->declaredValue=='' || $result[$i]->declaredValue=='0'){
        $declaredValue = '-';        
    }else{
        $declaredValue = number_format(($result[$i]->declaredValue),2,'.','');         
    }    
    if($result[$i]->collectOnDeliveryPrice=='' || $result[$i]->collectOnDeliveryPrice=='0'){
        $collectOnDeliveryPrice = '-';        
    }else{
        $collectOnDeliveryPrice = number_format(($result[$i]->collectOnDeliveryPrice),2,'.','');         
    }    
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
    
    echo '<tr>
            <td>'.$result[$i]->operationType.'</td>
            <td>'.date("d.m.Y H:i", strtotime($result[$i]->operationDate)).'</td>
            <td>'.$result[$i]->operationPlacePostalCode.'</td>
            <td>'.$result[$i]->operationPlaceName.'</td>
            <td>'.$operationAttribute.'</td>
            <td>'.$itemWeight.'</td>
            <td>'.$declaredValue.'</td>
            <td>'.$collectOnDeliveryPrice.'</td>
            <td>'.$result[$i]->destinationPostalCode.'</td>
            <td>'.$result[$i]->destinationAddress.'</td>
         </tr>';
}
echo '</table>';         
        //echo '<pre>';
	//print_r($result);	
}catch(RussianPostException $e) {
    die('ERROR: ' . $e->getMessage() . "\n");
}
?>