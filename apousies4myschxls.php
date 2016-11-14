<?php
require_once ('common.php');
checkUser ();
// Αν είναι subuser βγάζει μόνο το τρέχον τμήμα
if (! isset ( $_SESSION ['parentUser'] ) || $_SESSION ['parentUser'] != true) {
	checktmima ();
}

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';


if (! isset ( $_POST ["submitBtn"] )) {
	
	
	require_once 'Smarty/Smarty.class.php';
	$smarty = new Smarty ();
	
	$extra_style = '
<style type="text/css">
	td
        {
        border-style:none; text-align:center;
        }
	th
        {
        border-color:#ddd;border-style:none; border-width:1px; text-align:center;
        }
</style>
';
	
	$smarty->assign ( 'title', 'Απουσίες XLS για myschool' );
	$smarty->assign ( 'extra_style', $extra_style );
	$smarty->assign ( 'h1_title', 'Απουσίες XLS για myschool' );
	$smarty->assign ( 'body_attributes', "" );
	$smarty->assign ( 'extra_javascript', "" );
	
	$smarty->display ( 'apousiesgxls4myschool.tpl' );
	exit ();
}

$apo = '';
$eos = '';
if (isset ( $_POST ["apo"] )) {
	$mystr = str_replace("/", "-",$_POST ["apo"]);
	$time = strtotime($mystr);
	if( $time )	$apo = date('Ymd',$time);
}
if (isset ( $_POST ["eos"] )) {
	$mystr = str_replace("/", "-",$_POST ["eos"]);
	$time = strtotime($mystr);
	if( $time )	$eos = date('Ymd',$time);
}

$wherestr = '';
if ($apo)$wherestr .= " and mydate >= '$apo'";
if ($eos)$wherestr .= " and mydate <= '$eos'";


if ($tmima) {
	$prequery = "select  tmima, mydate, DATE_FORMAT(`mydate`,'%e/%c/%Y') as `formdate`, am, apous, dik, name from studentstmimata JOIN
	(SELECT concat(epitheto,' ', onoma) as name, am, mydate, apous, dik from students join apousies_pre on  am  = apousies_pre.student_am
	where apousies_pre.user='$parent'
	order by mydate, epitheto, onoma) AS t1
	on t1.am = studentstmimata.student_am
	WHERE tmima = '$tmima' $wherestr ";
	
	$query = "select  tmima, mydate, DATE_FORMAT(`mydate`,'%e/%c/%Y') as `formdate`, am, apous - 9 * FLOOR( (apous-1 ) / 9 ) as apous, dik, name from studentstmimata JOIN
	(SELECT concat(epitheto,' ', onoma) as name, am, mydate, apous, dik from students join apousies on  am  = apousies.student_am
	where apousies.user='$parent'
	order by mydate, epitheto, onoma) AS t1
	on t1.am = studentstmimata.student_am
	WHERE tmima = '$tmima' $wherestr";
} else {
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	$tmiarray = gettmimata4user ( $parent );
	$prequery = '';
	$query = '';
	$kod = $apousies_define_ini [0] ["kod"];
	foreach ( $tmiarray [$kod] as $tmigen ) {
		
		$prequery .= "select  tmima, mydate, DATE_FORMAT(`mydate`,'%e/%c/%Y') as `formdate`, am, apous, dik, name from studentstmimata JOIN
		(SELECT concat(epitheto,' ', onoma) as name, am, mydate, apous, dik from students join apousies_pre on  am  = apousies_pre.student_am
		where apousies_pre.user='$parent'
		order by mydate, epitheto, onoma) AS t1
		on t1.am = studentstmimata.student_am
		WHERE tmima = '$tmigen' $wherestr";
		
		$query .= "select tmima, mydate, DATE_FORMAT(`mydate`,'%e/%c/%Y') as `formdate`, am,  apous - 9 * FLOOR( (apous-1 ) / 9 ) as apous, dik, name from studentstmimata JOIN
		(SELECT concat(epitheto, ' ', onoma) as name, am, mydate, apous, dik from students join apousies on  am  = apousies.student_am
		where apousies.user='$parent'
		order by mydate, epitheto, onoma) AS t1
		on t1.am = studentstmimata.student_am
		WHERE tmima = '$tmigen' $wherestr";
		
		if (next ( $tmiarray [$kod] )) {
			$prequery .= "\nUNION\n";
			$query .= "\nUNION\n";
		}
	}
	
	mysqli_close ( $link );
}

$finalquery = $prequery . "\nUNION\n" . $query . " order by tmima, mydate, name ;";

// echo $finalquery;
// echo "<hr>";

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$result = mysqli_query ( $link, $finalquery );

if (! $result) {
	$errorText = mysqli_error ( $link );
	echo "$errorText<hr>";
}

//$num = mysqli_num_rows ( $result );

$data = array ();
$x = 0;
while ( $row = mysqli_fetch_assoc ( $result ) ) {
	$data [$x] [0] = $row ['tmima'];
	$data [$x] [1] = $row ['formdate'];
	$data [$x] [2] = $row ['am'];
	$data [$x] [3] = sum_per_digits ( $row ['apous'], 3 );
	$data [$x] [4] = sum_per_digits ( $row ['dik'], 3 );
	$data [$x] [5] = $row ['name'];
	$x ++;
}

mysqli_close ( $link );

// print_r($data);

$keytmima = "";
$keydate = "";
$pindata = array ();
$x = 0;
foreach ( $data as $rowdata ) {
	if ($keytmima == $rowdata [0] && $keydate == $rowdata [1]) {
		$pindata [$rowdata [0]] [$rowdata [1]] [$x] = [ 
				$rowdata [2],
				$rowdata [3],
				$rowdata [4],
				$rowdata [5] 
		];
	} else {
		$x = 0;
		$pindata [$rowdata [0]] [$rowdata [1]] [$x] = [ 
				$rowdata [2],
				$rowdata [3],
				$rowdata [4],
				$rowdata [5] 
		];
	}
	$x ++;
	$keytmima = $rowdata [0];
	$keydate = $rowdata [1];
}

// print_r($pindata);

/**
 * PHPExcel
 */
require_once ("{$classes_prefix}PHPExcel.php");

// Create new PHPExcel object
$objPHPExcel = new PHPExcel ();

// Set properties
$objPHPExcel->getProperties ()->setCreator ( "Apousies" )->setLastModifiedBy ( "Apousies" )->setTitle ( "apousies4myschool-$parent.xls" )->setSubject ( "" )->setDescription ( "" )->setKeywords ( "" )->setCategory ( "" );

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

$is_first = true;

foreach ( $pindata as $keytmima => $pin0 ) {
	if ($is_first) {
		$objWorksheet = $objPHPExcel->getActiveSheet ();
		$is_first = false;
	} else {
		$objWorksheet = $objPHPExcel->createSheet ();
	}
	
	$objWorksheet->setTitle ( $keytmima );
	
	$objWorksheet->getStyle ( "A1:E1" )->applyFromArray ( $styleArray );
	
	$objWorksheet->getColumnDimension ( "A" )->setAutoSize ( true );
	$objWorksheet->getColumnDimension ( "B" )->setAutoSize ( true );
	$objWorksheet->getColumnDimension ( "C" )->setAutoSize ( true );
	$objWorksheet->getColumnDimension ( "D" )->setAutoSize ( true );
	$objWorksheet->getColumnDimension ( "E" )->setAutoSize ( true );
	
	$objWorksheet->getCellByColumnAndRow ( 0, 1 )->setValue ( 'ΑΜ' );
	$objWorksheet->getCellByColumnAndRow ( 1, 1 )->setValue ( 'Σύνολο Απουσιών' );
	$objWorksheet->getCellByColumnAndRow ( 2, 1 )->setValue ( 'Δικαιολογημένες Απουσίες' );
	$objWorksheet->getCellByColumnAndRow ( 3, 1 )->setValue ( 'Ημερομηνία' );
	$objWorksheet->getCellByColumnAndRow ( 4, 1 )->setValue ( 'Ονοματεπώνυμο' );
	
	$lastrow = 2;
	foreach ( $pin0 as $keydate => $pin1 ) {
		foreach ( $pin1 as $keyindex => $pinvalues ) {
			$objWorksheet->getCellByColumnAndRow ( 0, $lastrow )->setValue ( $pinvalues [0] );
			$objWorksheet->getCellByColumnAndRow ( 1, $lastrow )->setValue ( $pinvalues [1] );
			if ($pinvalues [2] > 0) $objWorksheet->getCellByColumnAndRow ( 2, $lastrow )->setValue ( $pinvalues [2] );
			$objWorksheet->getCellByColumnAndRow ( 3, $lastrow )->setValue ( $keydate );
			$objWorksheet->getCellByColumnAndRow ( 4, $lastrow )->setValue ( $pinvalues [3] );
			$lastrow ++;
		}
	}
}

foreach ( $pindata as $keytmima => $pin0 ) {
	foreach ( $pin0 as $keydate => $pin1 ) {
		$objWorksheet = $objPHPExcel->createSheet ();
		$objWorksheet->setTitle ( createSafeSheetName ( $keydate . "_" . $keytmima ) );
		
		$objWorksheet->getStyle ( "A1:E1" )->applyFromArray ( $styleArray );
		
		$objWorksheet->getColumnDimension ( "A" )->setAutoSize ( true );
		$objWorksheet->getColumnDimension ( "B" )->setAutoSize ( true );
		$objWorksheet->getColumnDimension ( "C" )->setAutoSize ( true );
		$objWorksheet->getColumnDimension ( "D" )->setAutoSize ( true );
		$objWorksheet->getColumnDimension ( "E" )->setAutoSize ( true );
		
		$objWorksheet->getCellByColumnAndRow ( 0, 1 )->setValue ( 'ΑΜ' );
		$objWorksheet->getCellByColumnAndRow ( 1, 1 )->setValue ( 'Σύνολο Απουσιών' );
		$objWorksheet->getCellByColumnAndRow ( 2, 1 )->setValue ( 'Δικαιολογημένες Απουσίες' );
		$objWorksheet->getCellByColumnAndRow ( 3, 1 )->setValue ( 'Ημερομηνία' );
		$objWorksheet->getCellByColumnAndRow ( 4, 1 )->setValue ( 'Ονοματεπώνυμο' );
		
		$lastrow = 2;
		foreach ( $pin1 as $keyindex => $pinvalues ) {
			$objWorksheet->getCellByColumnAndRow ( 0, $lastrow )->setValue ( $pinvalues [0] );
			$objWorksheet->getCellByColumnAndRow ( 1, $lastrow )->setValue ( $pinvalues [1] );
			if ($pinvalues [2] > 0) $objWorksheet->getCellByColumnAndRow ( 2, $lastrow )->setValue ( $pinvalues [2] );
			$objWorksheet->getCellByColumnAndRow ( 3, $lastrow )->setValue ( $keydate );
			$objWorksheet->getCellByColumnAndRow ( 4, $lastrow )->setValue ( $pinvalues [3] );
			$lastrow ++;
		}
	}
}
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex ( 0 );

$backupdate = date ( 'j-m-Y' );
if ($tmima)
	$tmima .= "-";
	// excel 2000
	// Redirect output to a client’s web browser (Excel5)
header ( 'Content-Type: application/vnd.ms-excel' );
header ( "Content-Disposition: attachment;filename=\"apousies4myschool-$parent-$tmima$backupdate.xls\"" );
header ( 'Cache-Control: max-age=0' );

$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
$objWriter->save ( 'php://output' );
exit ();

?>