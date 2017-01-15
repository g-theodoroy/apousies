<?php
require_once('common.php');
checkUser();
checktmima();

$posttxtdata = array();
$filetxtdata = array();


foreach ($_POST as $key => $value) {
    if (substr($key, 0, 3) == "txt") {
        $posttxtdata[$key] = trim($value);
//	echo "$key = $value<br>";
    }
}

$orio_paper = $_POST ["apover"];

isset($_POST ["submitBtn"]) ? $todo = $_POST ["submitBtn"] : $todo = "";

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima = '';
isset($_POST['student']) ? $student = $_POST['student'] : $student = '';



if ($todo == "save") {

    //συνδέομαι με τη βάση
    include ("includes/dbinfo.inc.php");

    $query = "UPDATE `tmimata` SET `txt0` = ' " . $posttxtdata['txt0'] . "', `txt1` = ' " . $posttxtdata['txt1'] . "', `txt2` = ' " . $posttxtdata['txt2'] . "', `txt3` = ' " . $posttxtdata['txt3'] . "', `txt4` = ' " . $posttxtdata['txt4'] . "', `txt5` = ' " . $posttxtdata['txt5'] . "', `txt6` = ' " . $posttxtdata['txt6'] . "' WHERE `username` = '$parent' AND `tmima`= '$tmima'; ";

    $result = mysql_query($query);
    if (!$result) {
        $errorText = mysql_error();
        echo "1 $errorText<hr>";
    }
    if (!getparameter('orio_paper', $user, $tmima))
        setparameter('orio_paper', $orio_paper, $parent, $tmima);

    $_SESSION["havechanges"] = true;

    mysql_close();
}

//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$query = "SELECT * FROM `tmimata` WHERE `username` = '$parent' and `tmima`= '$tmima' ; ";

$result = mysql_query($query);
if (!$result) {
    $errorText = mysql_error();
    echo "2 $errorText<hr>";
}


if (mysql_result($result, $i, "txt0") != "") {
    $filetxtdata[0] = mysql_result($result, $i, "txt0");
    $filetxtdata[1] = mysql_result($result, $i, "txt1");
    $filetxtdata[2] = mysql_result($result, $i, "txt2");
    $filetxtdata[3] = mysql_result($result, $i, "txt3");
    $filetxtdata[4] = mysql_result($result, $i, "txt4");
    $filetxtdata[5] = mysql_result($result, $i, "txt5");
    $filetxtdata[6] = mysql_result($result, $i, "txt6");
}
$filetxtdata[7] = getparameter('orio_paper', $parent, $tmima);

mysql_close();

include "includes/header0";
?>

<title>ΕΙΔΟΠΟΙΗΤΗΡΙΟ</title>

<style type="text/css">
    td, th {border:none;}
    table#t1 {border:solid; border-color:#222;}
    table#t1 th,table#t1 td {border:solid; border-color:#232; border-width:1px; text-align:center; vertical-align:middle;padding: 0;white-space:wrap;}
</style>

<script language="javascript" type="text/javascript">

    // <!--
    function select_control_apover ()
    {
        if (document.frm.student.value == "all" )
        {
            document.frm.apover.style.visibility = 'visible'

        }else
        {
            document.frm.apover.style.visibility = 'hidden'
        }
    }

    function validate_form(form)
    {
        if (form.student.value == "" && form.action != "")
        {
            form.student.focus();
            alert ("Επιλέξτε μαθητή")
            return false;
        }
        if (form.student.value == "all" && form.apover.value == "")
        {
            form.apover.focus();
            alert ("Συμπληρώστε αριθμό απουσιών")
            return false;
        }
    }

    // -->
</script>

</head>
<body >
<?php include "includes/header1"; ?>

    <h1>Ειδοποιητήριο</h1>

    <?php include "includes/header2"; ?>
<?php include "includes/menu"; ?>

    <!-- CONTENT -->
    <div class="column span-21 last" >

        <!-- SELECT -->
        <div class="block">
            <div class="column span-21 last" align="center">

                <form name="frm" method="POST" action='' onsubmit=" return validate_form(this);">

                    <table border="0" width="770px">
                        <tbody>
                            <tr>
                                <td colspan="2">ΣΧΟΛΙΚΗ ΜΟΝΑΔΑ: <INPUT type="text" name="txt0" size="25" value="<?php echo(isset($filetxtdata[0]) ? $filetxtdata[0] : 'το σχολείο σας'); ?>" style="text-align : center; background-color : #fff;" tabindex="-1"></td>
                                <td  colspan="2" align="right">ΣΧΟΛΙΚΟ ΕΤΟΣ: <INPUT type="text" name="txt1" size="10" value="<?php echo(isset($filetxtdata[1]) ? $filetxtdata[1] : 'σχολ έτος'); ?>" style="text-align : center; background-color : #fff;" tabindex="-1"></td>
                            </tr>
                            <tr><td colspan="4" align="center" style="height : 10px;">&nbsp;</td></tr>
                            <tr>
                                <td colspan="4" align="center" ><p align="center" style="font-size : 20px;margin:0px;" >ΔΕΛΤΙΟ ΕΠΙΚΟΙΝΩΝΙΑΣ  ΣΧΟΛΕΙΟΥ - ΓΟΝΕΩΝ</p></th>
                            </tr>
                            <tr><td colspan="4" align="center" style="height : 20px;">&nbsp;</td></tr>
                            <tr>
                                <td colspan="2" style="padding : 0 0 0 80px;"><u>ΜΑΘΗΤΗΣ/ΤΡΙΑ</u></td>
                        <td colspan="2"  style="padding : 0 0 0 20px;"><u>ΠΑΡΑΛΗΠΤΗΣ</u></td>
                        </tr>
                        <tr>
                            <td align="right" style="width:100px">
                                ΕΠΩΝΥΜΟ:<br>
                                ΟΝΟΜΑ:<br>
                                ΤΑΞΗ:<br>
                                ΤΜΗΜΑ:<br>
                            </td>
                            <td style="width:275px;text-indent:0px;padding-left: 0px;">
                                [Επώνυμο]<br>
                                [Όνομα]<br>
                                <INPUT type="text" name="txt2" size="5" value="<?php echo(isset($filetxtdata[2]) ? $filetxtdata[2] : 'Τάξη'); ?>" style="text-align : center; background-color : #fff;" tabindex="-1"><br>
                                <INPUT type="text" name="txt3" size="5" value="<?php echo(isset($filetxtdata[3]) ? $filetxtdata[3] : 'Τμήμα'); ?>" style="text-align : center; background-color : #fff;" tabindex="-1"><br>

                            </td>
                            <td colspan="2" style="text-indent:0px;padding-left: 0px;">
                                [Επώνυμο &amp; Όνομα Κηδεμόνα]<br>
                                [Διεύθυνση]<br>
                                [ΤΚ]<br>
                                [Πόλη]<br>
                            </td>
                        </tr>
                        <tr><td colspan="4" align="center" style="height : 20px;">&nbsp;</td></tr>
                        <tr>
                            <td colspan="4" >
                                1. Σας πληροφορούμε ότι [ο/η] μαθη[τής/τρια] [ΕΠΙΘΕΤΟ & ΟΝΟΜΑ ΜΑΘΗΤΗ] της [Τάξη] τάξης του [Τμήμα] τμήματος του σχολείου μας από την έναρξη της σχολικής χρονιάς μέχρι σήμερα [HMNIA] σημείωσε <b>[ΣΥΝΟΛΟ-ΑΠΟΥΣΙΩΝ]</b> απουσίες. 
                                <br>Αναλυτικά:</td>
                        </tr>
                        </tbody>
                    </table>

                    <table id="t1" border="1" cellpadding="2" cellspacing="0" width="770px" style="outline : solid;">
                        <tbody>
                            <tr>
                                <th colspan="2" rowspan="2">ΔΙΚΑΙΟΛΟΓΗΜΕΝΕΣ<br>ΑΠΟΥΣΙΕΣ</th>
                                <th colspan="4" style="height:30px">ΑΔΙΚΑΙΟΛΟΓΗΤΕΣ ΑΠΟΥΣΙΕΣ</th>
                                <th colspan="2" rowspan="2">ΑΠΟΥΣΙΕΣ<br>ΑΠΟ ΠΟΙΝΕΣ</th>
                            </tr>
                            <tr>
                                <th colspan="2">ΜΕΜΟΝΩΜΕΝΕΣ<br>ΑΠΟΥΣΙΕΣ</th>
                                <th colspan="2">ΑΠΟΥΣΙΕΣ<br>ΟΛΟΚΛΗΡΗΣ<br>ΜΕΡΑΣ</th>
                            </tr>
                            <tr>
                                <td class="wide">
                                    α. Από Γονέα Κηδεμόνα:
                                </td>
                                <th class="narrow">[]</th>
                                <td class="wide">α. πρωινή απουσία:</td>
                                <th class="narrow">[]</th>
                                <th colspan="2" rowspan="4" align="center">[]</th>
                                <td class="wide">α. από ωριαίες αποβολές:</td>
                                <th class="narrow">[]</th>
                            </tr>
                            <tr>
                                <td>β. Για λόγους υγείας:</td>
                                <th>[]</th>
                                <td>β. ενδιάμεσες απουσίες:</td>
                                <th>[]</th>
                                <td>β. από ημερήσιες αποβολές:</td>
                                <th>[]</th>
                            </tr>
                            <tr>
                                <td>γ. από Διευθυντή:</td>
                                <th>[]</th>
                                <td>
                                    γ. απουσία τελευταίας ώρας:
                                </td>
                                <th>[]</th>
                                <th colspan="2"  rowspan="3">ΣΥΝΟΛΟ : []</th>
                            </tr>
                            <tr>
                                <th colspan="2" rowspan="2">ΣΥΝΟΛΟ : []</th>
                                <tH style="height:30px">ΣΥΝΟΛΟ ΜΕΜΟΝ/ΝΩΝ:</tH>
                                <th>[]</th>
                            </tr>
                            <tr>
                                <th colspan="4" style="height:40px">ΣΥΝΟΛΟ ΑΔΙΚ/ΤΩΝ: []</th>
                            </tr>
                        </tbody>
                    </table>
                    <table border="0" width="770px">
                        <tbody>
                            <tr><td colspan="2" align="center" style="height : 20px;">&nbsp;</td></tr>
                            <tr>
                                <td colspan="2">2. Στο χρονικό διάστημα απο [ΗΜΝΙΑ1] έως [ΗΜΝΙΑ2] &mdash;&rsaquo; έδωσε αφορμή <INPUT type="checkbox" name="chk1"> &mdash;&rsaquo; δεν έδωσε αφορμή <INPUT type="checkbox" name="chk2">  για πειθαρχικό έλεγχο.</td>
                            </tr>
                            <tr><td colspan="2" align="center" style="height : 20px;">&nbsp;</td></tr>
                            <tr>
                                <td colspan="2">3. Παρακαλούμε να προσέλθετε στο σχολείο κατά το διάστημα από 1 έως 10 του μήνα [ΜΗΝΑΣ] για ενημέρωσή σας σχετικά με τη φοίτηση του ανωτέρω μαθητη-τριας προσκομίζοντας την παρούσα επιστολή.</td>
                            </tr>
                            <tr><td colspan="4" align="left" style="height : 20px;">&nbsp;</td></tr>
                            <tr><td colspan="4" align="left" style="height : 20px;">ΑΡ.ΠΡΩΤ: <INPUT type="text" name="protok" size="4" value="" style="text-align : center;background-color : #fff;"  tabindex="-1">
                                    &nbsp;<select name="protokctrl" id="protokctrl"><option value="1">ΝΑ ΑΥΞΑΝΕΙ +1</option><option value="0">ΤΟ ΙΔΙΟ ΓΙΑ ΟΛΑ</option></select></td></tr>
                            <tr>
                                <td style="width : 400px;" >ΗΜ/ΝΙΑ: [ΗΜΝΙΑ]</td>
                                <td ><INPUT type="text" name="txt4" size="3" value="<?php echo(isset($filetxtdata[4]) ? $filetxtdata[4] : 'Ο/Η'); ?>" style="text-align : center;background-color : #fff;"  tabindex="-1" > ΚΑΘΗΓΗ<INPUT type="text" name="txt5" size="8" value="<?php echo(isset($filetxtdata[5]) ? $filetxtdata[5] : 'ΤΗΣ/ΤΡΙΑ'); ?>" style="text-align : center;background-color : #fff;"  tabindex="-1" ></td>
                            </tr>
                            <tr><td colspan="2" align="center" style="height : 50px;">&nbsp;</td></tr>
                            <tr>
                                <td >&nbsp;</td>
                                <td ><INPUT type="text" name="txt6" size="20" value="<?php echo(isset($filetxtdata[6]) ? $filetxtdata[6] : 'Επίθετο & όνομα καθηγητή'); ?>" style="text-align : center;background-color : #fff;"  tabindex="-1" ></td>
                            </tr>
                            <tr><td colspan="2" align="center" style="height : 30px;">&nbsp;</td></tr>
                        </tbody>
                    </table>
                    <table width="770px" style="outline : solid;">
                        <tbody>
                            <tr>
                                <td colspan="2">ΠΑΡΕΛΗΦΘΗ ΑΠΟ ΤΟΝ ΚΗΔΕΜΟΝΑ</td>
                            </tr>
                            <tr>
                                <td style="width : 570px;">Ονοματεπώνυμο: ...................................................................</td>
                                <td >Ημερομηνία : ............</td>
                            </tr>
                        </tbody>
                    </table>

                    <hr>

                    <h4 align="center">
                        Επιλογή μαθητή&nbsp;<SELECT name="student" onchange="select_control_apover();">
                            <option value="">&nbsp;</option>
                            <option value="all">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ΟΛΟΙ ΟΣΟΙ ΕΧΟΥΝ ΚΑΝΕΙ ΑΠΟΥΣΙΕΣ ΠΑΝΩ ΑΠΟ</option>
                            <option value="new">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ΜΟΝΟ ΤΑ ΝΕΑ ΕΙΔΟΠΟΙΗΤΗΡΙΑ</option>
                            <option value="">&nbsp;</option>
                            <?php
//συνδέομαι με τη βάση
                            include ("includes/dbinfo.inc.php");

                            $query = "SELECT  `students`.`am`, `students`.`epitheto`, `students`.`onoma`, `students`.`patronimo` ,SUM(`apousies`.`ap`)AS sumap, SUM(`apousies`.`apk`)AS sumapk, SUM(`apousies`.`ape`)AS sumape FROM `students` left JOIN `apousies` ON (`students`.`user` = `apousies`.`user` AND `students`.`am` = `apousies`.`student_am` ) WHERE `students`.`user` = '$parent' AND (`students`.`tmima`= '$tmima' OR `students`.`tmima-kat`= '$tmima' OR `students`.`tmima-epi`= '$tmima') GROUP BY `students`.`am` ORDER BY `epitheto`, `onoma` ASC; ";

                            $result = mysql_query($query);
                            if (!$result) {
                                $errorText = mysql_error();
                                echo "3 $errorText<hr>";
                            }
                            $num = mysql_numrows($result);

                            mysql_close();

                            for ($i = 0; $i < $num; $i++) {
                                $am = mysql_result($result, $i, "am");
                                $epitheto = mysql_result($result, $i, "epitheto");
                                $onoma = mysql_result($result, $i, "onoma");
                                $patronimo = mysql_result($result, $i, "patronimo");
                                //προυπάρχουσες απουσιες
                                $pre_apousies = get_pre_apousies($parent, $am);
                                $totalap = mysql_result($result, $i, "sumap") + mysql_result($result, $i, "sumapk") + mysql_result($result, $i, "sumape") + $pre_apousies["ap"] + $pre_apousies["apk"] + $pre_apousies["ape"];

                                if ($totalap) {
                                    if ($student == $am) {
                                        echo "<option value=\"$am\" selected >" . str_repeat("&nbsp;", 3 - strlen($totalap)) . "    $totalap    ->     $epitheto  $onoma $patronimo </option>\n";
                                    } else {
                                        echo "<option value=\"$am\" >" . str_repeat("&nbsp;", 3 - strlen($totalap)) . "     $totalap    ->     $epitheto $onoma $patronimo</option>\n";
                                    }
                                }
                            }
                            ?>
                        </SELECT>
                        <input type="text" name="apover" size="2" style="visibility : hidden;" value="<?php echo(isset($filetxtdata[7]) ? $filetxtdata[7] : ''); ?>">
                    </h4>
                    <h4 align="center">
                        Να αποθηκευτούν τα ειδοποιητήρια στο ιστορικό:&nbsp;<select name='history' id='history'><option value="0">ΟΧΙ</option><option value="1">ΝΑΙ</option></select>
                    </h4>

                    <h4 align="center">
                        Υπολόγισε τις απουσίες μέχρι τις&nbsp;<input name='lastdate' id='lastdate' value="<?php echo date("j/n/Y") ?>" size="10" style="text-align : center; background-color : #fff;"/>
                    </h4>

                    <h4 align="center">
                        <button type="submit" name="submitBtn" value="save" onclick="frm.action='';frm.target='_self'">ΑΠΟΘΗΚΕΥΣΗ</button>&nbsp;
                        <button type="submit" name="submitBtn" value="print" onclick="frm.action='paper.php';frm.target='_blank';">ΕΚΤΥΠΩΣΗ</button>&nbsp;
                        <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
                    </h4>
                </form>

            </div>
        </div>
    </div>
    <?php include "includes/footer"; ?>
</body>
</html>
