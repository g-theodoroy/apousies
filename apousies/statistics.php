<?php
require_once('common.php');
checkUser();

isset ( $_SESSION ['parentUser'] ) ? $parentUser = $_SESSION ['parentUser'] : $parentUser = false;
if (! $parentUser){
	checktmima();
}

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';


$extra_javascript = '
    <script type="text/javascript" language="javascript" >
    // <!--

    function valid(form){
        return true;
    }
    // -->
</script>

';



$smarty = new Smarty;

$smarty->assign('title', 'ΣΤΑΤΙΣΤΙΚΑ');
$smarty->assign('h1_title', 'Στατιστικά Μαθητών');
$smarty->assign('extra_javascript', $extra_javascript);

$smarty->display('statistics.tpl');

?>