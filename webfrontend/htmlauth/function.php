<?php
//require_once "loxberry_system.php";
//require_once "loxberry_web.php";
require_once "Config/Lite.php";

function zmata_conf($confdir, $device, $speed, $mode, $trx_control, $port, $maxconn, $timeout, $retries, $pause, $wait) {
  // read cfg global file
  $serialcfg = $confdir. '/mbusd.cfg';
  $scfg = new Config_Lite("$serialcfg");
  $serialpath=$scfg->get(null,"SERIAL");
  if (!$serialpath) {
    $serialpath = '/dev/serial/by-id/';
  }
  // read conf file
  $file = $confdir. '/mbusd-'. $device. '.conf';
  $cfg = new Config_Lite("$file");
  $cfgdevice = $serialpath. $device;
  $cfg->setQuoteStrings(False);
  $cfg->set(null,"device",$cfgdevice);
  $cfg->set(null,"speed",$speed);
  $cfg->set(null,"mode",$mode);
  $cfg->set(null,"trx_control",$trx_control);
  $cfg->set(null,"port",$port);
  $cfg->set(null,"maxconn",$maxconn);
  $cfg->set(null,"timeout",$timeout);
  $cfg->set(null,"retries",$retries);
  $cfg->set(null,"pause",$pause);
  $cfg->set(null,"wait",$wait);
  $cfg->save();
}

function zmata_cfg($confdir, $device, $loglevel) {
  $level = '-v'. $loglevel;
  $file = $confdir. '/mbusd-'. $device. '.cfg';
  $cfg = new Config_Lite("$file");
  $cfg->setQuoteStrings(True);
  $cfg->set(null,"OPTIONS",$level);
  $cfg->save();
}
?>

