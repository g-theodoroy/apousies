<?php

require_once 'common.php';
checkUser();

isset($_GET['t']) ? $tmima = $_GET['t'] : $tmima = $_SESSION['tmima'];
$referer = $_SERVER['HTTP_REFERER'];

if ($tmima == ''){
    unset($_SESSION['tmima']);
}else{
    $_SESSION['tmima'] = $tmima;
}
header("Location: $referer");
?>
