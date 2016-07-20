<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
require $_SERVER['DOCUMENT_ROOT'].'/modules/PHPExcel.php'; // Подключаем библиотеку PHPExcel
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0); // Делаем активной первую страницу и получаем её
//$exel = $objPHPExcel->getActiveSheet();
//$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
$objPHPExcel->getActiveSheet()->setTitle('export_lp-crm.biz_('.date('d.m.Y').')');
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize('9');
$objPHPExcel->getActiveSheet()->setCellValue("A1", "ФИО"); 
$objPHPExcel->getActiveSheet()->setCellValue("B1", "номер ТНН (курьер)");
$objPHPExcel->getActiveSheet()->setCellValue("C1", "Данные доставки");
$objPHPExcel->getActiveSheet()->setCellValue("D1", "Контактный телефон");
$objPHPExcel->getActiveSheet()->setCellValue("E1", "Товар (с количеством)");
$objPHPExcel->getActiveSheet()->setCellValue("F1", "Цена клиента");
$objPHPExcel->getActiveSheet()->setCellValue("G1", "Статус заказа");
$objPHPExcel->getActiveSheet()->setCellValue("H1", "Дата добавления");
$objPHPExcel->getActiveSheet()->setCellValue("I1", "Дата изменения");
foreach(range('A','I') as $columnID) {
    $objPHPExcel->getActiveSheet()->getStyle(''.$columnID.'1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(18);
    $objPHPExcel->getActiveSheet()->getStyle(''.$columnID.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);    
    $objPHPExcel->getActiveSheet()->getStyle(''.$columnID.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
}
foreach(range('A','I') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}
$i=2;
foreach ($_GET['need_delete'] as $id => $value) {
    db_connect();
    $query = "SELECT * FROM `zakazy` WHERE id='".(int)$id."' ";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    
    if($row['datetime'] > 0){
        $datetime = date("d.m.Y H:i:s",strtotime($row['datetime']));
    }else{
        $datetime = date("d.m.Y",strtotime($row['date']));
    }
    $phone = preg_replace('/[^0-9]/', '', $row['phone']); //убираем всё, кроме цифр
    $first_symbol = substr($phone, 0, 1); //проверяем первый символ начала строки
    if($first_symbol=='0'){$phone = '+38'.$phone;}
    if($first_symbol=='8'){$phone = '+3'.$phone;}
    if($first_symbol=='3'){$phone = '+'.$phone;}
    $number = substr($phone, 0, 1); //если номер начинается с "+"
    if(strlen($phone) == 13 && $number=='+'){} //если 13 символов 
    
    $products_sql = "SELECT * FROM `product_order` WHERE order_id='".$row['order_id']."' ";
    $products = mysql_query($products_sql);
    $products = db_result_to_array($products);
    //$tovary = '';
    $array = array();
    foreach ($products as $product){
        $sum = $product['quantity'] * $product['price'];
        $query3 = "SELECT * FROM `product` WHERE id='".$product['product_id']."' ";
            $result3 = mysql_query($query3);
            $row3 = mysql_fetch_array($result3);        
        array_push($array, $row3['name']." (".$product['quantity']."шт. x ".$product['price'].") = ".number_format($sum,2));            
        //$tovary .= $row3['name']." (".$product['quantity']."шт. x ".$product['price'].") = ".number_format($sum,2)." \n";
    }
    $tovary = implode("\n", $array);
    
    $query4 = "SELECT * FROM `statusy` WHERE id='".$row['status']."' ";
    $result4 = mysql_query($query4);
    $status = mysql_fetch_array($result4);
        
$objPHPExcel->getActiveSheet()->getCell("A".$i."")->setValue($row['bayer_name']);
$objPHPExcel->getActiveSheet()->getCell("B".$i."")->setValue($row['ttn']);
    $objPHPExcel->getActiveSheet()->getStyle("B".$i."")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
$objPHPExcel->getActiveSheet()->getCell("C".$i."")->setValue($row['delivery']." - ".$row['delivery_adress']);
    $objPHPExcel->getActiveSheet()->getStyle("C".$i."")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getCell("D".$i."")->setValue($phone);
$objPHPExcel->getActiveSheet()->getCell("E".$i."")->setValue($tovary);
    $objPHPExcel->getActiveSheet()->getStyle("E".$i."")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getCell("F".$i."")->setValue($row['total']);
$objPHPExcel->getActiveSheet()->getCell("G".$i."")->setValue($status['name']);
$objPHPExcel->getActiveSheet()->getCell("H".$i."")->setValue($datetime);
$objPHPExcel->getActiveSheet()->getCell("I".$i."")->setValue(date("d.m.Y",strtotime($row['date_update'])));

$objPHPExcel->getActiveSheet()->getStyle("A".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->getStyle("B".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
$objPHPExcel->getActiveSheet()->getStyle("C".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->getStyle("D".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->getStyle("E".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->getStyle("F".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
$objPHPExcel->getActiveSheet()->getStyle("G".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->getStyle("H".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->getStyle("I".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

    foreach(range('A','I') as $ID) {
        $objPHPExcel->getActiveSheet()->getStyle($ID)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    }
$i++;
}
if($_GET['type']=='xls'){
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // *.xls
        $type = 'xls';        
    header('Content-Type: text/html; charset=utf-8');
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="export_lp-crm.biz_'.date('d.m.Y').'.'.$type.'"');
}

if($_GET['type']=='xlsx'){
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); // *.xlsx     
        $type = 'xlsx';
    header('Content-Type: text/html; charset=utf-8');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="export_lp-crm.biz_'.date('d.m.Y').'.'.$type.'"');
    header('Cache-Control: max-age=0');
}
//$objWriter->save("export2015.xlsx"); // save file
//header ( "Cache-Control: no-cache, must-revalidate" );
//header ( "Pragma: no-cache" );
/*header('Content-Type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="export_lp-crm.biz_'.date('d.m.Y').'.'.$type.'"');*/
        
$objWriter->save('php://output');
?>