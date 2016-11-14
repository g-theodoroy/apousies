<?php
require_once ('common.php');
checkUser ();

isset ( $_SESSION ['parentUser'] ) ? $parentUser = $_SESSION ['parentUser'] : $parentUser = false;
if (! $parentUser){
	checktmima();
}

$user = $_SESSION ['userName'];
isset($_SESSION['parent']) ? $parent = $_SESSION['parent'] : $parent = '';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima = '';

// βρίσκω τις στήλες των τμημάτων
$columns = count ( $apousies_define );
// φτιάχνω τα γράμματα για το excell
$letters = array (
		'N',
		'O',
		'P',
		'Q',
		'R',
		'S' 
);

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

//ελέγχω αν υπάρχει το νέο τμήμα	
if ($tmima) {
    $query = "SELECT `am`, `epitheto`, `onoma`, `patronimo`, `ep_kidemona`, `on_kidemona`, `dieythinsi`, `tk`, `poli`, `til1`, `til2`, `filo`, `email` 
    FROM `students`  
    JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `student_am`) 
    WHERE `students`.`user` = '$parent' and  `studentstmimata`.`tmima` = '$tmima' 
    ORDER BY `epitheto`,`onoma` ASC ";
} else {
    $query = "SELECT * FROM `students` WHERE `user` = '$parent'  ORDER BY `epitheto`,`onoma` ASC ;";
}

$result = mysqli_query ( $link, $query );

if (! $result) {
	$errorText = mysqli_error ( $link );
	echo "1 $errorText<hr>";
}

$num = mysqli_num_rows ( $result );

mysqli_close ( $link );

// #####################################################

/**
 * Error reporting
 */
error_reporting ( E_ALL );

/**
 * PHPExcel
 */
require_once ("{$classes_prefix}PHPExcel.php");

// Create new PHPExcel object
$objPHPExcel = new PHPExcel ();

// Set properties
$objPHPExcel->getProperties ()->setCreator ( "Apousies" )->setLastModifiedBy ( "Apousies" )->setTitle ( "students-$parent.xlsx" )->setSubject ( "" )->setDescription ( "" )->setKeywords ( "" )->setCategory ( "" );

// Add some data

$styleArray = array (
		'font' => array (
				'bold' => true 
		),
		'alignment' => array (
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER 
		),
		'borders' => array (
				'bottom' => array (
						'style' => PHPExcel_Style_Border::BORDER_THIN 
				),
				'right' => array (
						'style' => PHPExcel_Style_Border::BORDER_THIN 
				) 
		),
		'fill' => array (
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array (
						'argb' => 'DDDDDDDD' 
				) 
		) 
);

$letter = $letters [$columns - 1];
$objPHPExcel->getActiveSheet ()->getStyle ( "A1:{$letter}1" )->applyFromArray ( $styleArray );

$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'A' )->setAutoSize ( true );
$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'B' )->setAutoSize ( true );
$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'C' )->setAutoSize ( true );
$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'D' )->setAutoSize ( true );
$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'E' )->setAutoSize ( true );
$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'F' )->setAutoSize ( true );
$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'G' )->setAutoSize ( true );
$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'H' )->setAutoSize ( true );
$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'I' )->setAutoSize ( true );
$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'J' )->setAutoSize ( true );
$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'K' )->setAutoSize ( true );
$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'L' )->setAutoSize ( true );
$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'M' )->setAutoSize ( true );
for($i = 0; $i < $columns; $i ++) {
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( $letters [$i] )->setAutoSize ( true );
}

$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 0, 1 )->setValue ( "ΑΜ" );
$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 1, 1 )->setValue ( "ΕΠΙΘΕΤΟ" );
$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 2, 1 )->setValue ( "ΟΝΟΜΑ" );
$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 3, 1 )->setValue ( "ΠΑΤΡΩΝΥΜΟ" );
$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 4, 1 )->setValue ( "ΕΠΙΘΕΤΟ-ΚΗΔΕΜΟΝΑ" );
$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 5, 1 )->setValue ( "ΟΝΟΜΑ-ΚΗΔΕΜΟΝΑ" );
$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 6, 1 )->setValue ( "ΔΙΕΥΘΥΝΣΗ" );
$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 7, 1 )->setValue ( "ΤΚ" );
$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 8, 1 )->setValue ( "ΠΟΛΗ" );
$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 9, 1 )->setValue ( "ΤΗΛ1" );
$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 10, 1 )->setValue ( "ΤΗΛ2" );
$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 11, 1 )->setValue ( "EMAIL" );
$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 12, 1 )->setValue ( "ΦΥΛΟ" );
for($i = 0; $i < $columns; $i ++) {
	$index = $i + 13;
	$setvalue = $apousies_define [$i] ["perigrafi"];
	$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( $index, 1 )->setValue ( $setvalue );
}

$i = 0;
while ( $row = mysqli_fetch_assoc ( $result ) ) {
	$k = $i + 2;
	$am = $row ["am"];
	$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 0, $k )->setValue ( $am );
	$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 1, $k )->setValue ( $row ["epitheto"] );
	$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 2, $k )->setValue ( $row ["onoma"] );
	$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 3, $k )->setValue ( $row ["patronimo"] );
	$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 4, $k )->setValue ( $row ["ep_kidemona"] );
	$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 5, $k )->setValue ( $row ["on_kidemona"] );
	$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 6, $k )->setValue ( $row ["dieythinsi"] );
	$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 7, $k )->setValue ( $row ["tk"] );
	$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 8, $k )->setValue ( $row ["poli"] );
	$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 9, $k )->setValue ( $row ["til1"] );
	$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 10, $k )->setValue ( $row ["til2"] );
	$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 11, $k )->setValue ( $row ["email"] );
	$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 12, $k )->setValue ( $row ["filo"] );
	
	$studentstmimata = gettmimata4student ( $parent, $am );
	
	for($x = 0; $x < $columns; $x ++) {
		$index = $x + 13;
		$kod = $apousies_define [$x] ["kod"];
		if (isset ( $studentstmimata [$kod] )) {
			$setvalue = $studentstmimata [$kod];
			$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( $index, $k )->setValue ( $setvalue );
		}
	}
	$i ++;
}

// Rename sheet
$objPHPExcel->getActiveSheet ()->setTitle ( "students" );

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex ( 0 );

// excel 2000
// Redirect output to a client’s web browser (Excel5)
header ( 'Content-Type: application/vnd.ms-excel' );
header ( "Content-Disposition: attachment;filename=\"students-$parent.xls\"" );
header ( 'Cache-Control: max-age=0' );

$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
$objWriter->save ( 'php://output' );

exit ();

?>

