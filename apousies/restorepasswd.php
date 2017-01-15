<?php
require_once ('common.php');

$error = '';

if (isset ( $_POST ['submitBtn'] )) {
	// Get user input
	$user = isset ( $_POST ['user'] ) ? trim ( $_POST ['user'] ) : '';
	$check = isset ( $_POST ['check'] ) ? trim ( $_POST ['check'] ) : '';
	
	// Try to change the user data
	$error = restorepass ( $user, $check );
}


$smarty = new Smarty ();

$extra_style = '
<style type="text/css">
	td {border-style:none; text-align:center; }
	th {border-color:#ddd;border-style:none; border-width:1px; text-align:center; }
</style>
';

$smarty->assign ( 'title', 'ΥΠΕΝΘΥΜΙΣΗ PASSWORD' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'extra_javascript', '' );
$smarty->assign ( 'h1_title', 'Υπενθύμιση password' );
$smarty->assign ( 'error', $error );

$smarty->display ( 'restorepasswd.tpl' );
