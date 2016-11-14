<?php
require_once('common.php');
//checkUser();

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user ='';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima ='';

isset($_GET['q']) ? $q = $_GET['q'] : $q ='';

require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty;

$extra_style = '
<style type="text/css">
	img.tour{ border:solid;border-color:#222;border-width:2px;width:100%}
	.box { background-color:#fff}
</style>
';

$smarty->assign('title', 'ΠΑΡΟΥΣΙΑΣΗ ΕΙΚΟΝΑΣ');
$smarty->assign('extra_style', $extra_style);
$smarty->assign('extra_javascript', '');
$smarty->assign('body_attributes', '');
$smarty->assign('h1_title', 'Παρουσίαση εικόνας');
$smarty->assign('q', $q);

$smarty->display('pictshow.tpl');
