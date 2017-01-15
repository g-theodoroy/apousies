<?php
require_once('common.php');
checkUser();
checktmima();

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user ='';
//isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima ='';

unlink ("upload/parous$user.txt");
header('Location: index.php');
?>