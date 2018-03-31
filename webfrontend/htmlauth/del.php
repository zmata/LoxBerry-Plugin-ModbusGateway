<?php
require_once "loxberry_system.php";
require_once "loxberry_web.php";
require_once "Config/Lite.php";

$cfgfile = $lbpconfigdir. '/'.  $_GET['gwfile'];
unlink($cfgfile);
$location = 'Location: index.php';
header ($location);
?>
