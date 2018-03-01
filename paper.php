<?php
require_once ('common.php');
checkUser ();
checktmima ();

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';
isset ( $_POST ['student'] ) ? $student = $_POST ['student'] : $student = '';
isset ( $_POST ['apover'] ) ? $apover = trim ( $_POST ['apover'] ) : $apover = '';
isset ( $_POST ['protok'] ) ? $protok = trim ( $_POST ['protok'] ) : $protok = '';
isset ( $_POST ['protokctrl'] ) ? $protokctrl = trim ( $_POST ['protokctrl'] ) : $protokctrl = 1;
isset ( $_POST ['history'] ) ? $history = trim ( $_POST ['history'] ) : $history = '';
isset ( $_POST ['lastdate'] ) ? $lastdate = makedatestamp ( trim ( $_POST ['lastdate'] ) ) : $lastdate = date ( 'Ymd' );
isset ( $_POST ['paperdate'] ) ? $paperdate = makedatestamp ( trim ( $_POST ['paperdate'] ) ) : $paperdate = date ( 'Ymd' );
isset ( $_POST ['paperdetails'] ) ? $paperdetails = trim ( $_POST ['paperdetails'] ) : $paperdetails = 0;
isset ( $_POST ['submitBtn'] ) ? $target = $_POST ['submitBtn'] : $target = 'print';
isset ( $_POST ['cc_sch'] ) ? $cc_sch = 1 : $cc_sch = 0;
isset ( $_POST ['cc_teacher'] ) ? $cc_teacher = 1 : $cc_teacher = 0;
isset ( $_POST ['smsusername'] ) ? $smsusername = $_POST ['smsusername'] : $smsusername = '';
isset ( $_POST ['smspassword'] ) ? $smspassword = $_POST ['smspassword'] : $smspassword = '';
isset ( $_POST ['paroxos'] ) ? $paroxos = $_POST ['paroxos'] : $paroxos = '';
isset ( $_POST ['smssendto'] ) ? $smssendto = $_POST ['smssendto'] : $smssendto = '';
isset ( $_POST ['smssender'] ) ? $smssender = $_POST ['smssender'] : $smssender = '';
isset ( $_POST ['emailsmsreport'] ) ? $emailsmsreport = $_POST ['emailsmsreport'] : $emailsmsreport = '';


if ($protokctrl == 2) {
	if (file_exists ( "includes/protocolapousies.inc.php" )) {

		// συνδέομαι με τη βάση
		include ("includes/protocolapousies.inc.php");
	}
	if ($protocol_con) {
		// μεταβλητή για αποθήκευση των query μετά
		$pinquery = array ();
		// ΑΝ ΕΠΙΛΕΓΕΙ ΗΛΕΚΤΡΟΝΙΚΟ ΠΡΩΤΟΚΟΛΛΟ ΒΡΙΣΚΩ ΤΟ ΕΠΟΜΕΝ0 Α/Α ΚΑΙ ΑΡΙΘΜΟ ΠΡΩΤΟΚΟΛΛΟΥ
		$yearinusequery = "SELECT  `value` FROM `configs` where `key`='yearInUse' ;";
		$yearinuseresult = mysqli_query ( $link, $yearinusequery );
		$row = mysqli_fetch_assoc ( $yearinuseresult );
		$yearinuse = ( int ) $row ["value"];

		$protquery = "SELECT  `protocolnum` FROM `protocols` where `etos`=$yearinuse ORDER BY `id` DESC  LIMIT 1  ;";
		$protresult = mysqli_query ( $link, $protquery );
		$row = mysqli_fetch_assoc ( $protresult );
		$protok = ( int ) $row ["protocolnum"] + 1;

		mysqli_close ( $link );
	}
}

if ($student != 'new' && $student != 'all') {
	$apover = 0;
}
// echo $apover . '<hr>';


$myuser = $parent;
$mytmima = $tmima;
$orio_paper = getparameter ( 'orio_paper', $parent, $tmima );

$apous_count = count ( $apousies_define );
$dik_count = count ( $dikaiologisi_define );

$dik_kod = array ();
foreach ( $dikaiologisi_define as $key => $value ) {
	$dik_kod [] = 'totald' . $value ['kod'];
}

$lastmonth = array (
		"Σεπτεμβρίου",
		"Οκτωβρίου",
		"Νοεμβρίου",
		"Δεκεμβρίου",
		"Ιανουαρίου",
		"Φεβρουαρίου",
		"Μαρτίου",
		"Απριλίου",
		"Μαΐου",
		"Ιουνίου",
		"Ιουλίου",
		"Αυγούστου"
);
$lastmonthaitiatiki = array (
		"Σεπτέμβριο",
		"Οκτώβριο",
		"Νοέμβριο",
		"Δεκέμβριο",
		"Ιανουάριο",
		"Φεβρουάριο",
		"Μάρτιο",
		"Απρίλιο",
		"Μάιο",
		"Ιούνιο",
		"Ιούλιο",
		"Αύγουστο"
);
$thisday = intval ( substr ( $lastdate, 6, 2 ) );
$thismonth = $monthindex = intval ( substr ( $lastdate, 4, 2 ) );
$thisyear = intval ( substr ( $lastdate, 0, 4 ) );

if ($thisday < 20) {
	$thismonth --;
}
if ($thismonth < 1) {
	$thisyear --;
	$thismonth = 12;
}
$aformidate = $thisyear;
$thismonth < 10 ? $aformidate .= '0' . $thismonth : $aformidate .= $thismonth;
$aformidate .= '01';

$monthindex < 8 ? $lastmonthindex = ($monthindex + 12) % 9 : $lastmonthindex = $monthindex % 9;

if (intval ( substr ( $lastdate, 6, 2 ) ) < 10 && $lastmonthindex > 0)
	$lastmonthindex --;

	// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");
$query = "SELECT `email` FROM `users` WHERE `username` = '$parent' ;";
$result = mysqli_query ( $link, $query );
$row = mysqli_fetch_assoc ( $result );
$sch_email = $row ["email"];
$query = "SELECT `email` FROM `users` join `tmimata`
            on `users`.`username` = `tmimata`.`username`
            WHERE `users`.`username`<>`users`.`groupname` and `groupname`='$parent' and `tmima`='$tmima' ;";
$result = mysqli_query ( $link, $query );
$row = mysqli_fetch_assoc ( $result );
$teacher_email = $row ["email"];
mysqli_close ( $link );

foreach ( $_POST as $key => $value ) {
	$postdata [$key] = $value;
	// echo "$key = $value<hr>";
}

$histdate = $paperdate;

$pd = array ();

include ("includes/dbinfo.inc.php");

$sumstr = '';
$presumstr = '';
for($x = 0; $x < $apous_count; $x ++) {
	$k = $x + 1;
	$y = $x * 3 + 1;
	$sumstr .= "SUM( MID(`apousies`.`apous`,$k,1)) +";
	$presumstr .= "CONVERT( MID(`apousies_pre`.`apous`,$y,3), UNSIGNED INTEGER) +";
}
$sumstr = substr ( $sumstr, 0, - 1 );
$presumstr = substr ( $presumstr, 0, - 1 );

$sumdikstr = '';
$sumpredikstr = '';
$totpredikstr = '';
$totsumdikstr = '';
$t2dikstr = '';
for($x = 0; $x < $dik_count; $x ++) {
	$y = $x * 3 + 1;
	$kod = $dikaiologisi_define [$x] ['kod'];
	$sumdikstr .= " SUM(IF(`from`='$kod',`dik`,0)) as `sumd{$kod}`,";
	$sumpredikstr .= " CONVERT( MID(`apousies_pre`.`dik`,$y,3), UNSIGNED INTEGER) as `sumd{$kod}`,";
	$totpredikstr .= " CONVERT( MID(`apousies_pre`.`dik`,$y,3), UNSIGNED INTEGER) +";
	$totsumdikstr .= "SUM(`sumd{$kod}`) as `sumd{$kod}`,";
	$t2dikstr .= "`t2`.`sumd{$kod}`,";
}
$sumdikstr = substr ( $sumdikstr, 0, - 1 );
$sumpredikstr = substr ( $sumpredikstr, 0, - 1 );
$totpredikstr = substr ( $totpredikstr, 0, - 1 );
$totsumdikstr = substr ( $totsumdikstr, 0, - 1 );
$t2dikstr = substr ( $t2dikstr, 0, - 1 );

$inquery = "SELECT `students`.`user` ,
        `students`.`am` ,
        `students`.`epitheto`,
        `students`.`onoma`,
        `students`.`patronimo`,
        `students`.`ep_kidemona`,
        `students`.`on_kidemona`,
        `students`.`dieythinsi`,
        `students`.`tk`,
        `students`.`poli`,
        `students`.`til1`,
        `students`.`til2`,
        `students`.`email`,
        `students`.`filo`,
        `t2`.`sumap`,
        `t2`.`sumdik`,
        `t2`.`sumadik`,
        `t2`.`sumdaysp`,
         $t2dikstr ,
        `t2`.`sumfh`,
        `t2`.`summh`,
        `t2`.`sumlh`,
        `t2`.`sumoa`,
        `t2`.`sumda`,
        `t2`.`sumoada`,
        `t2`.`aformi`

FROM `students`
JOIN
(SELECT `user`,
        `student_am`,
        SUM(`t1`.`sumap`) as `sumap`,
        SUM(`t1`.`sumdik`) as `sumdik`,
        SUM(`t1`.`sumadik`) as `sumadik`,
        SUM(`t1`.`sumdaysp`) as `sumdaysp`,
        $totsumdikstr ,
        SUM(`sumfh`) as `sumfh`,
        SUM(`summh`) as `summh`,
        SUM(`sumlh`) as `sumlh`,
        SUM(`sumoa`) as `sumoa`,
        SUM(`sumda`) as `sumda`,
        SUM(`sumoada`)as `sumoada` ,
        SUM(`aformi`)as `aformi`

FROM
(SELECT `user`,
        `apousies`.`student_am`,
        $sumstr as `sumap`,
        SUM(`dik`) as `sumdik`,
        $sumstr - SUM(`dik`) as `sumadik`,
        SUM(IF(`from`='P',1,0)) as `sumdaysp`,
        $sumdikstr ,
        SUM(`fh`) as `sumfh`,
        SUM(`mh`) as `summh`,
        SUM(`lh`) as `sumlh`,
        SUM(`oa`) as `sumoa`,
        SUM(`da`) as `sumda`,
        SUM(`oa`)  + SUM(`da`) as `sumoada` ,
        if( (SUM(IF(`mydate`>=$aformidate,`oa`,0)) + SUM(IF(`mydate`>=$aformidate,`da`,0)) )>0,1,0) as `aformi`
FROM `apousies`
where `apousies`.`user` = '$parent' AND `mydate` <= $lastdate
group by  `apousies`.`student_am`

UNION
SELECT `user`,
        `apousies_pre`.`student_am`,
        $presumstr as `sumap`,
        $totpredikstr as `sumdik`,
        $presumstr - ( $totpredikstr ) as `sumadik`,
        `daysk`,
        $sumpredikstr,
        `fh`,
        `mh`,
        `lh`,
        `oa`,
        `da`,
        `oa`+`da` as `oada`,
        '0' as `aformi`

FROM `apousies_pre`
where `apousies_pre`.`user` = '$parent' AND `mydate` <= $lastdate) as t1
group by `student_am`
having `sumap`>$apover) as `t2`

on `students`.`user` = `t2`.`user` AND  `students`.`am` = `t2`.`student_am`
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
WHERE  `studentstmimata`.`tmima` = '$tmima' #1#
ORDER BY `epitheto`,`onoma` ASC ";

$query = str_replace ( '#1#', "AND `students`.`am` = '$student'", $inquery );

if ($apover != '') {

	if ($student == "all" || $student == "new") {

		$query = str_replace ( '#1#', "", $inquery );
		// echo $query . "<hr>\n";
	}
	if ($student == "new") {

		$t3dikstr = str_replace ( "t2", "t3", $t2dikstr );

		$query = "SELECT
        `t3`.`am`,
        `t3`.`epitheto`,
        `t3`.`onoma`,
        `t3`.`patronimo`,
        `t3`.`ep_kidemona`,
        `t3`.`on_kidemona`,
        `t3`.`dieythinsi`,
        `t3`.`tk`,
        `t3`.`poli`,
        `t3`.`til1`,
        `t3`.`til2`,
        `t3`.`email`,
        `t3`.`filo`,
        `t3`.`sumap`,
        `t3`.`sumdik`,
        `t3`.`sumadik`,
        `t3`.`sumdaysp`,
         $t3dikstr ,
        `t3`.`sumfh`,
        `t3`.`summh`,
        `t3`.`sumlh`,
        `t3`.`sumoa`,
        `t3`.`sumda`,
        `t3`.`sumoada`,
        `t3`.`aformi`


FROM `paperhistory` RIGHT JOIN

(
$query )as `t3`

ON `paperhistory`.`user` = `t3`.`user` AND `paperhistory`.`am` = `t3`.`am`
WHERE ISNULL(`paperhistory`.`am`)
ORDER BY `epitheto`,`onoma` ASC;";
	}
}

// echo $query . "<hr>";

$result = mysqli_query ( $link, $query );
if (! $result) {
	$errorText = mysqli_error ( $link );
	echo "all1 $errorText<hr>";
}

mysqli_close ( $link );

$num = mysqli_num_rows ( $result );


if (! $num) header("Location: {$_SERVER['HTTP_REFERER']}");

$i = 0;
while ( $row = mysqli_fetch_assoc ( $result ) ) {

	$studentsdata [0] = $row ["epitheto"];
	$studentsdata [1] = $row ["onoma"];
	$studentsdata [2] = $row ["patronimo"];
	$studentsdata [3] = $row ["am"];
	$studentsdata [4] = $row ["ep_kidemona"];
	$studentsdata [5] = $row ["on_kidemona"];
	$studentsdata [6] = $row ["dieythinsi"];
	$studentsdata [7] = $row ["tk"];
	$studentsdata [8] = $row ["poli"];
	$studentsdata [9] = $row ["til1"];
	$studentsdata [10] = $row ["til2"];
	$studentsdata [11] = $row ["filo"];
	$studentsdata [12] = $row ["email"];

	$txtdata = get_all_parameters ( $parent, $tmima );
	$studentsdata [11] == "Α" ? $keimeno0 = "ο μαθητής " : $keimeno0 = "η μαθήτρια ";
	$studentsdata [11] == "Α" ? $keimeno1 = "του ανωτέρω μαθητή " : $keimeno1 = "της ανωτέρω μαθήτριας ";
	$mydate = substr ( $lastdate, 6, 2 ) . "/" . substr ( $lastdate, 4, 2 ) . "/" . substr ( $lastdate, 0, 4 );
	$nowdate = substr ( $paperdate, 6, 2 ) . "/" . substr ( $paperdate, 4, 2 ) . "/" . substr ( $paperdate, 0, 4 );
	if ($protokctrl == 2) {
		$nowdate = date ( "d/m/Y" );
	}

	$lastmonthindex9 = $lastmonthindex + 9;
	if ($lastmonthindex9 > 12)
		$lastmonthindex9 -= 12;
	$lastmonthindex1 = $lastmonthindex + 1;

	$aformichk = $row ["aformi"];
	$aformichk > 0 ? $aformi = "έδωσε αφορμή" : $aformi = "δεν έδωσε αφορμή";

	$pd [$i] ['studentsdata'] = $studentsdata;
	$pd [$i] ['txtdata'] = $txtdata [$tmima];
	$pd [$i] ['keimeno0'] = $keimeno0;
	$pd [$i] ['keimeno1'] = $keimeno1;
	$pd [$i] ['mydate'] = $mydate;

	$pd [$i] ['totalap'] = $row ["sumap"] > 0 ? $row ["sumap"] : '-';
	$pd [$i] ['totalfulldayadik'] = $row ["sumadik"] - $row ["sumfh"] - $row ["summh"] - $row ["sumlh"] > 0 ? $row ["sumadik"] - $row ["sumfh"] - $row ["summh"] - $row ["sumlh"] : '-';

	foreach ( $dikaiologisi_define as $key => $value ) {
		$kod1 = 'totald' . $value ['kod'];
		$kod2 = 'sumd' . $value ['kod'];
		$pd [$i] [$kod1] = $row [$kod2] > 0 ? $row [$kod2] : '-';
	}
	$pd [$i] ['totaldik'] = $row ["sumdik"] > 0 ? $row ["sumdik"] : '-';
	$pd [$i] ['totaladik'] = $row ["sumadik"] > 0 ? $row ["sumadik"] : '-';

	$pd [$i] ['totalfh'] = $row ["sumfh"] > 0 ? $row ["sumfh"] : '-';
	$pd [$i] ['totalmh'] = $row ["summh"] > 0 ? $row ["summh"] : '-';
	$pd [$i] ['totallh'] = $row ["sumlh"] > 0 ? $row ["sumlh"] : '-';
	$pd [$i] ['totalmemon'] = $row ["sumfh"] + $row ["summh"] + $row ["sumlh"] > 0 ? $row ["sumfh"] + $row ["summh"] + $row ["sumlh"] : '-';

	$pd [$i] ['totaloa'] = $row ["sumoa"] > 0 ? $row ["sumoa"] : '-';
	$pd [$i] ['totalda'] = $row ["sumda"] > 0 ? $row ["sumda"] : '-';
	$pd [$i] ['totaloada'] = $row ["sumoada"] > 0 ? $row ["sumoada"] : '-';

	$pd [$i] ['lastmonthindex9'] = $lastmonthindex9;
	$pd [$i] ['lastmonthindex'] = $lastmonthindex;
	$pd [$i] ['lastmonthindex1'] = $lastmonthindex1;
	$pd [$i] ['lastmonth'] = $lastmonth [$lastmonthindex1];
	$pd [$i] ['lastmonthaitiatiki'] = $lastmonthaitiatiki [$lastmonthindex];

	$pd [$i] ['aformi'] = $aformi;
	$pd [$i] ['protok'] = $protok;
	$pd [$i] ['nowdate'] = $nowdate;

	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");

	if ($history == "1") {

		$totalap = $row ["sumap"];
		$histam = $row ["am"];
		$email = $row ["email"];
		$mobile = $row ["til2"];
		$chk = true;
		
		if ($target == "sendsms"){
            if (! $mobile) $chk = false;
            if ($smssendto == "sms2mob"){
                if ($email) $chk = false;
            }
        }
        if ($target == "parents"){
            if (! $email) $chk = false;
		}
		if ($chk){
            $histquery = "INSERT INTO `paperhistory` (`protok`, `mydate`, `am`, `apous`, `user` ) VALUES ('$protok','$histdate','$histam','$totalap','$parent');";
            $histresult = mysqli_query ( $link, $histquery );
		
            // echo "$histquery <hr>";

            if (! $histresult) {
                $errorText = mysqli_error ( $link );
                echo "1 $errorText<hr>";
            }

            $_SESSION ["havechanges"] = true;
        }
	}

	if ($protokctrl == 2) {
		$protocolyear = date ( "Y" );
		$protocoldate = date ( "Ymd" );

		$studentsdata [11] == "Α" ? $strprot0 = "ΜΑΘΗΤΗΣ " : $strprot0 = "ΜΑΘΗΤΡΙΑ ";
		$strprot0 .= $tmima . " ΤΑΞΗΣ " . $studentsdata [0] . " " . $studentsdata [1];
		$strkid = $studentsdata [5] . " " . $studentsdata [4];

		$protocolquery = "INSERT INTO `protocols`
						(`user_id`, `protocolnum`, `protocoldate`, `etos`, `fakelos`, `thema`, 	    `in_num`, `in_date`,
						`in_topos_ekdosis`, `in_arxi_ekdosis`, `in_paraliptis`, `diekperaiosi`, `in_perilipsi`, `out_date`,
						`diekp_date`,  `sxetiko`, `out_to`,  `out_perilipsi`, 				`keywords`, `paratiriseis`, `flags`) VALUES
						( $entryby, $protok, 	   $protocoldate,  $protocolyear, 'Φ.$tmima', 'ΕΞΕΡΧΟΜΕΝΟ', '',	       NULL,
						'',		    '',		       '',		'$diekperaivsi', '',		$protocoldate,
						$protocoldate, '',	  '$strkid', 'ΔΕΛΤΙΟ ΕΠΙΚΟΙΝΩΝΙΑΣ ΣΧΟΛΕΙΟΥ - ΓΟΝΕΩΝ',   '',	    '$strprot0' ,   NULL )
						";

		array_push ( $pinquery, $protocolquery );
	}

	if ($protok && $protokctrl > 0) {
		$chk = true;
		
		if ($target == "sendsms"){
            if (! $mobile) $chk = false;
            if ($smssendto == "sms2mob"){
                if ($email) $chk = false;
            }
        }
        if ($target == "parents"){
            if (! $email) $chk = false;
		}
		if ($chk){
            $protok ++;
            if (isset ( $newid )) $newid ++;
        }
    }
	$i ++;
}


// ΕΔΏ ΕΙΣΑΓΩ ΤΑ ΔΕΔΟΜΕΝΑ ΣΤΟ ΠΡΩΤΟΚΟΛΛΟ

if ($protokctrl == 2 and $parent != "demo") {
	if (file_exists ( "includes/protocolapousies.inc.php" )) {
		// συνδέομαι με τη βάση
		include ("includes/protocolapousies.inc.php");
	}
	if ($protocol_con) {
		foreach ( $pinquery as $query ) {
			if (trim ( $query ))
				$result = mysqli_query ( $link, $query );
		}
		mysqli_close ( $link );
	}
}


$paper = new Smarty ();
$paper->assign ( 'title', 'ΕΙΔΟΠΟΙΗΤΗΡΙΟ' );
$paper->assign ( 'dik_count', $dik_count );
$paper->assign ( 'dik_kod', $dik_kod );
$paper->assign ( 'paper_dik_define', $paper_dik_define );
$paper->assign ( 'paperdetails', $paperdetails );

if ($target == 'parents') {
	$mail_good = array ();
	$mail_bad = array ();
    $sch_name = '';
    $sch_year = '';
    
	$mail = new MyPHPMailer ();

    $mail->SMTPKeepAlive = true;
    if ($teacher_email){
        $mail->ConfirmReadingTo = $teacher_email;
        $mail->addCustomHeader("Disposition-Notification-To: $teacher_email");
    }else{
        $mail->ConfirmReadingTo = $sch_email;
        $mail->addCustomHeader("Disposition-Notification-To: $sch_email");
    }
	
    if ($cc_sch && $sch_email) $mail->AddCC($sch_email);
    if ($cc_teacher && $teacher_email) $mail->AddCC($teacher_email);

	for($i = 0; $i < count ( $pd ); $i ++) {

		$email = $pd [$i] ['studentsdata'] [12];

		if ($email) { // an exei email
			$pd_one = array ();
			$pd_one [0] = $pd [$i];

            $mail->From = $sch_email;
            $sch_name = $pd_one[0]["txtdata"]["sch_name"];
            $sch_year = $pd_one[0]["txtdata"]["sch_year"];
            $mail->Fromname = $sch_name;
            $mail->Subject = "$sch_name. Δελτίο επικοινωνίας Σχολείου - Γονέων";

			$datetime = date("j/n/Y H:i:s");
			$studentsname = $pd_one[0] ['studentsdata'] [0] . " " . $pd_one[0] ['studentsdata'] [1];
			$parentsname = $pd_one[0] ['studentsdata'] [4] . " " . $pd_one[0] ['studentsdata'] [5];
			$totalap = $pd_one[0]['totalap'];
			$html = file_get_contents( 'templates/paper_email_plain.tpl' );

			$html = str_replace( "###datetime###" ,$datetime  , $html);
			$html = str_replace( "###from###" ,$sch_email  , $html);
			$html = str_replace( "###teacher_email###" ,$teacher_email  , $html);
			$html = str_replace( "###sch_name###" , $sch_name , $html);
			$html = str_replace( "###teach_name###" ,$pd_one[0]["txtdata"]["teach_name"]  , $html);
			$html = str_replace( "###sch_tmima###" ,$pd_one[0]["txtdata"]["sch_tmima"]  , $html);
			$html = str_replace( "###sch_tel###" ,$pd_one[0]["txtdata"]["sch_tel"]  , $html);
			$html = str_replace( "###email###" ,$email  , $html);
			$html = str_replace( "###sch_year###" ,$sch_year  , $html);
			$html = str_replace( "###eponimo###" ,$pd_one[0]["studentsdata"][0]  , $html);
			$html = str_replace( "###onoma###" ,$pd_one[0]["studentsdata"][1]  , $html);
			$html = str_replace( "###sch_class###" ,$pd_one[0]["txtdata"]["sch_class"]  , $html);
			$html = str_replace( "###eponimo_parent###" ,$pd_one[0]["studentsdata"][4]  , $html);
			$html = str_replace( "###onoma_parent###" ,$pd_one[0]["studentsdata"][5]  , $html);
			$html = str_replace( "###dieythinsi###" ,$pd_one[0]["studentsdata"][6]  , $html);
			$html = str_replace( "###tk###" ,$pd_one[0]["studentsdata"][7]  , $html);
			$html = str_replace( "###poli###" ,$pd_one[0]["studentsdata"][8]  , $html);
			$html = str_replace( "###keimeno0###" ,$pd_one[0]["keimeno0"]  , $html);
			$html = str_replace( "###keimeno1###" ,$pd_one[0]["keimeno1"]  , $html);
			$html = str_replace( "###mydate###" ,$pd_one[0]["mydate"]  , $html);
			$html = str_replace( "###totalap###" ,$pd_one[0]['totalap']  , $html);
			$html = str_replace( "###lastmonthindex9###" ,$pd_one[0]["lastmonthindex9"]  , $html);
			$html = str_replace( "###aformi###" ,$pd_one[0]["aformi"]  , $html);
			if ($pd_one[0]["protok"]){
                $html = str_replace( "###protok###" ,"Αρ.Πρωτ: " . $pd_one[0]["protok"]  , $html);
			}else{
                $html = str_replace( "###protok###" ,""  , $html);
			}
			$html = str_replace( "###nowdate###" ,$pd_one[0]["nowdate"]  , $html);
			$html = str_replace( "###teach_arthro###" ,$pd_one[0]["txtdata"]["teach_arthro"]  , $html);
			$html = str_replace( "###teach_last###" ,$pd_one[0]["txtdata"]["teach_last"]  , $html);

			$mail->Body = $html;
			$mail->AddAddress ( $email );

			if ($mail->Send ()) {
				$mail_good [] = array($datetime,$parentsname, $studentsname, $totalap);
			} else {
				$mail_bad [] = array($datetime,$parentsname, $studentsname, $totalap);;
			}
			// Clear all addresses and attachments for next loop
			$mail->ClearAddresses ();
		} // if ($email)
	} // for ($i = 0; $i < count($pd); $i++)

	$mail->SmtpClose ();
	

	if (count ( $mail_good ) > 0 || count ( $mail_bad ) > 0) {
	
	$report = new Smarty();
	$report->assign('sch_name',$sch_name);
	$report->assign('sch_year',$sch_year);
	$report->assign('mail_good',$mail_good);
	$report->assign('mail_bad',$mail_bad);
	
	$html = $report->fetch ( 'email_report_pdf.tpl' );

    if ($emailsmsreport && $sch_email){

    $mailreport = new MyPHPMailer ();

    $mailreport->Subject = "Διαχείριση Απουσιών. Αναφορά αποστολής email σε κηδεμόνες_{$tmima}_" . date("j-n-Y") . "";
    $mailreport->isHTML(true);
    $mailreport->Body = $html;
    $mailreport->AddAddress ( $sch_email );
    if ($teacher_email)$mailreport->AddAddress ( $teacher_email );
    $okmail = $mailreport->Send ();
	}
	
	$page_top = 25;
	$page_bottom = 25;
	$page_left = 15;
	$page_right = 15;
	$page_format = 'A4';
	$font_size = 10;
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

	$filename = "Αναφορά_αποστολής_email_{$parent}_{$tmima}_" . date("j-n-Y") . ".pdf";

	$mpdf->Output ( $filename, 'D' );
    exit ();
	
	}

} // if ($target == 'parents')


if ($target == 'sendsms'){
    
    $smsdata = array();
    $sch_name = '';
    $sch_year = '';
    $sms_good = array();
    $sms_bad = array();
    $balance = '';


	for($i = 0; $i < count ( $pd ); $i ++) {

		$email = $pd [$i] ['studentsdata'] [12];
		$mobile = $pd [$i] ['studentsdata'] [10];


        if (! $mobile)continue;
		if ($smssendto == "sms2mob"){
            if ($email)continue;
		}
		
			$pd_one = array ();
			$pd_one [0] = $pd [$i];

            $sch_name = $pd_one[0]["txtdata"]["sch_name"];
			$studentsname = $pd_one[0] ['studentsdata'] [0] . " " . $pd_one[0] ['studentsdata'] [1];
			$parentsname = $pd_one[0] ['studentsdata'] [4] . " " . $pd_one[0] ['studentsdata'] [5];
			$totalap = $pd_one[0]['totalap'];

			$html = file_get_contents( 'templates/paper_sms.tpl' );

			$html = str_replace( "###sch_name###" , $sch_name , $html);
			$html = str_replace( "###teach_name###" ,$pd_one[0]["txtdata"]["teach_name"]  , $html);
			$html = str_replace( "###sch_tmima###" ,$pd_one[0]["txtdata"]["sch_tmima"]  , $html);
			$html = str_replace( "###eponimo###" ,$pd_one[0]["studentsdata"][0]  , $html);
			$html = str_replace( "###onoma###" ,$pd_one[0]["studentsdata"][1]  , $html);
			$html = str_replace( "###eponimo_parent###" ,$pd_one[0]["studentsdata"][4]  , $html);
			$html = str_replace( "###onoma_parent###" ,$pd_one[0]["studentsdata"][5]  , $html);
			$html = str_replace( "###keimeno0###" ,$pd_one[0]["keimeno0"]  , $html);
			$html = str_replace( "###mydate###" ,$pd_one[0]["mydate"]  , $html);
			$html = str_replace( "###totalap###" ,$pd_one[0]['totalap']  , $html);

			$html = mb_strtoupper($html, 'UTF-8');
            $html = strtr($html, "ΆΈΉΊΪΌΎΫΏ", "ΑΕΗΙΙΟΥΥΩ");
            
            $smsdata[] = array ($mobile, $html, $studentsname, $parentsname, $totalap );
    }
    
    
    if ( ! count($smsdata)) header("Location: {$_SERVER['HTTP_REFERER']}");

    if($paroxos == "sms-marketing.gr"){
        $username = $smsusername; //username of smscanal account
        $password = $smspassword; //password of smscanal account
        $sender_name = $smssender; //the name that will be displayed as sender of the sms
        $sms_good[] = array( "Ημνία", "Μαθητής/τρια", "Κηδεμόνας", "Απουσίες", "mobile-no", "messageid");
        $sms_bad[] = array("Ημνία", "Μαθητής/τρια", "Κηδεμόνας", "Απουσίες", "error-code", "error-description");

        foreach ($smsdata as $smsone){

            $mobiles = "30" . $smsone[0] ; //the mobile number to send the sms, with 30 in front. Can be one or more (up to 500) separated with ,(comma).
            $message = urlencode(stripslashes($smsone[1])); //the message of sms, url encoded.

            //the url to send the request to api
            $URL="http://messaging.smscanal.com/sms/sendsms.jsp?";
            $http_request="user=".$username."&password=".$password."&mobiles=".$mobiles."&senderid=".$sender_name."&sms=".$message;

            $ch = curl_init($URL);   
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $http_request);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');

            $output = curl_exec($ch);
            curl_close($ch);
            
            try{
                $output = simplexml_load_string($output); 
                /*********successful xml response********
                <smslist>
                    <sms>
                        <smsclientid>XXXXXXXX</smsclientid>
                        <messageid>XXXXXXXX</messageid>
                        <mobile-no>+3069XXXXXXXX</mobile-no>
                    </sms>
                </smslist>
                
                *********UNsuccessful xml response********	
                <smslist>
                    <error>
                        <smsclientid>X</smsclientid>
                        <error-code>-XXXXX</error-code>
                        <error-description>XXXXXX</error-description>
                        <mobile-no>XXXXX</mobile-no>
                        <error-action>X</error-action>
                    </error>
                </smslist>*/
            }catch(Exception $e){
                $curlerror = "failed";
            }
            
            $json = json_encode($output);
            $arrsms = json_decode($json,TRUE);
            
            if(isset($arrsms["sms"]) && $curlerror != "failed") {
                foreach ($arrsms as $sms){
                    $datetime = date("j/n/Y H:i:s");
                    $sms_good[] = array($datetime, $smsone[2] , $smsone[3], $smsone[4], $sms["mobile-no"], $sms["messageid"] ) ; 
                }
            }

            
            if(isset($arrsms["error"]) && $curlerror != "failed") {
                foreach ($arrsms as $error){
                    $datetime = date("j/n/Y H:i:s");
                    $sms_bad[] = array($datetime, $smsone[2] , $smsone[3], $smsone[4], $error["error-code"], $error["error-description"] ) ; 
                }
            }
            

        } //foreach ($smsdata as $smsone){
    

        //ερώτηση υπολοίπου
        $URL="http://messaging.smscanal.com/sms/smscredit.jsp?";
        $http_request="user=".$username."&password=".$password;
            
        $ch = curl_init($URL);   
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $http_request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');

        $output = curl_exec($ch);
        curl_close($ch);
     
     
        try{
            $output = simplexml_load_string($output); 
            /*********successful xml response********
            <sms>    
                <accountexpdate>26/02/2019</accountexpdate>
                <balanceexpdate>22/12/2018</balanceexpdate>
                <credit>1.64</credit>
            </sms>	                
            *********UNsuccessful xml response********	
            <sms>
                <error>Invalid username or password</error>
            </sms>*/
        }catch(Exception $e){
            $curlerror = "failed";
        }
     
        $json = json_encode($output);
        $arrcredits = json_decode($json,TRUE);
        $balance = $arrcredits["credit"];
            
    }// if($paroxos == "sms-marketing")
   
    if($paroxos == "easysms.gr"){
        $username = $smsusername; //username of easysms account
        $password = $smspassword; //password of easysms account
        $sender_name = $smssender; //the name that will be displayed as sender of the sms
        $sms_good[] = array( "Ημνία", "Μαθητής/τρια", "Κηδεμόνας", "Απουσίες", "mobile-no", "messageid", "remarks");
        $sms_bad[] = array("Ημνία", "Μαθητής/τρια", "Κηδεμόνας", "Απουσίες", "mobile-no", "error", "remarks");
        
        // βρίσκω το API key
        $URL = "https://easysms.gr/api/key/get?username=" . $username . "&password=" . $password  . "&type=v2";
		$fp = fopen( $URL, 'r' );
		$key = fread( $fp, 1024 );
		
		if ($key){
        foreach ($smsdata as $smsone){

            $to = "30" . $smsone[0] ; //the mobile number to send the sms, with 30 in front.
            $message = urlencode(stripslashes($smsone[1])); //the message of sms, url encoded.

            $URL = "https://easysms.gr/api/sms/send?key=" . $key . "&to=" . $to;
            $URL .= "&text=" . $message  . '&from=' . urlencode($sender_name) . "&type=json";
            $fp = fopen( $URL, 'r' );
            $json = fread( $fp, 1024 );
            
            $arrsms = json_decode($json,TRUE);
            
            
            if(isset($arrsms["status"]) && $arrsms["status"] == "1" ) {
                    $datetime = date("j/n/Y H:i:s");
                    $sms_good[] = array($datetime, $smsone[2] , $smsone[3], $smsone[4], $to , $arrsms["id"], $arrsms["remarks"] ) ; 
            }


            
            if(isset($arrsms["status"]) && $arrsms["status"] !== "1" ) {
                    $datetime = date("j/n/Y H:i:s");
                    $sms_bad[] = array($datetime, $smsone[2] , $smsone[3], $smsone[4], $to , $arrsms["error"], $arrsms["remarks"] ) ; 
            }
            $balance = $arrsms["balance"];
         } 
         }else{
            $datetime = date("j/n/Y H:i:s");
            $sms_bad[] = array($datetime, "" , "" , "", "" , "101", "Error: Check your credentials" ) ;
         }


    } // if($paroxos == "easysms")

    if($paroxos == "sms4u.eu"){
        $username = $smsusername; //username of sms4u account
        $password = $smspassword; //password of sms4u account
        $sender_name = $smssender; //the name that will be displayed as sender of the sms
        $gateway = 2;
        if (!ctype_digit($sender_name)) $gateway = 3;
        $sms_good[] = array( "Ημνία", "Μαθητής/τρια", "Κηδεμόνας", "Απουσίες", "mobile-no", "count", "cost", "messageid");
        $sms_bad[] = array("Ημνία", "Μαθητής/τρια", "Κηδεμόνας", "Απουσίες", "error-code", "error-description");

        $password = urlencode($password);

        foreach ($smsdata as $smsone){

            $mobiles = "30" . $smsone[0] ; //the mobile number to send the sms, with 30 in front
            $message = urlencode(stripslashes($smsone[1])); //the message of sms, url encoded.
            
            //echo $smsone[1] . "<hr>";
            //echo $message . "<hr>";
            
            
            //the url to send the request to api
            $URL="https://members.sms4u.eu/api/sendsms?";
            $URL.="username=".$username."&password=".$password."&sender=".$sender_name."&recipient=".$mobiles."&smsmessage=".$message."&gateway=".$gateway;

             $fp = fopen( $URL, 'r' );
            $json = fread( $fp, 1024 );
            
            $arrsms = json_decode(json_decode($json,TRUE),TRUE);
            
            
            if(isset($arrsms["response"]) && $arrsms["response"] == "OK" ) {
                    $datetime = date("j/n/Y H:i:s");
                    $sms_good[] = array($datetime, $smsone[2] , $smsone[3], $smsone[4], $arrsms["recipient"], $arrsms["count"], $arrsms["cost"], $arrsms["id"] ) ; 
            }


            
            if(isset($arrsms["response"]) && $arrsms["response"] == "error" ) {
                    $datetime = date("j/n/Y H:i:s");
                    $sms_bad[] = array($datetime, $smsone[2] , $smsone[3], $smsone[4], $arrsms["code"], $arrsms["message"] ) ; 
            }

        }
    
        $URL="https://members.sms4u.eu/api/balance?username=".$username."&password=".$password ;
        $fp = fopen( $URL, 'r' );
        $json = fread( $fp, 1024 );
        $arrbalance = json_decode(json_decode($json,TRUE),TRUE);
        $balance = $arrbalance["Balance"];
      
    } // if($paroxos == "sms4u")
   
    // δημιουργία αναφοράς
    if(count($sms_good) > 1 || count($sms_bad) > 1){

	$report = new Smarty();
	$report->assign('sch_name',$sch_name);
	$report->assign('sch_year',$sch_year);
	$report->assign('sms_good',$sms_good);
	$report->assign('sms_bad',$sms_bad);
	$report->assign('balance',$balance);
	$report->assign('paroxos',$paroxos);
	
	$html = $report->fetch ( 'sms_report_pdf.tpl' );

    if ($sch_email){

    $mailreport = new MyPHPMailer ();

    $mailreport->Subject = "Διαχείριση Απουσιών. Αναφορά αποστολής sms σε κηδεμόνες_{$tmima}_" . date("j-n-Y") . "";
    $mailreport->isHTML(true);
    $mailreport->Body = $html;
    $mailreport->AddAddress ( $sch_email );
    if ($teacher_email)$mailreport->AddAddress ( $teacher_email );
    $okmail = $mailreport->Send ();
	}
	
	$page_top = 25;
	$page_bottom = 25;
	$page_left = 15;
	$page_right = 15;
	$page_format = 'A4';
	$font_size = 10;
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

	$filename = "Αναφορά_αποστολής_sms_{$parent}_{$tmima}_" . date("j-n-Y") . ".pdf";

	$mpdf->Output ( $filename, 'D' );
    exit ();
	}
	

} //if ($target == 'sendsms')


if ($target == 'print') {
	$paper->assign ( 'mypd', $pd );
	$paper->display ( 'paper.tpl' );
}

if ($target == 'pdf' || $target == 'email') {
	$paper->assign ( 'mypd', $pd );
	$html = $paper->fetch ( 'paper_pdf.tpl' );



	$page_top = 25;
	$page_bottom = 25;
	$page_left = 15;
	$page_right = 15;
	$page_format = 'A4';
	$font_size = 10;
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

	$filename = "Ειδοποιητήρια_{$parent}_{$tmima}_" . str_replace ( '/', '-', $nowdate ) . ".pdf";

	if ($target == 'email') {
		$fileContent = $mpdf->Output ( $filename, 'S' );

		$mail = new MyPHPMailer ();

		$mail->Subject = "Διαχείριση Απουσιών. Αποστολή Ειδοποιητηρίων";
		$mail->Body = "Στο επισυναπτόμενο αρχείο βρίσκονται σε μορφή pdf τα ειδοποιητήρια σας.";
		$mail->AddAddress ( $teacher_email );
		$mail->AddStringAttachment ( $fileContent, " $filename" );

		$okmail = $mail->Send ();

		if ($okmail) {
			header ( 'Location: paperedit.php?m=1' );
		} else {
			header ( 'Location: paperedit.php?m=0' );
		}
	} else {
		$mpdf->Output ( $filename, 'D' );
		exit ();
	}
}
?>
