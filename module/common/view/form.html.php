<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php js::set('timeout', $this->lang->timeout)?>
<?php
js::import($jsRoot . 'jquery/form/min.js');
js::import($jsRoot . 'jquery/form/zentao.js');
