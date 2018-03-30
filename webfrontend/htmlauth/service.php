<?php
require_once "loxberry_system.php";
require_once "loxberry_web.php";
require_once "Config/Lite.php";

if ($_GET['action'] == 'start') {
  $command = 'sudo '. $lbpbindir. '/service.sh start mbusd@'. $_GET[devfile]. '.service';
  $status = shell_exec($command);
  $command = 'sudo '. $lbpbindir. '/service.sh enable mbusd@'. $_GET[devfile]. '.service';
  $status = shell_exec($command);
}
elseif ($_GET['action'] == 'stop') {
  $command = 'sudo '. $lbpbindir. '/service.sh stop mbusd@'. $_GET[devfile]. '.service';
  $status = shell_exec($command);
  $command = 'sudo '. $lbpbindir. '/service.sh disable mbusd@'. $_GET[devfile]. '.service';
  $status = shell_exec($command);
}
$location = 'Location: index.php';
header ($location);
?>
