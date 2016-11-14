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

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$minquery = "SELECT min(`mydate`) AS mindate FROM `apousies` 
LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am`  
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
WHERE `apousies`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima';";
$minresult = mysqli_query ( $link, $minquery );
if (! $minresult) {
	$errorText = mysqli_error ( $link );
	echo "stu: $errorText<hr>";
}

$row = mysqli_fetch_assoc ( $minresult );
$minyear = substr ( $row ["mindate"], 0, 4 );

// echo $row["mindate"] . " = " . $minyear . "<hr>";

mysqli_close ( $link );

$students = array ();
$fieldschk = array ();
$sumchk = array ();

$fieldschk [0] ['fld'] = 'fldtot';
$fieldschk [0] ['chk'] = 0;
$fieldschk [0] ['lab'] = 'ΣΥΝ';
for($i = 0; $i < $apous_count; $i ++) {
	$index = $i + 1;
	$fieldschk [$index] ['fld'] = $apous_fld [$i] ['kod'];
	$fieldschk [$index] ['chk'] = 0;
	$fieldschk [$index] ['lab'] = $apous_fld [$i] ['label'];
}
$index ++;
$fieldschk [$index] ['fld'] = 'fldadik';
$fieldschk [$index] ['chk'] = 0;
$fieldschk [$index] ['lab'] = 'ΑΔΙΚ';
$index ++;
$fieldschk [$index] ['fld'] = 'flddik';
$fieldschk [$index] ['chk'] = 0;
$fieldschk [$index] ['lab'] = 'ΔΙΚ';
$index ++;
$fieldschk [$index] ['fld'] = 'flddaysp';
$fieldschk [$index] ['chk'] = 0;
$fieldschk [$index] ['lab'] = 'ΗΜΕ<br>ΚΗΔ';
for($i = 0; $i < $dik_count; $i ++) {
	$index1 = $i + 1 + $index;
	$fieldschk [$index1] ['fld'] = $dik_fld [$i] ['kod'];
	$fieldschk [$index1] ['chk'] = 0;
	$fieldschk [$index1] ['lab'] = $dik_fld [$i] ['label'];
}
$index1 ++;
$fieldschk [$index1] ['fld'] = 'fldoa';
$fieldschk [$index1] ['chk'] = 0;
$fieldschk [$index1] ['lab'] = 'ΩΡ<br>ΑΠΟΒ';
$index1 ++;
$fieldschk [$index1] ['fld'] = 'fldda';
$fieldschk [$index1] ['chk'] = 0;
$fieldschk [$index1] ['lab'] = 'ΗΜ<br>ΑΠΟΒ';
$index1 ++;
$fieldschk [$index1] ['fld'] = 'fldoada';
$fieldschk [$index1] ['chk'] = 0;
$fieldschk [$index1] ['lab'] = 'ΣΥΝ<br>ΑΠΟΒ';
$index1 ++;
$fieldschk [$index1] ['fld'] = 'fldfh';
$fieldschk [$index1] ['chk'] = 0;
$fieldschk [$index1] ['lab'] = '1ης<br>ΩΡΑΣ';
$index1 ++;
$fieldschk [$index1] ['fld'] = 'fldmh';
$fieldschk [$index1] ['chk'] = 0;
$fieldschk [$index1] ['lab'] = 'ΕΝΔ<br>ΩΡΑΣ';
$index1 ++;
$fieldschk [$index1] ['fld'] = 'fldlh';
$fieldschk [$index1] ['chk'] = 0;
$fieldschk [$index1] ['lab'] = 'ΤΕΛ<br>ΩΡΑΣ';

$x = 0;
foreach ( $_POST as $key => $value ) {
	if (substr ( $key, 0, 3 ) == "stu") {
		$students [] = $value;
	} elseif (substr ( $key, 0, 3 ) == "fld") {
		for($k = 0; $k < count ( $fieldschk ); $k ++) {
			if ($fieldschk [$k] ['fld'] == $value) {
				$fieldschk [$k] ['chk'] = 1;
				break;
			}
		}
	}
	// echo "$key = $value" . "<hr>";
}

$x = 0;
isset ( $_POST ['total'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['tetrA'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['tetrB'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['trimA'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['trimB'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['trimG'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['trimAB'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['trimBG'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['month09'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['month10'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['month11'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['month12'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['month01f'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['month01'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['month01l'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['month02'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['month03'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['month04'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['month05'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['st2stsum'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;
isset ( $_POST ['st2stdet'] ) ? $sumchk [$x] = 1 : $sumchk [$x] = 0;
$x ++;

isset ( $_POST ['periodendAtrim'] ) ? $periodendAtrim = makemydatestamp ( $_POST ['periodendAtrim'] ) : $periodendAtrim = '';
isset ( $_POST ['periodbegBtrim'] ) ? $periodbegBtrim = makemydatestamp ( $_POST ['periodbegBtrim'] ) : $periodbegBtrim = '';
isset ( $_POST ['periodendBtrim'] ) ? $periodendBtrim = makemydatestamp ( $_POST ['periodendBtrim'] ) : $periodendBtrim = '';
isset ( $_POST ['periodbegGtrim'] ) ? $periodbegGtrim = makemydatestamp ( $_POST ['periodbegGtrim'] ) : $periodbegGtrim = '';
isset ( $_POST ['periodendAtetr'] ) ? $periodendAtetr = makemydatestamp ( $_POST ['periodendAtetr'] ) : $periodendAtetr = '';
isset ( $_POST ['periodbegBtetr'] ) ? $periodbegBtetr = makemydatestamp ( $_POST ['periodbegBtetr'] ) : $periodbegBtetr = '';

isset ( $_POST ['st2ststart'] ) ? $st2ststart = makemydatestamp ( $_POST ['st2ststart'] ) : $st2ststart = '';
isset ( $_POST ['st2ststop'] ) ? $st2ststop = makemydatestamp ( $_POST ['st2ststop'] ) : $st2ststop = '';

isset ( $_POST ['submitBtn'] ) ? $target = $_POST ['submitBtn'] : $target = '';
isset ( $_POST ['templateselect'] ) ? $templateselect = $_POST ['templateselect'] : $templateselect = 0;

require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty ();

$extra_style = '
<style type="text/css">
    table {page-break-inside : avoid ;}
    th,td {text-align:center; vertical-align:middle; border-color:#ddd; border-width:1px; border-style:solid;padding:0 0 0 0;}
    .nomargin {margin : 0 0 0 0 ;}
</style>
';

$smarty->assign ( 'title', 'ΑΘΡΟΙΣΜΑΤΑ' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'h1_title', 'Αθροίσματα' );
$smarty->assign ( 'body_attributes', "" );
$smarty->assign ( 'extra_javascript', "" );

$studentsdata = array ();

for($i = 0; $i < count ( $students ); $i ++) {
	$l = $i + 1;
	
	$data = array ();
	
	$data [0] [0] = 'ΟΝΟΜΑΤΕΠΩΝΥΜΟ';
	$data [0] [1] = 'ΧΡΟΝ ΠΕΡΙΟΔΟΣ';
	$col = 2;
	for($y = 0; $y < count ( $fieldschk ); $y ++) {
		if ($fieldschk [$y] ['chk'] == 1) {
			$data [0] [$col] = $fieldschk [$y] ['lab'];
			$col ++;
		}
	}
	
	$studentsdata [0] ['data'] = $data;
	
	$pre_apousies = get_pre_apousies ( $parent, $students [$i] );
	
	if ($pre_apousies) {
		$label = "ΣΥΝΟΛΙΚΑ";
	} else {
		$label = "ΟΛΗ ΤΗ ΧΡΟΝΙΑ";
	}
	
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	
	$query_arr = array ();
	
	$sumstr = '';
	$sumdetstr = '';
	$presumstr = '';
	$presumdetstr = '';
	$t1apoustr = '';
	$t2apoustr = '';
	for($x = 0; $x < $apous_count; $x ++) {
		$kod = $apousies_define [$x] ['kod'];
		$k = $x + 1;
		$y = $x * 3 + 1;
		$sumstr .= "SUM( MID(`apousies`.`apous`,$k,1)) +";
		$sumdetstr .= "SUM( MID(`apousies`.`apous`,$k,1)) as `fldap{$kod}` ,";
		$presumstr .= "CONVERT( MID(`apousies_pre`.`apous`,$y,3), UNSIGNED INTEGER) +";
		$presumdetstr .= "CONVERT( MID(`apousies_pre`.`apous`,$y,3), UNSIGNED INTEGER) as `fldap{$kod}` ,";
		$t1apoustr .= "SUM(`t1`.`fldap{$kod}` ) as `fldap{$kod}` ,";
		$t2apoustr .= "`t2`.`fldap{$kod}`,";
	}
	$sumstr = substr ( $sumstr, 0, - 1 );
	$sumdetstr = substr ( $sumdetstr, 0, - 1 );
	$presumstr = substr ( $presumstr, 0, - 1 );
	$presumdetstr = substr ( $presumdetstr, 0, - 1 );
	$t1apoustr = substr ( $t1apoustr, 0, - 1 );
	$t2apoustr = substr ( $t2apoustr, 0, - 1 );
	
	$sumdikstr = '';
	$sumpredikstr = '';
	$totpredikstr = '';
	$totsumdikstr = '';
	$t2dikstr = '';
	for($x = 0; $x < $dik_count; $x ++) {
		$y = $x * 3 + 1;
		$kod = $dikaiologisi_define [$x] ['kod'];
		$sumdikstr .= " SUM(IF(`from`='$kod',`dik`,0)) as `fldd{$kod}`,";
		$sumpredikstr .= " CONVERT( MID(`apousies_pre`.`dik`,$y,3), UNSIGNED INTEGER) as `fldd{$kod}`,";
		$totpredikstr .= " CONVERT( MID(`apousies_pre`.`dik`,$y,3), UNSIGNED INTEGER) +";
		$totsumdikstr .= "SUM(`fldd{$kod}`) as `fldd{$kod}`,";
		$t2dikstr .= "`t2`.`fldd{$kod}`,";
	}
	$sumdikstr = substr ( $sumdikstr, 0, - 1 );
	$sumpredikstr = substr ( $sumpredikstr, 0, - 1 );
	$totpredikstr = substr ( $totpredikstr, 0, - 1 );
	$totsumdikstr = substr ( $totsumdikstr, 0, - 1 );
	$t2dikstr = substr ( $t2dikstr, 0, - 1 );
	
	$kodparent = $dikaiologisi_define [0] ['kod'];
	$student = $students [$i];
	
	$prequery = "SELECT 
         CONCAT(`students`.`epitheto` , '<br>' ,  `students`.`onoma`) as `name`,
        '#label#' as `label`,
        `t2`.`fldtot`, 
         $t2apoustr ,
        `t2`.`flddik`, 
        `t2`.`fldadik`, 
        `t2`.`flddaysp`, 
         $t2dikstr ,
        `t2`.`fh`, 
        `t2`.`mh`, 
        `t2`.`lh`, 
        `t2`.`oa`, 
        `t2`.`da`, 
        `t2`.`oada`
       
FROM `students`
LEFT JOIN 
(SELECT `user`,
        `apousies_pre`.`student_am`,
        $presumstr as `fldtot`,
        $presumdetstr ,
        $totpredikstr as `flddik`,
        $presumstr - ( $totpredikstr ) as `fldadik`,
        `daysk`as `flddaysp`,
        $sumpredikstr,
        `fh`,
        `mh`,
        `lh`,
        `oa`,       
        `da`,
        `oa`+`da` as `oada`
        
FROM `apousies_pre`
where `apousies_pre`.`user` = '$parent' 
) as `t2`
        
on `students`.`user` = `t2`.`user` AND  `students`.`am` = `t2`.`student_am` 
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
WHERE  `students`.`user` = '$parent'  AND  `studentstmimata`.`tmima` = '$tmima' AND `students`.`am` = '$student' 
";
	
	$tottotquery = "SELECT 
         CONCAT(`students`.`epitheto` , '<br>' ,  `students`.`onoma`) as `name`,
        '$label' as `label`,
        `t2`.`fldtot`, 
         $t2apoustr ,
        `t2`.`flddik`, 
        `t2`.`fldadik`, 
        `t2`.`flddaysp`, 
         $t2dikstr ,
        `t2`.`fldfh`, 
        `t2`.`fldmh`, 
        `t2`.`fldlh`, 
        `t2`.`fldoa`, 
        `t2`.`fldda`, 
        `t2`.`fldoada`
       
FROM `students`
LEFT JOIN 
(SELECT `user`,
        `student_am`, 
        SUM(`t1`.`fldtot`) as `fldtot`,
        $t1apoustr ,
        SUM(`t1`.`flddik`) as `flddik`,
        SUM(`t1`.`fldadik`) as `fldadik`,
        SUM(`t1`.`flddaysp`) as `flddaysp`,
        $totsumdikstr ,
        SUM(`fldfh`) as `fldfh`,
        SUM(`fldmh`) as `fldmh`,
        SUM(`fldlh`) as `fldlh`,
        SUM(`fldoa`) as `fldoa`,
        SUM(`fldda`) as `fldda`,
        SUM(`fldoada`)as `fldoada` 
                
FROM  
(SELECT `user`,
        `apousies`.`student_am`,
        $sumstr as `fldtot`,
        $sumdetstr ,
        SUM(`dik`) as `flddik`, 
        $sumstr - SUM(`dik`) as `fldadik`,
        SUM(IF(`from`='$kodparent',1,0)) as `flddaysp`,
        $sumdikstr ,
        SUM(`fh`) as `fldfh`,
        SUM(`mh`) as `fldmh`,
        SUM(`lh`) as `fldlh`,
        SUM(`oa`) as `fldoa`,
        SUM(`da`) as `fldda`,
        SUM(`oa`)  + SUM(`da`) as `fldoada` 
FROM `apousies`
where `apousies`.`user` = '$parent'  
group by  `apousies`.`student_am`
        
UNION
SELECT `user`,
        `apousies_pre`.`student_am`,
        $presumstr as `fldtot`,
        $presumdetstr ,
        $totpredikstr as `flddik`,
        $presumstr - ( $totpredikstr ) as `fldadik`,
        `daysk`,
        $sumpredikstr,
        `fh`,
        `mh`,
        `lh`,
        `oa`,       
        `da`,
        `oa`+`da` as `oada`
        
FROM `apousies_pre`
where `apousies_pre`.`user` = '$parent' ) as t1
group by `student_am`
) as `t2`
        
on `students`.`user` = `t2`.`user` AND  `students`.`am` = `t2`.`student_am` 
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
WHERE   `students`.`user` = '$parent'  AND  `studentstmimata`.`tmima` = '$tmima' AND `students`.`am` = '$student' 
";
	
	$totquery = "SELECT 
         CONCAT(`students`.`epitheto` , '<br>' ,  `students`.`onoma`) as `name`,
        '#label#' as `label`,
`t2`.`fldtot`,
$t2apoustr ,
`t2`.`flddik`, 
`t2`.`fldadik`, 
`t2`.`flddaysp`, 
$t2dikstr ,
`t2`.`fldfh`, 
`t2`.`fldmh`, 
`t2`.`fldlh`, 
`t2`.`fldoa`, 
`t2`.`fldda`, 
`t2`.`fldoada`

FROM `students` 
LEFT JOIN 

(SELECT 
`user`, 
`apousies`.`student_am`, 
$sumstr as `fldtot`,
$sumdetstr ,
SUM(`dik`) as `flddik`, 
$sumstr - SUM(`dik`) as `fldadik`,
SUM(IF(`from`='$kodparent',1,0)) as `flddaysp`,
$sumdikstr ,
SUM(`fh`) as `fldfh`, SUM(`mh`) as `fldmh`, 
SUM(`lh`) as `fldlh`, SUM(`oa`) as `fldoa`, 
SUM(`da`) as `fldda`, SUM(`oa`) + SUM(`da`) as `fldoada` 

FROM `apousies` 

where `apousies`.`user` = '$parent' #period#
 
group by  `student_am`

) as t2


on `students`.`user` = `t2`.`user` AND `students`.`am` = `t2`.`student_am` 

JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user` and `am` = `studentstmimata`.`student_am` 

WHERE  `students`.`user` = '$parent'  AND `studentstmimata`.`tmima` = '$tmima' AND `students`.`am` = '$student'  
 

";
	
	$query_array [0] = $tottotquery;
	if ($pre_apousies) {
		$query_array [0] .= " UNION " . str_replace ( '#label#', "ΠΡΟΥΠΑΡΧΟΥΣΕΣ", $prequery );
		$dummy0 = str_replace ( '#period#', "", $totquery );
		$query_array [0] .= " UNION " . str_replace ( '#label#', "ΟΛΗ ΤΗ ΧΡΟΝΙΑ", $dummy0 );
	}
	
	$dummy0 = str_replace ( '#period#', "AND `mydate`<='$periodendAtetr'", $totquery );
	$addquery = str_replace ( '#label#', "Α΄ ΤΕΤΡΑΜΗΝΟ", $dummy0 );
	
	$query_array [1] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "AND `mydate`>='$periodbegBtetr'", $totquery );
	$addquery = str_replace ( '#label#', "Β΄ ΤΕΤΡΑΜΗΝΟ", $dummy0 );
	
	$query_array [2] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "AND `mydate`<='$periodendAtrim'", $totquery );
	$addquery = str_replace ( '#label#', "Α΄ ΤΡΙΜΗΝΟ", $dummy0 );
	
	$query_array [3] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "AND `mydate`>='$periodbegBtrim' AND `mydate`<='$periodendBtrim'", $totquery );
	$addquery = str_replace ( '#label#', "Β΄ ΤΡΙΜΗΝΟ", $dummy0 );
	
	$query_array [4] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "AND `mydate`>='$periodbegGtrim'", $totquery );
	$addquery = str_replace ( '#label#', "Γ΄ ΤΡΙΜΗΝΟ", $dummy0 );
	
	$query_array [5] = $addquery;
	
	$dummy0 = str_replace ( '#period#', " AND `mydate`<='$periodendBtrim'", $totquery );
	$addquery = str_replace ( '#label#', "Α+Β΄ ΤΡΙΜΗΝΟ", $dummy0 );
	
	$query_array [6] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "  AND `mydate`>='$periodbegBtrim'", $totquery );
	$addquery = str_replace ( '#label#', "Β+Γ΄ ΤΡΙΜΗΝΟ", $dummy0 );
	
	$query_array [7] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "  AND MID(`mydate`, 5, 2)='09'", $totquery );
	$addquery = str_replace ( '#label#', "Σεπτέμβριος", $dummy0 );
	
	$query_array [8] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "  AND MID(`mydate`, 5, 2)='10'", $totquery );
	$addquery = str_replace ( '#label#', "Οκτώβριος", $dummy0 );
	
	$query_array [9] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "  AND MID(`mydate`, 5, 2)='11'", $totquery );
	$addquery = str_replace ( '#label#', "Νοέμβριος", $dummy0 );
	
	$query_array [10] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "  AND MID(`mydate`, 5, 2)='12'", $totquery );
	$addquery = str_replace ( '#label#', "Δεκέμβριος", $dummy0 );
	
	$query_array [11] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "  AND MID(`mydate`, 5, 2)='01' AND MID(`mydate`, 7, 2)< '21' ", $totquery );
	$addquery = str_replace ( '#label#', "1-20 Ιανουαρίου", $dummy0 );
	
	$query_array [12] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "  AND MID(`mydate`, 5, 2)='01'", $totquery );
	$addquery = str_replace ( '#label#', "Ιανουάριος", $dummy0 );
	
	$query_array [13] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "  AND MID(`mydate`, 5, 2)='01' AND MID(`mydate`, 7, 2)> '20'", $totquery );
	$addquery = str_replace ( '#label#', "21-31 Ιανουαρίου", $dummy0 );
	
	$query_array [14] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "  AND MID(`mydate`, 5, 2)='02'", $totquery );
	$addquery = str_replace ( '#label#', "Φεβρουάριος", $dummy0 );
	
	$query_array [15] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "  AND MID(`mydate`, 5, 2)='03'", $totquery );
	$addquery = str_replace ( '#label#', "Μάρτιος", $dummy0 );
	
	$query_array [16] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "  AND MID(`mydate`, 5, 2)='04'", $totquery );
	$addquery = str_replace ( '#label#', "Απρίλιος", $dummy0 );
	
	$query_array [17] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "  AND MID(`mydate`, 5, 2)='05'", $totquery );
	$addquery = str_replace ( '#label#', "Μάιος", $dummy0 );
	
	$query_array [18] = $addquery;
	
	$dummy0 = str_replace ( '#period#', "AND `mydate`>='$st2ststart' AND `mydate`<='$st2ststop'", $totquery );
	$newlabel = $_POST ['st2ststart'] . " έως " . $_POST ['st2ststop'];
	$addquery = str_replace ( '#label#', $newlabel, $dummy0 );
	
	$query_array [19] = $addquery;
	
	$sumstr = '';
	$sumdetstr = '';
	$t2apoustr = '';
	for($x = 0; $x < $apous_count; $x ++) {
		$kod = $apousies_define [$x] ['kod'];
		$k = $x + 1;
		$y = $x * 3 + 1;
		$sumstr .= "MID(`apousies`.`apous`,$k,1) +";
		$sumdetstr .= "MID(`apousies`.`apous`,$k,1) as `fldap{$kod}` ,";
		$t2apoustr .= "`t2`.`fldap{$kod}`,";
	}
	$sumstr = substr ( $sumstr, 0, - 1 );
	$sumdetstr = substr ( $sumdetstr, 0, - 1 );
	$t2apoustr = substr ( $t2apoustr, 0, - 1 );
	
	$sumdikstr = '';
	$sumpredikstr = '';
	$totpredikstr = '';
	$totsumdikstr = '';
	$t2dikstr = '';
	for($x = 0; $x < $dik_count; $x ++) {
		$y = $x * 3 + 1;
		$kod = $dikaiologisi_define [$x] ['kod'];
		$sumdikstr .= " IF(`from`='$kod',`dik`,0) as `fldd{$kod}`,";
		$totsumdikstr .= "`fldd{$kod}` as `fldd{$kod}`,";
		$t2dikstr .= "`t2`.`fldd{$kod}`,";
	}
	$sumdikstr = substr ( $sumdikstr, 0, - 1 );
	$totsumdikstr = substr ( $totsumdikstr, 0, - 1 );
	$t2dikstr = substr ( $t2dikstr, 0, - 1 );
	
	$detquery = "SELECT 
         CONCAT(`students`.`epitheto` , '<br>' ,  `students`.`onoma`) as `name`,
        `formdate` as `label`,
`t2`.`fldtot`,
$t2apoustr ,
`t2`.`flddik`, 
`t2`.`fldadik`, 
`t2`.`flddaysp`, 
$t2dikstr ,
`t2`.`fldfh`, 
`t2`.`fldmh`, 
`t2`.`fldlh`, 
`t2`.`fldoa`, 
`t2`.`fldda`, 
`t2`.`fldoada`

FROM `students` 
LEFT JOIN 

(SELECT 
`user`, 
DATE_FORMAT(`mydate`,'%e/%c/%Y') as `formdate`,
`apousies`.`student_am`, 
$sumstr as `fldtot`,
$sumdetstr ,
`dik` as `flddik`, 
$sumstr - `dik` as `fldadik`,
IF(`from`='$kodparent',1,0) as `flddaysp`,
$sumdikstr ,
`fh` as `fldfh`,
`mh` as `fldmh`, 
`lh` as `fldlh`,
`oa` as `fldoa`, 
`da` as `fldda`, 
`oa` + `da` as `fldoada` 

FROM `apousies` 

where `apousies`.`user` = '$parent' 

ORDER BY `mydate` ASC
 
) as t2


on `students`.`user` = `t2`.`user` AND `students`.`am` = `t2`.`student_am` 

JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user` and `am` = `studentstmimata`.`student_am` 

WHERE  `students`.`user` = '$parent'  AND  `studentstmimata`.`tmima` = '$tmima' AND `students`.`am` = '$student' 

";
	
	$query_array [20] = $detquery;
	
	// foreach ($query_array as $q){
	// echo "$q<hr>";
	// }
	
	$query = '';
	for($z = 0; $z < count ( $query_array ); $z ++) {
		if ($sumchk [$z] == 1) {
			if ($query == '') {
				$query .= $query_array [$z];
			} else {
				$query .= " UNION " . $query_array [$z];
			}
		}
	}
	
	// echo $query . "<hr>";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
		echo "$errorText<hr>";
	}
	
	$num = mysqli_num_rows ( $result );
	
	// if ($num==0) continue;
	
	$rows = 0;
	$x = 0;
	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		$rows ++;
		
		$data [$x] [0] = $row ['name'];
		$data [$x] [1] = $row ['label'];
		$col = 2;
		for($y = 0; $y < count ( $fieldschk ); $y ++) {
			if ($fieldschk [$y] ['chk'] == 1) {
				$data [$x] [$col] = $row [$fieldschk [$y] ['fld']] > 0 ? $row [$fieldschk [$y] ['fld']] : '&nbsp;';
				$col ++;
			}
		}
		$x ++;
	}
	// print_r($data);
	// echo "<hr>'";
	// print_r($rows);
	// echo "<hr>'";
	
	$studentsdata [$l] ['data'] = $data;
	$studentsdata [$l] ['rows'] = $rows;
	$studentsdata [$l] ['am'] = $student;
} // for ($i = 0; $i < count($students); $i++)
  // print_r($studentsdata);
  // echo "<hr>'";

$smarty->assign ( 'studentsdata', $studentsdata );

if ($target == 'print') {
	$templateselect == 0 ? $template = 'printsprint.tpl' : $template = 'printsprint1.tpl';
	$smarty->display ( $template );
} elseif ($target == 'pdf') {
	$templateselect == 0 ? $template = 'printsprint.tpl' : $template = 'printsprint1.tpl';
	$html = $smarty->fetch ( $template );
	
	require_once ("{$classes_prefix}mpdf/mpdf.php");
	
	$page_top = 10;
	$page_bottom = 10;
	$page_left = 5;
	$page_right = 5;
	$page_format = 'A4';
	$font_size = 8;
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
	
	$filename = "Αθροίσματα_$parent\_$tmima.pdf";
	
	$mpdf->Output ( $filename, 'D' );
	exit ();
} elseif ($target == 'xls') {
	
	/**
	 * PHPExcel
	 */
	require_once ("{$classes_prefix}PHPExcel.php");
	
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel ();
	
	// Set properties
	$objPHPExcel->getProperties ()->setCreator ( "Apousies" )->setLastModifiedBy ( "Apousies" )->setTitle ( "students-$parent.xlsx" )->setSubject ( "" )->setDescription ( "" )->setKeywords ( "" )->setCategory ( "" );
	
	// Add some data
	
	$styleArray = array (
			'font' => array (
					'bold' => true 
			),
			'alignment' => array (
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER 
			),
			'borders' => array (
					'bottom' => array (
							'style' => PHPExcel_Style_Border::BORDER_THIN 
					),
					'right' => array (
							'style' => PHPExcel_Style_Border::BORDER_THIN 
					) 
			),
			'fill' => array (
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array (
							'argb' => 'DDDDDDDD' 
					) 
			) 
	);
	
	// φτιάχνω τα γράμματα για το excell
	$letters = array (
			'A',
			'B',
			'C',
			'D',
			'E',
			'F',
			'G',
			'H',
			'I',
			'J',
			'K',
			'L',
			'M',
			'N',
			'O',
			'P',
			'Q',
			'R',
			'S' 
	);
	
	$columns = count ( $studentsdata [0] ['data'] [0] );
	$letter = $letters [$columns];
	$objPHPExcel->getActiveSheet ()->getStyle ( "A1:{$letter}1" )->applyFromArray ( $styleArray );
	
	for($i = 0; $i < $columns; $i ++) {
		$objPHPExcel->getActiveSheet ()->getColumnDimension ( $letters [$i] )->setAutoSize ( true );
	}
	
	for($i = 0; $i < $columns; $i ++) {
		$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 0, 1 )->setValue ( 'ΑΜ' );
		$mydata = str_replace ( '<br>', ' ', $studentsdata [0] ['data'] [0] [$i] );
		$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( $i + 1, 1 )->setValue ( $mydata );
	}
	
	$num = count ( $studentsdata );
	
	$lastrow = 1;
	
	for($i = 1; $i < $num; $i ++) {
		$rows = $studentsdata [$i] ['rows'];
		for($k = 0; $k < $columns; $k ++) {
			for($y = 0; $y < $rows; $y ++) {
				$mystudent = $studentsdata [$i] ['am'];
				$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 0, $lastrow + $y + 1 )->setValue ( $mystudent );
				$dummy = str_replace ( '&nbsp;', ' ', $studentsdata [$i] ['data'] [$y] [$k] );
				$mydata = str_replace ( '<br>', ' ', $dummy );
				$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( $k + 1, $lastrow + $y + 1 )->setValue ( $mydata );
			}
		}
		$lastrow += $rows;
	}
	// Rename sheet
	$objPHPExcel->getActiveSheet ()->setTitle ( "apousies" );
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex ( 0 );
	
	// excel 2000
	// Redirect output to a client’s web browser (Excel5)
	header ( 'Content-Type: application/vnd.ms-excel' );
	header ( "Content-Disposition: attachment;filename=\"apousies-$parent-$tmima.xls\"" );
	header ( 'Cache-Control: max-age=0' );
	
	$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
	$objWriter->save ( 'php://output' );
	exit ();
} else {
	$smarty->display ( 'printsview.tpl' );
}
?>
