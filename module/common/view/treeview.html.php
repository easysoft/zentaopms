<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
if($config->debug)
{
    css::import($defaultTheme . 'treeview.css');
    js::import($jsRoot . 'jquery/treeview/min.js');
}
?>
<script language='javascript'>$(function() { $(".tree").treeview({ persist: "cookie", collapsed: true, unique: false}) })</script>
