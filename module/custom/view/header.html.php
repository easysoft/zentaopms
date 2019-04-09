<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
  <?php
  foreach($lang->custom->object as $object => $name)
  {
      common::printLink('custom', 'set', "module=$object", "<span class='text'>{$name}</span>", '', "class='btn btn-link' id='{$object}Tab'"); 
  }

  foreach($lang->custom->system as $sysObject)
  {
      common::printLink('custom', $sysObject, "", "<span class='text'>{$lang->custom->$sysObject}</span>", '', "class='btn btn-link' id='{$sysObject}Tab'");
  }
  ?>
  </div>
</div>
