<?php
require_once ('common.php');
isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';
isset ( $_GET ['chkdate'] ) ? $chkdateget = $_GET ['chkdate'] : $chkdateget = Date ( "Ymd" );

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$mysqltimeoffset = 0;
$getapousiesfordatedata = "";
$apousiesdataarray = array ();

$query = "SELECT `apous` ,`dik` ,`from` , `fh` ,`mh` ,`lh` ,`oa` ,`da` ,`t1`.`student_am` from `students` 
RIGHT JOIN (SELECT * from `apousies` where `mydate` = '$chkdateget') as t1  on `t1`.`user` = `students`.`user`  
JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`) 
where `t1`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima' ;";

$result = mysqli_query ( $link, $query );
if (! $result) {
	$errorText = mysqli_error ( $link );
	echo "6 $errorText<hr>";
}

$num = mysqli_num_rows ( $result );

while ( $row = mysqli_fetch_assoc ( $result ) ) {
	$am = $row ["student_am"];
	for($x = 0; $x < count ( $apousies_define ); $x ++) {
		$kod = $apousies_define [$x] ["kod"];
		$apousiesdataarray [$am] ["ap$kod"] = substr ( $row ["apous"], $x, 1 ) > 0 ? substr ( $row ["apous"], $x, 1 ) : '';
	}
	$apousiesdataarray [$am] ["dik"] = $row ["dik"] > 0 ? $row ["dik"] : '';
	$apousiesdataarray [$am] ["from"] = $row ["from"] != "" ? $row ["from"] : '';
	$apousiesdataarray [$am] ["fh"] = $row ["fh"] > 0 ? $row ["fh"] : '';
	$apousiesdataarray [$am] ["mh"] = $row ["mh"] > 0 ? $row ["mh"] : '';
	$apousiesdataarray [$am] ["lh"] = $row ["lh"] > 0 ? $row ["lh"] : '';
	$apousiesdataarray [$am] ["oa"] = $row ["oa"] > 0 ? $row ["oa"] : '';
	$apousiesdataarray [$am] ["da"] = $row ["da"] > 0 ? $row ["da"] : '';
}

// ελέγχω αν υπάρχει το νέο τμήμα
$query = "SELECT `am` FROM `students`  JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `student_am`) WHERE `students`.`user` = '$parent' and  `studentstmimata`.`tmima` = '$tmima' ORDER BY `epitheto`,`onoma` ASC ;";

$result0 = mysqli_query ( $link, $query );
if (! $result0) {
	$errorText = mysqli_error ( $link );
	echo "6 $errorText<hr>";
}

$num = mysqli_num_rows ( $result0 );

mysqli_close ( $link );

while ( $row = mysqli_fetch_assoc ( $result0 ) ) {
	$am = $row ["am"];
	
	$apous = array ();
	
	for($x = 0; $x < count ( $apousies_define ); $x ++) {
		$kod = $apousies_define [$x] ["kod"];
		isset ( $apousiesdataarray [$am] ["ap$kod"] ) ? $apous [$x] = $apousiesdataarray [$am] ["ap$kod"] : $apous [$x] = '';
	}
	isset ( $apousiesdataarray [$am] ["dik"] ) ? $dik = $apousiesdataarray [$am] ["dik"] : $dik = '';
	isset ( $apousiesdataarray [$am] ["from"] ) ? $from = $apousiesdataarray [$am] ["from"] : $from = '';
	isset ( $apousiesdataarray [$am] ["fh"] ) ? $fh = $apousiesdataarray [$am] ["fh"] : $fh = '';
	isset ( $apousiesdataarray [$am] ["mh"] ) ? $mh = $apousiesdataarray [$am] ["mh"] : $mh = '';
	isset ( $apousiesdataarray [$am] ["lh"] ) ? $lh = $apousiesdataarray [$am] ["lh"] : $lh = '';
	isset ( $apousiesdataarray [$am] ["oa"] ) ? $oa = $apousiesdataarray [$am] ["oa"] : $oa = '';
	isset ( $apousiesdataarray [$am] ["da"] ) ? $da = $apousiesdataarray [$am] ["da"] : $da = '';
	
	$apousstr = "";
	for($x = 0; $x < count ( $apousies_define ); $x ++) {
		$kod = $apousies_define [$x] ["kod"];
		$apousstr .= "ap$kod$am,$apous[$x],";
	}
	
	$getapousiesfordatedata .= "{$apousstr}fh$am,$fh,mh$am,$mh,lh$am,$lh,oa$am,$oa,da$am,$da,dik$am,$dik,from$am,$from,";
}

$getapousiesfordate = substr ( $getapousiesfordatedata, 0, strlen ( $getapousiesfordatedata ) - 1 );

echo $getapousiesfordate;
?>