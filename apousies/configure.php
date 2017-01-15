<?php
require_once ('common.php');
checkUser ();
checkParent();

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';

if (isset ( $_POST ['submitBtn'] )) {
	
	$user_apou_def_old = $_POST ['oldapous'];
	$user_dik_def_old = $_POST ['olddik'];
	
	$user_apou_def_new = '';
	$user_dik_def_new = '';
	foreach ( $_POST as $key => $value ) {
		// echo "$key => $value <hr>";
		if (substr ( $key, 0, 4 ) == 'apou')
			$user_apou_def_new .= $value;
		if (substr ( $key, 0, 3 ) == 'dik')
			$user_dik_def_new .= $value;
	}
	
	if ($user_apou_def_new == '')
		$user_apou_def_new = '012';
	if ($user_dik_def_new == '')
		$user_dik_def_new = '012';
	
	$setstr = '';
	$setapou = "`apoucheck` = '$user_apou_def_new' ";
	$setdik = "`dikcheck` = '$user_dik_def_new' ";
	$setstr = "$setapou , $setdik";
	
	if (trim ( $setstr )) {
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		$query = "UPDATE `users` SET $setstr
WHERE `username`='$user'";
		
		$result = mysqli_query ( $link, $query );
		
		mysqli_close ( $link );
	}
	
	$user_apou_def_transform = '';
	
	for($i = 0; $i < count ( $apousies_define_ini ); $i ++) {
		if (! (strpos ( $user_apou_def_old, ( string ) $i ) === false) && ! (strpos ( $user_apou_def_new, ( string ) $i ) === false)) {
			$user_apou_def_transform .= strpos ( $user_apou_def_old, ( string ) $i ); // . strpos($user_apou_def_new, (string) $i);
		} elseif ((strpos ( $user_apou_def_old, ( string ) $i ) === false) && ! (strpos ( $user_apou_def_new, ( string ) $i ) === false)) {
			$user_apou_def_transform .= '+'; // . strpos($user_apou_def_new, (string) $i);
		}
	}
	
	$setstr = 'CONCAT(';
	for($i = 0; $i < strlen ( $user_apou_def_transform ); $i ++) {
		$k = $user_apou_def_transform [$i] + 1;
		if (substr ( $user_apou_def_transform, $i, 1 ) == '+') {
			$setstr .= " '0',";
		} else {
			$setstr .= " MID(`apous`,$k,1),";
		}
	}
	$setstr = substr ( $setstr, 0, - 1 ) . ')';
	
	$query = "UPDATE `apousies` SET `apous` = $setstr  WHERE `user`= '$user'";
	
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	$result = mysqli_query ( $link, $query );
	mysqli_close ( $link );
	
	$setstr = 'CONCAT(';
	for($i = 0; $i < strlen ( $user_apou_def_transform ); $i ++) {
		$k = $user_apou_def_transform [$i] * 3 + 1;
		if (substr ( $user_apou_def_transform, $i, 1 ) == '+') {
			$setstr .= " '000',";
		} else {
			$setstr .= " MID(`apous`,$k,3),";
		}
	}
	$setstr = substr ( $setstr, 0, - 1 ) . ')';
	
	$query = "UPDATE `apousies_pre` SET `apous` = $setstr  WHERE `user`= '$user'";
	
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	$result = mysqli_query ( $link, $query );
	mysqli_close ( $link );
	
	$user_dik_def_transform = '';
	
	for($i = 0; $i < count ( $dikaiologisi_define_ini ); $i ++) {
		if (! (strpos ( $user_dik_def_old, ( string ) $i ) === false) && ! (strpos ( $user_dik_def_new, ( string ) $i ) === false)) {
			$user_dik_def_transform .= strpos ( $user_dik_def_old, ( string ) $i ); // . strpos($user_apou_def_new, (string) $i);
		} elseif ((strpos ( $user_dik_def_old, ( string ) $i ) === false) && ! (strpos ( $user_dik_def_new, ( string ) $i ) === false)) {
			$user_dik_def_transform .= '+'; // . strpos($user_apou_def_new, (string) $i);
		}
	}
	
	$setstr = 'CONCAT(';
	for($i = 0; $i < strlen ( $user_dik_def_transform ); $i ++) {
		$k = $user_dik_def_transform [$i] * 3 + 1;
		if (substr ( $user_dik_def_transform, $i, 1 ) == '+') {
			$setstr .= " '000',";
		} else {
			$setstr .= " MID(`dik`,$k,3),";
		}
	}
	$setstr = substr ( $setstr, 0, - 1 ) . ')';
	
	$query = "UPDATE `apousies_pre` SET `dik` = $setstr  WHERE `user`= '$user'";
	
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	$result = mysqli_query ( $link, $query );
	mysqli_close ( $link );
}
// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

// παίρνω τις επιλογές τμημάτων και δικαιολόγησης του χρήστη
$query = "SELECT `apoucheck`, `dikcheck` FROM `users`    
WHERE `username` = '$user';";
$result = mysqli_query ( $link, $query );
$row = mysqli_fetch_assoc ( $result );
$user_apou_def = $row ["apoucheck"];
$user_dik_def = $row ["dikcheck"];

// ################################################################################
// βρίσκω αν έχει καταχωρημένες απουσίες για κάθε περίπτωση
// ################################################################################
$setstr = '';
for($i = 0; $i < strlen ( $user_apou_def ); $i ++) {
	$k = $i + 1;
	$fld = substr ( $user_apou_def, $i, 1 );
	$setstr .= " SUM(MID(`apous`,$k,1)) AS `$fld`,";
}
$setstr = substr ( $setstr, 0, - 1 );

$query = "SELECT $setstr FROM `apousies`    
WHERE `user` = '$user';";
$result = mysqli_query ( $link, $query );
$row = mysqli_fetch_assoc ( $result );

// τις βαζω σε πινακα για τον έλεγχο
$user_apou_def_check_array = array ();
for($i = 0; $i < strlen ( $user_apou_def ); $i ++) {
	$fld = substr ( $user_apou_def, $i, 1 );
	$user_apou_def_check_array [$fld] = $row [$fld];
}
// ###########################################
// βρίσκω αν έχει καταχωρημένες απουσίες_pre για κάθε περίπτωση
$setstr = '';
for($i = 0; $i < strlen ( $user_apou_def ); $i ++) {
	$k = $i * 3 + 1;
	$fld = substr ( $user_apou_def, $i, 1 );
	$setstr .= " SUM(CONVERT(MID(`apous`,$k,3),UNSIGNED INTEGER)) AS `$fld`,";
}
$setstr = substr ( $setstr, 0, - 1 );

$query = "SELECT $setstr FROM `apousies_pre`    
WHERE `user` = '$user';";

$result = mysqli_query ( $link, $query );
$row = mysqli_fetch_assoc ( $result );

// τις βαζω σε πινακα για τον έλεγχο
for($i = 0; $i < strlen ( $user_apou_def ); $i ++) {
	$fld = substr ( $user_apou_def, $i, 1 );
	// $fld = $dikaiologisi_define[substr($user_dik_def,$i,1)]['kod'];
	$user_apou_def_check_array [$fld] += $row [$fld];
}

// #################################################################################
// βρίσκω αν έχει καταχωρημένες δικαιολογημένες για κάθε περίπτωση
// #################################################################################
$setstr = '';
for($i = 0; $i < strlen ( $user_dik_def ); $i ++) {
	$k = $i + 1;
	$fld = substr ( $user_dik_def, $i, 1 );
	if (isset ( $dikaiologisi_define [substr ( $user_dik_def, $i, 1 )] ['kod'] )) {
		$val = $dikaiologisi_define [substr ( $user_dik_def, $i, 1 )] ['kod'];
		$setstr .= " SUM(IF(`from`= '$val' ,`dik`,0)) AS `$fld`,";
	}
}
$setstr = substr ( $setstr, 0, - 1 );

$query = "SELECT $setstr FROM `apousies`    
WHERE `user` = '$user' AND `from` <> '' ;";

$result = mysqli_query ( $link, $query );
$row = mysqli_fetch_assoc ( $result );

// τις βαζω σε πινακα για τον έλεγχο
$user_dik_def_check_array = array ();
for($i = 0; $i < strlen ( $user_dik_def ); $i ++) {
	$fld = substr ( $user_dik_def, $i, 1 );
	// $fld = $dikaiologisi_define[substr($user_dik_def,$i,1)]['kod'];
	$user_dik_def_check_array [$fld] = $row [$fld];
}

// ###########################################
// βρίσκω αν έχει καταχωρημένες απουσίες_pre για κάθε περίπτωση
$setstr = '';
for($i = 0; $i < strlen ( $user_dik_def ); $i ++) {
	$k = $i * 3 + 1;
	$fld = substr ( $user_dik_def, $i, 1 );
	// $fld = $dikaiologisi_define[substr($user_dik_def,$i,1)]['kod'];
	$setstr .= " SUM(CONVERT(MID(`dik`,$k,3),UNSIGNED INTEGER)) AS `$fld`,";
}
$setstr = substr ( $setstr, 0, - 1 );

$query = "SELECT $setstr FROM `apousies_pre`    
WHERE `user` = '$user';";

$result = mysqli_query ( $link, $query );
$row = mysqli_fetch_assoc ( $result );

// τις βαζω σε πινακα για τον έλεγχο
for($i = 0; $i < strlen ( $user_dik_def ); $i ++) {
	$fld = substr ( $user_dik_def, $i, 1 );
	// $fld = $dikaiologisi_define[substr($user_dik_def,$i,1)]['kod'];
	$user_dik_def_check_array [$fld] += $row [$fld];
}

// ##################################################################################
// #################################################################################

if ($user_apou_def == '')
	$user_apou_def = '012';
if ($user_dik_def == '')
	$user_dik_def = '012';

$apous_count = count ( $apousies_define_ini );
$dik_count = count ( $dikaiologisi_define_ini );

$apoustr = "<tr><td rowspan=$apous_count ><input type='hidden' name='oldapous' value ='$user_apou_def' /><h3>Τμήματα</h3></td>";
for($i = 0; $i < $apous_count; $i ++) {
	! (strpos ( $user_apou_def, ( string ) $i ) === false) ? $checked = 'checked' : $checked = '';
	if (isset ( $user_apou_def_check_array [$i] ) && $user_apou_def_check_array [$i] > 0)
		$checked .= " onclick='alert(\"Έχετε καταχωρημένες απουσίες " . $apousies_define_ini [$i] ['perigrafi'] . ".\\nΔεν μπορείτε να αποεπιλέξετε το κουμπί.\");checked=true'";
	if ($i > 0)
		$apoustr .= "<tr>";
	$apoustr .= "<td><input type=\"checkbox\" name=\"apou$i" . $apousies_define_ini [$i] ['kod'] . "\" value=\"$i\" $checked ></td><td>" . $apousies_define_ini [$i] ['perigrafi'] . "</td></tr>";
}

$dikstr = "<tr><td rowspan=$dik_count ><input type='hidden' name='olddik' value ='$user_dik_def' /><h3>Δικαιολόγηση</h3></td>";
for($i = 0; $i < $dik_count; $i ++) {
	! (strpos ( $user_dik_def, ( string ) $i ) === false) ? $checked = 'checked' : $checked = '';
	if (isset ( $user_dik_def_check_array [$i] ) && $user_dik_def_check_array [$i] > 0)
		$checked .= " onclick='alert(\"Έχετε δικαιολογημένες απουσίες από " . $dikaiologisi_define_ini [$i] ['perigrafi'] . ".\\nΔεν μπορείτε να αποεπιλέξετε το κουμπί.\");checked=true'";
	if ($i > 0)
		$dikstr .= "<tr>";
	$dikstr .= "<td><input type=\"checkbox\" name=\"dik$i" . $dikaiologisi_define_ini [$i] ['kod'] . "\" value=\"$i\" $checked ></td><td>" . $dikaiologisi_define_ini [$i] ['perigrafi'] . "</td></tr>";
}


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

$smarty->assign ( 'title', 'ΡΥΘΜΙΣΕΙΣ' );
$smarty->assign ( 'h1_title', 'Ρυθμίσεις Στηλών' );
$smarty->assign ( 'body_attributes', '' );
$smarty->assign ( 'extra_javascript', '' );
$smarty->assign ( 'extra_style', '' );
$smarty->assign ( 'apoustr', $apoustr );
$smarty->assign ( 'dikstr', $dikstr );

$smarty->display ( 'configure.tpl' );
?>