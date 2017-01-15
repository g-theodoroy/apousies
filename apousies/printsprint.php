<?php
require_once('common.php');
checkUser();
checktmima();

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user ='';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima ='';

$apous_count = count($apousies_define);
$dik_count = count($dikaiologisi_define);

$apous_fld = array();
foreach ($apousies_define as $key => $value) {
    $apous_fld[$key]['kod'] = 'fldap' . $value['kod'];
    $apous_fld[$key]['label'] =  ucfirst_utf8(mb_strtolower($value['label'],'UTF-8'));
}

$dik_fld = array();
foreach ($dikaiologisi_define as $key => $value) {
    $dik_fld[$key]['kod'] = 'fldd' . $value['kod'];
    $dik_fld[$key]['label'] =  ucfirst_utf8(mb_strtolower($value['label'],'UTF-8'));
}

//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$minquery = "SELECT min(`date`) AS mindate FROM `apousies` 
LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am`  
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
WHERE `apousies`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima';";
$minresult= mysql_query($minquery);
if (!$minresult) {
	$errorText= mysql_error();
	echo "stu: $errorText<hr>";
}

$minyear = date("Y",mysql_result($minresult,0,"mindate"));

//echo mysql_result($minresult,0,"mindate") . " = " . $minyear . "<hr>";



$students = array();



foreach($_POST as $key => $value){
	if(substr($key,0,3)=="stu"){
	$students[] = $value;
	}
}

isset($_POST['total']) ? $total = true :  $total = false;
isset($_POST['st2st']) ? $st2st = true :  $st2st = false;


isset($_POST['periodendAtrim']) ? $periodendAtrim = makemydatestamp($_POST['periodendAtrim']) :  $periodendAtrim = '';
isset($_POST['periodbegBtrim']) ? $periodbegBtrim = makemydatestamp($_POST['periodbegBtrim']) :  $periodbegBtrim = '';
isset($_POST['periodendBtrim']) ? $periodendBtrim = makemydatestamp($_POST['periodendBtrim']) :  $periodendBtrim = '';
isset($_POST['periodbegGtrim']) ? $periodbegGtrim = makemydatestamp($_POST['periodbegGtrim']) :  $periodbegGtrim = '';
isset($_POST['periodendAtetr']) ? $periodendAtetr = makemydatestamp($_POST['periodendAtetr']) :  $periodendAtetr = '';
isset($_POST['periodbegBtetr']) ? $periodbegBtetr = makemydatestamp($_POST['periodbegBtetr']) :  $periodbegBtetr = '';

isset($_POST['st2ststart']) ? $st2ststart = makemydatestamp($_POST['st2ststart']) :  $st2ststart = '';
isset($_POST['st2ststop']) ? $st2ststop = makemydatestamp($_POST['st2ststop']) :  $st2ststop = '';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="el">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<!-- Liquid Blueprint CSS -->
<link rel="stylesheet" href="{$style_prefix}blueprint/reset.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="{$style_prefix}blueprint/liquid.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="{$style_prefix}blueprint/typography.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="{$style_prefix}blueprint/fancy-type.css" type="text/css" media="screen, projection">
<!--[if IE]><link rel="stylesheet" href="../blueprint/lib/ie.css" type="text/css" media="screen, projection"><![endif]-->
	

  <title>ΣΥΝΟΛΟ ΑΠΟΥΣΙΩΝ</title>

<style type="text/css">
	table {width:100%; }
	th,td {text-align:center; vertical-align:middle; border-color:#ddd; border-width:1px; border-style:solid;}
</style>



</head>
<body >
<div class="container">
	<!-- HEADER -->
	<div class="block">
		<div class="column span-24 last">


	<table cellpadding="0" cellspacing="0" align="center" >
<?php
for ($i = 0;$i<count($students);$i++){

$stuquery =  "SELECT `epitheto` ,`onoma`  FROM `students` WHERE `user` = '$parent' AND `am` = '$students[$i]' ";
$sturesult= mysql_query($stuquery);
if (!$sturesult) {
	$errorText= mysql_error();
	echo "stu: $errorText<hr>";
}
	$epitheto = mysql_result($sturesult,0,"epitheto");
	$onoma = mysql_result($sturesult,0,"onoma");
	echo <<<END
	<tr>
	<th >$epitheto<br>$onoma</th>
	<td >Συν</td>
	<td >Γεν<br>παι</td>
	<td >Κατ<br>ευθ</td>
	<td >Επι<br>λογ</td>
	<td >Δικ</td>
	<td >Αδικ</td>
	<td >Κηδ</td>
	<td >Ημ<br>Κηδ</td>
	<td>Γιατ</td>
	<td>Δντη</td>
	<td>Ωρ<br>Αποβ</td>
	<td>Ημ<br>Αποβ</td>
	<td>Συν<br>Αποβ</td>
	<td >1η<br>Ωρα</td>
	<td >Ενδ<br>Ωρα</td>
	<td >Τελ<br>Ωρα</td>
	</tr>
END;

//προυπάρχουσες απουσιεσ

$pre_apousies = get_pre_apousies($parent, $students[$i]);

if($pre_apousies){
$pre_apousies["ap"]==0 ? $pre_ap = "&nbsp;" : $pre_ap = $pre_apousies["ap"];
$pre_apousies["apk"]==0 ? $pre_apk = "&nbsp;" : $pre_apk = $pre_apousies["apk"];
$pre_apousies["ape"]==0 ? $pre_ape = "&nbsp;" : $pre_ape = $pre_apousies["ape"];
$pre_apousies["fh"]==0 ? $pre_fh = "&nbsp;" : $pre_fh = $pre_apousies["fh"];
$pre_apousies["mh"]==0 ? $pre_mh = "&nbsp;" : $pre_mh = $pre_apousies["mh"];
$pre_apousies["lh"]==0 ? $pre_lh = "&nbsp;" : $pre_lh = $pre_apousies["lh"];
$pre_apousies["oa"]==0 ? $pre_oa = "&nbsp;" : $pre_oa = $pre_apousies["oa"];
$pre_apousies["da"]==0 ? $pre_da = "&nbsp;" : $pre_da = $pre_apousies["da"];
$pre_apousies["dp"]==0 ? $pre_dp = "&nbsp;" : $pre_dp = $pre_apousies["dp"];
$pre_apousies["daysp"]==0 ? $pre_daysp = "&nbsp;" : $pre_daysp = $pre_apousies["daysp"];
$pre_apousies["dd"]==0 ? $pre_dd = "&nbsp;" : $pre_dd = $pre_apousies["dd"];
$pre_apousies["dm"]==0 ? $pre_dm = "&nbsp;" : $pre_dm = $pre_apousies["dm"];
$pre_apousies["date"] ?  $pre_date = $pre_apousies["date"] : $pre_date = "";
$pre_totap = $pre_ap + $pre_apk + $pre_ape;
$pre_totdik =$pre_dp + $pre_dd + $pre_dm;
$pre_totadik = $pre_totap - $pre_totdik;
$pre_totoada= $pre_oa + $pre_da;
if ($pre_totdik == 0)$pre_totdik = "&nbsp;";
if ($pre_totadik == 0)$pre_totadik = "&nbsp;";
if ($pre_totoada == 0)$pre_totoada = "&nbsp;";

$total_label_add = "(+ ΠΡΟΥΠΑΡΧΟΥΣΕΣ)";

echo <<<END
	<tr>
	<td >ΠΡΟΥΠΑΡΧΟΥΣΕΣ ΜΕΧΡΙ $pre_date</td>
	<th >$pre_totap</th>
	<th >$pre_ap</th>
	<th >$pre_apk</th>
	<th >$pre_ape</th>
	<th >$pre_totdik</th>
	<th >$pre_totadik</th>
	<th >$pre_dp</th>
	<th >$pre_daysp</th>
	<th>$pre_dd</th>
	<th>$pre_dm</th>
	<th>$pre_oa</th>
	<th>$pre_da</th>
	<th>$pre_totoada</th>
	<th >$pre_fh</th>
	<th >$pre_mh</th>
	<th >$pre_lh</th>
	</tr>
END;
}else{
$total_label_add = "";
}


//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

if ($total){
$totquery =  "SELECT SUM(`ap`) AS sap ,SUM(`apk`) AS sapk, SUM(`ape`) AS sape, SUM(`dk`) AS sdp,SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' ";

$totresult= mysql_query($totquery);
if (!$totresult) {
	$errorText= mysql_error();
	echo "total: $errorText<hr>";
}
$sap = mysql_result($totresult,0,"sap") + $pre_apousies["ap"] ;
$sapk = mysql_result($totresult,0,"sapk") + $pre_apousies["apk"];
$sape = mysql_result($totresult,0,"sape") + $pre_apousies["ape"];
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($totresult,0,"sdp") + $pre_apousies["dp"];
$cdp = mysql_result($totresult,0,"cdp") + $pre_apousies["daysp"];
$sdd = mysql_result($totresult,0,"sdd") + $pre_apousies["dd"];
$sdm = mysql_result($totresult,0,"sdm") + $pre_apousies["dm"];
$sfh = mysql_result($totresult,0,"sfh") + $pre_apousies["fh"];
$smh = mysql_result($totresult,0,"smh") + $pre_apousies["mh"];
$slh = mysql_result($totresult,0,"slh") + $pre_apousies["lh"];
$soa = mysql_result($totresult,0,"soa") + $pre_apousies["oa"];
$sda = mysql_result($totresult,0,"sda") + $pre_apousies["da"];
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >ΟΛΗ ΤΗ ΧΡΟΝΙΑ $total_label_add</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}


if(isset($_POST['tetrA'])){
$query =  "SELECT SUM(`ap`) AS sap ,SUM(`apk`) AS sapk, SUM(`ape`) AS sape, SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`<='$periodendAtetr' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Α ΤΕΤΡΑΜΗΝΟ</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}


if(isset($_POST['tetrB'])){
$query =  "SELECT SUM(`ap`) AS sap ,SUM(`apk`) AS sapk,SUM(`ape`) AS sape, SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$periodbegBtetr' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Β ΤΕΤΡΑΜΗΝΟ</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}


if(isset($_POST['trimA'])){
$query =  "SELECT SUM(`ap`) AS sap ,SUM(`apk`) AS sapk, SUM(`ape`) AS sape, SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`<='$periodendAtrim' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Α ΤΡΙΜΗΝΟ</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}


if(isset($_POST['trimB'])){
$query =  "SELECT  SUM(`ap`) AS sap ,SUM(`apk`) AS sapk, SUM(`ape`) AS sape, SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$periodbegBtrim' AND `date`<='$periodendBtrim' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Β ΤΡΙΜΗΝΟ</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['trimG'])){
$query =  "SELECT SUM(`ap`) AS sap ,SUM(`apk`) AS sapk, SUM(`ape`) AS sape, SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$periodbegGtrim' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Γ ΤΡΙΜΗΝΟ</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['trimAB'])){
$query =  "SELECT  SUM(`ap`) AS sap ,SUM(`apk`) AS sapk, SUM(`ape`) AS sape, SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`<='$periodendBtrim' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Α+Β ΤΡΙΜΗΝΟ</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['trimBG'])){
$query =  "SELECT  SUM(`ap`) AS sap ,SUM(`apk`) AS sapk,SUM(`ape`) AS sape, SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$periodbegBtrim' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Β+Γ ΤΡΙΜΗΝΟ</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['month09'])){
$start = makemydatestamp("1/9");
$stop = makemydatestamp("30/9");
$query =  "SELECT SUM(`ap`) AS sap ,SUM(`apk`) AS sapk,SUM(`ape`) AS sape, SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$start'AND `date`<='$stop' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Σεπτέμβριος</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['month10'])){
$start = makemydatestamp("1/10");
$stop = makemydatestamp("31/10");
$query =  "SELECT SUM(`ap`) AS sap ,SUM(`apk`) AS sapk, SUM(`ape`) AS sape, SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$start'AND `date`<='$stop' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Οκτώβριος</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['month11'])){
$start = makemydatestamp("1/11");
$stop = makemydatestamp("30/11");
$query =  "SELECT  SUM(`ap`) AS sap , SUM(`apk`) AS sapk, SUM(`ape`) AS sape,SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$start'AND `date`<='$stop' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Νοέμβριος</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['month12'])){
$start = makemydatestamp("1/12");
$stop = makemydatestamp("31/12");
$query =  "SELECT  SUM(`ap`) AS sap ,SUM(`apk`) AS sapk, SUM(`ape`) AS sape, SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$start'AND `date`<='$stop' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Δεκέμβριος</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['month01f'])){
$start = makemydatestamp("1/1");
$stop = makemydatestamp("20/1");
$query =  "SELECT SUM(`ap`) AS sap, SUM(`apk`) AS sapk,SUM(`ape`) AS sape ,SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$start'AND `date`<='$stop' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >1-20 Ιανουαρίου</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['month01'])){
$start = makemydatestamp("1/1");
$stop = makemydatestamp("31/1");
$query =  "SELECT  SUM(`ap`) AS sap ,SUM(`apk`) AS sapk,SUM(`ape`) AS sape ,SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$start'AND `date`<='$stop' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Ιανουάριος</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['month01l'])){
$start = makemydatestamp("21/1");
$stop = makemydatestamp("31/1");
$query =  "SELECT  SUM(`ap`) AS sap ,SUM(`apk`) AS sapk,SUM(`ape`) AS sape ,SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$start'AND `date`<='$stop' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >21-31 ιανουαρίου</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['month02'])){
$start = makemydatestamp("1/2");
$stop = makemydatestamp("1/3");
$query =  "SELECT  SUM(`ap`) AS sap ,SUM(`apk`) AS sapk,SUM(`ape`) AS sape ,SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$start'AND `date`<'$stop' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Φεβρουάριος</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['month03'])){
$start = makemydatestamp("1/3");
$stop = makemydatestamp("31/3");
$query =  "SELECT  SUM(`ap`) AS sap ,SUM(`apk`) AS sapk,SUM(`ape`) AS sape ,SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$start'AND `date`<='$stop' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Μάρτιος</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['month04'])){
$start = makemydatestamp("1/4");
$stop = makemydatestamp("30/4");
$query =  "SELECT  SUM(`ap`) AS sap ,SUM(`apk`) AS sapk,SUM(`ape`) AS sape, SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$start'AND `date`<='$stop' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Απρίλιος</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['month05'])){
$start = makemydatestamp("1/5");
$stop = makemydatestamp("31/5");
$query =  "SELECT SUM(`ap`) AS sap ,SUM(`apk`) AS sapk,SUM(`ape`) AS sape ,SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$start'AND `date`<='$stop' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "tetrA : $errorText<hr>";
}
$sap =  mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >Μάιος</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['st2stsum'])){
$start = $st2ststart;
$stop = $st2ststop;
$start2write = $_POST['st2ststart'];
$stop2write = $_POST['st2ststop'];
$query =  "SELECT  SUM(`ap`) AS sap ,SUM(`apk`) AS sapk,SUM(`ape`) AS sape, SUM(`dk`) AS sdp ,SUM(IF(`dk`>0,1,0)) AS cdp ,SUM(`dd`) AS sdd , SUM(`dm`) AS sdm ,SUM(`fh`) AS sfh ,SUM(`mh`) AS smh ,SUM(`lh`) AS slh , SUM(`oa`) AS soa ,SUM(`da`) AS sda   FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$start'AND `date`<='$stop' ";

$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "ST2STSUM : $errorText<hr>";
}
$sap = mysql_result($result,0,"sap");
$sapk = mysql_result($result,0,"sapk");
$sape = mysql_result($result,0,"sape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,0,"sdp");
$cdp = mysql_result($result,0,"cdp");
$sdd = mysql_result($result,0,"sdd");
$sdm = mysql_result($result,0,"sdm");
$sfh = mysql_result($result,0,"sfh");
$smh = mysql_result($result,0,"smh");
$slh = mysql_result($result,0,"slh");
$soa = mysql_result($result,0,"soa");
$sda = mysql_result($result,0,"sda");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';
echo <<<END
	<tr>
	<td >$start2write-$stop2write</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}

if(isset($_POST['st2stdet'])){
$start = $st2ststart;
$stop = $st2ststop;
$start2write = $_POST['st2ststart'];
$stop2write = $_POST['st2ststop'];
$query =  "SELECT  *  FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$students[$i]' AND `date`>='$start'AND `date`<='$stop'  ORDER BY `date` ASC ;" ;

echo "<tr><td  colspan=\"17\" style=\"text-align:left;\">από $start2write εως $stop2write αναλυτικά</td></tr>\n";


$result= mysql_query($query);
if (!$result) {
	$errorText= mysql_error();
	echo "ST2STDET : $errorText<hr>";
}

$num1= mysql_numrows($result);

for ($k = 0;$k<$num1;$k++) {

$date2print = date("j/n/Y" ,mysql_result($result,$k,"date"));
$sap = mysql_result($result,$k,"ap");
$sapk = mysql_result($result,$k,"apk");
$sape = mysql_result($result,$k,"ape");
$sumsumap = $sap + $sapk +$sape;
$sdp = mysql_result($result,$k,"dk");
$sdp > 0 ? $cdp = 1 : $cdp = 0;
$sdd = mysql_result($result,$k,"dd");
$sdm = mysql_result($result,$k,"dm");
$sfh = mysql_result($result,$k,"fh");
$smh = mysql_result($result,$k,"mh");
$slh = mysql_result($result,$k,"lh");
$soa = mysql_result($result,$k,"oa");
$sda = mysql_result($result,$k,"da");
$sdik = $sdp+$sdd+$sdm;
$sadik = $sumsumap-$sdik;
$soada =$soa+$sda;
if($sap==0)$sap='&nbsp;';
if($sapk==0)$sapk='&nbsp;';
if($sape==0)$sape='&nbsp;';
if($sumsumap==0)$sumsumap='&nbsp;';
if($sdp==0)$sdp='&nbsp;';
if($cdp==0)$cdp='&nbsp;';
if($sdd==0)$sdd='&nbsp;';
if($sdm==0)$sdm='&nbsp;';
if($sfh==0)$sfh='&nbsp;';
if($smh==0)$smh='&nbsp;';
if($slh==0)$slh='&nbsp;';
if($soa==0)$soa='&nbsp;';
if($sda==0)$sda='&nbsp;';
if($sdik==0)$sdik='&nbsp;';
if($sadik==0)$sadik='&nbsp;';
if($soada==0)$soada='&nbsp;';

echo <<<END
	<tr>
	<td align="center">$date2print</td>
	<th >$sumsumap</th>
	<th >$sap</th>
	<th >$sapk</th>
	<th >$sape</th>
	<th >$sdik</th>
	<th >$sadik</th>
	<th >$sdp</th>
	<th >$cdp</th>
	<th>$sdd</th>
	<th>$sdm</th>
	<th>$soa</th>
	<th>$sda</th>
	<th>$soada</th>
	<th >$sfh</th>
	<th >$smh</th>
	<th >$slh</th>
	</tr>
END;
}
}



if ($i+1<count($students)){
echo "<tr><td  colspan=\"17\">&nbsp;</td></tr>";
}
}
?>
	</table>
				</div>
			</div>
		</div>
</body>
</html>
