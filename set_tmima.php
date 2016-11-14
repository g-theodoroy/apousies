<?php

require_once 'common.php';
checkUser();

isset($_GET['t']) ? $tmima = $_GET['t'] : $tmima = $_SESSION['tmima'];
$referer = $_SERVER['HTTP_REFERER'];

$_SESSION['tmima'] = $tmima;

header("Location: $referer");
?>
