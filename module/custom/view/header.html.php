<?php include '../../common/view/header.html.php';?>
<div id='featurebar'>
  <div class='f-left'>
  <?php
  echo "<span id='storyTab'>";    common::printLink('custom', 'index', "module=story",    $lang->custom->story);    echo '</span>';
  echo "<span id='taskTab'>";     common::printLink('custom', 'index', "module=task",     $lang->custom->task);     echo '</span>';
  echo "<span id='bugTab'>";      common::printLink('custom', 'index', "module=bug",      $lang->custom->bug);      echo '</span>';
  echo "<span id='testcaseTab'>"; common::printLink('custom', 'index', "module=testcase", $lang->custom->testcase); echo '</span>';
  echo "<span id='testtaskTab'>"; common::printLink('custom', 'index', "module=testtask", $lang->custom->testtask); echo '</span>';
  echo "<span id='todoTab'>";     common::printLink('custom', 'index', "module=todo",     $lang->custom->todo);     echo '</span>' ;
  echo "<span id='userTab'>";     common::printLink('custom', 'index', "module=user",     $lang->custom->user);     echo '</span>';
  echo "<script>$('#{$module}Tab').addClass('active')</script>";
  ?>
  </div>
</div>
