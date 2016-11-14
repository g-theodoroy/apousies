<?php
require_once ('common.php');

// έλεγχος αν επιτρεπεται η καταχώρηση νέων χρηστών
// αυτό αλλάζει στο common.php στη γραμμή 19-20
if (! $_SESSION ['allowregister'])
	header ( 'Location:login.php' );

$error = '';

if (isset ( $_POST ['submitBtn'] )) {
	// Get user input
	$username = isset ( $_POST ['username'] ) ? trim ( $_POST ['username'] ) : '';
	$email = isset ( $_POST ['email'] ) ? trim ( $_POST ['email'] ) : '';
	$check = isset ( $_POST ['check'] ) ? trim ( $_POST ['check'] ) : '';
	
	// Try to register the user
	$error = registerUser ( $username, $email, $check );
}

require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty ();

$extra_style = '
<style type="text/css">
	td 
        {
        border-style:none; text-align:center; 
        }
	th 
        {
        border-color:#ddd;border-style:none; border-width:1px; text-align:center; 
        }
</style>
';

$smarty->assign ( 'title', 'ΔΗΜΙΟΥΡΓΙΑ ΛΟΓΑΡΙΑΣΜΟΥ' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'h1_title', 'Εγγραφή' );
$smarty->assign ( 'body_attributes', "" );
$smarty->assign ( 'extra_javascript', '' );
$smarty->assign ( 'error', $error );

$smarty->display ( 'register.tpl' );
