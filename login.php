<?php
require_once('common.php');

$error = '';

if (isset($_POST['submitBtn'])) {
// Get user input
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username == '' || $password == '') {
        $error = 'Συμπληρώστε τα στοιχεία!';
    } else {
// Try to login the user
        $error = loginUser($username, $password);
    }

    if ($error == '') {
        //ενημέρωση των τμημάτων στη SESSION
        set_select_tmima($username);
        header('Location: index.php');
        die;
    }
}

require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty;

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

$smarty->assign('title', 'ΔΙΑΠΙΣΤΕΥΣΗ');
$smarty->assign('extra_style', $extra_style);
$smarty->assign('h1_title', 'Είσοδος');
$smarty->assign('body_attributes', '');
$smarty->assign('extra_javascript', '');
$smarty->assign('error', $error);

$smarty->display('login.tpl');
