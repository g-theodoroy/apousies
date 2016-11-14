<?php
require_once ('common.php');
checkUser ();
checktmima ();

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';
isset ( $_POST ['am'] ) ? $am = $_POST ['am'] : $am = '';
isset ( $_POST ['submitBtn'] ) ? $target = $_POST ['submitBtn'] : $target = 'print';

$valuearray = array ();

// φτιάχνω πίνακα valluearray με τα στοιχεία που έστειλε η φόρμα
foreach ( $_POST as $key => $value ) {
	$valuearray ["$key"] = $value;
}

// print_r($valuearray);
// echo "<hr>";

isset ( $valuearray ['protok'] ) ? $protokolo = $valuearray ['protok'] : $protokolo = null;
isset ( $valuearray ['protok_date'] ) ? $mydate = makedatestamp ( $valuearray ['protok_date'] ) : $mydate = null;
isset ( $valuearray ['countdays'] ) ? $countdays = $valuearray ['countdays'] : $countdays = null;
isset ( $valuearray ['firstday'] ) ? $firstday = $valuearray ['firstday'] : $firstday = null;
isset ( $valuearray ['lastday'] ) ? $lastday = $valuearray ['lastday'] : $lastday = null;
isset ( $valuearray ['allos-logos'] ) ? $allos_logos = trim ( $valuearray ['allos-logos'] ) : $allos_logos = null;

$index = null;

isset ( $valuearray ['dil_kid-iat_beb'] ) ? $kod = $valuearray ['dil_kid-iat_beb'] : $kod = null;

// για την εισαγωγή στον πίνακα δικαιολόγηση
// αν είναι από γιατρό 1 αλλιώς 0
$kod == 'D' ? $iat_beb = 1 :  $iat_beb = 0;

for($x = 0; $x < count ( $dikaiologisi_define ); $x ++) {
	if ($dikaiologisi_define [$x] ['kod'] == $kod) {
		$index = $x;
		break;
	}
}


isset ( $dik_me_list [$index] ) ? $bebaiosi = '-&nbsp;&nbsp;' . $dik_me_list [$index] : $bebaiosi = null;

isset ( $valuearray ['astheneia-logos'] ) ? $kod = $valuearray ['astheneia-logos'] : $kod = null;
if ($kod == 'allo') {
	if ($allos_logos) {
		$logos = '-&nbsp;&nbsp;' . $allos_logos;
	} else {
		$logos = '-&nbsp;&nbsp;' . '_______________________________';
	}
} else {
	for($x = 0; $x < count ( $dikaiologisi_define ); $x ++) {
		if ($dikaiologisi_define [$x] ['kod'] == $kod) {
			$index = $x;
			break;
		}
	}
	isset ( $logos_list [$index] ) ? $logos = '-&nbsp;&nbsp;' . $logos_list [$index] : $logos = null;
}

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

// αποθήκευση των στοιχείων της αίτησης
if (isset ( $_POST ['submitBtn'] ) && $valuearray ['history'] == '1') {
	// έλεγχος αν υπάρχει ήδη καταχωρημένη
	$query = "SELECT * FROM `dikaiologisi` WHERE `protokolo`='$protokolo' AND `mydate`='$mydate' AND `am`='$am' AND `user`='$parent'; ";
	$result = mysqli_query ( $link, $query );
	if (! $result) {
		$errorText = mysqli_error ( $link );
		echo "check-existance: $errorText<hr>";
	}
	$num = mysqli_num_rows ( $result );
	if (! $num) {
		$query = "INSERT INTO `dikaiologisi` (`protokolo`, `mydate`, `firstday`, `lastday`, `countdays`, `iat_beb`, `am`, `user`) VALUES ('$protokolo', '$mydate', '$firstday', '$lastday', '$countdays', '$iat_beb', '$am', '$parent'); ";
		
		$result = mysqli_query ( $link, $query );
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "insert: $errorText<hr>";
		}
	}
}

$query = "SELECT * FROM `students` WHERE `user` = '$parent' AND `am`= '$am' ; ";
$result = mysqli_query ( $link, $query );
if (! $result) {
	$errorText = mysqli_error ( $link );
	echo "select-students: $errorText<hr>";
}
$row = mysqli_fetch_assoc ( $result );

$studentsdata = array ();

$studentsdata ['am'] = $am;
$studentsdata ['ep'] = $row ["epitheto"];
$studentsdata ['on'] = $row ["onoma"];
$studentsdata ['pa'] = $row ["patronimo"];
$studentsdata ['ep_ki'] = $row ["ep_kidemona"];
$studentsdata ['on_ki'] = $row ["on_kidemona"];
$studentsdata ['filo'] = $row ["filo"];

if ($studentsdata ['filo'] == 'Α') {
	$student_arthro_0 = 'ΜΑΘΗΤΗ';
	$student_arthro_1 = 'ΜΑΘΗΤΗ';
	$student_arthro_2 = 'ο μαθητής';
} else {
	$student_arthro_0 = 'ΜΑΘΗΤΡΙΑ';
	$student_arthro_1 = 'ΜΑΘΗΤΡΙΑΣ';
	$student_arthro_2 = 'η μαθήτρια';
}

$txtdata = get_all_parameters ( $parent, $tmima );

require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty ();

$extra_style = '
<style type="text/css">
td , th
    {
    border-color:#ddd;
    border-style:none;
    border-width:1px;
    vertical-align : top;
    padding : 0px 5px 0px 5px;
    }
    .nomargin
    {
    margin : 0 0 0 0 ;                
    }
</style>
';

$extra_javascript = '';

$smarty->assign ( 'title', 'ΑΙΤΗΣΗ ΔΙΚΑΙΟΛΟΓΗΣΗΣ ΑΠΟΥΣΙΩΝ' );
$smarty->assign ( 'h1_title', 'Αίτηση Δικαιολόγησης' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'extra_javascript', $extra_javascript );

if (isset ( $valuearray ['protok'] )) {
	$smarty->assign ( 'protok', $valuearray ['protok'] );
} else {
	$smarty->assign ( 'protok', '' );
}
if (isset ( $valuearray ['protok_date'] )) {
	$smarty->assign ( 'protok_date', $valuearray ['protok_date'] );
} else {
	$smarty->assign ( 'protok_date', '' );
}
$smarty->assign ( 'studentsdata', $studentsdata );
$smarty->assign ( 'txtdata', $txtdata [$tmima] );
$smarty->assign ( 'countdays', $countdays );
$smarty->assign ( 'firstday', $firstday );
$smarty->assign ( 'lastday', $lastday );
$smarty->assign ( 'bebaiosi', $bebaiosi );
$smarty->assign ( 'logos', $logos );
$smarty->assign ( 'student_arthro_0', $student_arthro_0 );
$smarty->assign ( 'student_arthro_1', $student_arthro_1 );
$smarty->assign ( 'student_arthro_2', $student_arthro_2 );

if ($target == 'print') {
	$smarty->display ( 'aitisi_print.tpl' );
}
if ($target == 'pdf' || $target == 'email') {
	$html = $smarty->fetch ( 'aitisi_print.tpl' );
	
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
	$filename = $tmima . "_" . $studentsdata ['ep'] . "-" . $studentsdata ['on'] . "_" . $valuearray ['protok'] . "_" . str_replace ( '/', '-', $valuearray ['protok_date'] ) . ".pdf";
	
	if ($target == 'email') {
		
		isset ( $_POST ['protok'] ) ? $_SESSION ['protok'] = $_POST ['protok'] : $dummy = 1;
		
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		$query = "SELECT `email` FROM `users` WHERE `username` = '$parent' ;";
		$result = mysqli_query ( $link, $query );
		$row = mysqli_fetch_assoc ( $result );
		$email = $row ["email"];
		mysqli_close ( $link );
		
		$mail = new MyPHPMailer ();
		$mail->Subject = "Διαχείριση Απουσιών. Αίτηση Δικαιολόγησης Απουσιών";
		$fileContent = $mpdf->Output ( $filename, 'S' );
		$body = "Διαχείριση Απουσιών.\r\n\r\nΣτο επισυναπτόμενο αρχείο βρίσκεται σε μορφή pdf η αίτηση δικαιολόγησης απουσιών. \r\n";
		
		$mail->Body = $body;
		$mail->AddAddress ( $email );
		$mail->AddStringAttachment ( $fileContent, $filename );
		
		if ($mail->Send ()) {
			header ( 'Location: aitisi_dik.php?m=1' );
		} else {
			header ( 'Location: aitisi_dik.php?m=0' );
		}
	} else {
		$mpdf->Output ( $filename, 'D' );
		exit ();
	}
}
