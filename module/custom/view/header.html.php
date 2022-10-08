<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
  <?php
  $menuOrder = $lang->custom->menuOrder;
  ksort($menuOrder);

  foreach($menuOrder as $order => $object)
  {
      $name = $lang->custom->object[$object];
      if(strpos($lang->custom->dividerMenu, $object) !== false) echo "<span class='divider'></span>";
      if(strpos($lang->custom->separatePage, $object))
      {
          common::printLink('custom', $object, "", "<span class='text'>{$lang->custom->$object}</span>", '', "class='btn btn-link' id='{$object}Tab'");
      }
      else
      {
          common::printLink('custom', 'set', "module=$object&field=" . key($lang->custom->{$object}->fields), "<span class='text'>{$name}</span>", '', "class='btn btn-link' id='{$object}Tab'");
      }
      if($object == 'user') common::printLink('custom', 'required', "", "<span class='text'>{$lang->custom->required}</span>", '', "class='btn btn-link' id='requiredTab'");
  }

  if($config->systemMode == 'classic') common::printLink('custom', 'mode', "", "<span class='text'>{$lang->custom->mode}</span>", '', "class='btn btn-link' id='modeTab'");
  ?>
  </div>
</div>
