<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php 
if($config->debug)
{
    css::import($defaultTheme . 'colorbox.css');
    js::import($jsRoot . 'jquery/colorbox/min.js');
}
