<?php

require_once('common.php');
checkUser();
checkParent();
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
        $tmimata2save = array();
        $am2save = '0';

        for ($row = $valuearray["firstrow"]; $row <= $highestRow; ++$row) {
            $am = $objWorksheet->getCellByColumnAndRow($valuearray["am"]-1, $row)->getValue();
            $epitheto = $objWorksheet->getCellByColumnAndRow($valuearray["epitheto"]-1, $row)->getValue();
            $onoma = $objWorksheet->getCellByColumnAndRow($valuearray["onoma"]-1, $row)->getValue();
            $patronimo = $objWorksheet->getCellByColumnAndRow($valuearray["patronimo"]-1, $row)->getValue();
            $ep_kidemona = $objWorksheet->getCellByColumnAndRow($valuearray["ep_kidemona"]-1, $row)->getValue();
            $on_kidemona = $objWorksheet->getCellByColumnAndRow($valuearray["on_kidemona"]-1, $row)->getValue();
            $dieythinsi = $objWorksheet->getCellByColumnAndRow($valuearray["dieythinsi"]-1, $row)->getValue();
            $tk = $objWorksheet->getCellByColumnAndRow($valuearray["tk"]-1, $row)->getValue();
            $poli = $objWorksheet->getCellByColumnAndRow($valuearray["poli"]-1, $row)->getValue();
            $til1 = $objWorksheet->getCellByColumnAndRow($valuearray["til1"]-1, $row)->getValue();
            $til2 = $objWorksheet->getCellByColumnAndRow($valuearray["til2"]-1, $row)->getValue();
            $email = $objWorksheet->getCellByColumnAndRow($valuearray["email"]-1, $row)->getValue();
            $filo = $objWorksheet->getCellByColumnAndRow($valuearray["filo"]-1, $row)->getValue();
            $tmima2save = $objWorksheet->getCellByColumnAndRow($valuearray["tmima"]-1, $row)->getValue();
            
            if ($am && intval($am)){
                $query = "INSERT INTO `students` (  `am`, `epitheto`, `onoma`, `patronimo`,`ep_kidemona`, `on_kidemona`, `dieythinsi`, `tk`, `poli`, `til1`, `til2`, `email`, `filo` , `user` ) VALUES ('$am','$epitheto','$onoma', '$patronimo', '$ep_kidemona', '$on_kidemona', '$dieythinsi', '$tk', '$poli', '$til1', '$til2', '$email', '$filo', '$parent');"; 
                //echo $query . "<hr>" ; 
                $arr_query[] = $query;
                if ($tmima2save){
                    $query = "INSERT INTO `studentstmimata` (`student_am`, `tmima` , `user`) VALUES  ('$am', '$tmima2save', '$parent');"; 
                    //echo $query . "<hr>" ; 
                    $arr_query[] = $query;
                }
                if ( ! in_array($tmima2save, $tmimata2save)){
                    $tmimata2save[] = $tmima2save;
                    $query = "INSERT INTO `tmimata` (`username`, `tmima`, `lastselect`, `type`) VALUES  ('$parent', '$tmima2save', '', 'g');"; 
                    //echo $query . "<hr>" ; 
                    $arr_query[] = $query;
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
            $del_query = array();

            //διαγραφη και εισαγωγη μαθητων
            if ($tmima){
                $query = "DELETE `students` FROM `students` join `studentstmimata`
                        on `students`.am = `studentstmimata`.`student_am`
                        WHERE `students`.`user` = '$parent' AND `studentstmimata`.`tmima` = '$tmima' ;";
                $del_query[] = $query;        
                $query = "DELETE  FROM `studentstmimata` WHERE `user` = '$parent' AND `tmima` = '$tmima' ;";
                $del_query[] = $query;        
                //$query = "DELETE `tmimata` FROM `tmimata` join `users`
                //        on `tmimata`.`username` = `users`.`username`
                //        WHERE `groupname` = '$parent'  AND `tmima` = '$tmima' ;";
                $query = "DELETE FROM `tmimata` 
                        WHERE `username` = '$parent'  AND `tmima` = '$tmima' ;";
                $del_query[] = $query;        
            }else{
                $query = "DELETE  FROM `students` WHERE `user` = '$parent' ;";
                $del_query[] = $query;        
                $query = "DELETE  FROM `studentstmimata` WHERE `user` = '$parent' ;";
                $del_query[] = $query;        
                //$query = "DELETE `tmimata` FROM `tmimata` join `users`
                //        on `tmimata`.`username` = `users`.`username`
                //        WHERE `groupname` = '$parent';";
                $query = "DELETE FROM `tmimata` WHERE `username` = '$parent' ;";
                $del_query[] = $query;        
            }
            
            
            for ($i = 0; $i < count($del_query); $i++) {
                $numentries++;
                $result = mysqli_query($link, $del_query[$i]);
                if (!$result) {
                    $errorcheck = true;
                }
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
if (! document.frm.epitheto.value ){alert("Συμπληρώστε τη στήλη στο Επώνυμο μαθητή."); return false;}
if (! document.frm.onoma.value ){alert("Συμπληρώστε τη στήλη στο Όνομα μαθητή."); return false;}
if (! document.frm.patronimo.value ){alert("Συμπληρώστε τη στήλη στο Πατρώνυμο."); return false;}
if (! document.frm.ep_kidemona.value ){alert("Συμπληρώστε τη στήλη στο Επώνυμο Κηδεμόνα."); return false;}
if (! document.frm.on_kidemona.value ){alert("Συμπληρώστε τη στήλη στο Επώνυμο Κηδεμόνα."); return false;}
if (! document.frm.dieythinsi.value ){alert("Συμπληρώστε τη στήλη στη Διεύθυνση."); return false;}
if (! document.frm.tk.value ){alert("Συμπληρώστε τη στήλη στον Τ.Κ.."); return false;}
if (! document.frm.poli.value ){alert("Συμπληρώστε τη στήλη στη Περιοχή."); return false;}
if (! document.frm.til1.value ){alert("Συμπληρώστε τη στήλη στο Τηλέφωνο 1."); return false;}
if (! document.frm.til2.value ){alert("Συμπληρώστε τη στήλη στο Κινητό τηλέφωνο."); return false;}
if (! document.frm.email.value ){alert("Συμπληρώστε τη στήλη στο eMail."); return false;}
if (! document.frm.filo.value ){alert("Συμπληρώστε τη στήλη στο Φύλο."); return false;}
if (! document.frm.tmima.value ){alert("Συμπληρώστε τη στήλη στο Τμήμα."); return false;}
if (! document.frm.firstrow.value ){alert("Συμπληρώστε τη πρώτη γραμμή με δεδομένα."); return false;}
if (tmima ){
answer=confirm("ΠΡΟΣΟΧΗ !!!\n\nΤα δεδομένα που εισάγετε\nθα αντικαταστήσουν ΟΛΟΥΣ τους μαθητές\n του τμήματος " + tmima + "\nτου χρήστη " + user + " .\n\nΕπιβεβαιώστε παρακαλώ.");
}else{
answer=confirm("ΠΡΟΣΟΧΗ !!!\n\nΤα δεδομένα που εισάγετε\nθα αντικαταστήσουν\nΟΛΟΥΣ τους μαθητές\nκαι ΟΛΑ τα τμήματα\nτου χρήστη " + user + ".\n\nΕπιβεβαιώστε παρακαλώ.");
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


$smarty->assign('title', 'ΕΙΣΑΓΩΓΗ ΜΑΘΗΤΩΝ ΑΠΟ MYSCHOOL');
$smarty->assign('extra_javascript', $extra_javascript);
$smarty->assign('h1_title', 'Εισαγωγή μαθητών από myschool');
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

$smarty->display('importmyschstudents.tpl');
