<?php
require_once ('common.php');
checkUser ();
checktmima ();

$apous_count = count ( $apousies_define );

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';

if (isset ( $_SESSION ['mail_good'] )) {
	$mail_good = $_SESSION ['mail_good'];
	unset ( $_SESSION ['mail_good'] );
}
if (isset ( $_SESSION ['mail_bad'] )) {
	$mail_bad = $_SESSION ['mail_bad'];
	unset ( $_SESSION ['mail_bad'] );
}
require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty ();

$extra_style = '
<style type="text/css">
    td, th {border:none;text-align:center;}
    table{text-align:center; vertical-align:middle;padding: 10px;}
    .nomargin{ margin:0px;padding:0px;}
</style>
';

$extra_javascript = '
<script language="javascript" type="text/javascript">

    // <!--
    function select_control_apover ()
    {
        if (document.frm.student.value == "all" )
        {
            document.frm.apover.style.visibility = \'visible\'

        }else
        {
            document.frm.apover.style.visibility = \'hidden\'
        }
    }

    function validate_form(form)
    {
        if (form.student.value == "" && form.action != "")
        {
            form.student.focus();
            alert ("Επιλέξτε μαθητή")
            return false;
        }
        if (form.student.value == "all" && form.apover.value == "")
        {
            form.apover.focus();
            alert ("Συμπληρώστε αριθμό απουσιών")
            return false;
        }
        if (form.protokctrl.value == 2){
            return confirm ("Επιλέξατε αυτόματη πρωτοκόλληση των ειδοποιητηρίων στο ΗΛ. ΠΡΩΤΟΚΟΛΛΟ. θέλετε να συνεχίσετε;")
        }
    }

function check_mail_to_parents(){
    return confirm ("Επιλέξατε να αποσταλεί Ειδοποιητήριο Απουσιών με e-mail σε κηδεμόνες μαθητών που έχουν καταχωρημένο e-mail. θέλετε να συνεχίσετε;")
}
    // -->
</script>
    
';

// ελεγχος αν υπάρχει η βάση δεδομένων του πρωτοκολλου
$protocol_con = false;
if (file_exists ( "includes/protocolapousies.inc.php" )) {
	// συνδέομαι με τη βάση
	include ("includes/protocolapousies.inc.php");
	mysqli_close ( $link );
}
$smarty->assign ( 'prot_con', $protocol_con );

$smarty->assign ( 'title', 'ΕΚΤΥΠΩΣΗ ΕΙΔΟΠΟΙΗΤΗΡΙΩΝ' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'extra_javascript', $extra_javascript );
$smarty->assign ( 'body_attributes', '' );
$smarty->assign ( 'h1_title', 'Εκτύπωση ειδοποιητηρίων' );
if (isset($mail_good)) {
	$smarty->assign ( 'mail_good', $mail_good );
}
if (isset($mail_bad)) {
	$smarty->assign ( 'mail_bad', $mail_bad );
}

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

// $query = "SELECT `students`.`am`, `students`.`epitheto`, `students`.`onoma`, `students`.`patronimo` ,SUM(`apousies`.`ap`)AS sumap, SUM(`apousies`.`apk`)AS sumapk, SUM(`apousies`.`ape`)AS sumape
// FROM `students` left JOIN `apousies` ON (`students`.`user` = `apousies`.`user` AND `students`.`am` = `apousies`.`student_am` )
// WHERE `students`.`user` = '$parent' AND (`students`.`tmima`= '$tmima' OR `students`.`tmima-kat`= '$tmima' OR `students`.`tmima-epi`= '$tmima')
// GROUP BY `students`.`am` ORDER BY `epitheto`, `onoma` ASC; ";

$sumstr = '';
$presumstr = '';
for($x = 0; $x <= $apous_count; $x ++) {
	$k = $x + 1;
	$y = $x * 3 + 1;
	$sumstr .= "SUM( MID(`apousies`.`apous`,$k,1)) +";
	$presumstr .= "CONVERT( MID(`apousies_pre`.`apous`,$y,3), UNSIGNED INTEGER) +";
}
$sumstr = substr ( $sumstr, 0, - 1 ) . "as `sumap`";
$presumstr = substr ( $presumstr, 0, - 1 ) . "as `sumap`";

$query = "SELECT `students`.`am`, `students`.`epitheto`, `students`.`onoma`,`t2`.`sumap` 
FROM `students`
LEFT JOIN 
(SELECT `user`,`student_am`, SUM(`t1`.`sumap`) as `sumap` FROM  
(SELECT `user`, `apousies`.`student_am`,$sumstr  FROM `apousies`
where `apousies`.`user` = '$parent' group by  `apousies`.`student_am`
UNION
SELECT `user`, `apousies_pre`.`student_am`, $presumstr FROM `apousies_pre`
where `apousies_pre`.`user` = '$parent' ) as t1
group by `student_am`) as `t2`
on `students`.`user` = `t2`.`user` AND  `students`.`am` = `t2`.`student_am` 
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
WHERE  `studentstmimata`.`tmima` = '$tmima' 
ORDER BY `epitheto`,`onoma` ASC ;";

$result = mysqli_query ( $link, $query );
$num = mysqli_num_rows ( $result );

mysqli_close ( $link );

$selectdata = array ();

while ( $row = mysqli_fetch_assoc ( $result ) ) {
	$am = $row ["am"];
	$epitheto = $row ["epitheto"];
	$onoma = $row ["onoma"];
	$patronimo = isset ( $row ["patronimo"] ) ? $row ["patronimo"] : '';
	$totalap = $row ["sumap"];
	
	if ($totalap) {
		$selectdata [] = "<option value=\"$am\" >" . str_repeat ( "&nbsp;", 3 - strlen ( $totalap ) ) . "     $totalap    ->     $epitheto $onoma $patronimo</option>\n";
	}
}

$smarty->assign ( 'selectdata', $selectdata );

$orio_paper = getparameter ( 'orio_paper', $parent, $tmima );
$smarty->assign ( 'orio_paper', $orio_paper );

$smarty->display ( 'paperedit.tpl' );
?>
