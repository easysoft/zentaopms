<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
  <?php
  foreach($lang->custom->object as $object => $name)
  {
      common::printLink('custom', 'set', "module=$object", "<span class='text'>{$name}</span>", '', "class='btn btn-link' id='{$object}Tab'"); 
  }
  common::printLink('custom', 'flow', "", "<span class='text'>{$lang->custom->flow}</span>", '', "class='btn btn-link' id='flowTab'"); 
  common::printLink('custom', 'working', '', "<span class='text'>{$lang->custom->working}</span>", '', "class='btn btn-link' id='workingTab'"); 
  common::printLink('custom', 'required', '', "<span class='text'>{$lang->custom->required}</span>", '', "class='btn btn-link' id='requiredTab'");
  common::printLink('custom', 'score', '', "<span class='text'>{$lang->custom->score}</span>", '', "class='btn btn-link' id='scoreTab'");
  ?>
  </div>
</div>
