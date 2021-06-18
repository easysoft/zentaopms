<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
  <?php
  foreach($lang->custom->object as $object => $name)
  {
      if(strpos('story|todo|block', $object) !== false) echo "<span class='divider'></span>";
      if(strpos('execution|product', $object) !== false) common::printLink('custom', $object, "", "<span class='text'>{$lang->custom->$object}</span>", '', "class='btn btn-link' id='{$object}Tab'");
      if(strpos('execution|product', $object) === false) common::printLink('custom', 'set', "module=$object&field=" . key($lang->custom->{$object}->fields), "<span class='text'>{$name}</span>", '', "class='btn btn-link' id='{$object}Tab'");
      if($object == 'user') common::printLink('custom', 'required', "", "<span class='text'>{$lang->custom->required}</span>", '', "class='btn btn-link' id='requiredTab'");
  }

  foreach($lang->custom->system as $sysObject)
  {
      if($sysObject == 'required') continue;
      common::printLink('custom', $sysObject, "", "<span class='text'>{$lang->custom->$sysObject}</span>", '', "class='btn btn-link' id='{$sysObject}Tab'");
  }
  if((isset($config->systemMode) and $config->systemMode == 'classic') || (isset($config->global->upgradeStep) and $config->global->upgradeStep == 'mergeProgram'))
  {
      common::printLink('custom', 'mode', "", "<span class='text'>{$lang->custom->mode}</span>", '', "class='btn btn-link' id='modeTab'");
  }
  ?>
  </div>
</div>
