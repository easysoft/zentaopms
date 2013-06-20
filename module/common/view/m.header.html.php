<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include 'm.header.lite.html.php';
?>
<div data-role="header">
  <div data-role="navbar" id='navbar'>
    <?php commonModel::printMainmenu($this->moduleName, $this->methodName)?>
    </ul>
  </div>
</div>
<div data-role="content">
