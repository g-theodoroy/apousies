<?php

require_once('common.php');
checkUser();
//checktmima();

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima = '';
isset($_POST['tmima']) ? $newtmima = $_POST['tmima'] : $newtmima = $tmima;


if (isset($_POST["oldamget"])){
    $oldamget = $_POST["oldamget"];
} else {
    $oldamget = "";
}
    if (isset($_GET['am'])) {
    $amget = $_GET['am'];
} else {
    $amget = "";
}

$valuearray = array();

//φτιάχνω πίνακα valluearray με τα στοιχεία που έστειλε η φόρμα
foreach ($_POST as $key => $value) {
    if ($value != "ΕΙΣΑΓΩΓΗ")
        $valuearray[$key] = $value;
//echo  "$key --> $value <br>";
}


//echo "<hr>"; print_r($valuearray) ; echo "<hr>";
//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");


if (isset($_POST["am"]) && trim($_POST["am"] != "")) {
    /*
      array_shift($valuearray); //έξω το πρώτο στοιχείο tmima
      array_shift($valuearray); //έξω το 2 στοιχείο selstudent
      array_shift($valuearray); //έξω το 3 στοιχείο oldamget
      array_pop($valuearray); //έξω το τελευταίο button submit
      //echo "<hr>"; print_r($valuearray) ; echo "<hr>";
     */
    begin();
    $errorcheck = false;

    $query = "UPDATE `students` SET `am`='" . $valuearray["am"] . "',`epitheto`= '" . $valuearray["epitheto"] . "', `onoma`='" . $valuearray["onoma"] . "', `patronimo`='" . $valuearray["patronimo"] . "', `ep_kidemona`= '" . $valuearray["ep_kidemona"] . "', `on_kidemona`= '" . $valuearray["on_kidemona"] . "', `dieythinsi`='" . $valuearray["dieythinsi"] . " ', `tk`='" . $valuearray["tk"] . "', `poli`='" . $valuearray["poli"] . "', `til1`='" . $valuearray["til1"] . "' ,`til2`='" . $valuearray["til2"] . "' ,`email`='" . $valuearray["email"] . "', `filo`='" . $valuearray["gender"] . "' WHERE `user` = '$parent' AND `am`= '$oldamget';";
    $result = mysqli_query($link,$query);
    if (!$result) {
        $errorText = mysqli_error($link);
        echo "0 $errorText<hr>";
        $errorcheck = true;
    }

    //ενημερώνω τα τμηματα και τους μαθητές
     $query = "DELETE FROM `studentstmimata` WHERE `user` = '$parent' AND `student_am`= '$oldamget';";
    $result = mysqli_query($link,$query);
    if (!$result) {
        $errorText = mysqli_error($link);
        echo "1 $errorText<hr>";
    }
    for ($i = 0; $i < count($apousies_define); $i++) {
        $kod = $apousies_define[$i]["kod"];
        if (isset($valuearray[$kod]) && $valuearray[$kod] != "") {
            $newtmima = $valuearray[$kod];
            $query = "INSERT INTO `studentstmimata` (`user`, `student_am`, `tmima`) VALUES ('$parent', '" . $valuearray['am'] . "', '$newtmima' );";
            $result = mysqli_query($link,$query);
            if (!$result) {
                $errorText = mysqli_error($link);
                echo "2 $errorText<hr>";
            }
        }
    }

    //ενημερώνω και τον am στις απουσίες
    $query = "UPDATE `apousies` SET `student_am`='" . $valuearray["am"] . "' WHERE `user` = '$parent' AND `student_am`= '$oldamget';";
    $result = mysqli_query($link,$query);
    if (!$result) {
        $errorText = mysqli_error($link);
        echo "3 $errorText<hr>";
        $errorcheck = true;
    }
    //ενημερώνω και τον am στις απουσίες_pre
    $query = "UPDATE `apousies_pre` SET `student_am`='" . $valuearray["am"] . "' WHERE `user` = '$parent' AND `student_am`= '$oldamget';";
    $result = mysqli_query($link,$query);
    if (!$result) {
        $errorText = mysqli_error($link);
        echo "3 $errorText<hr>";
        $errorcheck = true;
    }
    //ενημερώνω και τον am στο ιστορικό ειδοποιητηρίων
    $query = "UPDATE `paperhistory` SET `am`='" . $valuearray["am"] . "' WHERE `user` = '$parent' AND `am`= '$oldamget';";
    $result = mysqli_query($link,$query);
    if (!$result) {
        $errorText = mysqli_error($link);
        echo "4 $errorText<hr>";
        $errorcheck = true;
    }

    if ($errorcheck == true) {
        rollback();
    } else {
        commit();
        $_SESSION["havechanges"] = true;
    }
}

if ($amget && $oldamget) {
    header('Location: students.php');
}


$smarty = new Smarty;

$extra_style = '
<style type="text/css">
	td, th {vertical-align:middle;border:none;}
</style>
';

$extra_javascript = '
 <script language="javascript" src="js/editstudent.js"></script>
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
    $errorText = mysqli_error($link);
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


$smarty->assign('title', 'ΕΠΕΞΕΡΓΑΣΙΑ ΜΑθΗΤΩΝ');
$smarty->assign('extra_style', $extra_style);
$smarty->assign('extra_javascript', $extra_javascript);
$smarty->assign('h1_title', 'Επεξεργασία');
$smarty->assign('maxam', $maxam);

$amget ? $body_attributes = "onload=\"document.getElementById('oldamget').value=$amget ; showHint($amget)\"" : $body_attributes = "";
$smarty->assign('body_attributes', $body_attributes);

//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");
if ($tmima) {
    $query = "SELECT  `am`,`epitheto`, `onoma` FROM `students`   JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `student_am`) WHERE `students`.`user` = '$parent' and  `studentstmimata`.`tmima` = '$tmima' ORDER BY `epitheto`, `onoma` ASC; ";
} else {
    $query = "SELECT  `am`,`epitheto`, `onoma` FROM `students`  WHERE `user` = '$parent' ORDER BY `epitheto`, `onoma` ASC; ";
}
$result = mysqli_query($link,$query);
if (!$result) {
    $errorText = mysqli_error($link);
    echo "3 $errorText<hr>";
}
$num = mysqli_num_rows($result);

mysqli_close($link);

$students = array();

while ($row = mysqli_fetch_assoc($result)) {
    $am = $row["am"];
    $epitheto = $row["epitheto"];
    $onoma = $row["onoma"];
    $students[] = "<option value=\"$am\" >$epitheto $onoma</option>";
}

$smarty->assign('students', $students);
$smarty->display('editstudent.tpl');


