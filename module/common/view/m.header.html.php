<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include 'm.header.lite.html.php';
?>
<div data-role="header" data-position='fixed'>
  <div data-role="navbar" id='navbar'>
    <?php commonModel::printMainmenu($this->moduleName, $this->methodName)?>
    </ul>
  </div>
</div>
<div data-role="content">
  <div data-role='navbar' style='margin:-15px 0 15px 0;'>
