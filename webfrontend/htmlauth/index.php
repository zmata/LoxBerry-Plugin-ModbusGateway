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

//MAIN
?>
<p><?=$L['MAIN.INTRO1']?></p>
<p><?=$L['MAIN.INTRO2']?></p>
<p><?=$L['MAIN.USAGE1']?></p>
<p><?=$L['MAIN.USAGE2']?></p>
<p></p>

<?php
//RS485
?>
<p class="wide"><?=$L['RS485.HEAD']?></p>
<p><?=$L['RS485.TEXT']?></p>

<?php
if ($handle = opendir('/dev/serial/by-id')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            echo "$entry\n";
        }
    }
    closedir($handle);
}
    
//GATEWAYS
?>
<br>
<br>
<p class="wide"><?=$L['GATEWAYS.HEAD']?></p>

<?php
if ($handle = opendir($lbpconfigdir)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
          // read cfg file
          $file = $lbpconfigdir. '/'. $entry;
//          echo '<p>'. $file. '</p>';
          $cfg = new Config_Lite("$file");
          $device=$cfg->get(null,"device");
          echo '<div>';
          echo '<p>'; ?> <?=$L['GATEWAYS.DEVICE']?> <?php echo ': '. $device. '</p>';
 
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
              <label for="speed"><?=$L['GATEWAYS.SPEED1']?> <i>(<?=$L['GATEWAYS.SPEED2']?>)</i></label>
              <select name="speed" id="speed">
            <?php echo zmata_option_set("1200", $speed);
                  echo zmata_option_set("2400", $speed);
                  echo zmata_option_set("4800", $speed);
                  echo zmata_option_set("9600", $speed);
                  echo zmata_option_set("19200", $speed);
                  echo zmata_option_set("38400", $speed);
                  echo zmata_option_set("57600", $speed);
                  echo zmata_option_set("115200", $speed); ?>
              </select>
              <label for="mode"><?=$L['GATEWAYS.MODE1']?> <i>(<?=$L['GATEWAYS.MODE2']?>)</i></label>
            <?php echo '<input name="mode" id="mode" placeholder="Text input" value='. $mode. ' type="text">'; ?>
              <label for="trx_control"><?=$L['GATEWAYS.TRX_CONTROL1']?> <i>(<?=$L['GATEWAYS.TRX_CONTROL2']?>)</i></label>
              <select name="trx_control" id="trx_control">
            <?php echo zmata_option_set("addc", $trx_control); 
                  echo zmata_option_set("rts", $trx_control); 
                  echo zmata_option_set("sysfs_0", $trx_control); 
                  echo zmata_option_set("sysfs_1", $trx_control); ?>
              </select>
                  <label for="port"><?=$L['GATEWAYS.PORT1']?> <i>(<?=$L['GATEWAYS.PORT2']?>)</i></label>
            <?php echo '<input name="port" id="port" placeholder="Text input" value='. $port. ' type="text">'; ?>
                  <label for="maxconn"><?=$L['GATEWAYS.MAXCONN1']?> <i>(<?=$L['GATEWAYS.MAXCONN2']?>)</i></label>
            <?php echo '<input name="maxconn" id="maxconn" placeholder="Text input" value='. $maxconn. ' type="text">'; ?>
                  <label for="timeout"><?=$L['GATEWAYS.TIMEOUT1']?> <i>(<?=$L['GATEWAYS.TIMEOUT2']?>)</i></label>
            <?php echo '<input name="timeout" id="timeout" placeholder="Text input" value='. $timeout. ' type="text">'; ?>
                  <label for="retries"><?=$L['GATEWAYS.RETRIES1']?> <i>(<?=$L['GATEWAYS.RETRIES2']?>)</i></label>
            <?php echo '<input name="retries" id="retries" placeholder="Text input" value='. $retries. ' type="text">'; ?>
                  <label for="pause"><?=$L['GATEWAYS.PAUSE1']?> <i>(<?=$L['GATEWAYS.PAUSE2']?>)</i></label>
            <?php echo '<input name="pause" id="pause" placeholder="Text input" value='.$pause. ' type="text">'; ?>
                  <label for="wait"><?=$L['GATEWAYS.WAIT1']?> <i>(<?=$L['GATEWAYS.WAIT2']?>)</i></label>
            <?php echo '<input name="wait" id="wait" placeholder="Text input" value='. $wait. ' type="text">'; ?>
          </form>
<?php
          echo '</div>';
        }
    }
    closedir($handle);
}
?>
</tbody>
</table>
<p><a href='new.php'><?=$L['GATEWAYS.NEW']?></a></p>



<?php
LBWeb::lbfooter();
?>


