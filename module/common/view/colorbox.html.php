<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php 
css::import($defaultTheme . 'colorbox.css');
js::import($jsRoot . 'jquery/colorbox/min.js');
?>
