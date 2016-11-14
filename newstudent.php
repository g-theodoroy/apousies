<?php

require_once('common.php');
checkUser();
checkParent();
//checktmima();

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima = '';

$valuearray = array();

//φτιάχνω πίνακα valluearray με τα στοιχεία που έστειλε η φόρμα
foreach ($_POST as $key => $value) {
    if ($value != "ΕΙΣΑΓΩΓΗ")
        $valuearray[$key] = $value;
//echo  "$key --> $value <hr>";
}


//echo "<hr>"; print_r($valuearray) ; echo "<hr>";
//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");


if (isset($_POST["am"]) && trim($_POST["am"] != "")) {

//	array_shift($valuearray);//έξω το πρώτο στοιχείο tmima
    array_pop($valuearray); //έξω το τελευταίο button submit

    $query = "INSERT INTO `students` (`am` ,`epitheto` ,`onoma` ,`patronimo` ,`ep_kidemona` ,`on_kidemona` ,`dieythinsi` ,`tk` ,`poli` ,`til1` ,`til2`, `email` ,`filo` ,`user` ) VALUES ( '" . $valuearray['am'] . "', '" . $valuearray['epitheto'] . "', '" . $valuearray['onoma'] . "', '" . $valuearray['patronimo'] . "', '" . $valuearray['epitheto_kidemona'] . "', '" . $valuearray['onoma_kidemona'] . "', '" . $valuearray['dieythinsi'] . "', '" . $valuearray['tk'] . "', '" . $valuearray['poli'] . "', '" . $valuearray['til1'] . "', '" . $valuearray['til2'] . "', '" . $valuearray['email'] . "', '" . $valuearray['gender'] . "', '" . $parent . "' );";
    $result = mysqli_query($link,$query);
    if (!$result) {
        $errorText = mysqli_error($link);
        echo "1 $errorText<hr>";
    }


    for ($i = 0; $i < count($apousies_define); $i++) {
        $kod = $apousies_define[$i]["kod"];
        if (isset($valuearray[$kod]) && $valuearray[$kod]!= "") {
            $newtmima = $valuearray[$kod];
            $query = "INSERT INTO `studentstmimata` (`user`, `student_am`, `tmima`) VALUES ('$parent', '" . $valuearray['am'] . "', '$newtmima' );";
            $result = mysqli_query($link,$query);
            if (!$result) {
                $errorText =  mysqli_error($link);
                echo "2 $errorText<hr>";
            }
       }
    }


    $_SESSION["havechanges"] = true;
}


require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty;

$extra_style = '
<style type="text/css">
	td, th {vertical-align:middle;border:none;}
</style>
';

$extra_javascript = '
 <script language="javascript" src="js/newstudent.js"></script>
 <script language="javascript" type="text/javascript">
  // <!--
var pinakas = new Array();
';

//φορτώνω τους am που είναι ήδη καταχωρημένοι
//σε ένα πίνακα στη javascript για να κάνω έλεγχο
//μοναδικότητας πριν στείλω στο server τα στοιχεία

$query = "SELECT `am` FROM `students` WHERE `user` = '$parent';";
$result = mysqli_query($link,$query);
if (!$result) {
    $errorText =  mysqli_error($link);
}
$num = mysqli_num_rows($result);

$maxam = 0;

$i = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $extra_javascript .= "pinakas[$i] = " . $row["am"] . ";\n";
    $i++;
    //βρίσκω το μεγαλύτερο am και προσθέτω 1
    if ($maxam < $row["am"])
        $maxam = $row["am"];
}

$maxam++;

$extra_javascript .= '
 // -->
  </script>
';


$smarty->assign('title', 'ΕΙΣΑΓΩΓΗ ΜΑθΗΤΩΝ');
$smarty->assign('extra_style', $extra_style);
$smarty->assign('extra_javascript', $extra_javascript);
$smarty->assign('h1_title', 'Εισαγωγή');
$smarty->assign('body_attributes', '');
$smarty->assign('maxam', $maxam);



$query = "SELECT `tmima`,`type` FROM `tmimata` WHERE `username` = '$parent'  ORDER BY `tmima`;";

$result = mysqli_query($link,$query);

if (!$result) {
    $errorText =  mysqli_error($link);
}

$num = mysqli_num_rows($result);
$tmimata_select_boxes = array();

for ($x = 0; $x < count($apousies_define); $x++) {
	$tmimata_select_boxes[$x] = "<select name='" . $apousies_define[$x]["kod"] . "' >\n<option value='' ><option>\n";
}

if ($num) {
	while ($row = mysqli_fetch_assoc($result)) {
	    for ($x = 0; $x < count($apousies_define); $x++) {
            if ($row["type"] == $apousies_define[$x]["kod"]) {
                if ($row["tmima"] == $tmima) {
                    $tmimata_select_boxes[$x] .= '<option value="' . $row["tmima"] . '" selected>' . $row["tmima"] . '</option>\n';
                } else {
                    $tmimata_select_boxes[$x] .= '<option value="' . $row["tmima"] . '" >' . $row["tmima"] . '</option>\n';
                }
            }
        }
    }
}
for ($x = 0; $x < count($apousies_define); $x++) {
	$tmimata_select_boxes[$x] .= "</select>";
}


mysqli_close($link);

$loopcount = count($apousies_define)-1;

$smarty->assign('tmimata_select_boxes', $tmimata_select_boxes);
$smarty->assign('tmimata_define', $apousies_define);
$smarty->assign('loopcount', $loopcount);

$smarty->display('newstudent.tpl');
