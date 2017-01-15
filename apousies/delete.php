<?php
require_once('common.php');
checkUser();
//checktmima();

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user ='';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
//isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima ='';

//βρίσκω πόσες εγγραφές έχω για να κάνω
//αντίστοιχες λούπες
$checknum = trim($_POST["checknum"]);
//βρίσκω τα πατημένα και βάζω σε πίνακα τους am για διαγραφή
for ($k = 0 ; $k < $checknum ; $k++){
	if (isset($_POST["chk$k"]) && trim($_POST["chk$k"]) != ""){
	$pinakas[] = trim($_POST["chk$k"]);
	}
}

//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

begin();$errorcheck=false;

for ($k = 0;$k<count($pinakas);$k++) {

	$query =  "DELETE  FROM `students` WHERE `user` = '$parent' AND `am` = '$pinakas[$k]' ;";
	
	$result= mysqli_query($link,$query);

	if (!$result) {
		$errorText= mysqli_error($link);
		echo "1 $errorText<hr>";
		$errorcheck=true;
	}

	$query =  "DELETE  FROM `apousies` WHERE `user` = '$parent' AND `student_am` = '$pinakas[$k]' ;";
	$result= mysqli_query($link,$query);

	if (!$result) {
		$errorText= mysqli_error($link);
		echo "2 $errorText<hr>";
		$errorcheck=true;
	}

        $query =  "DELETE  FROM `studentstmimata` WHERE `user` = '$parent' AND `student_am` = '$pinakas[$k]' ;";
	$result= mysqli_query($link,$query);

	if (!$result) {
		$errorText= mysqli_error($link);
		echo "3 $errorText<hr>";
		$errorcheck=true;
	}

	$query =  "DELETE  FROM `paperhistory` WHERE `user` = '$parent' AND `am` = '$pinakas[$k]' ;";
	$result= mysqli_query($link,$query);

	if (!$result) {
		$errorText= mysqli_error($link);
		echo "4 $errorText<hr>";
		$errorcheck=true;
	}

	$query =  "DELETE  FROM `apousies_pre` WHERE `user` = '$parent' AND `student_am` = '$pinakas[$k]' ;";
	$result= mysqli_query($link,$query);

	if (!$result) {
		$errorText= mysqli_error($link);
		echo "5 $errorText<hr>";
		$errorcheck=true;
	}
}	

if($errorcheck == true){rollback();}else{commit();$_SESSION["havechanges"]= true;}

mysqli_close($link);

header("Location: students.php")
?>
