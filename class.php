<?php
require_once ('common.php');
checkUser ();

/*
 * foreach($_POST as $key => $value){
 * echo "$key = $value<hr>";
 * }
 */

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';

if (isset ( $_POST ['submitBtn'] )) {
	// Get user input
	isset ( $_POST ['seltmima'] ) ? $newtmima = $_POST ['seltmima'] : $newtmima = '';
	isset ( $_POST ['type'] ) ? $type = $_POST ['type'] : $type = '';
	
	if (isset ( $_POST ['newtmima'] ) && ($_POST ['newtmima'] != '' || $_POST ['newtmima'] != null)) {
		$newtmima = $_POST ['newtmima'];
		$updatetmimacheck = true;
	}
	
	if ($newtmima != '' && $newtmima != null) {
		if ($newtmima == "allstu")
			unset ( $_SESSION ['tmima'] );
		else
			$_SESSION ['tmima'] = $newtmima;
		$lastselect = time ();
		
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `tmimata` WHERE `username` = '$parent' AND `tmima`= '$newtmima';";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
		}
		
		$num = mysqli_num_rows ( $result );
		
		if (! $num && $newtmima != 'allstu') { // αν δεν υπάρχει τότε το προσθέτω
			$query = "INSERT INTO `tmimata`(`username`, `tmima`, `lastselect`, `type` ) VALUES ('$parent', '$newtmima', '$lastselect' , '$type'  );";
			$result = mysqli_query ( $link, $query );
			if (! $result) {
				$errorText = mysqli_error ( $link );
			}
			
			$_SESSION ["havechanges"] = true;
		} else {
			if ($_POST ['submitBtn'] == "ΔΙΑΓΡΑΦΗ" && $newtmima != "demo") { // αν έχει σταλεί διαγραφή διαγράφω εκτός του demo
			                                                                 // ενημέρωση μαθητών
				$query = "DELETE FROM `studentstmimata` WHERE `user` = '$parent' AND `tmima`= '$newtmima';";
				
				$result = mysqli_query ( $link, $query );
				;
				if (! $result) {
					$errorText = mysqli_error ( $link );
				}
				
				// διαγραφή τμήματος
				$query = "DELETE FROM `tmimata`  WHERE `username` = '$parent' AND `tmima`= '$newtmima';";
				
				$result = mysqli_query ( $link, $query );
				if (! $result) {
					$errorText = mysqli_error ( $link );
				}
				
				// διαγραφή τμήματος
				$query = "DELETE FROM `parameters`  WHERE `user` = '$parent' AND `tmima`= '$newtmima';";
				
				// echo "6 $query<hr>";
				
				$result = mysqli_query ( $link, $query );
				if (! $result) {
					$errorText = mysqli_error ( $link );
				}
				
				$_SESSION ["havechanges"] = true;
				unset ( $_SESSION ['tmima'] );
			} else {
				if ($updatetmimacheck) {
					$query = "UPDATE `tmimata` SET `lastselect`=$lastselect ,`type`='$type'  WHERE `username` = '$parent' AND `tmima`= '$newtmima';";
					
					$result = mysqli_query ( $link, $query );
					if (! $result) {
						$errorText = mysqli_error ( $link );
					}
					$_SESSION ["havechanges"] = true;
				}
			}
		}
		
		mysqli_close ( $link );
		
		if ($_POST ['submitBtn'] == "ΕΠΙΛΟΓΗ")
			header ( "Location: index.php" );
	}
}

// ενημέρωση των τμημάτων στη SESSION
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';

set_select_tmima ( $parent );


$smarty = new Smarty ();

$extra_style = '
<style type="text/css">
	td {border-style:none; text-align:center; }
	th {border-color:#ddd;border-style:none; border-width:1px; text-align:center; }
	h4 {margin:0px;}
</style>
';

$extra_javascript = '
<script language="javascript" type="text/javascript">
<!--
 function confirm_delete(){

var theone;
var myvalue;
if(document.frm.seltmima.value === undefined){
	for (i=0;i<document.frm.seltmima.length;i++){
		if (document.frm.seltmima[i].checked==true){
		theone=i;
		break //exist for loop, as target acquired.
		}
	}
myvalue = document.frm.seltmima[theone].value ;

}else{
myvalue = document.frm.seltmima.value ;
}

var answer;
	answer =  confirm ("Θέλετε να διαγράψετε το tμήμα " + myvalue + ";\nΘα διαγραφούν μαζί και όλα τα στοιχεία που το αφορούν." )
if (answer == true){
	answer =  confirm ("Η διαδικασία δεν είναι αναστρέψιμη!\nΕίστε όντως σίγουροι για τη διαγραφή;")
}
return answer;

}
 // -->
</script>
';

$smarty->assign ( 'title', 'ΤΜΗΜΑΤΑ' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'extra_javascript', $extra_javascript );
$smarty->assign ( 'body_attributes', '' );
$smarty->assign ( 'h1_title', 'Τμήματα' );

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$query = "SELECT `tmima`,`type` FROM `tmimata` WHERE `username` = '$parent'  ORDER BY `tmima`;";

$result = mysqli_query ( $link, $query );

if (! $result) {
	$errorText = mysqli_error ( $link );
}

$num = mysqli_num_rows ( $result );

$tmimatalist = array ();
if ($num) {
	
	for($x = 0; $x < count ( $apousies_define ); $x ++) {
		$tmimatalist [$x] = "";
	}
	
	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		for($x = 0; $x < count ( $apousies_define ); $x ++) {
			if ($row ["type"] == $apousies_define [$x] ["kod"]) {
				if ($row ["tmima"] == $tmima) {
					$tmimatalist [$x] .= '<INPUT type="radio" name="seltmima" value="' . $row ["tmima"] . '" checked >' . $row ["tmima"] . '<br>';
				} else {
					$tmimatalist [$x] .= '<INPUT type="radio" name="seltmima" value="' . $row ["tmima"] . '" >' . $row ["tmima"] . '<br>';
				}
			}
		}
	}
}
if ($tmimatalist)
	ksort ( $tmimatalist );

$colspan = count ( $apousies_define );
$smarty->assign ( 'tmimata', $apousies_define );
$smarty->assign ( 'tmimatalist', $tmimatalist );
$smarty->assign ( 'colspan', $colspan );

$smarty->display ( 'class.tpl' );
