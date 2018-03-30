<?php
require_once "loxberry_system.php";
require_once "loxberry_web.php";
require_once "Config/Lite.php";

$file = $lbpconfigdir. '/mbusd-'. $_GET['gwfile']. '.conf';
$cfg = new Config_Lite("$file");
$cfgdevice = "/dev/serial/by-id/". $_GET['gwfile'];
$cfg->setQuoteStrings(False);
$cfg->set(null,"device",$cfgdevice);
$cfg->set(null,"speed","9600");
$cfg->set(null,"mode","8n1");
$cfg->set(null,"trx_control","addc");
$cfg->set(null,"port","502");
$cfg->set(null,"maxconn","32");
$cfg->set(null,"timeout","60");
$cfg->set(null,"retries","3");
$cfg->set(null,"pause","100");
$cfg->set(null,"wait","500");
$cfg->save();
$location = 'Location: index.php?gwfile=mbusd-'. $_GET['gwfile']. '.conf';
header ($location);
?>
