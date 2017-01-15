<?php
require_once ('common.php');
checkUser ();
checkParent();

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';

if ($parent != "demo") {
	
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	
	begin ();
	$errocheck = false;
	
	$query = "SELECT * FROM `users` WHERE `username` != '$user' and `groupname` = '$user'  ;";
	$result = mysqli_query($link,$query);
	if (!$result) {
		$errorText = mysqli_error($link);
		echo "1 $errorText<hr>";
	}
	while ($row = mysqli_fetch_assoc($result)) {
		$subuser = $row['username'];
		$delquery = "DELETE FROM `tmimata`  WHERE `username` = '$subuser';";
		$delresult = mysqli_query ( $link, $delquery );
		if (! $result)
			$errocheck = true;
	}
	
	$query = "DELETE FROM `users` WHERE `groupname` = '$parent' ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errocheck = true;
	
	$query = "DELETE FROM `tmimata`  WHERE `username` = '$parent';";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errocheck = true;
	
	$query = "DELETE FROM `students`  WHERE `user` = '$parent';";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errocheck = true;
	
	$query = "DELETE FROM `apousies`  WHERE `user` = '$parent';";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errocheck = true;
	
	$query = "DELETE FROM `paperhistory`  WHERE `user` = '$parent';";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errocheck = true;
	
	$query = "DELETE FROM `apousies_pre`  WHERE `user` = '$parent';";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errocheck = true;
	
	$query = "DELETE FROM `parameters`  WHERE `user` = '$parent';";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errocheck = true;
	
	if ($errorcheck == true) {
		rollback ();
	} else {
		commit ();
	}
	
	mysqli_close ( $link );
	
	logoutUser ();
}

header ( 'Location: index.php' );

?>