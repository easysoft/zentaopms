<?php include '../../common/view/header.html.php';?>
<div id='featurebar'>
  <ul class='nav'>
  <?php
  foreach($lang->custom->object as $object => $name)
  {
      echo "<li id='{$object}Tab'>"; 
      common::printLink('custom', 'set', "module=$object", $name); 
      echo '</li>';
  }
  echo "<li id='flowTab'>"; 
  common::printLink('custom', 'flow', "", $lang->custom->flow); 
  echo "</li><li id='workingTab'>"; 
  common::printLink('custom', 'working', '', $lang->custom->working); 
  echo "</li><li id='requiredTab'>"; 
  common::printLink('custom', 'required', '', $lang->custom->required);
  echo "</li><li id='scoreTab'>";
  common::printLink('custom', 'score', '', $lang->custom->score);
  echo '</li>';
  ?>
  </ul>
</div>
