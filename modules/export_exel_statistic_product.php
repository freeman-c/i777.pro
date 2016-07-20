<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
require $_SERVER['DOCUMENT_ROOT'].'/modules/PHPExcel.php'; // Подключаем библиотеку PHPExcel
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0); // Делаем активной первую страницу и получаем её
//$exel = $objPHPExcel->getActiveSheet();
//$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
$objPHPExcel->getActiveSheet()->setTitle('Менеджеры ('.date('d.m.Y').')');
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize('10');
$objPHPExcel->getActiveSheet()->setCellValue("A1", "Менеджер"); 
$objPHPExcel->getActiveSheet()->setCellValue("B1", "Заявок");
$objPHPExcel->getActiveSheet()->setCellValue("C1", "Заказов");
$objPHPExcel->getActiveSheet()->setCellValue("D1", "CV2");
$objPHPExcel->getActiveSheet()->setCellValue("E1", "Н/Э");
$objPHPExcel->getActiveSheet()->setCellValue("F1", "Э");
$objPHPExcel->getActiveSheet()->setCellValue("G1", "% доп.");
$objPHPExcel->getActiveSheet()->setCellValue("H1", "Допродажи");
$objPHPExcel->getActiveSheet()->setCellValue("I1", "Перекрестные продажи");

$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(10);

 

foreach(range('A','I') as $columnID) {
    $objPHPExcel->getActiveSheet()->getStyle($columnID.'1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(18);
    $objPHPExcel->getActiveSheet()->getStyle($columnID.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);    
    $objPHPExcel->getActiveSheet()->getStyle($columnID.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
}

function getManagerStat($managerId){
    
    db_connect();
    $query = "SELECT UD.login, UD.surname, UD.name FROM users_description AS UD
    LEFT JOIN users AS U ON UD.login = U.login
    WHERE U.access = 3 $managerIdWhere
    ORDER BY UD.surname, UD.name";
    // return $query.PHP_EOL;
    $result = mysql_query($query) or die ('error');
    $result = db_result_to_array($result); 
    return $result;  
}

function getRequestCount($login, $dateFrom, $dateTo){
    db_connect();

    if ($dateFrom){
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    }
    if ($dateTo){
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";
    }

    $query = "SELECT COUNT(DISTINCT Z.id) as requestCount FROM zakazy as Z
    LEFT JOIN users as U ON U.login = Z.user
    WHERE Z.status IN (3,11,13,14,18,29,30,31,32,33,34,36,37) AND Z.cart = 0 
    AND U.login = '$login' 
    $dateFromWhere $dateToWhere";

    $result = mysql_query($query);
    $result = mysql_fetch_array($result);
    return $result;
}

function getOrderCount($login, $dateFrom, $dateTo){
    db_connect();

    if ($dateFrom){
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    }
    if ($dateTo){
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";
    }

    $query = "SELECT COUNT(DISTINCT Z.id) as orderCount FROM zakazy as Z
    LEFT JOIN users as U ON U.login = Z.user
    WHERE Z.status IN (11,14,18,29,30,31,32,33,34,36,37) AND Z.cart = 0 
    AND U.login = '$login' 
    $dateFromWhere $dateToWhere";

    $result = mysql_query($query);
    $result = mysql_fetch_array($result);
    return $result;
}

function getSalesStat($login, $dateFrom, $dateTo){
    db_connect();

    if ($dateFrom){
        $dateFromWhere = "AND Z.date_stat >= '$dateFrom'";
    }
    if ($dateTo){
        $dateToWhere = "AND Z.date_stat <= '$dateTo'";
    }

    //Получить количество заказов с допродажами
    $query = "SELECT COUNT(DISTINCT Z.id) as orderCountEffective FROM zakazy as Z
    LEFT JOIN users as U ON U.login = Z.user
    LEFT JOIN product_order AS PO ON Z.order_id = PO.order_id
    WHERE Z.status IN (11,14,18,29,30,31,32,33,34,36,37) AND Z.cart = 0 
    AND U.login = '$login'
    AND 0 != (SELECT COUNT(_po.id) from product_order as _po WHERE _po.status_buy IN (2,3) AND _po.order_id = Z.order_id)
    $dateFromWhere $dateToWhere";
    $result = mysql_query($query);
    $result = mysql_fetch_array($result);

    //Получить количество товаров Допродажа
    $query = "SELECT SUM(PO.quantity) as addSalesProductCount FROM zakazy as Z
    LEFT JOIN users as U ON U.login = Z.user
    LEFT JOIN product_order AS PO ON Z.order_id = PO.order_id
    WHERE Z.status IN (11,14,18,29,30,31,32,33,34,36,37) AND Z.cart = 0 
    AND U.login = '$login'
    AND PO.status_buy IN (2)
    $dateFromWhere $dateToWhere";
    $result2 = mysql_query($query);
    $result2 = mysql_fetch_array($result2);
    $result['addSalesProductCount'] = $result2['addSalesProductCount'];

    $query = "SELECT SUM(PO.quantity) as crossSalesProductCount FROM zakazy as Z
    LEFT JOIN users as U ON U.login = Z.user
    LEFT JOIN product_order AS PO ON Z.order_id = PO.order_id
    WHERE Z.status IN (11,14,18,29,30,31,32,33,34,36,37) AND Z.cart = 0 
    AND U.login = '$login'
    AND PO.status_buy IN (3)
    $dateFromWhere $dateToWhere";
    $result3 = mysql_query($query);
    $result3 = mysql_fetch_array($result3);
    $result['crossSalesProductCount'] = $result3['crossSalesProductCount'];

    return $result;
}

$managerId = $_POST['managerId'];
$dateFrom = $_POST['dateFrom'];
$dateTo = $_POST['dateTo'];
$sort = $_POST['sort'];

$result = getManagerStat($managerId); 
$summury = array();
foreach ($result as $key => $value) {
    $requestArr = getRequestCount($value['login'], $dateFrom, $dateTo);
    $orderArr = getOrderCount($value['login'], $dateFrom, $dateTo); 
    $salesStatArr = getSalesStat($value['login'], $dateFrom, $dateTo);

    $result[$key]['requestCount'] = $requestArr['requestCount'];
    $summury['requestCount'] += $result[$key]['requestCount'];

    $result[$key]['orderCount'] = (int)$orderArr['orderCount'];
    $summury['orderCount'] += $result[$key]['orderCount'];

    $result[$key]['cv2'] =  round((float)$orderArr['orderCount'] / (float)$requestArr['requestCount'] * 100, 2);

    $result[$key]['orderCountNotEffective'] = $orderArr['orderCount'] - $salesStatArr['orderCountEffective'];
    $summury['orderCountNotEffective'] += $result[$key]['orderCountNotEffective'];

    $result[$key]['orderCountEffective'] = $salesStatArr['orderCountEffective'];
    $summury['orderCountEffective'] += $result[$key]['orderCountEffective'];

    $result[$key]['cv3'] =  round((float)$salesStatArr['orderCountEffective'] / $orderArr['orderCount'] * 100, 2);

    $result[$key]['addSalesProductCount'] = $salesStatArr['addSalesProductCount'];
    $summury['addSalesProductCount'] += $result[$key]['addSalesProductCount'];

    $result[$key]['crossSalesProductCount'] = $salesStatArr['crossSalesProductCount'];
    $summury['crossSalesProductCount'] += $result[$key]['crossSalesProductCount'];
}

$summury['cv2'] = round((float)$summury['orderCount'] / (float)$summury['requestCount']  * 100, 2);
$summury['cv3'] = round((float)$summury['orderCountEffective'] / (float)$summury['orderCount']  * 100, 2);

$i = 2;
foreach ($result as $key => $value) 
{ 
    if ($value['requestCount'] == 0)
        continue;
    $objPHPExcel->getActiveSheet()->getCell("A".$i."")->setValue($value['surname'].' '.$value['name']);
    $objPHPExcel->getActiveSheet()->getCell("B".$i."")->setValue($value['requestCount']);
    $objPHPExcel->getActiveSheet()->getCell("C".$i."")->setValue($value['orderCount']);
    $objPHPExcel->getActiveSheet()->getCell("D".$i."")->setValue($value['cv2']);
    $objPHPExcel->getActiveSheet()->getCell("E".$i."")->setValue($value['orderCountNotEffective']);
    $objPHPExcel->getActiveSheet()->getCell("F".$i."")->setValue($value['orderCountEffective']);
    $objPHPExcel->getActiveSheet()->getCell("G".$i."")->setValue($value['cv3']);
    $objPHPExcel->getActiveSheet()->getCell("H".$i."")->setValue($value['addSalesProductCount']);
    $objPHPExcel->getActiveSheet()->getCell("I".$i."")->setValue($value['crossSalesProductCount']);
    $i++;
}


for ($i=0; $i < count($result); $i++) { 
    $objPHPExcel->getActiveSheet()->getStyle("A".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $objPHPExcel->getActiveSheet()->getStyle("B".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
    $objPHPExcel->getActiveSheet()->getStyle("C".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
    $objPHPExcel->getActiveSheet()->getStyle("D".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
    $objPHPExcel->getActiveSheet()->getStyle("E".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
    $objPHPExcel->getActiveSheet()->getStyle("F".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
    $objPHPExcel->getActiveSheet()->getStyle("G".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
    $objPHPExcel->getActiveSheet()->getStyle("H".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
    $objPHPExcel->getActiveSheet()->getStyle("I".$i."")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
}

if($_GET['type']=='xls'){
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // *.xls
        $type = 'xls';        
    header('Content-Type: text/html; charset=utf-8');
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.date('d.m.Y').' Менеджеры.'.$type.'"');
}

if($_GET['type']=='xlsx'){
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); // *.xlsx     
        $type = 'xlsx';
    header('Content-Type: text/html; charset=utf-8');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'.date('d.m.Y').' Менеджеры.'.$type.'"');
    header('Cache-Control: max-age=0');
}

$objWriter->save('php://output');
?>