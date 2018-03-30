<?php
require_once "loxberry_system.php";
require_once "loxberry_web.php";
require_once "Config/Lite.php";

function zmata_option_set($optval, $value) {
  if ($value == $optval)
    $option = '<option value="'. $optval. '" selected>'. $optval. '</option>';
  else
    $option = '<option value="'. $optval. '">'. $optval. '</option>';
  return $option;
}

$L = LBWeb::readlanguage("language.ini");

$template_title = "Modbus Gateway";
$helplink = "http://www.loxwiki.eu:80/x/_wFmAQ";
$helptemplate = "pluginhelp.html";

$navbar[1]['Name'] = $L['NAVBAR.FIRST'];
$navbar[1]['URL'] = 'index.php';

$navbar[2]['Name'] = $L['NAVBAR.SECOND'];
//$navbar[2]['URL'] = 'settings.php';


// NAVBAR
$navbar[1]['active'] = True;

LBWeb::lbheader($template_title, $helplink, $helptemplate);

if ($_GET['action'] == 'new') {
//NEW  

  echo '<p class="wide">'. $L['GWNEW.HEAD']. '</p>';
  echo '<p>'. $L['GWNEW.TEXT']. '</p>';
  if ($handle = opendir('/dev/serial/by-id')) {
      while (false !== ($entry = readdir($handle))) {
          if ($entry != "." && $entry != "..") {
            $cfgfile = '/mbusd-'. $entry. '.conf';
            if (file_exists($lbpconfigdir. $cfgfile))
              echo '<div class="ui-corner-all ui-shadow"><a href="index.php?gwfile='. $cfgfile. '" data-role="button" data-inline="true" data-mini="true">'. $entry. '</a>'. $L['GWNEW.EXIST']. '</div>';
            else
              echo '<div class="ui-corner-all ui-shadow"><a href="new.php?gwfile='. $entry. '" data-role="button" data-inline="true" data-mini="true">'. $entry .'</a></div>';
          }
      }
      closedir($handle);
  }

}
//DEL
elseif ($_GET['action'] == 'del') {
  echo '<p class="wide">'. $L['GWDEL.HEAD']. '</p>';
  echo '<p>'. $L['GWDEL.TEXT']. $_GET['gwfile']. '</p>';
  echo '<a href="del.php?gwfile='. $_GET['gwfile']. '" data-role="button" data-inline="true" data-mini="true">'. $L['GWDEL.DELETE'] .'</a>';
  echo '<a href="index.php?gwfile='. $_GET['gwfile']. '" data-role="button" data-inline="true" data-mini="true">'. $L['GWDEL.RETURN'] .'</a></div>';
}
else {
//MAIN
?>
<p><?=$L['MAIN.INTRO1']?></p>
<br>

<?php
//GATEWAYS
echo '<p class="wide">'. $L['GATEWAYS.HEAD']. '</p>';
echo '<a href="index.php?action=new'. '" data-role="button" data-inline="true" data-mini="true" data-icon="plus">'. $L['GATEWAYS.NEW']. '</a>';
if ($_GET['gwfile'])
  $gwfile = $_GET['gwfile'];

if ($handle = opendir($lbpconfigdir)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
          // read cfg file
          $file = $lbpconfigdir. '/'. $entry;
          $cfg = new Config_Lite("$file");
          $device=$cfg->get(null,"device");
          $devfile_aray = explode("/",$device);
          $devfile = $devfile_aray[4];
          $command = $lbpbindir. '/service.sh status mbusd@'. $devfile. '.service  | grep Active';
          $status = shell_exec($command);
          echo '<div class="ui-corner-all ui-shadow">';
          echo '<a href="index.php?gwfile='. $entry. '" class="ui-btn ui-shadow ui-corner-all ui-icon-info ui-btn-icon-notext ui-btn-b ui-btn-inline">'. $L['GATEWAYS.DETAIL'] .'</a>';
          echo '<a href="index.php?action=del&gwfile='. $entry. '" class="ui-btn ui-shadow ui-corner-all ui-icon-delete ui-btn-icon-notext ui-btn-b ui-btn-inline">'. $L['GATEWAYS.DETAIL'] .'</a>';
          echo '<a href="index.php?gwfile='. $entry. '" data-role="button" data-inline="true" data-mini="true">'. $devfile .'</a>';
          if (substr($status,3,15) == 'Active: inactiv')
            echo '<a href="service.php?action=start&devfile='. $devfile. '" data-role="button" data-inline="true" data-mini="true">'. $L['GATEWAYS.START'] .'</a>';
          elseif (substr($status,3,15) == 'Active: active ') 
            echo '<a href="service.php?action=stop&devfile='. $devfile. '" data-role="button" data-inline="true" data-mini="true">'. $L['GATEWAYS.STOP'] .'</a>';
          else
            echo $status;
          echo '</div>';
          if (!$gwfile)
            $gwfile = $entry;
        }
    }
    closedir($handle);
}
?>
<br>
<br>
<?php
//DETAILS
echo '<p class="wide">'. $L['GWDETAIL.HEAD']. '</p>';
          // read cfg file
          $file = $lbpconfigdir. '/'. $gwfile;
          $cfg = new Config_Lite("$file");
          $device=$cfg->get(null,"device");
          $devfile_aray = explode("/",$device);
          $devfile = $devfile_aray[4];
          echo '<div>';
          echo '<p><b>'; ?> <?=$L['GATEWAYS.DEVICE']?> <?php echo ': '. $devfile. '</b></p>';
 
          $speed=$cfg->get(null,"speed");
          $mode=$cfg->get(null,"mode");
          $trx_control=$cfg->get(null,"trx_control");
          $port=$cfg->get(null,"port");
          $maxconn=$cfg->get(null,"maxconn");
          $timeout=$cfg->get(null,"timeout");
          $retries=$cfg->get(null,"retries");
          $pause=$cfg->get(null,"pause");
          $wait=$cfg->get(null,"wait");
?>
          <form>
              <label for="speed"><?=$L['GWDETAIL.SPEED1']?> <i>(<?=$L['GWDETAIL.SPEED2']?>)</i></label>
              <select data-inline="true" data-mini="true" name="speed" id="speed">
            <?php echo zmata_option_set("1200", $speed);
                  echo zmata_option_set("2400", $speed);
                  echo zmata_option_set("4800", $speed);
                  echo zmata_option_set("9600", $speed);
                  echo zmata_option_set("19200", $speed);
                  echo zmata_option_set("38400", $speed);
                  echo zmata_option_set("57600", $speed);
                  echo zmata_option_set("115200", $speed); ?>
              </select>
              <label for="mode"><?=$L['GWDETAIL.MODE1']?> <i>(<?=$L['GWDETAIL.MODE2']?>)</i></label>
            <?php echo '<input data-inline="true" data-mini="true" name="mode" id="mode" placeholder="Text input" value='. $mode. ' type="text">'; ?>
              <label for="trx_control"><?=$L['GWDETAIL.TRX_CONTROL1']?> <i>(<?=$L['GWDETAIL.TRX_CONTROL2']?>)</i></label>
              <select data-inline="true" data-mini="true" name="trx_control" id="trx_control">
            <?php echo zmata_option_set("addc", $trx_control); 
                  echo zmata_option_set("rts", $trx_control); 
                  echo zmata_option_set("sysfs_0", $trx_control); 
                  echo zmata_option_set("sysfs_1", $trx_control); ?>
              </select>
                  <label for="port"><?=$L['GWDETAIL.PORT1']?> <i>(<?=$L['GWDETAIL.PORT2']?>)</i></label>
            <?php echo '<input data-inline="true" data-mini="true" name="port" id="port" placeholder="Text input" value='. $port. ' type="text">'; ?>
                  <label for="maxconn"><?=$L['GWDETAIL.MAXCONN1']?> <i>(<?=$L['GWDETAIL.MAXCONN2']?>)</i></label>
            <?php echo '<input data-inline="true" data-mini="true" name="maxconn" id="maxconn" placeholder="Text input" value='. $maxconn. ' type="text">'; ?>
                  <label for="timeout"><?=$L['GWDETAIL.TIMEOUT1']?> <i>(<?=$L['GWDETAIL.TIMEOUT2']?>)</i></label>
            <?php echo '<input data-inline="true" data-mini="true" name="timeout" id="timeout" placeholder="Text input" value='. $timeout. ' type="text">'; ?>
                  <label for="retries"><?=$L['GWDETAIL.RETRIES1']?> <i>(<?=$L['GWDETAIL.RETRIES2']?>)</i></label>
            <?php echo '<input data-inline="true" data-mini="true" name="retries" id="retries" placeholder="Text input" value='. $retries. ' type="text">'; ?>
                  <label for="pause"><?=$L['GWDETAIL.PAUSE1']?> <i>(<?=$L['GWDETAIL.PAUSE2']?>)</i></label>
            <?php echo '<input data-inline="true" data-mini="true" name="pause" id="pause" placeholder="Text input" value='.$pause. ' type="text">'; ?>
                  <label for="wait"><?=$L['GWDETAIL.WAIT1']?> <i>(<?=$L['GWDETAIL.WAIT2']?>)</i></label>
            <?php echo '<input data-inline="true" data-mini="true" name="wait" id="wait" placeholder="Text input" value='. $wait. ' type="text">'; ?>
          </form>
<?php
          echo '</div>';
?>
</tbody>
</table>
<?php
}

LBWeb::lbfooter();
?>
