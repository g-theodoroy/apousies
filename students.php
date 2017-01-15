<?php
require_once('common.php');
checkUser();
isset ( $_SESSION ['parentUser'] ) ? $parentUser = $_SESSION ['parentUser'] : $parentUser = false;
if (! $parentUser){
	checktmima();
}

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima = '';
//παίρνω το τμήμα που επιλέχτηκε
//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

//ελέγχω αν υπάρχει το νέο τμήμα	
if ($tmima) {
    $query = "SELECT `am`, `epitheto`, `onoma`, `patronimo`, `ep_kidemona`, `on_kidemona`, `dieythinsi`, `tk`, `poli`, `til1`, `til2`, `filo`, `email` 
    FROM `students`  
    JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `student_am`) 
    WHERE `students`.`user` = '$parent' and  `studentstmimata`.`tmima` = '$tmima' 
    ORDER BY `epitheto`,`onoma` ASC ";
} else {
    $query = "SELECT * FROM `students` WHERE `user` = '$parent'  ORDER BY `epitheto`,`onoma` ASC ;";
}

$result = mysqli_query($link,$query);


if (!$result) {
    $errorText = mysqli_error($link);
    echo "1 $errorText<hr>";
}

$num = mysqli_num_rows($result);

mysqli_close($link);

$data = array();
$i = -1;
while ($row = mysqli_fetch_assoc($result)) {
	$i++;
    $k = $i + 1;
    
    $am = $row["am"];
    
   $studentstmimata = gettmimata4student($parent,$am);
   
    
    $data[$i]['k'] = $k;
    $data[$i]['i'] = $i;
    $data[$i]['epi'] = $row["epitheto"];
    $data[$i]['ono'] = $row["onoma"];
    $data[$i]['pat'] = $row["patronimo"];
    $data[$i]['am'] = $am;
    $data[$i]['epkid'] = $row["ep_kidemona"];
    $data[$i]['onkid'] = $row["on_kidemona"];
    $data[$i]['die'] = $row["dieythinsi"];
    $data[$i]['tk'] = $row["tk"];
    $data[$i]['poli'] = $row["poli"];
    $data[$i]['til1'] = $row["til1"];
    $data[$i]['til2'] = $row["til2"];
    $data[$i]['filo'] = $row["filo"];
    $data[$i]['email'] = $row["email"];
    $data[$i]['tmimata'] = $studentstmimata;
}


$smarty = new Smarty;

$extra_style = '
<style type="text/css">
	table {table-layout:fixed;width:100%; }
	th,td {overflow:hidden;}
</style>
';
$extra_javascript = '
<script language="javascript" >
<!--    
function check_form(tid) {
    var  num = 0;
    var checkvalue = new Array;
 
    for (i=0;i<document.frm.elements.length;i++){ 
        if (document.frm.elements[i].type == "checkbox" && document.frm.elements[i].checked){
            checkvalue[num]=document.frm.elements[i].value;
            num++;
        }
    }

    switch (tid){
        case "alt":
            //αν δεν έχω καμιά επιλογή
            if  (num==0){
                alert("Για να τροποποιήσετε κάποια εγγραφή\nπρέπει να επιλέξετε το αντίστοιχο κουτί μπροστά από αυτή");
                return false;
                break;
            }
            //αν έχω πάνω από 1
            if  (num>1){
                alert ("Μπορείτε να τροποποιήσετε μόνο 1 εγγραφή τη φορά")
                return false;
                break;
            }
            //αν έχω 1 όπως θέλω στέλνω το am με query-string στη φόρμα για επεξεργασία
            if  (num==1){
                window.location = "editstudent.php?am=" + checkvalue[0] ;
                return false;
                break;
            }

        case "del":
            if  (num==0){
                alert("Για να διαγράψετε κάποια εγγραφή\nπρέπει να επιλέξετε το αντίστοιχο κουτί μπροστά από αυτή");
                return false;
                break;
            }
            answer =  confirm ("Πρόκειται να διαγράψετε " + num  + " εγγραφές.\nΘα διαγραφούν και όλα τα στοιχεία που σχετίζονται μαζί τους.\nΕίστε σίγουρος;")
            if(!answer){
                return false;
                break;
                }
                document.frm.action = "delete.php";
                return true;
                break;
        case "pdf":
            document.frm.action = "";
            return true;
            break;
    }
}
-->   
</script>
';

$smarty->assign('title', 'ΜΑΘΗΤΕΣ');
$smarty->assign('extra_style', $extra_style);
$smarty->assign('extra_javascript', $extra_javascript);
$smarty->assign('h1_title', 'Μαθητές');
$smarty->assign('body_attributes', '');
$smarty->assign('data', $data);
$smarty->assign('num', $num);
$smarty->assign('tmimata_def', $apousies_define);

if (isset($_POST['pdf'])) {

    $html = $smarty->fetch('students_pdf.tpl');


    $mpdf = new mPDF('utf-8', 'A4-L');

    $mpdf->WriteHTML($html);

    $filename = "Μαθητές_$parent\_$tmima.pdf";

    $mpdf->Output($filename, 'D');
    exit;
} else {
    $smarty->display('students.tpl');
}



