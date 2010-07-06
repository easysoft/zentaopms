<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<script src='<?php echo $jsRoot;?>jquery/colorize/full.js' type='text/javascript'></script>
<script language='javascript'>$(function(){$('.colored').colorize();});</script>
