<?php
require_once('common.php');
checkUser();
checktmima();

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima = '';

require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty;
$smarty->assign('title', 'ΕΚΤΥΠΩΣΗ ΕΤΙΚΕΤΩΝ');
$smarty->assign('h1_title', 'Εκτύπωση ετικετών');
$extra_style = '
<style type="text/css">
    td, th {border:none;vertical-align: middle;}
</style>
';

$orio_paper = getparameter('orio_paper', $parent, $tmima);
        
$smarty->assign('extra_style', $extra_style);
$smarty->assign ( 'body_attributes', '' );
$smarty->assign ( 'extra_javascript', '' );
$smarty->assign('orio_paper', $orio_paper);

$smarty->display('labelsetup.tpl');
