<?php include '../../common/view/header.html.php';?>
<div id='featurebar'>
  <div class='f-left'>
  <?php
  echo "<span id='storyTab'>";    common::printLink('customlang', 'story',    "", $lang->customlang->story);    echo '</span>';
  echo "<span id='taskTab'>";     common::printLink('customlang', 'task',     "", $lang->customlang->task);     echo '</span>';
  echo "<span id='bugTab'>";      common::printLink('customlang', 'bug',      "", $lang->customlang->bug);      echo '</span>';
  echo "<span id='testcaseTab'>"; common::printLink('customlang', 'testcase', "", $lang->customlang->testcase); echo '</span>';
  echo "<span id='testtaskTab'>"; common::printLink('customlang', 'testtask', "", $lang->customlang->testtask); echo '</span>';
  echo "<span id='todoTab'>";     common::printLink('customlang', 'todo',     "", $lang->customlang->todo);     echo '</span>' ;
  echo "<span id='userTab'>";     common::printLink('customlang', 'user',     "", $lang->customlang->user);     echo '</span>';
  echo "<script>$('#{$this->methodName}Tab').addClass('active')</script>";
  ?>
  </div>
</div>
