<?php
require_once('common.php');
checkUser();
//checkParent();
//checktmima();

function getAm($epitheto, $onoma, $patronimo, $user){
        include ("includes/dbinfo.inc.php");
        $query = "SELECT `am` FROM `students`
            WHERE `user` = '$user' AND 
            `epitheto` = '$epitheto' AND 
            `onoma` = '$onoma' AND 
            `patronimo` = '$patronimo' ;";
        
        $result = mysqli_query($link, $query);
        if (!$result) return false;
        $row = mysqli_fetch_assoc($result);
        
        mysqli_close($link);
        
        return $row["am"];
}

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

        for ($row = $valuearray["firstrow"]; $row <= $highestRow; ++$row) {
            $epitheto = $objWorksheet->getCellByColumnAndRow($valuearray["epitheto"]-1, $row)->getValue();
            if ($epitheto){
                $onoma = $objWorksheet->getCellByColumnAndRow($valuearray["onoma"]-1, $row)->getValue();
                $patronimo = $objWorksheet->getCellByColumnAndRow($valuearray["patronimo"]-1, $row)->getValue();
                $am = getAm($epitheto, $onoma, $patronimo, $parent);
                $mydate = date("Ymd");
                $apous = $objWorksheet->getCellByColumnAndRow($valuearray["ap"]-1, $row)->getValue();
                $dik = $objWorksheet->getCellByColumnAndRow($valuearray["dik"]-1, $row)->getValue();
                
                if (strlen(trim($apous)) < 3 ) $apous = str_repeat('0', 3 - strlen(trim($apous))). trim($apous);
                for ($y = 1; $y < count($apousies_define); $y++) {
                    $apous .= str_repeat('0', 3 );
                }
                if ($apous == str_repeat('0', 3 * count($apousies_define) )) $apous = '';
                
                if (strlen(trim($dik)) < 3 ) $dik = str_repeat('0', 3 - strlen(trim($dik))). trim($dik);
                for ($y = 1; $y < count($dikaiologisi_define); $y++) {
                    $dik .= str_repeat('0', 3 );
                }
                if ($dik == str_repeat('0', 3 * count($dikaiologisi_define) )) $dik = '';

                $query = "REPLACE INTO `apousies_pre` 
                (`mydate`, `apous`, `dik`, `daysk`, `fh`, `mh`, `lh`, `oa`, `da`, `user`, `student_am`) VALUES
                ('$mydate', '$apous', '$dik', '0', '0', '0', '0', '0', '0', '$parent', '$am');";
                $arr_query[] = $query;
            }
            //echo($mydate . ", " . $am . ", " . $epitheto . ", " . $onoma . ", " . $patronimo . ", " . $apous . ", " . $dik . "<hr>" );
            //echo $query . "<hr>";
            
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
                $query = "DELETE `apousies_pre` FROM `apousies_pre` join  
                    (SELECT `students`.* FROM `students` join `studentstmimata`
                    on `students`.`am` = `studentstmimata`.`student_am`
                    WHERE `tmima` = '$tmima' and  `students`.`user` = '$parent') as `T1`
                    on `apousies_pre`.`student_am` = `T1`.`am` ;";
            }else{
                $query = "DELETE FROM `apousies_pre` WHERE `user` = '$parent';";
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
if (! document.frm.epitheto.value ){alert("Συμπληρώστε τη στήλη του Επωνύμου μαθητή."); return false;}
if (! document.frm.onoma.value ){alert("Συμπληρώστε τη στήλη του Ονόματος μαθητή."); return false;}
if (! document.frm.patronimo.value ){alert("Συμπληρώστε τη στήλη του Ονόματος πατέρα."); return false;}
if (! document.frm.ap.value ){alert("Συμπληρώστε τη στήλη στο Σύνολο απουσιών."); return false;}
if (! document.frm.dik.value ){alert("Συμπληρώστε τη στήλη στις Δικαιολογημένες."); return false;}
if (! document.frm.firstrow.value ){alert("Συμπληρώστε τη πρώτη γραμμή με δεδομένα."); return false;}
if (tmima ){
answer=confirm("ΠΡΟΣΟΧΗ !!!\n\nΤα δεδομένα που εισάγετε\nθα αντικαταστήσουν ΟΛΕΣ τις προυπάρχουσες απουσίες\nτων μαθητών του τμήματος " + tmima + "\nτου χρήστη " + user + " .\n\nΕπιβεβαιώστε παρακαλώ.");
}else{
answer=confirm("ΠΡΟΣΟΧΗ !!!\n\nΤα δεδομένα που εισάγετε\nθα αντικαταστήσουν ΟΛΕΣ τις προυπάρχουσες απουσίες\nτων μαθητών ΟΛΩΝ των τμημάτων\nτου χρήστη " + user + ".\n\nΕπιβεβαιώστε παρακαλώ.");
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


$smarty->assign('title', 'ΠΡΟΥΠΑΡΧΟΥΣΕΣ ΑΠΟ MYSCHOOL');
$smarty->assign('extra_javascript', $extra_javascript);
$smarty->assign('h1_title', 'Προυπάρχουσες από myschool');
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

$smarty->display('importmyschapousies_pre.tpl');

?>
