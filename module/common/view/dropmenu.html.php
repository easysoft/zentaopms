<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
css::import($defaultTheme . 'dropmenu.css');
js::import($jsRoot . 'jquery/dropmenu/dropmenu.js');
?>
