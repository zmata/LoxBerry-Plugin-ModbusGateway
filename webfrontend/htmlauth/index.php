<?php
require_once "loxberry_system.php";
require_once "loxberry_web.php";
require_once "Config/Lite.php";

function zmata_option_set($optval, $value) {
  if ($value == $optval)
    $option = '<option value="'. $optval. '" selected>'. $optval. '</option>';
//    $option = '<option value="38400" selected>38400</option>';
  else
    $option = '<option value="'. $optval. '">'. $optval. '</option>';
//    $option = '<option value="38400">38400</option>';
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
/*=$L['GATEWAYS.DEVICE']
=$L['GATEWAYS.SPEED']
=$L['GATEWAYS.MODE']
=$L['GATEWAYS.TRX_CONTROL']
=$L['GATEWAYS.PORT']
=$L['GATEWAYS.MAXCONN']
=$L['GATEWAYS.TIMEOUT']
=$L['GATEWAYS.RETRIES']
=$L['GATEWAYS.PAUSE']
=$L['GATEWAYS.WAIT'] */
?>
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
          echo '<p>Parameters: '. $speed. ' '. $mode. ' '. $trx_control. ' '. $port. ' '. $maxconn. ' '. $timeout. ' '. $retries. ' '. $pause. ' '. $wait. '</p>';
?>
          <form>
              <label for="speed"><?=$L['GATEWAYS.SPEED']?></label>
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
              <label for="mode"><?=$L['GATEWAYS.MODE']?></label>
              <select name="mode" id="mode">
            <?php echo zmata_option_set("8n1", $mode); ?>
              </select>
              <label for="trx_control"><?=$L['GATEWAYS.TRX_CONTROL']?></label>
              <select name="trx_control" id="trx_control">
            <?php echo zmata_option_set("addc", $trx_control); 
                  echo zmata_option_set("rts", $trx_control); 
                  echo zmata_option_set("sysfs_0", $trx_control); 
                  echo zmata_option_set("sysfs_1", $trx_control); ?>
              </select>
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


