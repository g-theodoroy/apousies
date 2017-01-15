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
isset ( $_POST ['submitBtn'] ) ? $target = $_POST ['submitBtn'] : $target = 'print';

if ($protokctrl == 2) {
	if (file_exists ( "includes/protocolapousies.inc.php" )) {
		
		// συνδέομαι με τη βάση
		include ("includes/protocolapousies.inc.php");
	}
	if ($protocol_con) {
		// μεταβλητή για αποθήκευση των query μετά
		$pinquery = array ();
		// ΑΝ ΕΠΙΛΕΓΕΙ ΗΛΕΚΤΡΟΝΙΚΟ ΠΡΩΤΟΚΟΛΛΟ ΒΡΙΣΚΩ ΤΟ ΕΠΟΜΕΝ0 Α/Α ΚΑΙ ΑΡΙΘΜΟ ΠΡΩΤΟΚΟΛΛΟΥ
		$protquery = "SELECT `id`, `ari8mosprotokolisis`, `akronimio` FROM `protokolisi` ORDER BY `id` DESC  LIMIT 1  ;";
		$protresult = mysqli_query ( $link, $protquery );
		$row = mysqli_fetch_assoc ( $protresult );
		$newid = ( int ) $row ["id"] + 1;
		$protok = ( int ) $row ["ari8mosprotokolisis"] + 1;
		$akronimio = $row ["akronimio"];
		
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
		$histquery = "INSERT INTO `paperhistory` (`protok`, `mydate`, `am`, `apous`, `user` ) VALUES ('$protok','$histdate','$histam','$totalap','$parent');";
		$histresult = mysqli_query ( $link, $histquery );
		
		// echo "$histquery <hr>";
		
		if (! $histresult) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$_SESSION ["havechanges"] = true;
	}
	
	if ($protokctrl == 2) {
		$protocolyear = date ( "Y" );
		$protocoldate = date ( "Y-m-d" );
		
		$studentsdata [11] == "Α" ? $strprot0 = "ΜΑΘΗΤΗΣ " : $strprot0 = "ΜΑΘΗΤΡΙΑ ";
		$strprot0 .= $tmima . " ΤΑΞΗΣ " . $studentsdata [0] . " " . $studentsdata [1];
		$strkid = $studentsdata [5] . " " . $studentsdata [4];
		
		$protocolquery = "INSERT INTO `protokolisi` 
            (`id`, `ari8mosprotokolisis`, `8ema`, `etos`, `entryby`, `imerominiaparalabis`, `in_ari8mos`,
            `in_toposekdosis`, `in_imerominia`, `in_arxiekdosis`, `in_perilipsi`, `in_paraliptis`, `in_koinopoiisi`,
            `out_apey8inetai`, `out_koinopoisi`, `out_perilipsi`, `out_hmeriminia`, `le3ikleidi`, `diekperaivsi`, 
            `diekperaivsidate`, `fakelos`, `fakelosdate`, `paratiriseis`, `editdate`, `akronimio`, `sxetika`) VALUES 
            ($newid, $protok, 'ΕΞΕΡΧΟΜΕΝΟ', '$protocolyear', $entryby, '$protocoldate', '', '$city', '0000-00-00', '$school', 
            '$strprot0', '$strkid', '', '$strkid', '', 'ΔΕΛΤΙΟ ΕΠΙΚΟΙΝΩΝΙΑΣ ΣΧΟΛΕΙΟΥ - ΓΟΝΕΩΝ', '$protocoldate', '', '$diekperaivsi', 
             '0000-00-00', 'Φ.$tmima', '0000-00-00', '', '0000-00-00', '$akronimio', '') ;
            ";
		
		array_push ( $pinquery, $protocolquery );
	}
	
	if ($protok && $protokctrl > 0) {
		$protok ++;
		if (isset ( $newid ))
			$newid ++;
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

if ($target == 'parents') {
	
	$mail_good = array ();
	$mail_bad = array ();
	
	
	
	$page_top = 25;
	$page_bottom = 25;
	$page_left = 15;
	$page_right = 15;
	$page_format = 'A4';
	$font_size = 10;
	$orientation = 'P';
	
	$mail = new MyPHPMailer ();
	
	$mail->SMTPKeepAlive = true;
	$mail->ConfirmReadingTo = $teacher_email;
	$mail->Subject = "Διαχείριση Απουσιών. Δελτίο επικοινωνίας Σχολείου - Γονέων";
	
	for($i = 0; $i < count ( $pd ); $i ++) {
		
		$email = $pd [$i] ['studentsdata'] [12];
		
		if ($email) { // an exei email
			$pd_one = array ();
			$pd_one [0] = $pd [$i];
			$paper->assign ( 'mypd', $pd_one );
			$html = $paper->fetch ( 'paper_pdf.tpl' );
			
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
			
			$studentsname = $pd [$i] ['studentsdata'] [0] . "-" . $pd [$i] ['studentsdata'] [1];
			$filename = " Απουσίες-$studentsname-" . str_replace ( '/', '-', $nowdate ) . ".pdf";
			$fileContent = $mpdf->Output ( $filename, 'S' );
			$body = "Διαχείριση Απουσιών. \r\n \r\n Σας αποστέλλουμε στο επισυναπτόμενο pdf αρχείο το 'Δελτίο Επικοινωνίας Σχολείου - Γονέων' όπου φαίνονται οι απουσίες που έχει ο/η μαθητής/τρια $studentsname μέχρι σήμερα.\r\n\r\nΠαρακαλούμε για πληρέστερη ενημέρωσή σας να περάσετε από το σχολείο μας.\r\n\r\nΟ υπέυθυνος καθηγητής.";
			
			$mail->Body = $body;
			$mail->AddAddress ( $email );
			$mail->AddStringAttachment ( $fileContent, $filename );
			
			if ($mail->Send ()) {
				$mail_good [] = $studentsname;
			} else {
				$mail_bad [] = $studentsname;
			}
			// Clear all addresses and attachments for next loop
			$mail->ClearAddresses ();
			$mail->ClearAttachments ();
		} // if ($email)
	} // for ($i = 0; $i < count($pd); $i++)
	
	$mail->SmtpClose ();
	
	if (count ( $mail_good ) > 0) {
		$_SESSION ['mail_good'] = $mail_good;
	}
	if (count ( $mail_bad ) > 0) {
		$_SESSION ['mail_bad'] = $mail_bad;
	}
	if (count ( $mail_good ) > 0 || count ( $mail_bad ) > 0) {
		header ( 'Location: paperedit.php?m=1' );
	} else {
		header ( 'Location: paperedit.php' );
	}
} // if ($target == 'parents')

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