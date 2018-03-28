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
$navbar[2]['URL'] = 'settings.php';


// Activate the first element
$navbar[1]['active'] = True;

LBWeb::lbheader($template_title, $helplink, $helptemplate);

?>
<p><?=$L['MAIN.INTRO']?></p>
<p><?=$L['MAIN.USAGE1']?></p>
<p><?=$L['MAIN.USAGE2']?></p>
<p></p>




<?php
LBWeb::lbfooter();
?>
