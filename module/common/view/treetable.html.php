<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<link rel='stylesheet' href='<?php echo $defaultTheme;?>treetable.css' type='text/css' />
<script src='<?php echo $jsRoot;?>jquery/treetable/min.js' type='text/javascript'></script>
<script language='javascript'>$(function() { $("#treetable").treeTable({clickableNodeNames:true,initialState:"expanded"})})</script>
