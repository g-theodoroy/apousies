<?php
require_once('common.php');
//checkUser();

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user ='';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima ='';

require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty;

$extra_style = '
<style type="text/css">
	img.tour{ border:solid;border-color:#222;border-width:2px;width:100%}
	.box { background-color:#fff}
</style>
';

$smarty->assign('title', 'ΔΥΝΑΤΟΤΗΤΕΣ ΤΗΣ ΕΦΑΡΜΟΓΗΣ');
$smarty->assign('extra_style', $extra_style);
$smarty->assign('h1_title', 'Δυνατότητες της εφαρμογής');
$smarty->assign('body_attributes', '');
$smarty->assign('extra_javascript', '');

$smarty->display('tour.tpl');

