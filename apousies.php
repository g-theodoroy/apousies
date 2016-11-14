<?php
require_once ('common.php');
checkUser ();
checktmima ();

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';

// Επειδή έχουμε 7 ώρες διαφορά με Νεα Υόρκη που είναι ο server
// προσθέτω 7*3600 sec στην αποθηκευμένη ώρα
$mysqltimeoffset = 0;

// φτιάχνω πίνακα valluearray με τα στοιχεία που έστειλε η φόρμα ()
$valuearray = array ();
foreach ( $_POST as $key => $value ) {
	if (substr ( $key, 0, 2 ) == "am") {
		$valuearray [] = $value; // am
		
		$newapous = "";
		for($i = 0; $i < count ( $apousies_define ); $i ++) {
			$kod = $apousies_define [$i] ["kod"];
			isset ( $_POST ["ap$kod$value"] ) && trim ( $_POST ["ap$kod$value"] ) != '' ? $newapous .= $_POST ["ap$kod$value"] : $newapous .= "0"; // apousies
		}
		$valuearray [] = $newapous; // am
	}
	if (substr ( $key, 0, 3 ) == "dik" || substr ( $key, 0, 4 ) == "from" || substr ( $key, 0, 2 ) == "oa" || (substr ( $key, 0, 2 ) == "da" && substr ( $key, 0, 4 ) != "days") || substr ( $key, 0, 2 ) == "fh" || substr ( $key, 0, 2 ) == "mh" || substr ( $key, 0, 2 ) == "lh") {
		$valuearray [] = $value;
	}
}

// κρατάω τα δεδομένα που με ενδιαφέρουν
// παίρνω το τμήμα που επιλέχτηκε
// τι να κάνω - new = νέα εγγραφή - replace = αντικατάσταση - delete = διαγραφή
if (isset ( $_POST ["todo"] )) {
	$todo = $_POST ["todo"];
} else {
	$todo = "nothing";
}
// ημέρα
if (isset ( $_POST ["myday"] )) {
	$myday = trim ( $_POST ["myday"] );
} else {
	$myday = date ( "d" );
}
// μήνας
if (isset ( $_POST ["mymonth"] )) {
	$mymonth = trim ( $_POST ["mymonth"] );
} else {
	$mymonth = date ( "m" );
}
// έτος
if (isset ( $_POST ["myyear"] )) {
	$myyear = trim ( $_POST ["myyear"] );
} else {
	$myyear = date ( "Y" );
}

// ημερομηνία που θα αποθηκεύσω
if ($todo == "delete" || $todo == "replace") {
	
	if (isset ( $_POST ["daysadded"] )) {
		$date2save = $_POST ["daysadded"];
		// $date2save = substr($_POST["daysadded"],10);
		$date2del = $date2save;
	} else {
		$date2save = $myyear . $mymonth . $myday;
	} // strftime("%d/%m/%Y", mktime(0,0,0,$mymonth,$myday,$myyear));
} else {
	$date2save = $myyear . $mymonth . $myday; // strftime("%d/%m/%Y", mktime(0,0,0,$mymonth,$myday,$myyear));
}

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

// αν η ενέργεια είναι new
if ($todo == "new") {
	
	begin ();
	$errorcheck = false;
	
	// για κάθε am που έχω πάρει απουσίες τις φτιάχνω ΗΜΝΙΑ|ΑΠΟΥΣΙΕΣ|ΔΙΚ-ΚΗΔ|ΔΙΚ-ΓΙΑ|ΑΠΟΒ
	for($i = 0; $i < count ( $valuearray ); $i += 9) {
		// $valuearray[$i] = am
		$k = $i + 1; // $valuearray[$k] = απουσίες
		$l = $i + 2; // $valuearray[$l] = 1η ώρα
		$m = $i + 3; // $valuearray[$m] = ενδιάμεσες
		$n = $i + 4; // $valuearray[$n] = τελευταία ώρα
		$o = $i + 5; // $valuearray[$o] = ωριαίες αποβολές
		$p = $i + 6; // $valuearray[$p] = ημερήσιες αποβολές
		$q = $i + 7; // $valuearray[$q] = δικαολογημένες
		$r = $i + 8; // $valuearray[$r] = δικαολογημένες από
		
		$am = $valuearray [$i];
		$apous = $valuearray [$k];
		$fh = $valuearray [$l];
		$mh = $valuearray [$m];
		$lh = $valuearray [$n];
		$oa = $valuearray [$o];
		$da = $valuearray [$p];
		$dik = $valuearray [$q];
		$from = $valuearray [$r];
		
		$check_apous_data = "";
		for($x = 0; $x < count ( $apousies_define ); $x ++) {
			$check_apous_data .= "0";
		}
		
		if (trim ( $apous ) != $check_apous_data) {
			
			$query = "INSERT  INTO `apousies` (`mydate` ,`apous`,`dik` ,`from` ,`fh` ,`mh` ,`lh` ,`oa` ,`da` ,`user` ,`student_am`) VALUES ('$date2save','$apous','$dik', '$from','$fh','$mh','$lh', '$oa', '$da', '$parent', '$am');";
			
			$result = mysqli_query ( $link, $query );
			if (! $result)
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

if ($todo == "replace") {
	
	begin ();
	$errorcheck = false;
	
	$query = "DELETE  `apousies`  FROM `apousies` 
    LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am`  
    JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am`  
    where `apousies`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima' AND `mydate` = '$date2del';";
	
	$result = mysqli_query ( $link, $query );
	if (! $result) {
		$errorcheck = true;
	}
	
	// για κάθε am που έχω πάρει απουσίες τις φτιάχνω και τις καταχωρώ
	for($i = 0; $i < count ( $valuearray ); $i += 9) {
		// $valuearray[$i] = am
		$k = $i + 1; // $valuearray[$k] = απουσίες
		$l = $i + 2; // $valuearray[$l] = 1η ώρα
		$m = $i + 3; // $valuearray[$m] = ενδιάμεσες
		$n = $i + 4; // $valuearray[$n] = τελευταία ώρα
		$o = $i + 5; // $valuearray[$o] = ωριαίες αποβολές
		$p = $i + 6; // $valuearray[$p] = ημερήσιες αποβολές
		$q = $i + 7; // $valuearray[$q] = δικαολογημένες
		$r = $i + 8; // $valuearray[$r] = δικαολογημένες από
		
		$am = $valuearray [$i];
		$apous = $valuearray [$k];
		$fh = $valuearray [$l];
		$mh = $valuearray [$m];
		$lh = $valuearray [$n];
		$oa = $valuearray [$o];
		$da = $valuearray [$p];
		$dik = $valuearray [$q];
		$from = $valuearray [$r];
		
		$check_apous_data = "";
		for($x = 0; $x < count ( $apousies_define ); $x ++) {
			$check_apous_data .= "0";
		}
		
		if (trim ( $apous ) != $check_apous_data) {
			
			$query = "INSERT  INTO `apousies` (`mydate` ,`apous`,`dik` ,`from` ,`fh` ,`mh` ,`lh` ,`oa` ,`da` ,`user` ,`student_am`) VALUES ('$date2save','$apous','$dik', '$from','$fh','$mh','$lh', '$oa', '$da', '$parent', '$am');";
			
			$result = mysqli_query ( $link, $query );
			if (! $result)
				$errorcheck = true;
		} // if ((trim($ap)!="" && $ap!=0) || ...
	} // for ($i=0;$i<count($valuearray);$i+=12)
	
	if ($errorcheck == true) {
		rollback ();
	} else {
		commit ();
		$_SESSION ["havechanges"] = true;
	}
} // if ($todo == "replace")
  // αν η εντολή είναι delete
if ($todo == "delete") {
	$query = "DELETE `apousies` FROM `apousies` 
    LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am`  
    JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am` 
    where `apousies`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima' AND `apousies`.`mydate` = '$date2del';";
	$result = mysqli_query ( $link, $query );
	if (! $result) {
		$errorText = mysql_error ();
		echo "4 $errorText<hr>";
	}
}

// ελέγχω αν υπάρχει το νέο τμήμα
$query = "SELECT `am` FROM `students`  JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `student_am`) WHERE `students`.`user` = '$parent' and  `studentstmimata`.`tmima` = '$tmima' ORDER BY `epitheto`,`onoma` ASC ;";

$result0 = mysqli_query ( $link, $query );
if (! $result0) {
	$errorText = mysql_error ();
	echo "8 $errorText<hr>";
}

$num = mysqli_num_rows ( $result0 );

$amarray = array ();

while ( $row = mysqli_fetch_assoc ( $result0 ) ) {
	$amarray [] = $row ["am"];
}

// mysqli_close($link);

require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty ();

$extra_style = '
<style type="text/css">
    input[type=text], input.text 
    {
    margin:0;padding:0;border-color:lightgrey;width:97%;
    }
    table 
    {
    table-layout:fixed;width:100%; 
    }
    th,td 
    {
    text-align: center; vertical-align:middle;padding: 0;overflow:hidden;white-space:nowrap;
    }
    th 
    {
    border-color:#ddd;border-style:solid; border-width:1px;
    }
</style>
';

$extra_javascript = '
<script type="text/javascript" src="js/apousies.js"></script>
<script type="text/javascript">
// <!--
var myStudentsAm=new Array();
';

for($i = 0; $i < count ( $amarray ); $i ++) {
	$extra_javascript .= "myStudentsAm[$i]='$amarray[$i]';\n";
}

$extra_javascript .= '
var pre_date_array = new Array();
';

// $query = "SELECT `student_am`,`mydate` FROM `apousies_pre` LEFT JOIN `students` on `apousies_pre`.`user` = `students`.`user` and `apousies_pre`.`student_am` = `students`.`am` where `apousies_pre`.`user` ='$parent' and (`students`.`tmima` = '$tmima' OR `students`.`tmima-kat` = '$tmima' OR `students`.`tmima-epi` = '$tmima' ) ;";
$query = "SELECT  `apousies_pre`.`student_am`,`mydate` 
FROM `apousies_pre` 
LEFT JOIN `students` on `apousies_pre`.`user` = `students`.`user` and  `apousies_pre`.`student_am` = `students`.`am` 
JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`) 
 where `apousies_pre`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima' ;";
$result = mysqli_query ( $link, $query );

if (! $result) {
	$errorText = mysql_error ();
	echo "5 $errorText<hr>";
}

$num = mysqli_num_rows ( $result );

while ( $row = mysqli_fetch_assoc ( $result ) ) {
	$kod = $row ["student_am"];
	$extra_javascript .= "pre_date_array['$kod'] = '" . substr ( $row ["mydate"], 0, 4 ) . "/" . substr ( $row ["mydate"], 4, 2 ) . "/" . substr ( $row ["mydate"], 6, 2 ) . "'\n";
}

$extra_javascript .= '
var apous_def_array = new Array();
';
for($x = 0; $x < count ( $apousies_define ); $x ++) {
	$kod = $apousies_define [$x] ["kod"];
	$extra_javascript .= "apous_def_array[$x] = 'ap$kod'\n";
}

$extra_javascript .= '
// -->
</script>
';

$smarty->assign ( 'title', 'ΕΙΣΑΓΩΓΗ ΑΠΟΥΣΙΩΝ ΑΝΑ ΗΜΕΡΑ' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'extra_javascript', $extra_javascript );
$smarty->assign ( 'body_attributes', 'onload="showHint();"' );
$smarty->assign ( 'h1_title', 'Καταχώρηση' );
$smarty->assign ( 'myday', $myday );
$smarty->assign ( 'mymonth', $mymonth );
$smarty->assign ( 'myyear', $myyear );

// φορτώνω τα ονόματα και τους am για τη φόρμα
// συνδέομαι με τη βάση
// include ("includes/dbinfo.inc.php");
// ελέγχω αν υπάρχει το νέο τμήμα
$query = "SELECT * FROM `students`  JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `student_am`) WHERE `students`.`user` = '$parent' and  `studentstmimata`.`tmima` = '$tmima' ORDER BY `epitheto`,`onoma` ASC ;";

$result0 = mysqli_query ( $link, $query );

$num = mysqli_num_rows ( $result0 );
$sizenum = $num + $num / 2.2;

mysqli_close ( $link );

$students = array ();

$i = 0;
while ( $row0 = mysqli_fetch_assoc ( $result0 ) ) {
	$epitheto = $row0 ["epitheto"];
	$onoma = $row0 ["onoma"];
	$am = $row0 ["am"];
	$x = $i + 1;
	
	$tmimata_array = gettmimata4student ( $parent, $am );
	$showtmima = '';
	foreach ( $tmimata_array as $key => $value ) {
		if ($value != $tmima) {
			$showtmima .= " $value ,";
		}
	}
	$showtmima = substr ( $showtmima, 0, - 1 );
	
	$students [$i] ['epitheto'] = $epitheto;
	$students [$i] ['onoma'] = $onoma;
	$students [$i] ['am'] = $am;
	$students [$i] ['x'] = $x;
	$students [$i] ['showtmima'] = $showtmima;
	$i ++;
}

$smarty->assign ( 'num', $num );
$smarty->assign ( 'sizenum', $sizenum );
$smarty->assign ( 'students', $students );

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

// γεμίζω τις ημέρες που έχουν καταχωρηθει
// Επειδή έχουμε 7 ώρες διαφορά με Νεα Υόρκη που είναι ο server προσθέτω 7*3600 sec στην αποθηκευμένη ώρα
$query = "SELECT DISTINCT `mydate` from `apousies` 
LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am`  
JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`) 
where `apousies`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima'
order by `mydate` DESC;";

$result = mysqli_query ( $link, $query );

$num2 = mysqli_num_rows ( $result );

$greeknamedays = array (
		"Δευ",
		"Τρι",
		"Τετ",
		"Πεμ",
		"Παρ",
		"Σαβ",
		"Κυρ" 
);
$daysadded = array ();

$x = 0;
while ( $row = mysqli_fetch_assoc ( $result ) ) {
	
	$checkdate = $row ["mydate"];
	$strcheckdate = substr ( $checkdate, 6, 2 ) . "-" . substr ( $checkdate, 4, 2 ) . "-" . substr ( $checkdate, 0, 4 );
	$nameday = $greeknamedays [date ( "N", strtotime ( $strcheckdate ) ) - 1];
	$date2show = date ( "d/m/y", strtotime ( $strcheckdate ) ) . " $nameday";
	
	$daysadded [$x] ['checkdate'] = $checkdate;
	//$daysadded [$x] ['mydate'] = $mydate;
	$daysadded [$x] ['date2show'] = $date2show;
	$x ++;
}

$smarty->assign ( 'daysadded', $daysadded );

// ελεγχος για τον τύπο του τμήματος
$query1 = "SELECT `type` FROM `tmimata` WHERE `username` = '$parent' AND `tmima`= '$tmima'  ;";
$result1 = mysqli_query ( $link, $query1 );
$row = mysqli_fetch_assoc ( $result1 );
$type = $row ["type"];

$state = array ();

for($x = 0; $x < count ( $apousies_define ); $x ++) {
	if ($apousies_define [$x] ["kod"] == $type) {
		$state [$x] = '';
	} else {
		$state [$x] = 'readOnly=true';
	}
}

$smarty->assign ( 'state', $state );

if ($type != 'g') {
	$mark4tmima = '';
	
	$query = "SELECT `tmima` FROM `tmimata` WHERE `username` = '$parent' AND `type`= 'g'  ;";
	$result = mysqli_query ( $link, $query );
	$num = mysqli_num_rows ( $result );
	
	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		$addtmima = $row ["tmima"];
		$mark4tmima .= "&nbsp;<a href='javascript:mark4tmima(\"$addtmima\")'>$addtmima</a>&nbsp;";
	}
	$mark4tmima = $mark4tmima . "<hr>";
	
	$smarty->assign ( 'mark4tmima', $mark4tmima );
}

mysqli_close ( $link );

$smarty->assign ( 'selectfrom', $dikaiologisi_define );
$smarty->assign ( 'apousies_def', $apousies_define );
$apousies_columns = count ( $apousies_define );
$smarty->assign ( 'apousies_columns', $apousies_columns );

$smarty->display ( 'apousies.tpl' );

