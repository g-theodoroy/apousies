<?php
require_once('common.php');
checkUser();
checkParent();
if (isset($_SESSION["tmima"]))unset($_SESSION['tmima']);
header("Location: students.php")
?>