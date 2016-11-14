<?php

require_once('common.php');
checkUser();
checktmima();

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima = '';

//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");


if (isset($_POST["save"])) {

//φτιάχνω πίνακα valluearray με τα στοιχεία που έστειλε η φόρμα ()
    $valuearray = array();

    foreach ($_POST as $key => $value) {
        if (substr($key, 0, 2) == "am") {

            $data = '';
            $apou_count = count($apousies_define);
            for ($y = 0; $y < $apou_count; $y++) {
                $kod = "ap" . $apousies_define[$y]["kod"];
                $data .= str_repeat('0', 3 - strlen(trim($_POST["$kod$value"]))) . trim($_POST["$kod$value"]);
            }
//    echo "$data<hr>";
            if ($data == str_repeat('0', 3 * $apou_count))
                $data = '';
//    echo "$data<hr>";

            $valuearray[$value]["apous"] = $data;
            $valuearray[$value]["fh"] = $_POST["fh$value"];
            $valuearray[$value]["mh"] = $_POST["mh$value"];
            $valuearray[$value]["lh"] = $_POST["lh$value"];
            $valuearray[$value]["oa"] = $_POST["oa$value"];
            $valuearray[$value]["da"] = $_POST["da$value"];
            $valuearray[$value]["daysp"] = $_POST["daysp$value"];

            $data = '';
            $dik_count = count($dikaiologisi_define);
            for ($y = 0; $y < $dik_count; $y++) {
                $kod = "di" . $dikaiologisi_define[$y]["kod"];
                $data .= str_repeat('0', 3 - strlen(trim($_POST["$kod$value"]))) . trim($_POST["$kod$value"]);
            }
 //   echo "$data<hr>";
            if ($data == str_repeat('0', 3 * $dik_count))
                $data = '';
//    echo "$data<hr>";

            $valuearray[$value]["dik"] = $data;
            $valuearray[$value]["date"] = $_POST["date$value"];
        }
    }

//print_r($valuearray);
//echo "<hr>";
//βρίσκω τους Αρ Μητρώου

    $query = "SELECT `am` FROM `students`  JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `student_am`) WHERE `students`.`user` = '$parent' and  `studentstmimata`.`tmima` = '$tmima' ORDER BY `epitheto`,`onoma` ASC ;";

    $result = mysqli_query($link, $query);
    if (!$result) {
        $errorText = mysqli_error($link);
        echo "2 $errorText<hr>";
    }

    $num = mysqli_num_rows($result);


    while ($row = mysqli_fetch_assoc($result)) {
        $am = $row["am"];
        set_pre_apousies($valuearray[$am]["date"], $valuearray[$am]["apous"], $valuearray[$am]["daysp"], $valuearray[$am]["dik"], $valuearray[$am]["fh"], $valuearray[$am]["mh"], $valuearray[$am]["lh"], $valuearray[$am]["oa"], $valuearray[$am]["da"], $parent, $am);
    }

    if ($num)
        $_SESSION["havechanges"] = true;
}//if isset($_POST["save"])
//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

//$query =  "SELECT  `am` FROM `students` LEFT JOIN `apousies` ON (`students`.`user`= `apousies`.`user` AND `students`.`tmima`= `apousies`.`tmima` AND `students`.`am` = `apousies`.`student_am`) WHERE `students`.`user` = '$parent' AND `students`.`tmima`= '$tmima' ORDER BY `epitheto`,`onoma` ASC ;";

$query = "SELECT `am` FROM `students`  JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `student_am`) WHERE `students`.`user` = '$parent' and  `studentstmimata`.`tmima` = '$tmima' ORDER BY `epitheto`,`onoma` ASC ;";

$result = mysqli_query($link, $query);

if (!$result) {
    $errorText = mysqli_error($link);
    echo "4 $errorText<hr>";
}

$num = mysqli_num_rows($result);

$amarray = array(); //τον χρειάζομαι πιο κάτω

while ($row = mysqli_fetch_assoc($result)) {
    $kod = $row["am"];
    $amarray[] = $kod; //τον χρειάζομαι πιο κάτω
}

//$query1 =  "SELECT  min(`date`) as mindate ,`student_am` FROM `apousies`  WHERE `user` = '$parent' AND `tmima`= '$tmima' GROUP BY `student_am`;";
$query1 = "SELECT  min(`mydate`) as mindate ,`apousies`.`student_am` FROM `apousies`  
LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am`  
JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`)
where `apousies`.`user` ='$parent' and `studentstmimata`.`tmima` = '$tmima'  
GROUP BY `apousies`.`student_am`;";

$result1 = mysqli_query($link, $query1);

if (!$result1) {
    $errorText = mysqli_error($link);
    echo "5 $errorText<hr>";
}

$num = mysqli_num_rows($result1);

$firstdayarray = array(); //τον χρειάζομαι πιο κάτω

    while ($row1 = mysqli_fetch_assoc($result1)) {

    $kod = $row1["student_am"];
    $firstdayarray[$kod] = substr($row1["mindate"], 0, 4) . "/" . substr($row1["mindate"], 4, 2) . "/" . substr($row1["mindate"], 6, 2); //τον χρειάζομαι πιο κάτω
}


mysqli_close($link);



require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty;

$extra_style = '
<style type="text/css">
	input[type=text], input.text {margin:0;padding:0;border-color:lightgrey;width:97%;}
	table {table-layout:fixed;width:100%; }
	th,td {text-align: center; vertical-align:middle;padding: 0;overflow:hidden;white-space:nowrap;}
	th {border-color:#ddd;border-style:solid; border-width:1px;}
</style>
';

$extra_javascript = '
<script type="text/javascript" src="js/apousies_pre.js"></script>
<script type="text/javascript">
// <!--
var myStudentsAm=new Array();
';

for ($i = 0; $i < count($amarray); $i++) {
    $extra_javascript .= "myStudentsAm[$i]='$amarray[$i]';\n";
}

$extra_javascript .= '
var myfirstdayarray = new Array();
';

foreach ($firstdayarray as $key => $value) {
    $extra_javascript .= "myfirstdayarray['$key']='$value';\n";
}

$extra_javascript .= '
var apous_def_array = new Array();
';

foreach ($apousies_define as $key => $value) {
    $extra_javascript .= "apous_def_array[$key]= 'ap" . $value['kod'] . "';\n";
}

$extra_javascript .= '
var dik_def_array = new Array();
';

foreach ($dikaiologisi_define as $key => $value) {
    $extra_javascript .= "dik_def_array[$key]= 'di" . $value['kod'] . "';\n";
}

$extra_javascript .= '
// -->
</script>
';


$smarty->assign('title', 'ΕΙΣΑΓΩΓΗ ΠΟΛΛΩΝ ΑΠΟΥΣΙΩΝ ΜΕΧΡΙ ΜΙΑ ΗΜΕΡΑ');
$smarty->assign('extra_style', $extra_style);
$smarty->assign('extra_javascript', $extra_javascript);
$smarty->assign('h1_title', 'Προϋπάρχουσες απουσίες');
$smarty->assign ( 'body_attributes', '' );
$smarty->assign('tmima', $tmima);


//φορτώνω τα ονόματα και τους am για τη φόρμα
//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

//ελέγχω αν υπάρχει το νέο τμήμα	
$query = "SELECT * FROM `students`  JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `student_am`) WHERE `students`.`user` = '$parent' and  `studentstmimata`.`tmima` = '$tmima' ORDER BY `epitheto`,`onoma` ASC ;";

$result0 = mysqli_query($link, $query);
$num = mysqli_num_rows($result0);
mysqli_close($link);

$studentsdata = array();
$i = 0;
while ($row0 = mysqli_fetch_assoc($result0)) {
    $epitheto = $row0["epitheto"];
    $onoma = $row0["onoma"];
    $am = $row0["am"];
    $x = $i + 1;

    $pre_apousies = get_pre_apousies($parent, $am);

//    print_r($pre_apousies);
//    echo "<hr>";

    $apous = array();
    $apou_count = count($apousies_define);
    for ($y = 0; $y < $apou_count; $y++) {
        $kod = "ap" . $apousies_define[$y]["kod"];
        $pre_apousies[$kod] == 0 ? $apous[$y] = "" : $apous[$y] = $pre_apousies[$kod];
    }

//    print_r($apous);
//    echo "<hr>";

    $dik = array();
    $dik_count = count($dikaiologisi_define);
    for ($y = 0; $y < $dik_count; $y++) {
        $kod = "di" . $dikaiologisi_define[$y]["kod"];
        $pre_apousies[$kod] == 0 ? $dik[$y] = "" : $dik[$y] = $pre_apousies[$kod];
    }
//    print_r($dik);
//    echo "<hr>";


    $pre_apousies["fh"] == 0 ? $fh = "" : $fh = $pre_apousies["fh"];
    $pre_apousies["mh"] == 0 ? $mh = "" : $mh = $pre_apousies["mh"];
    $pre_apousies["lh"] == 0 ? $lh = "" : $lh = $pre_apousies["lh"];
    $pre_apousies["oa"] == 0 ? $oa = "" : $oa = $pre_apousies["oa"];
    $pre_apousies["da"] == 0 ? $da = "" : $da = $pre_apousies["da"];
    $pre_apousies["daysp"] == 0 ? $daysp = "" : $daysp = $pre_apousies["daysp"];
    $pre_apousies["date"] ? $date = $pre_apousies["date"] : $date = "";


    $studentsdata[$i]['x'] = $x;
    $studentsdata[$i]['am'] = $am;
    $studentsdata[$i]['epitheto'] = $epitheto;
    $studentsdata[$i]['onoma'] = $onoma;
    $studentsdata[$i]['onoma'] = $onoma;

    $studentsdata[$i]['apous'] = $apous;

    $studentsdata[$i]['fh'] = $fh;
    $studentsdata[$i]['mh'] = $mh;
    $studentsdata[$i]['lh'] = $lh;
    $studentsdata[$i]['oa'] = $oa;
    $studentsdata[$i]['da'] = $da;
    $studentsdata[$i]['dik'] = $dik;
    $studentsdata[$i]['daysp'] = $daysp;
    $studentsdata[$i]['date'] = $date;
    $i++;
}


//print_r($studentsdata);
//echo "<hr>";

$smarty->assign('studentsdata', $studentsdata);
$smarty->assign('apou_count', $apou_count);
$smarty->assign('dik_count', $dik_count);
$smarty->assign('apou_define', $apousies_define);
$smarty->assign('dik_define', $dikaiologisi_define);

$smarty->display('apousies_pre.tpl');
