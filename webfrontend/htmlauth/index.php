<?php
require_once "loxberry_system.php";
require_once "loxberry_web.php";
require_once "Config/Lite.php";
require_once "function.php";

function zmata_option_set($optval, $pre, $ext, $value) {
  $opt = $pre. $optval. $ext;
  if ($value == $opt)
    $option = '<option value="'. $optval. '" selected>'. $optval. '</option>';
  else
    $option = '<option value="'. $optval. '">'. $optval. '</option>';
  return $option;
}

$L = LBWeb::readlanguage("language.ini");

$template_title = "Modbus Gateway";
$helplink = $L['LINKS.WIKI'];

$helptemplate = "pluginhelp.html";

$navbar[1]['Name'] = $L['NAVBAR.FIRST'];
$navbar[1]['URL'] = 'index.php';

$navbar[2]['Name'] = $L['NAVBAR.SECOND'];
$navbar[2]['URL'] = 'log.php';

if ($_POST['req_start']) {
  $command = 'sudo '. $lbpbindir. '/service.sh start mbusd@'. $_POST[device]. '.service';
  $cmdstat = shell_exec($command);
  $command = 'sudo '. $lbpbindir. '/service.sh enable mbusd@'. $_POST[device]. '.service';
  $cmdstat = shell_exec($command);
  header ("Location: index.php");
}

if ($_POST['req_stop']) {
  $command = 'sudo '. $lbpbindir. '/service.sh stop mbusd@'. $_POST[device]. '.service';
  $cmdstat = shell_exec($command);
  $command = 'sudo '. $lbpbindir. '/service.sh disable mbusd@'. $_POST[device]. '.service';
  $cmdstat = shell_exec($command);
  header ("Location: index.php");
}

if ($_POST['save_new']) {
  zmata_conf($lbpconfigdir, $_POST[device], '9600', '8n1', 'addc', '502', '32', '60', '3', '100', '500');
  zmata_cfg($lbpconfigdir, $_POST[device], '2');
  header ("Location: index.php");
}

if ($_POST['change']) {
  zmata_conf($lbpconfigdir, $_POST[device], $_POST['speed'], $_POST['mode'], $_POST['trx_control'], $_POST['port'], $_POST['maxconn'], $_POST['timeout'], $_POST['retries'], $_POST['pause'], $_POST['wait']);  
  zmata_cfg($lbpconfigdir, $_POST[device], $_POST['loglevel']);

  $command = 'sudo '. $lbpbindir. '/service.sh restart mbusd@'. $_POST[device]. '.service';
  $cmdstat = shell_exec($command);
  header ("Location: index.php");
}

if ($_POST['save_del']) {
  //conf
  $cfgfile = $lbpconfigdir. '/mbusd-'.  $_POST['device']. '.conf';
  unlink($cfgfile);
  //cfg
  $cfgfile = $lbpconfigdir. '/mbusd-'.  $_POST['device']. '.cfg';
  unlink($cfgfile);

  header ("Location: index.php");
}

// NAVBAR
$navbar[1]['active'] = True;

LBWeb::lbheader($template_title, $helplink, $helptemplate);

//NEW
if ($_POST['req_new']) {
  echo '<p class="wide">'. $L['GWNEW.HEAD']. '</p>';
  echo '<p>'. $L['GWNEW.TEXT']. '</p>';
  if ($handle = opendir('/dev/serial/by-id')) {
    while (false !== ($device = readdir($handle))) {
      if ($device != "." && $device != "..") {
        $file = 'mbusd-'. $device. '.conf';
        echo '<div class="ui-corner-all ui-shadow">';
        echo '<form action="index.php" method="post">';
        echo '<input type="hidden" name="device" value="'. $device. '">';
        if (file_exists($lbpconfigdir. '/'. $file))
          echo '<input data-role="button" data-inline="true" data-mini="true" data-icon="info" type="submit" name="return" value='. $device. '>'. $L['GWNEW.EXIST'];
        else
          echo '<input data-role="button" data-inline="true" data-mini="true" data-icon="plus" type="submit" name="save_new" value='. $device. '>';
        echo '</form>';
        echo '</div>';
      }
    }
    closedir($handle);
  }
}

//DEL
elseif ($_POST['req_del']) {
  echo '<p class="wide">'. $L['GWDEL.HEAD']. '</p>';
  echo '<div class="ui-corner-all ui-shadow">';
  echo '<form action="index.php" method="post">';
  echo '<input type="hidden" name="device" value="'. $_POST['device']. '">';
  if ($_POST['status'] == 'inacti') {
    echo '<p>'. $L['GWDEL.TEXT']. ' '. $_POST['device']. '</p>';
    echo '<input data-role="button" data-inline="true" data-mini="true" data-icon="delete" type="submit" name="save_del" value='. $L['GWDEL.DELETE']. '>';
  }
  else {
    echo '<p>'. $L['GWDEL.ACTIVE']. '</p>';
  }
  echo '<input data-role="button" data-inline="true" data-mini="true" data-icon="back" type="submit" name="return" value='. $L['GWDEL.RETURN']. '>';
  echo '</form>';
  echo '</div>';
}

//MAIN
else {
  echo '<p>'. $L['MAIN.INTRO1']. '</p>';
  echo '<br>';

  //GATEWAYS
  echo '<p class="wide">'. $L['GATEWAYS.HEAD']. '</p>';
  echo '<form action="index.php" method="post">';
  echo '<input data-role="button" data-inline="true" data-mini="true" data-icon="plus" type="submit" name="req_new" value='. $L['GATEWAYS.NEW']. '>';
  echo '</form>';
  
  if ($_POST['device'])
    $gwdevice = $_GET['device'];

  $mask = $lbpconfigdir. "/". "*.conf";
  foreach (glob($mask) as $file) {
    // read conf file
    $cfg = new Config_Lite("$file");
    $devfile=$cfg->get(null,"device");
    $devfile_aray = explode("/",$devfile);
    $device = $devfile_aray[4];
    $command = $lbpbindir. '/service.sh status mbusd@'. $device. '.service  | grep Active';
    $cmd = shell_exec($command);
    $status = substr($cmd,11,6);
    echo '<div class="ui-corner-all ui-shadow ui-field-contain">';
    echo '<form action="index.php" method="post">';
    echo '<input type="hidden" name="device" value="'. $device. '">';
    echo '<input type="hidden" name="status" value="'. $status. '">';
    echo '<input data-role="button" data-inline="true" data-mini="true" data-icon="delete" type="submit" name="req_del" value='. $L['GATEWAYS.DEL']. '>';
    echo '<input data-role="button" data-inline="true" data-mini="true" data-icon="info" type="submit" name="show_detail" value='. $device. '>';
    if (substr($cmd,3,16) == 'Active: inactive')
      echo '<input data-role="button" data-inline="true" data-mini="true" data-icon="check" type="submit" name="req_start" value='. $L['GATEWAYS.START']. '>';
    elseif (substr($cmd,3,15) == 'Active: active ') 
      echo '<input data-role="button" data-inline="true" data-mini="true" data-icon="delete" type="submit" name="req_stop" value='. $L['GATEWAYS.STOP']. '>';
    else
      echo $cmd;
    echo '</form>';
    echo '</div>';

    if (!$gwdevice)
      $gwdevice = $device;
    $found = 'X';

    //if cfg not exist --> create it and restart service if running
    $filecfg = $lbpconfigdir. '/mbusd-'. $gwdevice. '.cfg';
    if (!file_exists($filecfg)) {
      zmata_cfg($lbpconfigdir, $gwdevice, '2');
      if ($_POST['status'] != 'inacti') {
        $command = 'sudo '. $lbpbindir. '/service.sh restart mbusd@'. $_POST[device]. '.service';
        $cmdstat = shell_exec($command);
      }
    }
  }

  //DETAILS
  if ($found) {
    echo '<br><br>';
    echo '<p class="wide">'. $L['GWDETAIL.HEAD']. '</p>';
    // read conf file
    $file = $lbpconfigdir. '/mbusd-'. $gwdevice. '.conf';
    $cfg = new Config_Lite("$file");
    $devfile=$cfg->get(null,"device");
    echo '<div>';
    echo '<p><b>'. $L['GATEWAYS.DEVICE']. ': '. $gwdevice. '</b></p>';
    $speed=$cfg->get(null,"speed");
    $mode=$cfg->get(null,"mode");
    $trx_control=$cfg->get(null,"trx_control");
    $port=$cfg->get(null,"port");
    $maxconn=$cfg->get(null,"maxconn");
    $timeout=$cfg->get(null,"timeout");
    $retries=$cfg->get(null,"retries");
    $pause=$cfg->get(null,"pause");
    $wait=$cfg->get(null,"wait");
    //write form
    echo '<form action="index.php" method="post">';
    echo '<label for="speed">'. $L['GWDETAIL.SPEED1']. ' <i>('. $L['GWDETAIL.SPEED2']. ')</i></label>';
    echo '<input type="hidden" name="device" value="'. $gwdevice. '">';
    echo '<select data-inline="true" data-mini="true" name="speed" id="speed">';
      echo zmata_option_set("1200",   "", "", $speed);
      echo zmata_option_set("2400",   "", "", $speed);
      echo zmata_option_set("4800",   "", "", $speed);
      echo zmata_option_set("9600",   "", "", $speed);
      echo zmata_option_set("19200",  "", "", $speed);
      echo zmata_option_set("38400",  "", "", $speed);
      echo zmata_option_set("57600",  "", "", $speed);
      echo zmata_option_set("115200", "", "", $speed);
    echo '</select>';
    echo '<label for="mode">'. $L['GWDETAIL.MODE1']. ' <i>('. $L['GWDETAIL.MODE2']. ')</i></label>';
    echo '<input data-inline="true" data-mini="true" name="mode" id="mode" placeholder="Text input" value='. $mode. ' type="text">';
    echo '<label for="trx_control">'. $L['GWDETAIL.TRX_CONTROL1']. ' <i>('. $L['GWDETAIL.TRX_CONTROL2']. ')</i></label>';
    echo '<select data-inline="true" data-mini="true" name="trx_control" id="trx_control">';
      echo zmata_option_set("addc",    "", "", $trx_control);
      echo zmata_option_set("rts",     "", "", $trx_control);
      echo zmata_option_set("sysfs_0", "", "", $trx_control);
      echo zmata_option_set("sysfs_1", "", "", $trx_control);
    echo '</select>';
    echo '<label for="port">'. $L['GWDETAIL.PORT1']. ' <i>('. $L['GWDETAIL.PORT2']. ')</i></label>';
    echo '<input data-inline="true" data-mini="true" name="port" id="port" placeholder="Text input" value='. $port. ' type="text">';
    echo '<label for="maxconn">'. $L['GWDETAIL.MAXCONN1']. ' <i>('. $L['GWDETAIL.MAXCONN2']. ')</i></label>';
    echo '<input data-inline="true" data-mini="true" name="maxconn" id="maxconn" placeholder="Text input" value='. $maxconn. ' type="text">';
    echo '<label for="timeout">'. $L['GWDETAIL.TIMEOUT1']. ' <i>('. $L['GWDETAIL.TIMEOUT2']. ')</i></label>';
    echo '<input data-inline="true" data-mini="true" name="timeout" id="timeout" placeholder="Text input" value='. $timeout. ' type="text">';
    echo '<label for="retries">'. $L['GWDETAIL.RETRIES1']. ' <i>('. $L['GWDETAIL.RETRIES2']. ')</i></label>';
    echo '<input data-inline="true" data-mini="true" name="retries" id="retries" placeholder="Text input" value='. $retries. ' type="text">';
    echo '<label for="pause">'. $L['GWDETAIL.PAUSE1']. ' <i>('. $L['GWDETAIL.PAUSE2']. ')</i></label>';
    echo '<input data-inline="true" data-mini="true" name="pause" id="pause" placeholder="Text input" value='.$pause. ' type="text">';
    echo '<label for="wait">'. $L['GWDETAIL.WAIT1']. ' <i>('. $L['GWDETAIL.WAIT2']. ')</i></label>';
    echo '<input data-inline="true" data-mini="true" name="wait" id="wait" placeholder="Text input" value='. $wait. ' type="text">';
    // read cfg file
    $filecfg = $lbpconfigdir. '/mbusd-'. $gwdevice. '.cfg';
    $cfg = new Config_Lite("$filecfg");
    $loglevel=$cfg->get(null,"OPTIONS");
    echo '<label for="loglevel">'. $L['GWDETAIL.LOGLEVEL1']. ' <i>('. $L['GWDETAIL.LOGLEVEL2']. ')</i></label>';
    echo '<select data-inline="true" data-mini="true" name="loglevel" id="loglevel">';
      echo zmata_option_set("0", "-v", "", $loglevel);
      echo zmata_option_set("1", "-v", "", $loglevel);
      echo zmata_option_set("2", "-v", "", $loglevel);
      echo zmata_option_set("3", "-v", "", $loglevel);
      echo zmata_option_set("4", "-v", "", $loglevel);
      echo zmata_option_set("5", "-v", "", $loglevel);
      echo zmata_option_set("6", "-v", "", $loglevel);
      echo zmata_option_set("7", "-v", "", $loglevel);
      echo zmata_option_set("8", "-v", "", $loglevel);
      echo zmata_option_set("9", "-v", "", $loglevel);
    echo '</select>';
    echo '<br><input data-role="button" data-inline="true" data-mini="true" type="submit" name="change" value='. $L['GWDETAIL.SUBMIT']. '>';
    echo '</form>';
    echo '</div>';
  }
}

LBWeb::lbfooter();
?>
