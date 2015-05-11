<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<!--[if lte IE 8]>
<?php
js::import($jsRoot . 'chartjs/excanvas.min.js');
?>
<![endif]-->
<?php
if($config->debug)
{
    js::import($jsRoot . 'chartjs/chart.min.js');
}
?>
