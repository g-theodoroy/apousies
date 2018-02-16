<?php

require_once('common.php');
checkUser();
checktmima();

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima = '';

/*
 * παραμετροι για κάθε τμήμα
 * $orio_adik       =   όριο αδικαιολόγητων απουσιών
 * $orio_dik        =   όριο δικαιολογημένων απουσιών πάνω από τις αδικαιολόγητες
 * $orio_paper      =   όριο απουσιών για αποστολή σημειωμάτων
 * 
 * $sch_name        =   όνομα του σχολείου
 * $sch_year        =   τρέχον σχολικό έτος
 * $sch_class       =   τάξη
 * $sch_tmima       =   τμήμα
 * 
 * $teach_arthro    =   Ο ή Η
 * $teach_last      =   ΤΗΣ ή ΤΡΙΑ
 * $teach_name      =   ΟΝΟΜΑΤΕΠΩΝΥΜΟ ΚΑΘΗΓΗΤΗ-ΤΡΙΑΣ
 * 
 */


if (isset($_POST['submitBtn'])) {
// Get user input
    $orio_adik = isset($_POST['orio_adik']) ? $_POST['orio_adik'] : '';
    $orio_dik = isset($_POST['orio_dik']) ? $_POST['orio_dik'] : '';
    $orio_paper = isset($_POST['orio_paper']) ? $_POST['orio_paper'] : '';

    $sch_name = isset($_POST['sch_name']) ? $_POST['sch_name'] : '';
    $sch_tel = isset($_POST['sch_tel']) ? $_POST['sch_tel'] : '';
    $sch_year = isset($_POST['sch_year']) ? $_POST['sch_year'] : '';
    $sch_class = isset($_POST['sch_class']) ? $_POST['sch_class'] : '';
    $sch_tmima = isset($_POST['sch_tmima']) ? $_POST['sch_tmima'] : '';

    $teach_arthro = isset($_POST['teach_arthro']) ? $_POST['teach_arthro'] : '';
    $teach_last = isset($_POST['teach_last']) ? $_POST['teach_last'] : '';
    $teach_name = isset($_POST['teach_name']) ? $_POST['teach_name'] : '';

    setparameter('orio_adik', $orio_adik, $parent, $tmima);
    setparameter('orio_dik', $orio_dik, $parent, $tmima);
    setparameter('orio_paper', $orio_paper, $parent, $tmima);

    setparameter('sch_name', $sch_name, $parent, $tmima);
    setparameter('sch_tel', $sch_tel, $parent, $tmima);
    setparameter('sch_year', $sch_year, $parent, $tmima);
    setparameter('sch_class', $sch_class, $parent, $tmima);
    setparameter('sch_tmima', $sch_tmima, $parent, $tmima);

    setparameter('teach_arthro', $teach_arthro, $parent, $tmima);
    setparameter('teach_last', $teach_last, $parent, $tmima);
    setparameter('teach_name', $teach_name, $parent, $tmima);
}

$orio_adik = getparameter('orio_adik', $parent, $tmima);
$orio_dik = getparameter('orio_dik', $parent, $tmima);
$orio_paper = getparameter('orio_paper', $parent, $tmima);

$sch_name = getparameter('sch_name', $parent, $tmima);
$sch_tel = getparameter('sch_tel', $parent, $tmima);
$sch_year = getparameter('sch_year', $parent, $tmima);
$sch_class = getparameter('sch_class', $parent, $tmima);
$sch_tmima = getparameter('sch_tmima', $parent, $tmima);

$teach_arthro = getparameter('teach_arthro', $parent, $tmima);
$teach_last = getparameter('teach_last', $parent, $tmima);
$teach_name = getparameter('teach_name', $parent, $tmima);


$smarty = new Smarty;

$extra_style = '
<style type="text/css">
	td { border-style:none;margin:0px;padding:0px;padding-right:5px;text-align:center;vertical-align:middle;}
	.nomargin{ margin:0px;padding:0px;}
	.r{text-align:right;}
	.c{text-align:center;vertical-align:middle;}
	input{text-align:center;}
</style>
';

$smarty->assign('title', 'ΡΥΘΜΙΣΕΙΣ');
$smarty->assign('extra_style', $extra_style);
$smarty->assign('extra_javascript', '');
$smarty->assign('body_attributes', '');
$smarty->assign('h1_title', 'Ρυθμίσεις');

$smarty->assign('orio_adik', $orio_adik);
$smarty->assign('orio_dik', $orio_dik);
$smarty->assign('orio_paper', $orio_paper);

$smarty->assign('sch_name', $sch_name);
$smarty->assign('sch_tel', $sch_tel);
$smarty->assign('sch_year', $sch_year);
$smarty->assign('sch_class', $sch_class);
$smarty->assign('sch_tmima', $sch_tmima);

$smarty->assign('teach_arthro', $teach_arthro);
$smarty->assign('teach_last', $teach_last);
$smarty->assign('teach_name', $teach_name);

$smarty->display('parameters.tpl');

?>
