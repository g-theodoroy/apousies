<?php

require_once('common.php');
checkUser();
//checktmima();

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima = '';


if (isset($_GET['q'])) {
    $amget = $_GET['q'];
} else {
    $amget = "";
}


$dataget = array();

//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

if ($amget) {

    //ελέγχω αν υπάρχει o νέος am	
    $query = "SELECT * FROM `students` WHERE `user` = '$parent' AND `am`= '$amget';";


    $result = mysqli_query($link,$query);
    if (!$result) {
        $errorText = mysqli_error($link);
        echo "1 $errorText<hr>";
    }
    $num = mysqli_num_rows($result);



    if ($num) {
    	$row = mysqli_fetch_assoc($result);
        $am = $row["am"];
        $studentstmimata = gettmimata4student($parent, $am);
        $dataget['am'] = $am;
        $dataget['epi'] = $row["epitheto"];
        $dataget['ono'] = $row["onoma"];
        $dataget['pat'] = $row["patronimo"];
        $dataget['epkid'] = $row["ep_kidemona"];
        $dataget['onkid'] = $row["on_kidemona"];
        $dataget['die'] = $row["dieythinsi"];
        $dataget['tk'] = $row["tk"];
        $dataget['poli'] = $row["poli"];
        $dataget['til1'] = $row["til1"];
        $dataget['til2'] = $row["til2"];
        $dataget['filo'] = $row["filo"];
        $dataget['email'] = $row["email"];
        $dataget['tmimata'] = $studentstmimata;
    }
}

mysqli_close($link);


$response = "
<table align=\"center\" cellpadding=\"2\">
  <tbody  >
    <tr>
      <td align=\"right\">ΑΜ<INPUT type=\"hidden\" name=\"oldamget\" value=\"$amget\" ></td>
      <td><input type=\"text\" name=\"am\" value=\"";
if (count($dataget) > 0)
    $response.= $dataget['am'];
$response.="\">*</td>
    </tr>
    <tr>
      <td align=\"right\">ΕΠΙΘΕΤΟ</td>
      <td><input type=\"text\" name=\"epitheto\" value=\"";
if (count($dataget) > 0)
    $response.= $dataget['epi'];
$response.="\" >*</td>
    </tr>
    <tr>
      <td align=\"right\">ΟΝΟΜΑ</td>
      <td><input type=\"text\" name=\"onoma\" value=\"";
if (count($dataget) > 0)
    $response.= $dataget['ono'];
$response.="\">*</td>
    </tr>
    <tr>
      <td align=\"right\">ΠΑΤΡΩΝΥΜΟ</td>
      <td><input type=\"text\" name=\"patronimo\" value=\"";
if (count($dataget) > 0)
    $response.= $dataget['pat'];
$response.="\"></td>
    </tr>
    <tr>
      <td align=\"right\">ΕΠΙΘΕΤΟ ΚΗΔΕΜΟΝΑ</td>
      <td><input type=\"text\" name=\"ep_kidemona\" value=\"";
if (count($dataget) > 0)
    $response.= $dataget['epkid'];
$response.="\"></td>
    </tr>
    <tr>
      <td align=\"right\">ΟΝΟΜΑ ΚΗΔΕΜΟΝΑ</td>
      <td><input type=\"text\" name=\"on_kidemona\" value=\"";
if (count($dataget) > 0)
    $response.= $dataget['onkid'];
$response.="\"></td>
    </tr>
    <tr>
      <td align=\"right\">ΔΙΕΥΘΥΝΣΗ</td>
      <td><input type=\"text\" name=\"dieythinsi\" value=\"";
if (count($dataget) > 0)
    $response.= $dataget['die'];
$response.="\"></td>
    </tr>
    <tr>
      <td align=\"right\">ΤΚ</td>
      <td><input type=\"text\" name=\"tk\" value=\"";
if (count($dataget) > 0)
    $response.= $dataget['tk'];
$response.="\"></td>
    </tr>
    <tr>
      <td align=\"right\">ΠΟΛΗ</td>
      <td><input type=\"text\" name=\"poli\" value=\"";
if (count($dataget) > 0)
    $response.= $dataget['poli'];
$response.="\"></td>
    </tr>
    <tr>
      <td align=\"right\">ΤΗΛ1</td>
      <td><input type=\"text\" name=\"til1\" value=\"";
if (count($dataget) > 0)
    $response.= $dataget['til1'];
$response.="\"></td>
    </tr>
    <tr>
      <td align=\"right\">KINHTO</td>
      <td><input type=\"text\" name=\"til2\" value=\"";
if (count($dataget) > 0)
    $response.= $dataget['til2'];
$response.="\"></td>
    </tr>
    <tr>
      <td align=\"right\">EMAIL</td>
      <td><input type=\"text\" name=\"email\" value=\"";
if (count($dataget) > 0)
    $response.= $dataget['email'];
$response.="\"></td>
    </tr>
    <tr>
      <td align=\"right\">ΦΥΛΟ</td>
      <td>
	<SELECT name=\"gender\">
	<option value='Α' ";
if (count($dataget) > 0 && $dataget['filo'] == 'Α')
    $response.= "selected"; $response.=" >ΑΡΕΝ</option>
	<option value='Θ' ";
if (count($dataget) > 0 && $dataget['filo'] == 'Θ')
    $response.= "selected"; $response.=" >ΘΗΛΥ</option>
	</SELECT>
     </td>
    </tr>";

################################################################################

$studentstmimata = gettmimata4student($parent, $am);

//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$query = "SELECT `tmima`,`type` FROM `tmimata` WHERE `username` = '$parent'  ORDER BY `tmima`;";

$result = mysqli_query($link,$query);

if (!$result) {
    $errorText = mysqli_error($link);
    echo "$errorText <hr>";
}

$num = mysqli_num_rows($result);

if ($num) {
    $tmimata_select_boxes = array();

    for ($x = 0; $x < count($apousies_define); $x++) {
        $tmimata_select_boxes[$x] = "<select name='" . $apousies_define[$x]["kod"] . "' >\n<option value='' ><option>\n";
    }

    while ($row = mysqli_fetch_assoc($result)) {
        for ($x = 0; $x < count($apousies_define); $x++) {
            $type = $apousies_define[$x]["kod"];
            if ($row["type"] == $type) {
                $selected = '';
                foreach ($studentstmimata as $key => $value) {
                    if ($row["tmima"] == $value) {
                        $selected = 'selected';
                    }
                }
                $tmimata_select_boxes[$x] .= '<option value="' . $row["tmima"] . '" ' . $selected . ' >' . $row["tmima"] . '</option>\n';
            }
        }
    }

    for ($x = 0; $x < count($apousies_define); $x++) {
        $tmimata_select_boxes[$x] .= "</select>";
    }
}


mysqli_close($link);
################################################################################
for ($x = 0; $x < count($apousies_define); $x++) {

    $response.="<tr>
        <td>" . $apousies_define[$x]['perigrafi'] . "</td>
        <td>" . $tmimata_select_boxes[$x] . "</td>
            </tr>";
}

$response.= "</tbody>
</table>";


//output the response
echo $response;
?>
