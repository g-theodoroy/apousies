<?php
require_once ('common.php');
checkUser ();
checktmima ();

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';
if (isset ( $_SESSION ['protok'] )) {
	$protok = $_SESSION ['protok'];
	unset ( $_SESSION ['protok'] );
} else {
	$protok = '';
}

if (isset ( $_POST ['selstudent'] )) {
	$am = $_POST ['selstudent'];
	$_SESSION ['post_aitisi_dik'] = $_POST;
} else {
	if (isset ( $_SESSION ['post_aitisi_dik'] )) {
		$_POST = $_SESSION ['post_aitisi_dik'];
		unset ( $_SESSION ['post_aitisi_dik'] );
		$am = $_POST ['selstudent'];
	} else {
		$am = '';
	}
}

$valuearray = array ();

// φτιάχνω πίνακα valluearray με τα στοιχεία που έστειλε η φόρμα
$x = 0;
foreach ( $_POST as $key => $value ) {
	if (substr ( $key, 0, 3 ) == "chk") {
		$mydate = substr ( $key, 3, 8 );
		$valuearray [$x] ['mydate'] = $mydate;
		$valuearray [$x] ['field'] = isset ( $_POST ['diktype' . $mydate] ) ? $_POST ['diktype' . $mydate] : "";
		$x ++;
	}
}

$countdays = count ( $valuearray );
$firstday = null;
$lastday = null;

if ($countdays == 1) {
	$mydate = $valuearray [0] ['mydate'];
	$formateddate = intval ( substr ( $mydate, 6, 2 ) ) . "/" . intval ( substr ( $mydate, 4, 2 ) ) . "/" . substr ( $mydate, 0, 4 );
	$firstday = $lastday = $formateddate;
} elseif ($countdays > 1) {
	sort ( $valuearray );
	$mydate = $valuearray [0] ['mydate'];
	$formateddate = intval ( substr ( $mydate, 6, 2 ) ) . "/" . intval ( substr ( $mydate, 4, 2 ) ) . "/" . substr ( $mydate, 0, 4 );
	$firstday = $formateddate;
	$mydate = $valuearray [$countdays - 1] ['mydate'];
	$formateddate = intval ( substr ( $mydate, 6, 2 ) ) . "/" . intval ( substr ( $mydate, 4, 2 ) ) . "/" . substr ( $mydate, 0, 4 );
	$lastday = $formateddate;
}
isset($valuearray [0] ['field'] ) ? $field = $valuearray [0] ['field'] : $field = null ;
$field_array = array ();
$kod_array = array ();
$dik_count = count ( $dikaiologisi_define );
for($x = 0; $x < $dik_count; $x ++) {
	if ($field == $dikaiologisi_define [$x] ['kod']) {
		$field_array [$x] = 'checked';
	} else {
		$field_array [$x] = '';
	}
	$kod_array [$x] = $dikaiologisi_define [$x] ['kod'];
}

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$query = "SELECT * FROM `students` WHERE `user` = '$parent' AND `am`= '$am' ; ";
$result = mysqli_query ( $link, $query );
if (! $result) {
	$errorText = mysqli_error ( $link );
	echo "0 $errorText<hr>";
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
    vertical-align : middle;
    padding : 0px 10px 0px 10px;
    }
.nomargin
    {
    margin : 0 0 0 0 ;                
    }
    input{
    border-color:#ddd;
    border-style:none;
    font-size:90%;
    }
</style>
';

$extra_javascript = '';

$smarty->assign ( 'title', 'ΑΙΤΗΣΗ ΔΙΚΑΙΟΛΟΓΗΣΗΣ ΑΠΟΥΣΙΩΝ' );
$smarty->assign ( 'h1_title', 'Αίτηση Δικαιολόγησης' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'extra_javascript', $extra_javascript );
$smarty->assign ( 'body_attributes', '');
$smarty->assign ( 'protok', $protok );
$smarty->assign ( 'studentsdata', $studentsdata );
$smarty->assign ( 'txtdata', $txtdata[$tmima] );
$smarty->assign ( 'countdays', $countdays );
$smarty->assign ( 'firstday', $firstday );
$smarty->assign ( 'lastday', $lastday );
$smarty->assign ( 'field_array', $field_array );
$smarty->assign ( 'kod_array', $kod_array );
$smarty->assign ( 'logos_list', $logos_list );
$smarty->assign ( 'dik_me_list', $dik_me_list );
$smarty->assign ( 'dik_count', $dik_count );
$smarty->assign ( 'student_arthro_0', $student_arthro_0 );
$smarty->assign ( 'student_arthro_1', $student_arthro_1 );
$smarty->assign ( 'student_arthro_2', $student_arthro_2 );

$smarty->display ( 'aitisi_dik.tpl' );
