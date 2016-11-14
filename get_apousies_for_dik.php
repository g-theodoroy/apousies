<?php

require_once('common.php');
isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima = '';
isset($_GET['am']) ? $amget = $_GET['am'] : $amget = '';
isset($_GET['oa']) ? $mychecknum = $_GET['oa'] : $mychecknum = 1;
isset($_GET['bt']) ? $bt = $_GET['bt'] : $bt = -2;


$mycheckdate = add_months(date('Y-m-d'), $bt);

$apou_count = count($apousies_define);
$dik_count = count($dikaiologisi_define);

if ($bt == 0) {
    $mycheckdate_contition = '';
} else {
    $mycheckdate_contition = "AND `mydate` > $mycheckdate";
}

//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$query = "SELECT COUNT(`from`) AS `daysk` from `apousies` WHERE `student_am` = '$amget' AND `user`= '$parent' AND `from` ='" . $dikaiologisi_define[0]['kod'] . "' ;";


$result = mysqli_query($link, $query);
if (!$result) {
    $errorText = mysqli_error($link);
    echo "0 $errorText<hr>";
}
$row = mysqli_fetch_assoc($result);
$daysk = $row["daysk"];

$apous_sum = "";
        for ($x = 0; $x < $apou_count ; $x++) {
            $k = $x + 1;
            $apous_sum .="MID(`apous`,$k,1)+";
        }
$apous_sum = substr($apous_sum,0,  strlen($apous_sum)-1) ;


$query = "SELECT `mydate`, `apous` ,`dik`,`from` ,`fh` ,`mh` ,`lh` ,`oa` ,`da` from `apousies` WHERE `student_am` = '$amget' AND `user`= '$parent' and $apous_sum > $mychecknum $mycheckdate_contition ORDER BY `mydate` DESC;";

$result = mysqli_query($link, $query);
if (!$result) {
    $errorText = mysqli_error($link);
    echo "1 $errorText<hr>";
}

$num = mysqli_num_rows($result);

$dik_columns = $dik_count + 1;

if ($num) {
    $getapousiesfordik = '
<table>
<tr>
<th rowspan = 2 >ΗΜΝΙΑ</th>
<th colspan = ' . $apou_count . ' >ΑΠΟΥΣΙΕΣ</th>
<th rowspan = 2 >ΕΠΙΛΟΓΗ<br>ΕΓΓΡΑΦΗΣ<br>ΓΙΑ ΕΝΗΜΕΡΩΣΗ</th>
<th colspan = ' . $dik_columns . ' >ΔΙΚΑΙΟΛΟΓΗΜΕΝΕΣ</th>
</tr>
<tr>';
    foreach ($apousies_define as $key => $value) {
        $getapousiesfordik .= '<th>' . $value['label'] . '</th>';
    }
    $getapousiesfordik .= '<th>ΑΠΟΥΣΙΕΣ</th>';
    foreach ($dikaiologisi_define as $key => $value) {
        $getapousiesfordik .= '<th>' . $value['label'] . '</th>';
    }
    $getapousiesfordik .= '</tr>
';

    while ($row = mysqli_fetch_assoc($result)) {
        $getapousiesfordik .= "<tr>\n";

        $mydate = $row["mydate"];
        $inputdate = "<input type='hidden' value='$mydate' name='mydate$mydate' id='mydate$mydate' />";
        $formateddate = substr($mydate, 6, 2) . "/" . substr($mydate, 4, 2) . "/" . substr($mydate, 0, 4);
        $getapousiesfordik .= "<th>$formateddate $inputdate</th>\n";

        $apous = (int) $row["apous"] > 0 ? $row["apous"] : "";

        $sumap = 0;
        for ($x = 0; $x < $apou_count; $x++) {
            $data = (int) substr($apous, $x, 1) > 0 ? (int) substr($apous, $x, 1) : '&nbsp';
            $getapousiesfordik .= '<th>' . $data . '</th>';
            $sumap += (int) substr($apous, $x, 1);
        }


        $inputsumap = "<input type='hidden' value='$sumap' name='sumap$mydate' id='sumap$mydate' />";

        $getapousiesfordik .= "<td ><input type='checkbox' name='chk$mydate' id='chk$mydate' /></td>\n";

        $dik = $row["dik"] > 0 ? $row["dik"] : "";
        $from = $row["from"];


        $checked_array = array();
        $class_array = array();
        for ($x = 0; $x < $dik_count; $x++) {
            $checked_array[$x] = '';
        }

        $dikvalue = $dik;
        if ($from == '') {
            $dikclass = "class='white' ";
        } else {
            for ($x = 0; $x < $dik_count; $x++) {
                if ($from == $dikaiologisi_define[$x]['kod']) {
                    $dikclass = "class='" . $dikaiologisi_define[$x]['color'] . "' ";
                    $checked_array[$x] = 'checked';
                }
            }
        }


        $dikchange = "onchange=\"return change_things(this)\" ";

        $inputdik = "<input value='$dikvalue' $dikclass name='dik$mydate' id='dik$mydate' $dikchange />";
        $getapousiesfordik .= "<td >$inputsumap $inputdik</td>\n";

        $click_array = array();
        for ($x = 0; $x < $dik_count; $x++) {
            $class = $dikaiologisi_define[$x]['color'];
            $click_array[$x] = "onclick=\"document.getElementById('dik$mydate').value = document.getElementById('sumap$mydate').value ; document.getElementById('dik$mydate').className ='$class' ; document.getElementById('chk$mydate').checked = true \" ";
        }

        for ($x = 0; $x < $dik_count; $x++) {
            $class = $dikaiologisi_define[$x]['color'];
            $kod = $dikaiologisi_define[$x]['kod'];
            $checked = $checked_array[$x];
            $click = $click_array[$x];
            $getapousiesfordik .= "<td class='$class' ><input type='radio' name='diktype$mydate' id='{$x}diktype$mydate' value='$kod' $checked $click /></td>\n";
        }


        $getapousiesfordik .= "</tr>\n";
    }

    $getapousiesfordik .= "</table>\n";
} else {
    $getapousiesfordik = "<h3>Δεν υπάρχουν δεδομένα να ικανοποιούν τα κριτήρια.</h3>";
}

$getapousiesfordik .= "<input type='hidden' value='$daysk' name='daysk' id='daysk' />";

echo $getapousiesfordik;
?>
