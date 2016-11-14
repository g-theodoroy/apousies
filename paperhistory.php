<?php
require_once ('common.php');
checkUser ();
checktmima ();

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';
// παίρνω το τμήμα που επιλέχτηκε
// βρίσκω πόσες εγγραφές έχω για να κάνω
// αντίστοιχες λούπες
isset ( $_POST ["checknum"] ) ? $checknum = trim ( $_POST ["checknum"] ) : $checknum = null;
// βρίσκω τα πατημένα και βάζω σε πίνακα τους am για διαγραφή
for($k = 0; $k < $checknum; $k ++) {
	if (isset ( $_POST ["chk$k"] ) && trim ( $_POST ["chk$k"] ) != "") {
		$pinakas [] = trim ( $_POST ["chk$k"] );
	}
}

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

if (isset($pinakas)){
	for($k = 0; $k < count ( $pinakas ); $k ++) {
		
		$query = "DELETE  FROM `paperhistory` WHERE `user` = '$parent' AND `aa` = '$pinakas[$k]' ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$_SESSION ["havechanges"] = true;
	}
}

if ($parent) {
	// εμφανίζω τα στοιχεία
	$query = "SELECT `aa`, `epitheto`, `onoma`,`protok`,`mydate`,`apous` 
        FROM `paperhistory`  
        JOIN `students` on `students`.`am`= `paperhistory`.`am` AND `students`.`user`= `paperhistory`.`user`  
       JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am`
WHERE `paperhistory`.`user` = '$parent' AND `studentstmimata`.`tmima`= '$tmima' ORDER BY   `mydate`, `students`.`epitheto`, `students`.`onoma` ASC ";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
		echo "1 $errorText<hr>";
	}
	
	$num = mysqli_num_rows ( $result );
}

mysqli_close ( $link );

$data = array ();

$i = 0;
while ( $row = mysqli_fetch_assoc ( $result ) ) {
	
	$data [$i] ['ind'] = $i;
	$data [$i] ['k'] = $i + 1;
	$data [$i] ['aa'] = $row ["aa"];
	$data [$i] ['protokolo'] = $row ["protok"];
	$mydate = $row ["mydate"];
	$formateddate = intval ( substr ( $mydate, 6, 2 ) ) . "/" . intval ( substr ( $mydate, 4, 2 ) ) . "/" . substr ( $mydate, 0, 4 );
	$data [$i] ['date'] = $formateddate;
	$data [$i] ['epitheto'] = $row ["epitheto"];
	$data [$i] ['onoma'] = $row ["onoma"];
	$data [$i] ['apousies'] = $row ["apous"];
	$i ++;
}

require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty ();

$extra_style = '
<style type="text/css">
	th {text-align:center;}
</style>
';

$extra_javascript = '
   <script language="javascript" type="text/javascript">
  // <!--
//έλεγχος της φόρμας
function valid(form) {
var elegxos = false;
var  i = 0;
var d = 0;

//ελέγχω άν υπάρχει έστω και ένα κουμπί πατημένο
for (i=0;i<form.elements.length;i++){ 
if (form.elements[i].type == "checkbox" && form.elements[i].checked){
	   elegxos = true;
	}
}
//αν δεν είναι πατημένο κανένα δίνω οδηγίες
if ( elegxos == false) {
  alert("Για να διαγράψετε κάποια εγγραφή\nπρέπει να επιλέξετε το αντίστοιχο κουτί μπροστά από αυτή");
}

return elegxos;
}

function testdelete(form){
var answer;
var i;
var num = 0;


	for (i=0;i<form.elements.length;i++){ 
		if (form.elements[i].type == "checkbox" && form.elements[i].checked){
			num++;
		}
	}
	//αν είναι πατημένα για διαγραφή ζητάω επιβεβαίωση
	if  (num>0){
		answer =  confirm ("Πρόκειται να διαγράψετε " + num  + " εγγραφές.\nΕίστε σίγουρος;")
		return answer;
	}
}

  // -->
  </script>
';

$smarty->assign ( 'title', 'ΙΣΤΟΡΙΚΟ ΕΙΔΟΠΟΙΗΤΗΡΙΩΝ' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'extra_javascript', $extra_javascript );
$smarty->assign ( 'body_attributes', '' );
$smarty->assign ( 'h1_title', 'Ιστορικό Ειδοποιητηρίων' );
$smarty->assign ( 'num', $num );
$smarty->assign ( 'data', $data );

$smarty->display ( 'paperhistory.tpl' );



