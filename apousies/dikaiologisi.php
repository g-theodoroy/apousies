<?php
require_once ('common.php');
checkUser ();
checktmima ();

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';
isset ( $_POST ['selstudent'] ) ? $am = $_POST ['selstudent'] : $am = '';
isset ( $_POST ['overapousies'] ) ? $overapousies = $_POST ['overapousies'] : $overapousies = 1;
isset ( $_POST ['backtime'] ) ? $backtime = $_POST ['backtime'] : $backtime = - 2;

if (isset ( $_GET ['st'] )) {
	$am = $_GET ['st'];
}

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

if (isset ( $_POST ['save'] )) {
	
	$valuearray = array ();
	
	// φτιάχνω πίνακα valluearray με τα στοιχεία που έστειλε η φόρμα
	$x = 0;
	foreach ( $_POST as $key => $value ) {
		if (substr ( $key, 0, 3 ) == "chk") {
			$mydate = substr ( $key, 3, 8 );
			$valuearray [$x] ['mydate'] = $mydate;
			isset($_POST ['diktype' . $mydate]) ? $valuearray [$x] ['from'] = $_POST ['diktype' . $mydate] : $valuearray [$x] ['from'] = null;
			$valuearray [$x] ['apousies'] = $_POST ['dik' . $mydate];
			$x ++;
		}
		// echo "$key --> $value <hr>";
	}
	
	begin ();
	$errorcheck = false;
	
	for($i = 0; $i < count ( $valuearray ); $i ++) {
		
		if ($valuearray [$i] ['apousies'] == '' || $valuearray [$i] ['apousies'] == 0) {
			
			$setstr = 'SET `dik`= 0 , `from` = "" ';
		} else {
			$setstr = 'SET `dik` = ' . $valuearray [$i] ['apousies'] . ', `from` = "' . $valuearray [$i] ['from'] . '"';
		}
		
		$mydate = $valuearray [$i] ['mydate'];
		
		$query = "UPDATE `apousies` $setstr WHERE `user` = '$parent' AND `student_am`= '$am' AND `mydate` = $mydate ;";
		
		$result = mysqli_query ( $link, $query );
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "0 $errorText<hr>";
			$errorcheck = true;
		}
	}
	
	if ($errorcheck == true) {
		rollback ();
	} else {
		commit ();
		$_SESSION ["havechanges"] = true;
	}
}


$smarty = new Smarty ();

$extra_style = '
<style type="text/css">
td , th
    {
    border-color:#ddd;
    border-style:solid;
    border-width:1px;
    text-align: center;
    vertical-align : middle;
     padding : 0px 10px 0px 10px;
    }
</style>
';

$extra_javascript = '
<script type="text/javascript" src="js/dikaiologisi.js"></script>
<script type="text/javascript">
// <!--
';
$extra_javascript .= '
var apous_def_array = new Array();
';

foreach ( $apousies_define as $key => $value ) {
	$extra_javascript .= "apous_def_array[$key]= 'ap" . $value ['kod'] . "';\n";
}

$extra_javascript .= '
var dik_def_array = new Array();
';

foreach ( $dikaiologisi_define as $key => $value ) {
	$extra_javascript .= "dik_def_array[$key]= 'di" . $value ['kod'] . "';\n";
}

$extra_javascript .= '
// -->
</script>
';

$smarty->assign ( 'title', 'ΔΙΚΑΙΟΛΟΓΗΣΗ ΑΠΟΥΣΙΩΝ' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'extra_javascript', $extra_javascript );
$smarty->assign ( 'h1_title', 'Δικαιολόγηση' );
// $smarty->assign('maxam', $maxam);

if ($am) {
	$body_attributes = "onload=\"document.getElementById('selstudent').value=$am ; showHint($am, $overapousies, $backtime )\"";
} else {
	$body_attributes = '';
}
$smarty->assign ( 'body_attributes', $body_attributes );

$query = "SELECT `am`,`epitheto`, `onoma` FROM `students`  JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `student_am`) WHERE `students`.`user` = '$parent' and  `studentstmimata`.`tmima` = '$tmima' ORDER BY `epitheto`,`onoma` ASC ;";
$result = mysqli_query ( $link, $query );
if (! $result) {
	$errorText = mysqli_error ( $link );
	echo "3 $errorText<hr>";
}
$num = mysqli_num_rows ( $result );

mysqli_close ( $link );

$students = array ();

while ( $row = mysqli_fetch_assoc ( $result ) ) {
	$am = $row ["am"];
	$epitheto = $row ["epitheto"];
	$onoma = $row ["onoma"];
	$students [] = "<option value=\"$am\" >$epitheto $onoma</option>";
}

$smarty->assign ( 'students', $students );
$smarty->display ( 'dikaiologisi.tpl' );
