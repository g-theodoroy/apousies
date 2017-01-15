<?php

require_once('common.php');
checkUser();
checkParent();
//checktmima();

$user = $_SESSION['userName'];
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
if (isset($_SESSION['tmima']))
    $tmima = $_SESSION['tmima'];
//παίρνω το τμήμα που επιλέχτηκε

/*
  foreach($_POST as $key => $value){
  $valuearray[]=$value;
  echo "$key = $value<hr>";
  }
 */

//βρίσκω τις στήλες των τμημάτων
$columns = count($apousies_define);

$errorText = '';
$check = '';
$warning = '';
$numstudents = '';

if (isset($_POST['save'])) {

    if ($_FILES["file"]["error"] > 0) {
        echo "Λάθος: " . $_FILES["file"]["error"] . "<br />";
    } else {
        /*
          echo "Upload: " . $_FILES["file"]["name"] . "<br />";
          echo "Type: " . $_FILES["file"]["type"] . "<br />";
          echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
          echo "Stored in: " . $_FILES["file"]["tmp_name"] . "<br />" ;
          echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
         */

        move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);

//#################################READ XLSX ################################################
        /** PHPExcel * */


        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objReader->setReadDataOnly(true);

        $objPHPExcel = $objReader->load("upload/" . $_FILES["file"]["name"]);
        $objPHPExcel->setActiveSheetIndexByName('students');
        $objWorksheet = $objPHPExcel->getActiveSheet();

        $highestRow = $objWorksheet->getHighestRow(); // e.g. 10
//$highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'

        $highestColumnIndex = 12; //PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5



        $arr_query = array();
        $arr_stutmi_query = array();


        for ($row = 2; $row <= $highestRow; ++$row) {
            $query = "INSERT INTO `students` (  `am`, `epitheto`, `onoma`, `patronimo`,`ep_kidemona`, `on_kidemona`, `dieythinsi`, `tk`, `poli`, `til1`, `til2`, `email`, `filo` , `user` ) VALUES (";
            for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                $query .= "'" . str_replace("'", "", $objWorksheet->getCellByColumnAndRow($col, $row)->getValue()) . "', ";
            }
            $query .= "'$parent' );";
            $arr_query[] = $query;
//            echo $query . "<hr>";
            for ($col = $highestColumnIndex+1; $col <= $highestColumnIndex + $columns; ++$col) {
                if (str_replace("'", "", $objWorksheet->getCellByColumnAndRow($col, $row)->getValue()) != ''){
                    $studentstmimataquery = 'INSERT INTO `studentstmimata` (`student_am`, `tmima` , `user`) VALUES (';
                    $studentstmimataquery .= "'" . str_replace("'", "", $objWorksheet->getCellByColumnAndRow(0, $row)->getValue()) . "', ";
                    $studentstmimataquery .= "'" . str_replace("'", "", $objWorksheet->getCellByColumnAndRow($col, $row)->getValue()) . "', ";
                    $studentstmimataquery .= "'$parent' );";

                    $arr_stutmi_query[] = $studentstmimataquery;
//                    echo $studentstmimataquery . "<hr>";
                }
            }
        }

        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);

        unlink("upload/" . $_FILES["file"]["name"]);


        if (!$arr_query) {
            $errorText = "Το αρχείο δεν περιέχει κατάλληλα δεδομένα.";
        }

//συνδέομαι με τη βάση
        include ("includes/dbinfo.inc.php");

        if ($errorText == '') {

            begin();
            $errorcheck = false;

            //διαγραφη και εισαγωγη μαθητων
            $query = "DELETE  FROM `students` WHERE `user` = '$parent' ;";
            $result = mysqli_query($link, $query);

            if (!$result) {
                $errorcheck = true;
            }

            $numstudents = 0;

            for ($i = 0; $i < count($arr_query); $i++) {
                $numstudents++;
                $result = mysqli_query($link, $arr_query[$i]);
                if (!$result) {
                    $errorcheck = true;
                }
            }


            //διαγραφη και εισαγωγη τμημάτων μαθητων
            $query = "DELETE  FROM `studentstmimata` WHERE `user` = '$parent' ;";
            $result = mysqli_query($link, $query);

            if (!$result) {
                $errorcheck = true;
            }
            for ($i = 0; $i < count($arr_stutmi_query); $i++) {
                $result = mysqli_query($link, $arr_stutmi_query[$i]);
                if (!$result) {
                    $errorcheck = true;
                }
            }
        }

        if ($errorcheck == true) {
            rollback();
        } else {
            commit();
        }

        mysqli_close($link);
    }
}//if (isset($_POST['save']))



$smarty = new Smarty;

$extra_javascript = '<script type="text/javascript" language="javascript" >
// <!--

function check_submit(){
var user ="' . $parent . '";
var answer;

if (! document.frm.file.value ){alert("Επιλέξτε ένα αρχείο παρακαλώ!"); return false;}

answer=confirm("Τα δεδομένα που εισάγετε θα αντικαταστήσουν τους μαθητές του χρήστη " + user + ".\nΕπιβεβαιώστε παρακαλώ.");
if (answer == false){return false;}
return true;
}
// -->
</script>
';


$smarty->assign('title', 'ΕΙΣΑΓΩΓΗ ΜΑΘΗΤΩΝ ΑΠΟ XLS');
$smarty->assign('extra_javascript', $extra_javascript);
$smarty->assign('h1_title', 'Εισαγωγή μαθητών από xls');
$smarty->assign('extra_style', '');
$smarty->assign('body_attributes', '');

if (isset($_POST['save'])) {
    if ($errorcheck == false && $errorText == '' && $_FILES["file"]["error"] == 0) {
        $check = 1;
    } elseif ($_FILES["file"]["error"] > 0) {
        $check = 2;
    } elseif ($errorText !== '') {
        $check = 3;
    } elseif ($errorcheck == true) {
        $check = 4;
    }
}

//echo "check = $check <hr>";

if (sumapousies($parent, $tmima)) {
    $warning = true;
}


$smarty->assign('errorText', $errorText);
$smarty->assign('check', $check);
$smarty->assign('warning', $warning);
$smarty->assign('numstudents', $numstudents);

$smarty->display('importstudentsxls.tpl');
