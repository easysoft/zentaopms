<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php 
css::import($defaultTheme . 'superfish/superfish.css');
js::import($jsRoot . 'jquery/superfish/min.js');
?>
