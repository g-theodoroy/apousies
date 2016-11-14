<?php
require_once('common.php');
checkUser();

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user ='';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima ='';

require_once 'Smarty/Smarty.class.php';
$smarty = new Smarty;


$smarty->assign('title', 'ΣΤΗΡΙΖΩ ΤΗ ΔΙΑΧΕΙΡΙΣΗ ΑΠΟΥΣΙΩΝ');
$smarty->assign('extra_style', '');
$smarty->assign('extra_javascript', '');
$smarty->assign('body_attributes', '');
$smarty->assign('h1_title', 'Στηρίζω τη Διαχείριση Απουσιών');

$smarty->display('support.tpl');

