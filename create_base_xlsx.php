<?php

//echo ini_get('memory_limit');

require_once('common.php');
checkUser();
//checktmima();
isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user = '';

/** Error reporting */
error_reporting(E_ALL);

/** PHPExcel */
require_once ("{$classes_prefix}PHPExcel.php");

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("Apousies")
        ->setLastModifiedBy("Apousies")
        ->setTitle("backup-$user.xls")
        ->setSubject("")
        ->setDescription("")
        ->setKeywords("")
        ->setCategory("");

// Add some data
$styleArray = array(
    'font' => array(
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
    'borders' => array(
        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
        'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('argb' => 'DDDDDDDD')
    ),
);

$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

/*
  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, 1)->setValue("tmima");
  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, 1)->setValue("type");
  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, 1)->setValue("lastselect");
 */

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle("tmimata");

##################################################################################################################################

$objWorksheet1 = $objPHPExcel->createSheet();
$objWorksheet1->setTitle('students');

$objWorksheet1->getStyle('A1:L1')->applyFromArray($styleArray);
$objWorksheet1->getColumnDimension('A')->setWidth(5);
$objWorksheet1->getColumnDimension('B')->setWidth(20);
$objWorksheet1->getColumnDimension('C')->setWidth(20);
$objWorksheet1->getColumnDimension('D')->setWidth(20);
$objWorksheet1->getColumnDimension('E')->setWidth(20);
$objWorksheet1->getColumnDimension('F')->setWidth(20);
$objWorksheet1->getColumnDimension('G')->setWidth(15);
$objWorksheet1->getColumnDimension('H')->setWidth(10);
$objWorksheet1->getColumnDimension('I')->setWidth(10);
$objWorksheet1->getColumnDimension('J')->setWidth(15);
$objWorksheet1->getColumnDimension('K')->setWidth(15);
$objWorksheet1->getColumnDimension('L')->setWidth(5);

/*
  $objWorksheet1->getCellByColumnAndRow(0, 1)->setValue("am");
  $objWorksheet1->getCellByColumnAndRow(1, 1)->setValue("epitheto");
  $objWorksheet1->getCellByColumnAndRow(2, 1)->setValue("onoma");
  $objWorksheet1->getCellByColumnAndRow(3, 1)->setValue("patronimo");
  $objWorksheet1->getCellByColumnAndRow(4, 1)->setValue("ep_kidemona");
  $objWorksheet1->getCellByColumnAndRow(5, 1)->setValue("on_kidemona");
  $objWorksheet1->getCellByColumnAndRow(6, 1)->setValue("dieythinsi");
  $objWorksheet1->getCellByColumnAndRow(7, 1)->setValue("tk");
  $objWorksheet1->getCellByColumnAndRow(8, 1)->setValue("poli");
  $objWorksheet1->getCellByColumnAndRow(9, 1)->setValue("til1");
  $objWorksheet1->getCellByColumnAndRow(10, 1)->setValue("til2");
  $objWorksheet1->getCellByColumnAndRow(11, 1)->setValue("filo");
 */

////////////////////////////apoysies////////////////////////////////////////////////////

$objWorksheet2 = $objPHPExcel->createSheet();
$objWorksheet2->setTitle('apousies');

$objWorksheet2->getStyle('A1:J1')->applyFromArray($styleArray);

$objWorksheet2->getColumnDimension('A')->setWidth(10);
$objWorksheet2->getColumnDimension('B')->setWidth(10);
$objWorksheet2->getColumnDimension('C')->setWidth(5);
$objWorksheet2->getColumnDimension('D')->setWidth(5);
$objWorksheet2->getColumnDimension('E')->setWidth(5);
$objWorksheet2->getColumnDimension('F')->setWidth(5);
$objWorksheet2->getColumnDimension('G')->setWidth(5);
$objWorksheet2->getColumnDimension('H')->setWidth(5);
$objWorksheet2->getColumnDimension('I')->setWidth(5);
$objWorksheet2->getColumnDimension('J')->setWidth(10);

/*
  $objWorksheet2->getCellByColumnAndRow(0, 1)->setValue("mydate");
  $objWorksheet2->getCellByColumnAndRow(1, 1)->setValue("apous");
  $objWorksheet2->getCellByColumnAndRow(2, 1)->setValue("dik");
  $objWorksheet2->getCellByColumnAndRow(3, 1)->setValue("from");
  $objWorksheet2->getCellByColumnAndRow(4, 1)->setValue("fh");
  $objWorksheet2->getCellByColumnAndRow(5, 1)->setValue("mh");
  $objWorksheet2->getCellByColumnAndRow(6, 1)->setValue("lh");
  $objWorksheet2->getCellByColumnAndRow(7, 1)->setValue("oa");
  $objWorksheet2->getCellByColumnAndRow(8, 1)->setValue("da");
  $objWorksheet2->getCellByColumnAndRow(9, 1)->setValue("student_am");
 */

////////////////////////////history////////////////////////////////////////////////////

$objWorksheet3 = $objPHPExcel->createSheet();
$objWorksheet3->setTitle('paperhistory');

$objWorksheet3->getStyle('A1:D1')->applyFromArray($styleArray);

$objWorksheet3->getColumnDimension('A')->setWidth(10);
$objWorksheet3->getColumnDimension('B')->setWidth(10);
$objWorksheet3->getColumnDimension('C')->setWidth(10);
$objWorksheet3->getColumnDimension('D')->setWidth(10);
/*
  $objWorksheet3->getCellByColumnAndRow(0, 1)->setValue("protok");
  $objWorksheet3->getCellByColumnAndRow(1, 1)->setValue("mydate");
  $objWorksheet3->getCellByColumnAndRow(2, 1)->setValue("am");
  $objWorksheet3->getCellByColumnAndRow(3, 1)->setValue("apous");
 */

////////////////////////////apoysies////////////////////////////////////////////////////

$objWorksheet4 = $objPHPExcel->createSheet();
$objWorksheet4->setTitle('apousies_pre');

$objWorksheet4->getStyle('A1:J1')->applyFromArray($styleArray);

$objWorksheet4->getColumnDimension('A')->setWidth(10);
$objWorksheet4->getColumnDimension('B')->setWidth(10);
$objWorksheet4->getColumnDimension('C')->setWidth(5);
$objWorksheet4->getColumnDimension('D')->setWidth(15);
$objWorksheet4->getColumnDimension('E')->setWidth(5);
$objWorksheet4->getColumnDimension('F')->setWidth(5);
$objWorksheet4->getColumnDimension('G')->setWidth(5);
$objWorksheet4->getColumnDimension('H')->setWidth(5);
$objWorksheet4->getColumnDimension('I')->setWidth(5);
$objWorksheet4->getColumnDimension('J')->setWidth(5);
/*
  $objWorksheet4->getCellByColumnAndRow(0, 1)->setValue("mydate");
  $objWorksheet4->getCellByColumnAndRow(1, 1)->setValue("apous");
  $objWorksheet4->getCellByColumnAndRow(2, 1)->setValue("daysk");
  $objWorksheet4->getCellByColumnAndRow(3, 1)->setValue("dik");
  $objWorksheet4->getCellByColumnAndRow(4, 1)->setValue("fh");
  $objWorksheet4->getCellByColumnAndRow(5, 1)->setValue("mh");
  $objWorksheet4->getCellByColumnAndRow(6, 1)->setValue("lh");
  $objWorksheet4->getCellByColumnAndRow(7, 1)->setValue("oa");
  $objWorksheet4->getCellByColumnAndRow(8, 1)->setValue("da");
  $objWorksheet4->getCellByColumnAndRow(9, 1)->setValue("student_am");
 */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$objWorksheet5 = $objPHPExcel->createSheet();
$objWorksheet5->setTitle('parameters');

$objWorksheet5->getStyle('A1:C1')->applyFromArray($styleArray);



$objWorksheet5->getColumnDimension('A')->setWidth(15);
$objWorksheet5->getColumnDimension('B')->setWidth(15);
$objWorksheet5->getColumnDimension('C')->setWidth(20);
/*
  $objWorksheet5->getCellByColumnAndRow(0, 1)->setValue("tmima");
  $objWorksheet5->getCellByColumnAndRow(1, 1)->setValue("key");
  $objWorksheet5->getCellByColumnAndRow(2, 1)->setValue("value");
 */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$objWorksheet6 = $objPHPExcel->createSheet();
$objWorksheet6->setTitle('dikaiologisi');

$objWorksheet6->getStyle('A1:G1')->applyFromArray($styleArray);

$objWorksheet6->getColumnDimension('A')->setWidth(10);
$objWorksheet6->getColumnDimension('B')->setWidth(10);
$objWorksheet6->getColumnDimension('C')->setWidth(10);
$objWorksheet6->getColumnDimension('D')->setWidth(10);
$objWorksheet6->getColumnDimension('E')->setWidth(10);
$objWorksheet6->getColumnDimension('F')->setWidth(10);
$objWorksheet6->getColumnDimension('G')->setWidth(10);
/*
  $objWorksheet6->getCellByColumnAndRow(0, 1)->setValue("protokolo");
  $objWorksheet6->getCellByColumnAndRow(1, 1)->setValue("mydate");
  $objWorksheet6->getCellByColumnAndRow(2, 1)->setValue("firstday");
  $objWorksheet6->getCellByColumnAndRow(3, 1)->setValue("lastday");
  $objWorksheet6->getCellByColumnAndRow(4, 1)->setValue("countdays");
  $objWorksheet6->getCellByColumnAndRow(5, 1)->setValue("iat_beb");
  $objWorksheet6->getCellByColumnAndRow(6, 1)->setValue("am");
 */
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$objWorksheet7 = $objPHPExcel->createSheet();
$objWorksheet7->setTitle('studentstmimata');

$objWorksheet7->getStyle('A1:B1')->applyFromArray($styleArray);

$objWorksheet7->getColumnDimension('A')->setWidth(10);
$objWorksheet7->getColumnDimension('B')->setWidth(10);


/*
  $objWorksheet7->getCellByColumnAndRow(0, 1)->setValue("student_am");
  $objWorksheet7->getCellByColumnAndRow(1, 1)->setValue("tmima");
 */


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save("useful/base.xlsx");

echo "Το αρχείο base.xlsx δημιουργήθηκε με επιτυχία στις " . date('c');
?>