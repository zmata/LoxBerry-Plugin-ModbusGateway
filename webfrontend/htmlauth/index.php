<?php
require_once "loxberry_system.php";
require_once "loxberry_web.php";
require_once "Config/Lite.php";

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
=$L['GATEWAYS.CONTROL']
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
          $cfg = new Config_Lite("$file");
          $device=$cfg->get(null,"device");
          echo '<p>'; ?> <?=$L['GATEWAYS.DEVICE']?> <?php echo ': '. $device. '</p>';
 
          $speed=$cfg->get(null,"speed");
          $mode=$cfg->get(null,"mode");
          $control=$cfg->get(null,"control");
          $port=$cfg->get(null,"port");
          $maxconn=$cfg->get(null,"maxconn");
          $timeout=$cfg->get(null,"timeout");
          $retries=$cfg->get(null,"retries");
          $pause=$cfg->get(null,"pause");
          $wait=$cfg->get(null,"wait");
          echo '<p>Parameters: '. $speed. ' '. $mode. ' '. $control. ' '. $port. ' '. $maxconn. ' '. $timeout. ' '. $retries. ' '. $pause. ' '. $wait. '</p>';
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


