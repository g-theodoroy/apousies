<?php

require_once('common.php');



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

$smarty->assign('title', 'ΑΣΦΑΛΗΣ ΣΥΝΔΕΣΗ');
$smarty->assign('extra_style', $extra_style);
$smarty->assign('extra_javascript', '');
$smarty->assign('h1_title', 'Ασφαλής σύνδεση https');
$smarty->display('safety.tpl');

?>