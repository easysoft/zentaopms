<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php 
css::import($defaultTheme . 'treetable.css');
js::import($jsRoot . 'jquery/treetable/min.js');
?>
<script language='javascript'>$(function() { $("#treetable").treeTable({clickableNodeNames:true,initialState:"expanded"})})</script>
