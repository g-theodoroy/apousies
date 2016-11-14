<?php
require_once ('common.php');
checkUser ();
checktmima ();

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';

$apous_count = count ( $apousies_define );
$dik_count = count ( $dikaiologisi_define );

$apous_fld = array ();
foreach ( $apousies_define as $key => $value ) {
	$apous_fld [$key] ['kod'] = 'fldap' . $value ['kod'];
	$apous_fld [$key] ['label'] = $value ['label'];
}

$dik_fld = array ();
foreach ( $dikaiologisi_define as $key => $value ) {
	$dik_fld [$key] ['kod'] = 'fldd' . $value ['kod'];
	$dik_fld [$key] ['label'] = $value ['label'];
}

require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty ();

$extra_style = '
<style type="text/css">
    td {border-style:none;vertical-align: top; }
    th {border-style:none;vertical-align: middle;}
</style>
';

$extra_javascript = '
<script type="text/javascript" src="js/prints_sel.js"></script>
';

$smarty->assign ( 'title', 'ΑΘΡΟΙΣΜΑΤΑ' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'extra_javascript', $extra_javascript );
$smarty->assign ( 'h1_title', 'Αθροίσματα' );
$smarty->assign ( 'body_attributes', '' );
$smarty->assign ( 'apous_fld', $apous_fld );
$smarty->assign ( 'dik_fld', $dik_fld );

// /συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

// ελέγχω αν υπάρχει το νέο τμήμα
$query = "SELECT `am`, `epitheto`, `onoma` 
    FROM `students`  
    JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `student_am`) 
    WHERE `students`.`user` = '$parent' and  `studentstmimata`.`tmima` = '$tmima' 
    ORDER BY `epitheto`,`onoma`,`patronimo` ASC ";

$result = mysqli_query ( $link, $query );

$num = mysqli_num_rows ( $result );

mysqli_close ( $link );

$studentsdata = Array ();

$i = 0;
while ( $row = mysqli_fetch_assoc ( $result ) ) {
	
	$epitheto = $row ["epitheto"];
	$onoma = $row ["onoma"];
	$am = $row ["am"];
	
	$studentsdata [$i] ['name'] = "$epitheto $onoma";
	$studentsdata [$i] ['am'] = $am;
	$i ++;
}

$smarty->assign ( 'studentsdata', $studentsdata );

$smarty->display ( 'prints_sel.tpl' );
