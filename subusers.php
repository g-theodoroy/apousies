<?php
require_once('common.php');
checkUser();
checkParent();
//checktmima();

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user = '';
isset($_SESSION['parent']) ? $parent = $_SESSION['parent'] : $parent = '';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima = '';

$action = '';
if(isset($_POST['new'])) $action = 'new';
if(isset($_POST['update'])) $action = 'update';
if(isset($_POST['deluser'])) $action = 'deluser';
if(isset($_POST['deltmima'])) $action = 'deltmima';

isset($_POST['username']) ? $newuser = $_POST['username'] : $newuser = '';
isset($_POST['password']) ? $newpass = $_POST['password'] : $newpass = '';
isset($_POST['reminder']) ? $reminder = $_POST['reminder'] : $reminder = '';
isset($_POST['email']) ? $email = $_POST['email'] : $email = '';


$usrsel = array();
$tmisel = array();
$newtmi = array();
$data = array();

foreach ($_POST as $key => $value) {
	
	
	if (substr($key,0,6)== 'chkusr'){
		$usrsel[] =$value;
	}
	if (substr($key,0,6)== 'chktmi'){
		$pos = strpos($key, '-') + 1;
		$data['u'] = substr($key,$pos);
		$data['t'] = $value;
		$tmisel[] = $data;
	}
	if (substr($key,0,6)== 'newtmi'){
		if ($value){
			$newtmi[substr($key,6)] = $value;
		}
	}
}

$errorTextRegister = '';

if ($action == 'new'){
	$errorTextRegister = registerUser($newuser, $email, $reminder, $newpass, $parent);
	if ($errorTextRegister == ''){
		//συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		foreach ($newtmi as $type => $value){
			//έλεγχος αν υπάρχει η καταχώρηση
			$query = "SELECT * FROM `tmimata` WHERE `username` = '$newuser' and `tmima` = '$value'  ;";
			$result = mysqli_query($link,$query);
			if (!$result) {
				$errorText = mysqli_error($link);
				echo "1 $errorText<hr>";
			}
			$num = mysqli_num_rows($result);
			// αν δεν υπάρχει προσθέτω
			if($num == 0){
				$lastselect = time();
				$query = "INSERT INTO `tmimata`(`username`, `tmima`, `lastselect`, `type` ) VALUES ('$newuser', '$value', '$lastselect' , '$type'  );";
				$result = mysqli_query($link,$query);
				if (!$result) {
					$errorText = mysqli_error($link);
					echo "1 $errorText<hr>";
				}
			}
		}
		mysqli_close ( $link );
	}
	
}elseif ($action == 'update'){
	$subuser = $usrsel[0];
	$set = '';
	if ($newpass){
		$userpass = md5 ( $newpass );
		$set .= "`password` = \"$userpass\" ,";
	}
	if ($reminder){
		$set .= "`reminder` = \"$reminder\" ,";
	}
	if ($email){
		$set .= "`email` = \"$email\" ,";
	}
	
	//συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	
	if ($set){
	$set = substr($set,0,-1);
	$query = "UPDATE `users` SET $set WHERE `username` = '$subuser' ;";
	
	$result = mysqli_query($link,$query);
	if (!$result) {
		$errorText = mysqli_error($link);
		echo "1 $errorText<hr>";
	}
	}
	
	foreach ($newtmi as $type => $value){
		//έλεγχος αν υπάρχει η καταχώρηση
		$query = "SELECT * FROM `tmimata` WHERE `username` = '$subuser' and `tmima` = '$value'  ;";
		$result = mysqli_query($link,$query);
		if (!$result) {
			$errorText = mysqli_error($link);
			echo "1 $errorText<hr>";
		}
		$num = mysqli_num_rows($result);
		// αν δεν υπάρχει προσθέτω
		if($num == 0){
			$lastselect = time();
			$query = "INSERT INTO `tmimata`(`username`, `tmima`, `lastselect`, `type` ) VALUES ('$subuser', '$value', '$lastselect' , '$type'  );";
			$result = mysqli_query($link,$query);
			if (!$result) {
				$errorText = mysqli_error($link);
				echo "1 $errorText<hr>";
			}
		}
	}
	mysqli_close ( $link );
	
}elseif ($action == 'deluser' ){
	//συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	
	foreach ($usrsel as $subuser){
		
		$query = "DELETE FROM `tmimata` WHERE `username` = '$subuser'";
		$result = mysqli_query($link,$query);
		if (!$result) {
			$errorText = mysqli_error($link);
			echo "1 $errorText<hr>";
		}
		$query = "DELETE FROM `users` WHERE `username` = '$subuser'";
		$result = mysqli_query($link,$query);
		if (!$result) {
			$errorText = mysqli_error($link);
			echo "2 $errorText<hr>";
		}
	}
	
	mysqli_close ( $link );
	
}elseif ($action == 'deltmima' ){
	
	//συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");

	foreach ($tmisel as $data){
		$subuser = $data['u'];
		$tmi2del  = $data['t'];
		$query = "DELETE FROM `tmimata` WHERE `username` =  '$subuser'  AND `tmima` = '$tmi2del' ;";
		$result = mysqli_query($link,$query);
		if (!$result) {
			$errorText = mysqli_error($link);
			echo "1 $errorText<hr>";
		}
	}
	
	mysqli_close ( $link );
	
	
}



//παίρνω το τμήμα που επιλέχτηκε
//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

    $query = "SELECT * FROM `users` WHERE `username` != '$user' and `groupname` = '$user'  ;";

    
$result = mysqli_query($link,$query);


if (!$result) {
    $errorText = mysqli_error($link);
    echo "1 $errorText<hr>";
}

$num = mysqli_num_rows($result);


$data = array();
$i = -1;
$tmimanum = 0;
while ($row = mysqli_fetch_assoc($result)) {
	$i++;
    $k = $i + 1;
    $subuser = $row["username"];
    
    $tmimata = gettmimata4user($subuser);
	if ($tmimata){
	    foreach ($tmimata as $key => $pin){
	    	$tmimanum += count($pin);
	    }
	}
	    $data[$i]['k'] = $k;
	    $data[$i]['i'] = $i;
	    $data[$i]['username'] = $subuser;
	    $data[$i]['password'] = $row["password"];
	    $data[$i]['email'] = $row["email"];
	    $data[$i]['reminder'] = $row["reminder"];
	    $data[$i]['tmimata'] = $tmimata;
}

$query = "SELECT `tmima`,`type` FROM `tmimata` WHERE `username` = '$user'  ORDER BY `tmima`;";

$result = mysqli_query($link,$query);

if (!$result) {
	$errorText =  mysqli_error($link);
}

$num1 = mysqli_num_rows($result);
$tmimata_select_boxes = array();

for ($x = 0; $x < count($apousies_define); $x++) {
	$tmimata_select_boxes[$x] = "<select name='newtmi" . $apousies_define[$x]["kod"] . "' >\n<option value='' ><option>\n";
}

if ($num1) {
	while ($row = mysqli_fetch_assoc($result)) {
		for ($x = 0; $x < count($apousies_define); $x++) {
			if ($row["type"] == $apousies_define[$x]["kod"]) {
					$tmimata_select_boxes[$x] .= '<option value="' . $row["tmima"] . '" >' . $row["tmima"] . '</option>\n';
			}
		}
	}
}
for ($x = 0; $x < count($apousies_define); $x++) {
	$tmimata_select_boxes[$x] .= "</select>";
}
$loopcount = count($apousies_define)-1;

mysqli_close($link);


$smarty = new Smarty;

$extra_style = '
<style type="text/css">
	table {table-layout:fixed;width:100%; }
	th,td {overflow:hidden;vertical-align:middle; text-align:center;}
</style>
';
$extra_javascript = '
<script language="javascript" >
<!--    
function check_form(tid) {
   var  numusr = 0;
   var  numtmi = 0;
		var checkusrvalue = new Array;
    var checktmimavalue = new Array;
		
    for (i=0;i<document.frm.elements.length;i++){ 
        if (document.frm.elements[i].type == "checkbox" && document.frm.elements[i].checked){
			if (document.frm.elements[i].id.substr(0, 6) == "chkusr"){
            	checkusrvalue[numusr]=document.frm.elements[i].value;
            	numusr++;
			}
			if (document.frm.elements[i].id.substr(0, 6) == "chktmi"){
            	checktmimavalue[numtmi]=document.frm.elements[i].value;
            	numtmi++;
			}
		}
    }

    switch (tid){
        case "new":
			var errorstr = "";
			if (document.getElementById("username").value.trim() == ""){
				errorstr += "Συμπληρώστε το USERNAME\n";
			}
			if (document.getElementById("password").value.trim() == ""){
				errorstr += "Συμπληρώστε το PASSWORD\n";
			}
			if (document.getElementById("reminder").value.trim() == ""){
				errorstr += "Συμπληρώστε το REMINDER\n";
			}
			if (document.getElementById("email").value.trim() == ""){
				errorstr += "Συμπληρώστε το EMAIL\n";
			}
			if (errorstr != ""){
				errorstr = "Για να εισάγετε νέο χρήστη:\n\n" + errorstr + "\nΠαρακαλώ διορθώστε"
				 alert(errorstr);
				return false;
				break;
			}
			return true;
			break;
		
		case "update":
		//αν δεν έχω καμιά επιλογή
            if  (numusr==0){
                alert("Για να τροποποιήσετε τα στοιχεία κάποιου χρήστη\nή να του προσθέσετε ένα άλλο τμήμα\nπρέπει να επιλέξετε το αντίστοιχο κουτί μπροστά από αυτόν\nκαι να συμπληρώσετε στα textbox και combobox\nτα στοιχεία που θέλετε να αλλάξετε.\nΤο USERNAME δεν δύναται να αλλάξει.\nΚενά text-combo-box δεν επιφέρουν καμία αλλαγή.");
                return false;
                break;
            }
            //αν έχω πάνω από 1
            if  (numusr>1){
                alert ("Μπορείτε να τροποποιήσετε τα στοιχεία ενός μόνο χρήστη τη φορά")
                return false;
                break;
            }
            //αν έχω 1 όπως θέλω στέλνω το am με query-string στη φόρμα για επεξεργασία
            if  (numusr==1){
                return true;
                break;
            }

        case "deluser":
            if  (numusr==0){
                alert("Για να διαγράψετε κάποιο χρήστη\nπρέπει να επιλέξετε το αντίστοιχο\nκουτί μπροστά από αυτόν");
                return false;
                break;
            }
		answer =  confirm ("Πρόκειται να διαγράψετε " + numusr  + " χρήστες.\nΘα διαγραφούν και όλα τα τμήματα που σχετίζονται μαζί τους.\nΕίστε σίγουρος;")
            if(!answer){
                return false;
                break;
			}
            return true;
            break;
		
		case "deltmima":
            if  (numtmi==0){
                alert("Για να διαγράψετε κάποιο τμήμα\nπρέπει να επιλέξετε το αντίστοιχο\nκουτί μπροστά από αυτό");
                return false;
                break;
            }
		answer =  confirm ("Πρόκειται να διαγράψετε " + numtmi  + " τμήματα.\nΕίστε σίγουρος;")
            if(!answer){
                return false;
                break;
                }
                return true;
	}
}
-->   
</script>
';

$smarty->assign('title', 'ΧΡΗΣΤΕΣ');
$smarty->assign('extra_style', $extra_style);
$smarty->assign('extra_javascript', $extra_javascript);
$smarty->assign('h1_title', 'Χρήστες');
$smarty->assign('body_attributes', '');
$smarty->assign('data', $data);
$smarty->assign('num', $num);
$smarty->assign('tmimanum', $tmimanum);
$smarty->assign('tmimata_def', $apousies_define);
$smarty->assign('tmimata_select_boxes', $tmimata_select_boxes);
$smarty->assign('loopcount', $loopcount);
$smarty->assign('errorTextRegister', $errorTextRegister);

$smarty->display('subusers.tpl');



