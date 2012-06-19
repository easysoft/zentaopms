<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
css::import($defaultTheme . 'chosen.css');
js::import($jsRoot . 'jquery/chosen/chosen.min.js');
?>
