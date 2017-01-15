<?php
require_once('common.php');
database_maintain();

isset($_SESSION['havechanges']) ? $havechanges = $_SESSION['havechanges'] : $havechanges = false;
if (isset($_POST["submitBtn"]))
    $havechanges = false;

if ($havechanges == false) {
    logoutUser();
    header('Location: index.php');
    exit;
} else {

    
    $smarty = new Smarty;

    $extra_style = '
<style type="text/css">
	td {border-style:none; text-align:center; }
	th {border-color:#ddd;border-style:none; border-width:1px; text-align:center; }
</style>
';

    $smarty->assign('title', 'ΑΠΟΘΗΚΕΥΣΗ ΔΕΔΟΜΕΝΩΝ');
    $smarty->assign('extra_style', $extra_style);
    $smarty->assign('extra_javascript', '');
    $smarty->assign('h1_title', 'Αποθήκευση δεδομένων');

    $smarty->display('logout.tpl');
}
?> 
