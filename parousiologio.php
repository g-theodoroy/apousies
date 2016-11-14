<?php
require_once ('common.php');
checkUser ();
checktmima ();

// καθορίζει πόσα inputs θα φαίνονται
// οταν φορτώνεται το παρουσιολόγιο Α Λυκείου
// ή Γυμνασίου
$showinputs = 1;

$apous_count = count ( $apousies_define );
$dik_count = count ( $dikaiologisi_define );

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';
isset ( $_GET ['t'] ) ? $target = $_GET ['t'] : $target = 'l';

switch ($target) {
	case "l" :
		$template = 'parousiologio_lyk.tpl';
		$get_totals = 'get_totals_parous_lyk.php';
		$parous_print = 'parousprint.php?t=l&';
		$showinputs = count($apousies_define);
		break;
	case "a" :
		$template = 'parousiologio_lyk.tpl';
		$get_totals = 'get_totals_parous_A_lyk.php';
		$parous_print = 'parousprint.php?t=a&';
		break;
	case "g" :
		$template = 'parousiologio_gym.tpl';
		$get_totals = 'get_totals_parous_gym.php';
		$parous_print = 'parousprint.php?t=g&';
		break;
}

$novalue = str_repeat ( '0', $apous_count ) . '0-00000';

if (isset ( $_POST ["oldstudent"] )) {
	$oldstudent = $_POST ["oldstudent"];
	$_SESSION ['student'] = $oldstudent;
} else {
	$oldstudent = "";
	$_SESSION ['student'] = "";
}
if (isset ( $_POST ["newstudent"] )) {
	$newstudent = $_POST ["newstudent"];
} else {
	$newstudent = "";
}
if (isset ( $_POST ["todo"] )) {
	$todo = $_POST ["todo"];
} else {
	$todo = "";
}

foreach ( $_POST as $key => $value ) {
	if (substr ( $key, 0, 6 ) == "stdata") {
			$postdataarray [$key] = $value;
	}
	// echo "$key = $value<hr>";
}

// echo "postdataarray<br>";
// print_r ($_POST);
// echo "<hr>";
// print_r ($postdataarray);
// echo "<hr>";
// echo "oldstudent = $oldstudent<hr>";
// echo "newstudent = $newstudent<hr>";
// echo "todo = $todo<hr>";
// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

// ελεγχος για τον τύπο του τμήματος
$query1 = "SELECT DISTINCT `type` FROM `tmimata` WHERE `username` = '$parent' GROUP BY `type`;";
$result1 = mysqli_query ( $link, $query1 );
$typecount = mysqli_num_rows ( $result1 );

if ($typecount == 1) {
	$allow = true;
} else {
	$allow = false;
}

// ---------------------------------------------------------------------

if ($todo == "save") {
	
	begin ();
	$errorcheck = false;
	
	$query = "DELETE `apousies` FROM `apousies` LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am`  
    JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am`) 
    where `apousies`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima' AND `apousies`.`student_am`= '$oldstudent';";
	
	$result = mysqli_query ( $link, $query );
	if (! $result) {
		$errorcheck = true;
		echo 'del= ' . mysqli_error ( $link ) . '<br>';
	}
	
	for($i = 1; $i < count ( $postdataarray ) + 1; $i ++) {
		if ($postdataarray ["stdata$i"] !== $novalue ) {
			$month = ( int ) ($i / 31 + 9);
			if ($month > 12) {
				$month -= 12;
			}
			$day = $i % 31;
			
			$yeartemp = date ( "Y" );
			$yeartemp1 = $yeartemp + 1;
			$yeartemp2 = $yeartemp - 1;
			
			if ((date ( "m" ) > 7 && $month > 7) || (date ( "m" ) < 8 && $month < 8)) {
				$year = $yeartemp;
			} else if (date ( "m" ) > 7 && $month < 8) {
				$year = $yeartemp1;
			} else if (date ( "m" ) < 8 && $month > 7) {
				$year = $yeartemp2;
			}
			
			if ($day == 0) {
				$day = 31;
				$month --;
			}
			
			if ($month < 10) {
				$month = 0 . $month;
			}
			if ($day < 10) {
				$day = 0 . $day;
			}
			// echo "$i - $day/$month/$year<hr>";
			
			$newdate = $year . $month . $day;
			// echo "$i - $day/$month/$year - $newdate<hr>";
			
			$apous = substr ( $postdataarray ["stdata$i"], 0, $apous_count );
			$dik = substr ( $postdataarray ["stdata$i"], $apous_count, 1 );
			$from = substr ( $postdataarray ["stdata$i"], $apous_count + 1, 1 );
			if ($from == '-') {
				$from = '';
			}
			$fh = substr ( $postdataarray ["stdata$i"], $apous_count + 2, 1 );
			$mh = substr ( $postdataarray ["stdata$i"], $apous_count + 3, 1 );
			$lh = substr ( $postdataarray ["stdata$i"], $apous_count + 4, 1 );
			$oa = substr ( $postdataarray ["stdata$i"], $apous_count + 5, 1 );
			$da = substr ( $postdataarray ["stdata$i"], $apous_count + 6, 1 );
			$query = "INSERT INTO `apousies` (`mydate` ,`apous` ,`dik` ,`from` ,`fh` ,`mh`,`lh` ,`oa` ,`da` ,`user` ,`student_am`) VALUES 
            ('$newdate','$apous' ,'$dik' ,'$from' ,'$fh' ,'$mh','$lh' ,'$oa' ,'$da' , '$parent','$oldstudent'); ";
			
			$result = mysqli_query ( $link, $query );
			if (! $result) {
				$errorcheck = true;
				echo 'ins= ' . mysqli_error ( $link ) . '<br>';
			}
		}
	}
	
	if ($errorcheck == true) {
		echo mysqli_error ( $link );
		rollback ();
	} else {
		commit ();
		$_SESSION ["havechanges"] = true;
	}
} // if ($todo == "save")
  // θα ανοίξω τα καινούρια στοιχεία για τον ΑΜ newstudent
  // συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$totapstr = '';
$apstr = '';

for($i = 0; $i < $apous_count; $i ++) {
	$k = $i + 1;
	$totapstr .= " MID(`apousies`.`apous`,$k,1) +";
	$apstr .= " MID(`apousies`.`apous`,$k,1) as `ap" . $apousies_define [$i] ['kod'] . "`,";
}
$totapstr = substr ( $totapstr, 0, - 1 ) . " as `totap`";
$apstr = substr ( $apstr, 0, - 1 );

$colorstr = "CASE `from` WHEN '' THEN 'white' ";
for($i = 0; $i < $dik_count; $i ++) {
	$kod = $dikaiologisi_define [$i] ['kod'];
	$value = $dikaiologisi_define [$i] ['color'];
	$colorstr .= "WHEN '$kod' THEN '$value' ";
}
$colorstr .= "END as `color`";

$query = "SELECT `mydate`,$totapstr ,`apous`, `dik`,`from`, `fh`, `mh`,`lh`, `oa`, `da`, $colorstr , IF(`oa`=0 and `da`=0, '','marked') as `class` FROM `apousies` 
    LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am`  
    JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am`  
    where `apousies`.`user` ='$parent' and `studentstmimata`.`tmima`= '$tmima' and `apousies`.`student_am`='$newstudent' order by `mydate`;";

// echo $query. "<hr>";

$result = mysqli_query ( $link, $query );
if (! $result) {
	$errorText = mysqli_error ( $link );
	echo "1 $errorText<hr>";
}
$num = mysqli_num_rows ( $result );

$dataarray = array ();

while ( $row = mysqli_fetch_assoc ( $result ) ) {
	$mydate = $row ["mydate"];
	$day = intval ( substr ( $mydate, 6, 2 ) );
	$month = intval ( substr ( $mydate, 4, 2 ) );
	
	$totap = $row ["totap"];
	$apous = $row ["apous"];
	if (! $apous) {
		$apous = str_repeat ( '0', $apous_count );
	}
	$apous_array = array ();
	for($x = 0; $x < $apous_count; $x ++) {
		$apous_array [$x] = ( int ) substr ( $apous, $x, 1 );
	}
	$dik = $row ["dik"];
	if (! $dik)
		$dik = '0';
	$from = $row ["from"];
	if ($from == '')
		$from = '-';
	
	$class = $row ["color"] . " " . $row ["class"];
	$mydata = $apous . $dik . $from . $row ["fh"] . $row ["mh"] . $row ["lh"] . $row ["oa"] . $row ["da"];
	
	$dataarray [$month] [$day] [0] = $mydata;
	$dataarray [$month] [$day] [1] = $totap;
	$dataarray [$month] [$day] [2] = $apous_array;
	$dataarray [$month] [$day] [3] = $class;
}

// print_r($dataarray);

require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty ();

$extra_style = '
<style type="text/css">
    input[type=text], input.text 
    {
    margin:0;padding:0;
    color:black;
    }
    table 
    { 
    border:solid; border-color:#222; 
    }
    th,td 
    {
    border:solid; border-color:#232; border-width:1px;text-align: center; vertical-align:middle;padding: 0;
    }
</style>
';

$extra_javascript = '
<script type="text/javascript">
    function showHint()
    {
        if(document.getElementById("txtHint").innerHTML!="")
        {
            document.getElementById("txtHint").innerHTML="";
            return;
        }
	
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","' . $get_totals . '?am=" + "' . $newstudent . '" , true);
        xmlhttp.send();
    }

    function check_form_submit(){
        if(document.getElementById("newstudent").value == "all"){
            window.open("' . $parous_print . 'st=all", "_blank");
            document.getElementById("newstudent").value = document.getElementById("oldstudent").value;
        }else if(document.getElementById("newstudent").value == "allpdf"){
            window.open("' . $parous_print . 'st=allpdf&do=allpdf");
            document.getElementById("newstudent").value = document.getElementById("oldstudent").value;
        }else{
            document.getElementById("frm").submit();
        }
    }
</script>
';

$smarty->assign ( 'title', 'ΠΑΡΟΥΣΙΟΛΟΓΙΟ' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'extra_javascript', $extra_javascript );
$smarty->assign ( 'h1_title', 'Παρουσιολόγιο' );
$smarty->assign ( 'body_attributes', '' );
$smarty->assign ( 'allow', $allow );
$smarty->assign ( 'newstudent', $newstudent );

$smarty->assign ( 'novalue', $novalue );
$smarty->assign ( 'apous_count', $apous_count );
$smarty->assign ( 'showinputs', $showinputs );
$smarty->assign ( 'parous_print', $parous_print );

$row_names_array = array ();
for($x = 0; $x < $apous_count; $x ++) {
	$row_names_array [$x] = substr ( $apousies_define [$x] ['perigrafi'], 0, 2 );
}

$smarty->assign ( 'row_names_array', $row_names_array );

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$sumap_names_array = array ();
$pre_names_array = array ();
$strfields = '';

for($i = 0; $i < $apous_count; $i ++) {
	$k = $i + 1;
	$sumap_names_array [$i] = 'sumap' . $apousies_define [$i] ['kod'];
	$pre_names_array [$i] = 'ap' . $apousies_define [$i] ['kod'];
	$strfields .= " SUM(MID(`apousies`.`apous`,$k,1) ) as `sumap" . $apousies_define [$i] ['kod'] . '`,';
}

$strfields = substr ( $strfields, 0, - 1 );

$query = "SELECT  `students`.`am`, `students`.`epitheto`, `students`.`onoma`, `students`.`patronimo`, $strfields 
FROM `students` LEFT JOIN `apousies` ON `students`.`user` = `apousies`.`user` AND `students`.`am` = `apousies`.`student_am`  
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am`  
WHERE `students`.`user` = '$parent' AND `studentstmimata`.`tmima`= '$tmima'  
GROUP BY `students`.`am` ORDER BY `epitheto`, `onoma` ASC; ";

$result = mysqli_query ( $link, $query );

// echo $query . "<hr>";
// if (!$result) echo mysqli_error($link);

$num = mysqli_num_rows ( $result );

mysqli_close ( $link );

$selectdata = array ();

while ( $row = mysqli_fetch_assoc ( $result ) ) {
	$am = $row ["am"];
	$epitheto = $row ["epitheto"];
	$onoma = $row ["onoma"];
	$patronimo = $row ["patronimo"];
	
	$tmimata_array = gettmimata4student ( $parent, $am );
	$showtmima = '';
	foreach ( $tmimata_array as $key => $value ) {
		if ($value != $tmima) {
			$showtmima .= " $value,";
		}
	}
	$showtmima = substr ( $showtmima, 0, - 1 );
	
	// προυπάρχουσες απουσιες
	$pre_apousies = get_pre_apousies ( $parent, $am );
	
	$totalap = 0;
	
	for($x = 0; $x < $apous_count; $x ++) {
		$sumap_name = $sumap_names_array [$x];
		$totalap += ( int ) $row [$sumap_name];
	}
	
	for($x = 0; $x < $apous_count; $x ++) {
		$pre_name = $pre_names_array [$x];
		$totalap += $pre_apousies [$pre_name];
	}
	
	// $totalap = $row["sumapg"] + $row["sumapk"' + $row["sumape"] + $pre_apousies["apg"] + $pre_apousies["apk"] + $pre_apousies["ape"];
	// echo $row["sumap"] . '+' . $row["sumapk"] . '+' . $row["sumape"] . '<hr>';
	
	if ($newstudent == $am) {
		$selectdata [] = "<option value=\"$am\" selected >" . str_repeat ( "&nbsp;", 3 - strlen ( $totalap ) ) . "    $totalap    ->     $epitheto  $onoma $patronimo -> $showtmima </option>\n";
	} else {
		$selectdata [] = "<option value=\"$am\" >" . str_repeat ( "&nbsp;", 3 - strlen ( $totalap ) ) . "     $totalap    ->     $epitheto $onoma $patronimo  -> $showtmima </option>\n";
	}
}

$smarty->assign ( 'selectdata', $selectdata );
$smarty->assign ( 'dataarray', $dataarray );

$smarty->display ( $template );

