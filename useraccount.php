<?php
require_once ('common.php');
checkUser ();
//checkParent();

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';

$error = '';

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$query = "SELECT * FROM `users` WHERE `username` = '$user' ;";

$result = mysqli_query ( $link, $query );

if (! $result) {
	$errorText = mysql_error ();
}
$row = mysqli_fetch_assoc ( $result );
$email = $row ["email"];
$timestamp = $row ["timestamp"];
$check = $row ["reminder"];
mysqli_close ( $link );

if (isset ( $_POST ['submitBtn'] )) {
	// Get user input
	$email = isset ( $_POST ['email'] ) ? trim ( $_POST ['email'] ) : '';
	$check = isset ( $_POST ['check'] ) ? trim ( $_POST ['check'] ) : '';
	$oldpass = isset ( $_POST ['oldpass'] ) ? trim ( $_POST ['oldpass'] ) : '';
	$newpass1 = isset ( $_POST ['newpass1'] ) ? trim ( $_POST ['newpass1'] ) : '';
	$newpass2 = isset ( $_POST ['newpass2'] ) ? trim ( $_POST ['newpass2'] ) : '';
	
	// Try to change the user data
	if ($user != "demo")
		$error = changeUserdata ( $user, $oldpass, $newpass1, $newpass2, $email, $check );
}

require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty ();

$extra_style = '
<style type="text/css">
	td, th {border:none;}
</style>
';
$extra_javascript = '
<script type="text/javascript">

function delete_me(){
    var answer;
            answer =  confirm ("Θέλετε να διαγραφείτε;\nΘα διαγραφούν μαζί και όλα τα στοιχεία που σχετίζονται με σας.")
    if (answer == true){
            answer =  confirm ("Η διαδικασία δεν είναι αναστρέψιμη!\nΕίστε όντως σίγουροι για τη διαγραφή;")
    }
    if (answer == true){
        window.location="deleteme.php"
    }

}
</script>
';

$smarty->assign ( 'title', 'ΔΙΑΧΕΙΡΙΣΗ ΛΟΓΑΡΙΑΣΜΟΥ' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'extra_javascript', $extra_javascript );
$smarty->assign ( 'body_attributes', '' );
$smarty->assign ( 'h1_title', 'Διαχείριση λογαριασμού' );
$smarty->assign ( 'error', $error );
$smarty->assign ( 'email', $email );
$smarty->assign ( 'check', $check );
$smarty->assign ( 'mydate', date ( "j/n/Y H:i:s", $timestamp ) );

$smarty->display ( 'useraccount.tpl' );

