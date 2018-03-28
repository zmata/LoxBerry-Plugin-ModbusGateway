<?php
require_once "loxberry_system.php";
require_once "loxberry_web.php";

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
<p class="wide"><?=$L['GATEWAYS.HEAD']?></p>
<p><a href='new.php'><?=$L['GATEWAYS.NEW']?></a></p>



<?php
LBWeb::lbfooter();
?>
