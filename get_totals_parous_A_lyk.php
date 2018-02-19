<?php
require_once ('common.php');
checkUser ();
checktmima ();

$apous_count = count ( $apousies_define );
$dik_count = count ( $dikaiologisi_define );

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';
isset ( $_GET ['am'] ) ? $am = $_GET ['am'] : $am = '';

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$sumtotapstr = '';
$sumapstr = '';

for($i = 0; $i < $apous_count; $i ++) {
	$kod = $apousies_define [$i] ['kod'];
	$k = $i + 1;
	$sumtotapstr .= " sum( MID(`apous`,$k,1))+";
	$sumapstr .= " sum(MID(`apous`,$k,1)) as `sumap$kod`,";
}
$sumtotapstr = substr ( $sumtotapstr, 0, - 1 ) . " as `sumtotap`";
$sumapstr = substr ( $sumapstr, 0, - 1 );

$color_array = array ();
$sumdstr = '';
for($i = 0; $i < $dik_count; $i ++) {
	$kod = $dikaiologisi_define [$i] ['kod'];
	$sumdstr .= " sum(if(`from` = '$kod',`dik`,0)) as `sumd$kod`,";
	
	$color_array [$i] = $dikaiologisi_define [$i] ['color'];
}
$sumdstr = substr ( $sumdstr, 0, - 1 );
$kod_dik_0 = $dikaiologisi_define [0] ['kod'];

$query0 = "SELECT DATE_FORMAT(`mydate`,'%c') as `mymonth` ,$sumtotapstr, $sumapstr , sum(if(`from` = '$kod_dik_0',1,0)) as `daysp`, $sumdstr, sum(`oa`) as `sumoa`, sum(`da`) as `sumda`, sum(`fh`) as `sumfh`, sum(`mh`) as `summh`, sum(`lh`) as `sumlh` 
                FROM `apousies` LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am`  
                JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am`  
                where `apousies`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima' and `apousies`.`student_am`='$am' group by DATE_FORMAT(`mydate`,'%c') order by `mydate` ;";

$result0 = mysqli_query ( $link, $query0 );
if (! $result0) {
	$errorText = mysqli_error ( $link );
	echo "0 $errorText<hr>";
}

$num0 = mysqli_num_rows ( $result0 );

$year = date ( "Y" );
$month = date ( "n" );
if ($month > 7) {
	$year ++;
}

$query1 = "SELECT DATE_FORMAT(`mydate`,'%c') as `mymonth` ,$sumtotapstr, $sumapstr , sum(if(`from` = '$kod_dik_0',1,0)) as `daysp`, $sumdstr , sum(`oa`) as `sumoa`, sum(`da`) as `sumda`, sum(`fh`) as `sumfh`, sum(`mh`) as `summh`, sum(`lh`) as `sumlh` 
                FROM `apousies` LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am`  
                JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am`  
                where `apousies`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima' and `apousies`.`student_am`='$am' and `mydate` > '" . $year . "0101'and `mydate` < '" . $year . "0121' group by DATE_FORMAT(`mydate`,'%c') order by `mydate`;";

$result1 = mysqli_query ( $link, $query1 );
if (! $result1) {
	$errorText = mysqli_error ( $link );
	echo "1 $errorText<hr>";
}

$query2 = "SELECT DATE_FORMAT(`mydate`,'%c') as `mymonth` ,$sumtotapstr, $sumapstr , sum(if(`from` = '$kod_dik_0',1,0)) as `daysp`, $sumdstr , sum(`oa`) as `sumoa`, sum(`da`) as `sumda`, sum(`fh`) as `sumfh`, sum(`mh`) as `summh`, sum(`lh`) as `sumlh` 
                FROM `apousies` LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am`  
                JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am`  
                where `apousies`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima' and `apousies`.`student_am`='$am' and `mydate` > '" . $year . "0120' and `mydate` < '" . $year . "0201' group by DATE_FORMAT(`mydate`,'%c') order by `mydate`;";

$result2 = mysqli_query ( $link, $query2 );
if (! $result2) {
	$errorText = mysqli_error ( $link );
	echo "2 $errorText<hr>";
}

$query3 = "SELECT DATE_FORMAT(`mydate`,'%c') as `mymonth` ,$sumtotapstr, $sumapstr , sum(if(`from` = '$kod_dik_0',1,0)) as `daysp`, $sumdstr, sum(`oa`) as `sumoa`, sum(`da`) as `sumda`, sum(`fh`) as `sumfh`, sum(`mh`) as `summh`, sum(`lh`) as `sumlh` 
                FROM `apousies` LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am`  
                JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am`  
                where `apousies`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima' and `apousies`.`student_am`='$am';";

$result3 = mysqli_query ( $link, $query3 );
if (! $result1) {
	$errorText = mysqli_error ( $link );
	echo "3 $errorText<hr>";
}

$sumarray = array ();
$sumap_array = array ();
$sumd_array = array ();

while ( $row0 = mysqli_fetch_assoc ( $result0 ) ) {
	$themonth = $row0 ["mymonth"];
	$mymonth = intval ( $themonth );
	$sumtotap = $row0 ["sumtotap"];
	for($x = 0; $x < $apous_count; $x ++) {
		$kod = 'sumap' . $apousies_define [$x] ['kod'];
		$sumap_array [$kod] = $row0 [$kod];
	}
	$daysp = $row0 ["daysp"];
	for($x = 0; $x < $dik_count; $x ++) {
		$kod = 'sumd' . $dikaiologisi_define [$x] ['kod'];
		$sumd_array [$x] = $row0 [$kod];
	}
	$sumoa = $row0 ["sumoa"];
	$sumda = $row0 ["sumda"];
	$sumfh = $row0 ["sumfh"];
	$summh = $row0 ["summh"];
	$sumlh = $row0 ["sumlh"];
	
	$sumarray [$mymonth] ["sumtotap"] = $sumtotap;
	for($x = 0; $x < $apous_count; $x ++) {
		$kod = 'sumap' . $apousies_define [$x] ['kod'];
		$sumarray [$mymonth] [$kod] = $sumap_array [$kod];
	}
	$sumarray [$mymonth] ["daysp"] = $daysp;
	for($x = 0; $x < $dik_count; $x ++) {
		$kod = 'sumd' . $dikaiologisi_define [$x] ['kod'];
		$sumarray [$mymonth] [$kod] = $sumd_array [$x];
	}
	$sumarray [$mymonth] ["sumoa"] = $sumoa;
	$sumarray [$mymonth] ["sumda"] = $sumda;
	$sumarray [$mymonth] ["sumfh"] = $sumfh;
	$sumarray [$mymonth] ["summh"] = $summh;
	$sumarray [$mymonth] ["sumlh"] = $sumlh;
}

$row1 = mysqli_fetch_assoc ( $result1 );

$themonth = $row1 ["mymonth"];
$mymonth = intval ( $themonth );
$sumtotap = $row1 ["sumtotap"];
for($x = 0; $x < $apous_count; $x ++) {
	$kod = 'sumap' . $apousies_define [$x] ['kod'];
	$sumap_array [$kod] = $row1 [$kod];
}
$daysp = $row1 ["daysp"];
for($x = 0; $x < $dik_count; $x ++) {
	$kod = 'sumd' . $dikaiologisi_define [$x] ['kod'];
	$sumd_array [$x] = $row1 [$kod];
}
$sumoa = $row1 ["sumoa"];
$sumda = $row1 ["sumda"];
$sumfh = $row1 ["sumfh"];
$summh = $row1 ["summh"];
$sumlh = $row1 ["sumlh"];

$sumarray ['1-'] ["sumtotap"] = $sumtotap;
for($x = 0; $x < $apous_count; $x ++) {
	$kod = 'sumap' . $apousies_define [$x] ['kod'];
	$sumarray ['1-'] [$kod] = $sumap_array [$kod];
}
$sumarray ['1-'] ["daysp"] = $daysp;
for($x = 0; $x < $dik_count; $x ++) {
	$kod = 'sumd' . $dikaiologisi_define [$x] ['kod'];
	$sumarray ['1-'] [$kod] = $sumd_array [$x];
}
$sumarray ['1-'] ["sumoa"] = $sumoa;
$sumarray ['1-'] ["sumda"] = $sumda;
$sumarray ['1-'] ["sumfh"] = $sumfh;
$sumarray ['1-'] ["summh"] = $summh;
$sumarray ['1-'] ["sumlh"] = $sumlh;

$row2 = mysqli_fetch_assoc ( $result2 );

$themonth = $row2 ["mymonth"];
$mymonth = intval ( $themonth );
$sumtotap = $row2 ["sumtotap"];
for($x = 0; $x < $apous_count; $x ++) {
	$kod = 'sumap' . $apousies_define [$x] ['kod'];
	$sumap_array [$kod] = $row2 [$kod];
}
$daysp = $row2 ["daysp"];
for($x = 0; $x < $dik_count; $x ++) {
	$kod = 'sumd' . $dikaiologisi_define [$x] ['kod'];
	$sumd_array [$x] = $row2 [$kod];
}
$sumoa = $row2 ["sumoa"];
$sumda = $row2 ["sumda"];
$sumfh = $row2 ["sumfh"];
$summh = $row2 ["summh"];
$sumlh = $row2 ["sumlh"];

$sumarray ['1+'] ["sumtotap"] = $sumtotap;
for($x = 0; $x < $apous_count; $x ++) {
	$kod = 'sumap' . $apousies_define [$x] ['kod'];
	$sumarray ['1+'] [$kod] = $sumap_array [$kod];
}
$sumarray ['1+'] ["daysp"] = $daysp;
for($x = 0; $x < $dik_count; $x ++) {
	$kod = 'sumd' . $dikaiologisi_define [$x] ['kod'];
	$sumarray ['1+'] [$kod] = $sumd_array [$x];
}
$sumarray ['1+'] ["sumoa"] = $sumoa;
$sumarray ['1+'] ["sumda"] = $sumda;
$sumarray ['1+'] ["sumfh"] = $sumfh;
$sumarray ['1+'] ["summh"] = $summh;
$sumarray ['1+'] ["sumlh"] = $sumlh;

$sumtotapA = 0;
$sumtotapA += isset ( $sumarray [9] ["sumtotap"] ) ? $sumarray [9] ["sumtotap"] : 0;
$sumtotapA += isset ( $sumarray [10] ["sumtotap"] ) ? $sumarray [10] ["sumtotap"] : 0;
$sumtotapA += isset ( $sumarray [11] ["sumtotap"] ) ? $sumarray [11] ["sumtotap"] : 0;
$sumtotapA += isset ( $sumarray [12] ["sumtotap"] ) ? $sumarray [12] ["sumtotap"] : 0;
$sumtotapA += isset ( $sumarray ['1-'] ["sumtotap"] ) ? $sumarray ['1-'] ["sumtotap"] : 0;

$sumdayspA = 0;
$sumdayspA += isset ( $sumarray [9] ["daysp"] ) ? $sumarray [9] ["daysp"] : 0;
$sumdayspA += isset ( $sumarray [10] ["daysp"] ) ? $sumarray [10] ["daysp"] : 0;
$sumdayspA += isset ( $sumarray [11] ["daysp"] ) ? $sumarray [11] ["daysp"] : 0;
$sumdayspA += isset ( $sumarray [12] ["daysp"] ) ? $sumarray [12] ["daysp"] : 0;
$sumdayspA += isset ( $sumarray ['1-'] ["daysp"] ) ? $sumarray ['1-'] ["daysp"] : 0;

$sumdA_array = array ();
for($x = 0; $x < $dik_count; $x ++) {
	$kod = 'sumd' . $dikaiologisi_define [$x] ['kod'];
	$sumdA_array [$x] = 0;
	$sumdA_array [$x] += isset ( $sumarray [9] [$kod] ) ? $sumarray [9] [$kod] : 0;
	$sumdA_array [$x] += isset ( $sumarray [10] [$kod] ) ? $sumarray [10] [$kod] : 0;
	$sumdA_array [$x] += isset ( $sumarray [11] [$kod] ) ? $sumarray [11] [$kod] : 0;
	$sumdA_array [$x] += isset ( $sumarray [12] [$kod] ) ? $sumarray [12] [$kod] : 0;
	$sumdA_array [$x] += isset ( $sumarray ['1-'] [$kod] ) ? $sumarray ['1-'] [$kod] : 0;
}
$sumoaA = 0;
$sumoaA += isset ( $sumarray [9] ["sumoa"] ) ? $sumarray [9] ["sumoa"] : 0;
$sumoaA += isset ( $sumarray [10] ["sumoa"] ) ? $sumarray [10] ["sumoa"] : 0;
$sumoaA += isset ( $sumarray [11] ["sumoa"] ) ? $sumarray [11] ["sumoa"] : 0;
$sumoaA += isset ( $sumarray [12] ["sumoa"] ) ? $sumarray [12] ["sumoa"] : 0;
$sumoaA += isset ( $sumarray ['1-'] ["sumoa"] ) ? $sumarray ['1-'] ["sumoa"] : 0;

$sumdaA = 0;
$sumdaA += isset ( $sumarray [9] ["sumda"] ) ? $sumarray [9] ["sumda"] : 0;
$sumdaA += isset ( $sumarray [10] ["sumda"] ) ? $sumarray [10] ["sumda"] : 0;
$sumdaA += isset ( $sumarray [11] ["sumda"] ) ? $sumarray [11] ["sumda"] : 0;
$sumdaA += isset ( $sumarray [12] ["sumda"] ) ? $sumarray [12] ["sumda"] : 0;
$sumdaA += isset ( $sumarray ['1-'] ["sumda"] ) ? $sumarray ['1-'] ["sumda"] : 0;

$sumfhA = 0;
$sumfhA += isset ( $sumarray [9] ["sumfh"] ) ? $sumarray [9] ["sumfh"] : 0;
$sumfhA += isset ( $sumarray [10] ["sumfh"] ) ? $sumarray [10] ["sumfh"] : 0;
$sumfhA += isset ( $sumarray [11] ["sumfh"] ) ? $sumarray [11] ["sumfh"] : 0;
$sumfhA += isset ( $sumarray [12] ["sumfh"] ) ? $sumarray [12] ["sumfh"] : 0;
$sumfhA += isset ( $sumarray ['1-'] ["sumfh"] ) ? $sumarray ['1-'] ["sumfh"] : 0;

$summhA = 0;
$summhA += isset ( $sumarray [9] ["summh"] ) ? $sumarray [9] ["summh"] : 0;
$summhA += isset ( $sumarray [10] ["summh"] ) ? $sumarray [10] ["summh"] : 0;
$summhA += isset ( $sumarray [11] ["summh"] ) ? $sumarray [11] ["summh"] : 0;
$summhA += isset ( $sumarray [12] ["summh"] ) ? $sumarray [12] ["summh"] : 0;
$summhA += isset ( $sumarray ['1-'] ["summh"] ) ? $sumarray ['1-'] ["summh"] : 0;

$sumlhA = 0;
$sumlhA += isset ( $sumarray [9] ["sumlh"] ) ? $sumarray [9] ["sumlh"] : 0;
$sumlhA += isset ( $sumarray [10] ["sumlh"] ) ? $sumarray [10] ["sumlh"] : 0;
$sumlhA += isset ( $sumarray [11] ["sumlh"] ) ? $sumarray [11] ["sumlh"] : 0;
$sumlhA += isset ( $sumarray [12] ["sumlh"] ) ? $sumarray [12] ["sumlh"] : 0;
$sumlhA += isset ( $sumarray ['1-'] ["sumlh"] ) ? $sumarray ['1-'] ["sumlh"] : 0;

$sumdikA = 0;
for($x = 0; $x < $dik_count; $x ++) {
	$sumdikA += $sumdA_array [$x];
}
$sumadikA = $sumtotapA - $sumdikA;
$sumoadaA = $sumoaA + $sumdaA;

$sumtotapB = 0;
$sumtotapB += isset ( $sumarray ['1+'] ["sumtotap"] ) ? $sumarray ['1+'] ["sumtotap"] : 0;
$sumtotapB += isset ( $sumarray [2] ["sumtotap"] ) ? $sumarray [2] ["sumtotap"] : 0;
$sumtotapB += isset ( $sumarray [3] ["sumtotap"] ) ? $sumarray [3] ["sumtotap"] : 0;
$sumtotapB += isset ( $sumarray [4] ["sumtotap"] ) ? $sumarray [4] ["sumtotap"] : 0;
$sumtotapB += isset ( $sumarray [5] ["sumtotap"] ) ? $sumarray [5] ["sumtotap"] : 0;

$sumdayspB = 0;
$sumdayspB += isset ( $sumarray ['1+'] ["daysp"] ) ? $sumarray ['1+'] ["daysp"] : 0;
$sumdayspB += isset ( $sumarray [2] ["daysp"] ) ? $sumarray [2] ["daysp"] : 0;
$sumdayspB += isset ( $sumarray [3] ["daysp"] ) ? $sumarray [3] ["daysp"] : 0;
$sumdayspB += isset ( $sumarray [4] ["daysp"] ) ? $sumarray [4] ["daysp"] : 0;
$sumdayspB += isset ( $sumarray [5] ["daysp"] ) ? $sumarray [5] ["daysp"] : 0;

$sumdB_array = array ();
for($x = 0; $x < $dik_count; $x ++) {
	$kod = 'sumd' . $dikaiologisi_define [$x] ['kod'];
	$sumdB_array [$x] = 0;
	$sumdB_array [$x] += isset ( $sumarray ['1+'] [$kod] ) ? $sumarray ['1+'] [$kod] : 0;
	$sumdB_array [$x] += isset ( $sumarray [2] [$kod] ) ? $sumarray [2] [$kod] : 0;
	$sumdB_array [$x] += isset ( $sumarray [3] [$kod] ) ? $sumarray [3] [$kod] : 0;
	$sumdB_array [$x] += isset ( $sumarray [4] [$kod] ) ? $sumarray [4] [$kod] : 0;
	$sumdB_array [$x] += isset ( $sumarray [5] [$kod] ) ? $sumarray [5] [$kod] : 0;
}

$sumoaB = 0;
$sumoaB += isset ( $sumarray ['1+'] ["sumoa"] ) ? $sumarray ['1+'] ["sumoa"] : 0;
$sumoaB += isset ( $sumarray [2] ["sumoa"] ) ? $sumarray [2] ["sumoa"] : 0;
$sumoaB += isset ( $sumarray [3] ["sumoa"] ) ? $sumarray [3] ["sumoa"] : 0;
$sumoaB += isset ( $sumarray [4] ["sumoa"] ) ? $sumarray [4] ["sumoa"] : 0;
$sumoaB += isset ( $sumarray [5] ["sumoa"] ) ? $sumarray [5] ["sumoa"] : 0;

$sumdaB = 0;
$sumdaB += isset ( $sumarray ['1+'] ["sumda"] ) ? $sumarray ['1+'] ["sumda"] : 0;
$sumdaB += isset ( $sumarray [2] ["sumda"] ) ? $sumarray [2] ["sumda"] : 0;
$sumdaB += isset ( $sumarray [3] ["sumda"] ) ? $sumarray [3] ["sumda"] : 0;
$sumdaB += isset ( $sumarray [4] ["sumda"] ) ? $sumarray [4] ["sumda"] : 0;
$sumdaB += isset ( $sumarray [5] ["sumda"] ) ? $sumarray [5] ["sumda"] : 0;

$sumfhB = 0;
$sumfhB += isset ( $sumarray ['1+'] ["sumfh"] ) ? $sumarray ['1+'] ["sumfh"] : 0;
$sumfhB += isset ( $sumarray [2] ["sumfh"] ) ? $sumarray [2] ["sumfh"] : 0;
$sumfhB += isset ( $sumarray [3] ["sumfh"] ) ? $sumarray [3] ["sumfh"] : 0;
$sumfhB += isset ( $sumarray [4] ["sumfh"] ) ? $sumarray [4] ["sumfh"] : 0;
$sumfhB += isset ( $sumarray [5] ["sumfh"] ) ? $sumarray [5] ["sumfh"] : 0;

$summhB = 0;
$summhB += isset ( $sumarray ['1+'] ["summh"] ) ? $sumarray ['1+'] ["summh"] : 0;
$summhB += isset ( $sumarray [2] ["summh"] ) ? $sumarray [2] ["summh"] : 0;
$summhB += isset ( $sumarray [3] ["summh"] ) ? $sumarray [3] ["summh"] : 0;
$summhB += isset ( $sumarray [4] ["summh"] ) ? $sumarray [4] ["summh"] : 0;
$summhB += isset ( $sumarray [5] ["summh"] ) ? $sumarray [5] ["summh"] : 0;

$sumlhB = 0;
$sumlhB += isset ( $sumarray ['1+'] ["sumlh"] ) ? $sumarray ['1+'] ["sumlh"] : 0;
$sumlhB += isset ( $sumarray [2] ["sumlh"] ) ? $sumarray [2] ["sumlh"] : 0;
$sumlhB += isset ( $sumarray [3] ["sumlh"] ) ? $sumarray [3] ["sumlh"] : 0;
$sumlhB += isset ( $sumarray [4] ["sumlh"] ) ? $sumarray [4] ["sumlh"] : 0;
$sumlhB += isset ( $sumarray [5] ["sumlh"] ) ? $sumarray [5] ["sumlh"] : 0;

$sumdikB = 0;
for($x = 0; $x < $dik_count; $x ++) {
	$sumdikB += $sumdB_array [$x];
}
$sumadikB = $sumtotapB - $sumdikB;
$sumoadaB = $sumoaB + $sumdaB;

// προυπάρχουσες απουσιεσ

$pre_apousies = get_pre_apousies ( $parent, $am );

for($x = 0; $x < $apous_count; $x ++) {
	$kod = 'ap' . $apousies_define [$x] ['kod'];
	$pre_apousies [$kod] == 0 ? $pre_apous_array [$x] = "&nbsp;" : $pre_apous_array [$x] = $pre_apousies [$kod];
}

$pre_apousies ["fh"] == 0 ? $pre_fh = "&nbsp;" : $pre_fh = $pre_apousies ["fh"];
$pre_apousies ["mh"] == 0 ? $pre_mh = "&nbsp;" : $pre_mh = $pre_apousies ["mh"];
$pre_apousies ["lh"] == 0 ? $pre_lh = "&nbsp;" : $pre_lh = $pre_apousies ["lh"];
$pre_apousies ["oa"] == 0 ? $pre_oa = "&nbsp;" : $pre_oa = $pre_apousies ["oa"];
$pre_apousies ["da"] == 0 ? $pre_da = "&nbsp;" : $pre_da = $pre_apousies ["da"];
$pre_apousies ["daysp"] == 0 ? $pre_daysp = "&nbsp;" : $pre_daysp = $pre_apousies ["daysp"];

for($x = 0; $x < $dik_count; $x ++) {
	$kod = 'di' . $dikaiologisi_define [$x] ['kod'];
	$pre_apousies [$kod] == 0 ? $pre_dik_array [$x] = "&nbsp;" : $pre_dik_array [$x] = $pre_apousies [$kod];
}

$pre_apousies ["date"] ? $pre_date = $pre_apousies ["date"] : $pre_date = "";

$pre_totap = 0;
for($x = 0; $x < $apous_count; $x ++) {
	$pre_totap += intval($pre_apous_array [$x]);
}

$pre_totdik = 0;
for($x = 0; $x < $dik_count; $x ++) {
	$pre_totdik += intval($pre_dik_array [$x]);
}

$pre_totadik = $pre_totap - $pre_totdik;
$pre_totoada = intval($pre_oa) + intval($pre_da);
if ($pre_totdik == 0)
	$pre_totdik = "&nbsp;";
if ($pre_totadik == 0)
	$pre_totadik = "&nbsp;";
if ($pre_totoada == 0)
	$pre_totoada = "&nbsp;";

$row3 = mysqli_fetch_assoc ( $result3 );

$tottotap = $row3 ["sumtotap"];
for($x = 0; $x < $apous_count; $x ++) {
	$tottotap += intval($pre_apous_array [$x]);
}

// $totap = $row3["sumap"];
// $totapk = $row3["sumapk"];
// $totape = $row3["sumape"];
$totdaysp = $row3 ["daysp"] + $pre_apousies ["daysp"];

$totd_array = array ();

for($x = 0; $x < $dik_count; $x ++) {
	$kod = 'sumd' . $dikaiologisi_define [$x] ['kod'];
	$pre_kod = 'di' . $dikaiologisi_define [$x] ['kod'];
	$totd_array [$x] = $row3 [$kod] + $pre_apousies [$pre_kod];
}
$totoa = $row3 ["sumoa"] + $pre_apousies ["oa"];
$totda = $row3 ["sumda"] + $pre_apousies ["da"];
$totfh = $row3 ["sumfh"] + $pre_apousies ["fh"];
$totmh = $row3 ["summh"] + $pre_apousies ["mh"];
$totlh = $row3 ["sumlh"] + $pre_apousies ["lh"];
$totdik = 0;
for($x = 0; $x < $dik_count; $x ++) {
	$totdik += $totd_array [$x];
}
$totadik = $tottotap - $totdik;
$totoada = $totoa + $totda;
if ($pre_totoada == 0)
	$pre_totoada = "&nbsp;";

mysqli_close ( $link );

$label_array = array ();
for($x = 0; $x < $apous_count; $x ++) {
	$label_array [$x] = substr ( $apousies_define [$x] ['perigrafi'], 0, 2 );
}

$mytextdata = "<table cellspacing=\"0\" border=\"1\" cellpadding=\"0\" align=\"center\" frame=\"box\" >
  <tbody align=\"center\" >
";
if ($pre_apousies ["date"]) {
	$mytextdata .= "<tr>
	<th colspan=\"3\">ΠΡΟΥΠΑΡΧΟΥΣΕΣ<br>ΜΕΧΡΙ $pre_date</th>
	<td class=\"bigfont\"  id=\"white\">$pre_totap</td>
	<td class=\"bigfont\"  id=\"white\">$pre_totdik</td>
	<td class=\"bigfont\"  id=\"white\">$pre_totadik</td>
	<td class=\"bigfont\"  id=\"green\">$pre_daysp</td>";
	
	for($x = 0; $x < $dik_count; $x ++) {
		$mytextdata .= "<td class=\"bigfont\"  id=\"$color_array[$x]\">$pre_dik_array[$x]</td>";
	}
	
	$colspan = $dik_count + 13;
	$mytextdata .= "<td class=\"bigfont\"  id=\"grey\">$pre_oa</td>
	<td class=\"bigfont\"  id=\"grey\">$pre_da</td>
	<td class=\"bigfont\"  id=\"grey\">$pre_totoada</td>
	<td class=\"bigfont\"  id=\"white\">$pre_fh</td>
	<td class=\"bigfont\"  id=\"white\">$pre_mh</td>
	<td class=\"bigfont\"  id=\"white\">$pre_lh</td>
</tr>
	<tr><td colspan=\"$colspan\">&nbsp;</td></tr>
";
}
$colspan = $dik_count + 1;

$mytextdata .= "<tr>
      <th  rowspan=\"2\">ΤΕΤΡ</th>
      <th rowspan=\"2\">ΜΗΝ</th>
	<th colspan=\"4\">ΣΥΝΟΛΟ</th>
	<th colspan=\"$colspan\" >ΔΙΚΑΙΟΛΟΓΗΜΕΝΕΣ ΑΠΟ</th>
      <th colspan=\"3\">ΑΠΟ ΑΠΟΒΟΛΕΣ</th>
      <th colspan=\"3\">ΜΕΜΟΝΩΜΕΝΕΣ</th>
    </tr>
    <tr>
      <th rowspan=\"1\">ΜΗΝΑ</th>
      <th rowspan=\"1\">&nbsp;ΤΕΤΡ&nbsp;</th>
      <th rowspan=\"1\">&nbsp;ΔΙΚ&nbsp;</th>
      <th rowspan=\"1\">&nbsp;ΑΔΙ&nbsp;</th>
	<th rowspan=\"1\" >ΗΜΕ<br>ΚΗΔ</th>";

for($x = 0; $x < $dik_count; $x ++) {
	$label = $dikaiologisi_define [$x] ['label'];
	$mytextdata .= "<th rowspan=\"1\">$label</th>";
}

$mytextdata .= "<th rowspan=\"1\">ΩΡ<br>ΑΠΟΒ</th>
      <th rowspan=\"1\">ΗΜ<br>ΑΠΟΒ</th>
      <th rowspan=\"1\">ΣΥΝ<br>ΑΠΟΥ</th>
      <th rowspan=\"1\">1ης<br>ΩΡΑΣ</th>
      <th rowspan=\"1\">ΕΝΔΙΑ<br>ΜΕΣΑ</th>
      <th rowspan=\"1\">ΤΕΛ<br>ΩΡΑΣ</th>
    </tr>";
$mytextdata .= "<tr>
      <th rowspan=\"5\">Α΄</th>
      <th>9ος</td>
	<td  class=\"demifont\" id=\"white\" >";
if (isset ( $sumarray [9] ['sumtotap'] ) && $sumarray [9] ['sumtotap'] > 0) {
	$mytextdata .= $sumarray [9] ['sumtotap'];
} else {
	$mytextdata .= "&nbsp;";
}
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"white\"> ";
if ($sumtotapA == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumtotapA;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"white\"> ";
if ($sumdikA == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumdikA;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"white\"> ";
if ($sumadikA == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumadikA;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"green\"> ";
if ($sumdayspA == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumdayspA;

for($x = 0; $x < $dik_count; $x ++) {
	$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"$color_array[$x]\"> ";
	if ($sumdA_array [$x] == 0) {
		$mytextdata .= "&nbsp;";
	} else {
		$mytextdata .= $sumdA_array [$x];
	}
}

$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"grey\"> ";
if ($sumoaA == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumoaA;
$mytextdata .= " </td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"grey\"> ";
if ($sumdaA == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumdaA;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"grey\"> ";
if ($sumoadaA == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumoadaA;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"white\"> ";
if ($sumfhA == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumfhA;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"white\"> ";
if ($summhA == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $summhA;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"white\"> ";
if ($sumlhA == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumlhA;
$mytextdata .= "</td>
	</tr>
	<tr>
	<th >10ος</th>
	<td  class=\"demifont\" id=\"white\" > ";
if (isset ( $sumarray [10] ['sumtotap'] ) && $sumarray [10] ['sumtotap'] > 0) {
	$mytextdata .= $sumarray [10] ['sumtotap'];
} else {
	$mytextdata .= "&nbsp;";
}
$mytextdata .= "</td>
	</tr>
	<tr>
	<th >11ος</th>

	<td class=\"demifont\" id=\"white\" > ";
if (isset ( $sumarray [11] ['sumtotap'] ) && $sumarray [11] ['sumtotap'] > 0) {
	$mytextdata .= $sumarray [11] ['sumtotap'];
} else {
	$mytextdata .= "&nbsp;";
}
$mytextdata .= "</td>
	</tr>

	<tr>
	<th >12ος</th>

	<td  class=\"demifont\" id=\"white\" > ";
if (isset ( $sumarray [12] ['sumtotap'] ) && $sumarray [12] ['sumtotap'] > 0) {
	$mytextdata .= $sumarray [12] ['sumtotap'];
} else {
	$mytextdata .= "&nbsp;";
}
$mytextdata .= "</td>
	</tr>

	<tr>
	<th >1ος</th>

	<td class=\"demifont\" id=\"white\" > ";
if ($sumarray ['1-'] ['sumtotap'] == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumarray ['1-'] ['sumtotap'];
$mytextdata .= "</td>
	</tr>
		
	<tr>
	<th rowspan=\"5\">Β΄</th>
	<th >1ος</th>

	<td  class=\"demifont\" id=\"white\" >";
if ($sumarray ['1+'] ['sumtotap'] == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumarray ['1+'] ['sumtotap'];
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"white\">";
if ($sumtotapB == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumtotapB;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"white\"> ";
if ($sumdikB == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumdikB;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"white\"> ";
if ($sumadikB == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumadikB;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"green\"> ";
if ($sumdayspB == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumdayspB;

for($x = 0; $x < $dik_count; $x ++) {
	$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"$color_array[$x]\"> ";
	if ($sumdB_array [$x] == 0) {
		$mytextdata .= "&nbsp;";
	} else {
		$mytextdata .= $sumdB_array [$x];
	}
}
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"grey\"> ";
if ($sumoaB == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumoaB;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"grey\"> ";
if ($sumdaB == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumdaB;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"grey\"> ";
if ($sumoadaB == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumoadaB;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"white\"> ";
if ($sumfhB == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumfhB;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"white\"> ";
if ($summhB == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $summhB;
$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"white\"> ";
if ($sumlhB == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $sumlhB;
$mytextdata .= "</td>
	</tr>
	<tr>
	<th >2ος</th>

	<td class=\"demifont\" id=\"white\" > ";
if (isset ( $sumarray [2] ['sumtotap'] ) && $sumarray [2] ['sumtotap'] > 0) {
	$mytextdata .= $sumarray [2] ['sumtotap'];
} else {
	$mytextdata .= "&nbsp;";
}
$mytextdata .= "</td>
	</tr>

	<tr>
	<th >3ος</th>

	<td class=\"demifont\" id=\"white\" > ";
if (isset ( $sumarray [3] ['sumtotap'] ) && $sumarray [3] ['sumtotap'] > 0) {
	$mytextdata .= $sumarray [3] ['sumtotap'];
} else {
	$mytextdata .= "&nbsp;";
}
$mytextdata .= "</td>
	</tr>
	<tr>
	<th >4ος</th>

	<td class=\"demifont\" id=\"white\" > ";
if (isset ( $sumarray [4] ['sumtotap'] ) && $sumarray [4] ['sumtotap'] > 0) {
	$mytextdata .= $sumarray [4] ['sumtotap'];
} else {
	$mytextdata .= "&nbsp;";
}
$mytextdata .= "</td>
	</tr>
	<tr>
	<th >5ος</th>

	<td class=\"demifont\" id=\"white\" > ";
if (isset ( $sumarray [5] ['sumtotap'] ) && $sumarray [5] ['sumtotap'] > 0) {
	$mytextdata .= $sumarray [5] ['sumtotap'];
} else {
	$mytextdata .= "&nbsp;";
}
$mytextdata .= "</td>
	</tr>

	<tr>
	<th colspan=\"3\">ΕΤΗΣΙΟ<br>ΣΥΝΟΛΟ</th>
	<td  class=\"bigfont\" id=\"white\"> ";
if ($tottotap == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $tottotap;
$mytextdata .= "</td>
	<td  class=\"bigfont\" id=\"white\">";
if ($totdik == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $totdik;
$mytextdata .= "</td>
	<td  class=\"bigfont\" id=\"white\"> ";
if ($totadik == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $totadik;
$mytextdata .= "</td>
	<td  class=\"bigfont\" id=\"green\"> ";
if ($totdaysp == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $totdaysp;

for($x = 0; $x < $dik_count; $x ++) {
	$mytextdata .= "</td>
	<td rowspan=\"5\" class=\"bigfont\"  id=\"$color_array[$x]\"> ";
	if ($totd_array [$x] == 0) {
		$mytextdata .= "&nbsp;";
	} else {
		$mytextdata .= $totd_array [$x];
	}
}

$mytextdata .= "</td>
	<td  class=\"bigfont\" id=\"grey\"> ";
if ($totoa == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $totoa;
$mytextdata .= "</td>
	<td  class=\"bigfont\" id=\"grey\"> ";
if ($totda == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $totda;
$mytextdata .= "</td>
	<td  class=\"bigfont\" id=\"grey\"> ";
if ($totoada == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $totoada;
$mytextdata .= "</td>
	<td  class=\"bigfont\" id=\"white\"> ";
if ($totfh == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $totfh;
$mytextdata .= "</td>
	<td  class=\"bigfont\" id=\"white\"> ";
if ($totmh == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $totmh;
$mytextdata .= "</td>
	<td  class=\"bigfont\" id=\"white\"> ";
if ($totlh == 0)
	$mytextdata .= "&nbsp;";
else
	$mytextdata .= $totlh;
$mytextdata .= "</td>
	</tr>
	</tbody>
	</table>";

echo $mytextdata;
?>					
