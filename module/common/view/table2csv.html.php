<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
css::import($defaultTheme . 'alert.css');
js::import($jsRoot . 'jquery/alert/raw.js');
js::import($jsRoot . 'jquery/table2csv/raw.js');
?>
