<?php
require_once('common.php');
logoutUser();
loginUser('demo','demopass');
set_select_tmima('demo');
header("Location: index.php")
?>
