<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
css::import($defaultTheme . 'autocomplete.css');
js::import($jsRoot . 'jquery/autocomplete/autocomplete.min.js');
?>
