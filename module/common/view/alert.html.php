<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$webRoot      = $this->app->getWebRoot();
$jsRoot       = $webRoot . "js/";
$defaultTheme = $webRoot . 'theme/default/';
css::import($defaultTheme . 'alert.css');
js::import($jsRoot . 'jquery/alert/min.js');
