<?php

require_once('common.php');
checkUser();
//checkParent();
//checktmima();

$user = $_SESSION['userName'];
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';

if ($user !== $parent)checktmima();

if (isset($_SESSION['tmima'])) $tmima = $_SESSION['tmima'];
//παίρνω το τμήμα που επιλέχτηκε


  foreach($_POST as $key => $value){
  $valuearray[$key]=$value;
  //echo "$key = $value<hr>";
  }


//βρίσκω τις στήλες των τμημάτων
$columns = count($apousies_define);

$errorText = '';
$check = '';
$warning = '';
$numentries = '';

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
        $objWorksheet = $objPHPExcel->getActiveSheet();

        $highestRow = $objWorksheet->getHighestRow(); // e.g. 10
//$highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'

        $highestColumnIndex = 12; //PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5



        $arr_query = array();
        $am2save = '0';

        for ($row = $valuearray["firstrow"]; $row <= $highestRow; ++$row) {
            $am = $objWorksheet->getCellByColumnAndRow($valuearray["am"]-1, $row)->getValue();
            if ($am && $am !== $am2save)$am2save = $am;
            $xlsdate = $objWorksheet->getCellByColumnAndRow($valuearray["date"]-1, $row)->getValue();
            $apous = $objWorksheet->getCellByColumnAndRow($valuearray["ap"]-1, $row)->getValue();
            if ($xlsdate && $apous > 7) {
                $errorText = "<b>Έλεγχος αρχείου xls</b><br><br>Οι απουσίες δεν μπορούν να υπερβαίνουν<br><br>τις <b>7</b> για κάθε ημέρα.<br><br>
                Στη γραμμή $row του xls καταχωρίστηκαν $apous<br><br>
                Κάντε έλεγχο στο xls αρχείο εισαγωγής.";
                break;
            }
            for($i = 1; $i < count ( $apousies_define ); $i ++) {
                $apous .= '0';
            }
            $dik = $objWorksheet->getCellByColumnAndRow($valuearray["dik"]-1, $row)->getValue();
            $from = $objWorksheet->getCellByColumnAndRow($valuearray["from"]-1, $row)->getValue();
            switch ($from) {
                case "ΚΗΔΕΜΟΝΑΣ":
                    $from = "P";
                    break;
                case "ΓΙΑΤΡΟΣ":
                    $from = "D";
                    break;
                case "ΔΙΕΥΘΥΝΤΗΣ":
                    $from = "M";
                    break;
            }
            if($xlsdate){
            $date2save = \PHPExcel_Style_NumberFormat::toFormattedString($xlsdate, 'YYYYMMDD');
            $query = "REPLACE  INTO `apousies` (`mydate` ,`apous`,`dik` ,`from` ,`fh` ,`mh` ,`lh` ,`oa` ,`da` ,`user` ,`student_am`) VALUES ('$date2save','$apous','$dik', '$from','$fh','$mh','$lh', '$oa', '$da', '$parent', '$am2save');"; 
            //echo $am2save . ", " . $date2save . ", " . $apous . ", " . $dik . ", " . $from  . "<hr>" ; 
            //echo $query . "<hr>" ; 
            $arr_query[] = $query;
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
            if ($tmima){
                $query = "DELETE `apousies` FROM `apousies` join  
                    (SELECT `students`.* FROM `students` join `studentstmimata`
                    on `students`.`am` = `studentstmimata`.`student_am`
                    WHERE `tmima` = '$tmima' and  `students`.`user` = '$parent') as `T1`
                    on `apousies`.`student_am` = `T1`.`am` ;";
            }else{
                $query = "DELETE FROM `apousies` WHERE `user` = '$parent';";
            }
            
            
            $result = mysqli_query($link, $query);

            if (!$result) {
                $errorcheck = true;
            }

            
            $numentries = 0;

            for ($i = 0; $i < count($arr_query); $i++) {
                $numentries++;
                $result = mysqli_query($link, $arr_query[$i]);
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
var tmima ="' . $tmima . '";
var answer;

if (! document.frm.file.value ){alert("Επιλέξτε ένα αρχείο παρακαλώ."); return false;}
if (! document.frm.am.value ){alert("Συμπληρώστε τη στήλη του Αρ. Μητρώου."); return false;}
if (! document.frm.date.value ){alert("Συμπληρώστε τη στήλη στην Ημ/νία."); return false;}
if (! document.frm.ap.value ){alert("Συμπληρώστε τη στήλη στο Σύνολο απουσιών."); return false;}
if (! document.frm.dik.value ){alert("Συμπληρώστε τη στήλη στις Δικαιολογημένες."); return false;}
if (! document.frm.from.value ){alert("Συμπληρώστε τη στήλη του Δικαιολογήθηκε από."); return false;}
if (! document.frm.firstrow.value ){alert("Συμπληρώστε τη πρώτη γραμμή με δεδομένα."); return false;}
if (tmima ){
answer=confirm("ΠΡΟΣΟΧΗ !!!\n\nΤα δεδομένα που εισάγετε\nθα αντικαταστήσουν ΟΛΕΣ τις απουσίες\nτων μαθητών του τμήματος " + tmima + "\nτου χρήστη " + user + " .\n\nΕπιβεβαιώστε παρακαλώ.");
}else{
answer=confirm("ΠΡΟΣΟΧΗ !!!\n\nΤα δεδομένα που εισάγετε\nθα αντικαταστήσουν ΟΛΕΣ τις απουσίες\nτων μαθητών ΟΛΩΝ των τμημάτων\nτου χρήστη " + user + ".\n\nΕπιβεβαιώστε παρακαλώ.");
}
if (answer == false){return false;}
return true;
}

function checkmyvaluenum(element){
    if (element.value){
        if(!(!isNaN(element.value) && parseInt(Number(element.value)) == element.value && !isNaN(parseInt(element.value, 10)))){
            alert("Πληκτρολογείστε μόνο ακέραιους αριθμούς")
            element.value = ""
        }
    }
}
// -->
</script>
';


$smarty->assign('title', 'ΕΙΣΑΓΩΓΗ ΑΠΟΥΣΙΩΝ ΑΠΟ MYSCHOOL');
$smarty->assign('extra_javascript', $extra_javascript);
$smarty->assign('h1_title', 'Εισαγωγή απουσιών από myschool');
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
$smarty->assign('numentries', $numentries);

$smarty->display('importmyschapousies.tpl');
