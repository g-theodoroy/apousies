<?php
if (! file_exists ( 'includes/dbinfo.inc.php' )){
    header('Location: install/index.php');
}
require_once ('common.php');
// ενημέρωση μεταβλητών usercount και tmimacount
$_SESSION ['usercount'] = usercount ();
$_SESSION ['tmimacount'] = tmimacount ();

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';


$smarty = new Smarty ();

$extra_style = '
<style type="text/css">
    td
    {
    border-style:none;margin:0px;padding:0px;padding-right:25px;
    }
    h4.nomargin
    {
    margin:0px;padding:0px;
    }
    h4.r
    {
    text-align:right;
    }
</style>
';

$smarty->assign ( 'title', 'ΔΙΑΧΕΙΡΙΣΗ ΑΠΟΥΣΙΩΝ' );
$smarty->assign ( 'body_attributes', "" );
$smarty->assign ( 'extra_style', $extra_style );

$orio_paper = getparameter ( "orio_paper", $parent, $tmima );

$newpapers = newpapers ( $parent, $tmima, $orio_paper );
$almost_orio_adik = almost_orio_adik ( $parent, $tmima );
$over_orio_adik = over_orio_adik ( $parent, $tmima );
$almost_orio_total = almost_orio_total ( $parent, $tmima );
$over_orio_total = over_orio_total ( $parent, $tmima );
$check_many_apousies = check_many_apousies ( $parent , $tmima);

$smarty->assign ( 'newpapers', $newpapers );
$smarty->assign ( 'almost_orio_adik', $almost_orio_adik );
$smarty->assign ( 'over_orio_adik', $over_orio_adik );
$smarty->assign ( 'almost_orio_total', $almost_orio_total );
$smarty->assign ( 'over_orio_total', $over_orio_total );
$smarty->assign ( 'check_many_apousies', $check_many_apousies );

$extra_javascript = "\n<script language=\"JavaScript\">\n\n";

if ($newpapers > 0) {
	$extra_javascript .= "function newpapers()\n{\n";
	$extra_javascript .= "alert(\"Οι παρακάτω μαθητές:\\n\\n$newpapers\\nσυμπλήρωσαν $orio_paper απουσιες\\nκαι πρέπει να σταλούν ειδοποιητήρια!\");\n";
	$extra_javascript .= "}\n\n";
}

if ($almost_orio_adik > 0) {
	$extra_javascript .= "function almost_orio_adik()\n{\n";
	$extra_javascript .= "alert(\"Οι παρακάτω μαθητές:\\n\\n$almost_orio_adik\\nπλησιάζουν επικίνδυνα το όριο αδικαιολόγητων απουσιών!\");\n";
	$extra_javascript .= "}\n\n";
}

if ($over_orio_adik > 0) {
	$extra_javascript .= "function over_orio_adik()\n{\n";
	$extra_javascript .= "alert(\"Οι παρακάτω μαθητές:\\n\\n$over_orio_adik\\nξεπέρασαν το όριο αδικαιολόγητων απουσιών!\");\n";
	$extra_javascript .= "}\n\n";
}

if ($almost_orio_total > 0) {
	$extra_javascript .= "function almost_orio_total()\n{\n";
	$extra_javascript .= "alert(\"Οι παρακάτω μαθητές:\\n\\n$almost_orio_total\\nπλησιάζουν επικίνδυνα το συνολικό όριο απουσιών!\");\n";
	$extra_javascript .= "}\n\n";
}

if ($over_orio_total > 0) {
	$extra_javascript .= "function over_orio_total(){\n";
	$extra_javascript .= "alert(\"Οι παρακάτω μαθητές:\\n\\n$over_orio_total\\nξεπέρασαν το συνολικό όριο απουσιών!\");\n";
	$extra_javascript .= "}\n\n";
}

if ($check_many_apousies) {
	$extra_javascript .= "function check_many_apousies(){\n";
	$extra_javascript .= "alert('$check_many_apousies')";
	$extra_javascript .= "}\n\n";
}

$extra_javascript .= "</script>\n";

$smarty->assign ( 'extra_javascript', $extra_javascript );

$smarty->assign ( 'h1_title', 'Διαχείριση Απουσιών' );

$parameters_not_set = false;
if (! getparameter ( 'orio_paper', $parent, $tmima ) || ! getparameter ( 'orio_adik', $parent, $tmima ) || ! getparameter ( 'orio_dik', $parent, $tmima )) {
	$parameters_not_set = true;
}
$smarty->assign ( 'parameters_not_set', $parameters_not_set );

$studentscount = studentscount ( $parent, $tmima );
$dayscount = dayscount ( $parent, $tmima );
$sumapousies = sumapousies ( $parent, $tmima );
$sumpapers = sumpapers ( $parent, $tmima );

$smarty->assign ( 'studentscount', $studentscount );
$smarty->assign ( 'dayscount', $dayscount );
$smarty->assign ( 'sumapousies', $sumapousies );
$smarty->assign ( 'sumpapers', $sumpapers );

$smarty->display ( 'index.tpl' );
