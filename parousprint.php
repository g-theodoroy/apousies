<?php
require_once ('common.php');
checkUser ();
checktmima ();

$apous_count = count ( $apousies_define );
$dik_count = count ( $dikaiologisi_define );
$rowspan_big = $apous_count * 5;
$rowspan_min = $apous_count;

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';
isset ( $_GET ['st'] ) ? $student = $_GET ['st'] : $student = '';
isset ( $_GET ['t'] ) ? $target = $_GET ['t'] : $target = 'l';
isset ( $_GET ['do'] ) ? $todo = $_GET ['do'] : $todo = '';

switch ($target) {
	case "l" :
		$template = 'parousprint_lyk.tpl';
		$totalfile = 'get_totals_parous_lyk.php';
		break;
	case "a" :
		$template = 'parousprint_A_lyk.tpl';
		$totalfile = 'get_totals_parous_A_lyk.php';
		break;
	case "g" :
		$template = 'parousprint_gym.tpl';
		$totalfile = 'get_totals_parous_gym.php';
		break;
}

$students = array ();

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

if ($student == 'all' || $student == 'allpdf') {
	$query0 = "SELECT `am` FROM `students`JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am`) 
    where `students`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima' ORDER BY `epitheto`,`onoma` ASC ;";
	$result0 = mysqli_query ( $link, $query0 );
	if (! $result0) {
		$errorText = mysqli_error ( $link );
		echo "0 $errorText<hr>";
	}
	$num0 = mysqli_num_rows ( $result0 );
	
	while ( $row0 = mysqli_fetch_assoc ( $result0 ) ) {
		$students [] = $row0 ["am"];
	}
} else {
	$students [] = $student;
}

$studentspinakas = array ();

for($z = 0; $z < count ( $students ); $z ++) {
	$student = $students [$z];
	
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	
	// έλεγχος αν το τμήμα είναι συνδεμένο με άλλα κατ ή ειδ -----------------------------
	$query1 = "SELECT `am`, `epitheto`, `onoma`, `patronimo` 
    FROM `students`JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am` 
    where `students`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima' AND `students`.`am`= '$student' ;";
	$result1 = mysqli_query ( $link, $query1 );
	if (! $result1) {
		$errorText = mysqli_error ( $link );
		echo "3 $errorText<hr>";
	}
	$row1 = mysqli_fetch_assoc ( $result1 );
	$am = $row1 ["am"];
	$tmimata_array = gettmimata4student ( $parent, $am );
	$showtmima = '';
	if ($tmimata_array) {
		foreach ( $tmimata_array as $key => $value ) {
			// if ($value != $tmima) {
			$showtmima .= " $value ,";
			// }
		}
		$showtmima = substr ( $showtmima, 0, - 1 );
	}
	
	$studata = $row1 ["epitheto"] . " " . $row1 ["onoma"] . " " . $row1 ["patronimo"] . " =>" . $showtmima;
	
	// ######################################################
	$studentspinakas [$z] ['studata'] = $studata;
	// ######################################################
	
	$totapstr = '';
	$apstr = '';
	
	for($i = 0; $i < $apous_count; $i ++) {
		$k = $i + 1;
		$totapstr .= " MID(`apousies`.`apous`,$k,1) +";
		$apstr .= " MID(`apousies`.`apous`,$k,1) as `ap" . $apousies_define [$i] ['kod'] . "`,";
	}
	$totapstr = substr ( $totapstr, 0, - 1 ) . " as `totap`";
	$apstr = substr ( $apstr, 0, - 1 );
	
	$colorstr = "CASE `from` WHEN '' THEN 'white' ";
	for($i = 0; $i < $dik_count; $i ++) {
		$kod = $dikaiologisi_define [$i] ['kod'];
		$value = $dikaiologisi_define [$i] ['color'];
		$colorstr .= "WHEN '$kod' THEN '$value' ";
	}
	$colorstr .= "END as `color`";
	
	$query = "SELECT DATE_FORMAT(`mydate`,'%e') as `myday`, DATE_FORMAT(`mydate`,'%c') as `mymonth`, $totapstr, `apous` ,`dik` , `fh`, `mh`,`lh`, `oa`, `da`,$colorstr, IF(`oa`=0 and `da`=0, '','marked') as `class` 
    FROM `apousies` LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am`  
    JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am`  
    where `apousies`.`user` ='$parent' and `studentstmimata`.`tmima`= '$tmima' and `apousies`.`student_am`='$student' order by `mydate`;";
	
	$result = mysqli_query ( $link, $query );
	if (! $result) {
		$errorText = mysqli_error ( $link );
		echo "1 $errorText<hr>";
	}
	$num = mysqli_num_rows ( $result );
	
	$detaildata = array ();
	
	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		$day = $row ["myday"];
		$month = $row ["mymonth"];
		
		$totap = $row ["totap"];
		
		$apous = $row ["apous"];
		if (! $apous) {
			$apous = str_repeat ( '0', $apous_count );
		}
		$apous_array = array ();
		for($x = 0; $x < $apous_count; $x ++) {
			$apous_array [$x] = ( int ) substr ( $apous, $x, 1 ) == 0 ? '&nbsp;' : ( int ) substr ( $apous, $x, 1 );
		}
		$dik = $row ["dik"];
		if (! $dik)
			$dik = '&nbsp;';
		
		$class = $row ["color"] . " " . $row ["class"];
		
		$detaildata [$month] [$day] [0] = $totap;
		$detaildata [$month] [$day] [1] = $apous_array;
		$detaildata [$month] [$day] [2] = $class;
	}
	
	// ######################################################
	$studentspinakas [$z] ['detaildata'] = $detaildata;
	// ######################################################
	
	ob_start ();
	$_GET ['am'] = $student;
	include ($totalfile);
	$totalstring = ob_get_contents ();
	ob_end_clean ();
	
	// ######################################################
	$studentspinakas [$z] ['totaldata'] = $totalstring;
	// ######################################################
} // For

$extra_style = '
<style type="text/css">
    table {page-break-inside : avoid ;}
</style>
';

require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty ();
$smarty->assign ( 'title', 'ΠΑΡΟΥΣΙΟΛΟΓΙΟ' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'studentspinakas', $studentspinakas );
$smarty->assign ( 'apous_count', $apous_count );
$smarty->assign ( 'rowspan_big', $rowspan_big );
$smarty->assign ( 'rowspan_min', $rowspan_min );
$row_names_array = array ();

for($x = 0; $x < $apous_count; $x ++) {
	$row_names_array [$x] = substr ( $apousies_define [$x] ['perigrafi'], 0, 2 );
}

$smarty->assign ( 'row_names_array', $row_names_array );

if ($todo == 'allpdf') {
	
	$html = $smarty->fetch ( $template );
	
	require_once ("{$classes_prefix}mpdf/mpdf.php");
	
	$page_top = 15;
	$page_bottom = 15;
	$page_left = 30;
	$page_right = 30;
	$page_format = 'A4';
	$font_size = 0;
	$orientation = 'P';
	
	$mpdf = new mPDF ( '', // mode - default ''
$page_format, // format - A4, for example, default ''
$font_size, // font size - default 0
'', // default font family
$page_left, // margin_left
$page_right, // margin right
$page_top, // margin top
$page_bottom, // margin bottom
0, // margin header
0, // margin footer
$orientation ); // L - landscape, P - portrait
	
	$mpdf->WriteHTML ( $html );
	
	$filename = "Παρουσιολόγιο_$parent\_$tmima\_" . date ( "j-n-Y" ) . ".pdf";
	
	$mpdf->Output ( $filename, 'D' );
	exit ();
}

if ($todo == 'pdf') {
	
	$html = $smarty->fetch ( $template );
	
	require_once ("{$classes_prefix}mpdf/mpdf.php");
	
	$page_top = 15;
	$page_bottom = 15;
	$page_left = 30;
	$page_right = 30;
	$page_format = 'A4';
	$font_size = 0;
	$orientation = 'P';
	
	$mpdf = new mPDF ( '', // mode - default ''
$page_format, // format - A4, for example, default ''
$font_size, // font size - default 0
'', // default font family
$page_left, // margin_left
$page_right, // margin right
$page_top, // margin top
$page_bottom, // margin bottom
0, // margin header
0, // margin footer
$orientation ); // L - landscape, P - portrait
	
	$mpdf->WriteHTML ( $html );
	
	$filename = "Παρουσιολόγιο_$parent\_$tmima\_" . date ( "j-n-Y" ) . ".pdf";
	
	$mpdf->Output ( $filename, 'D' );
	exit ();
}

$smarty->display ( $template );
?>