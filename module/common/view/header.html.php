<?php include 'header.lite.html.php';?>
<div id='topbar' class='yui-d0 yui-t6'>
  <div class='yui-main'>
    <div class='yui-b'>
      <?php
      printf($lang->welcome, $app->company->name);
      if($app->company->website)  echo html::a($app->company->website,  $lang->company->website,  '_blank');
      if($app->company->backyard) echo html::a($app->company->backyard, $lang->company->backyard, '_blank');
      ?>
    </div>
  </div>
  <div class='yui-b a-right'><?php common::printTopBar();?></div>
</div>
<div id='navbar' class='yui-d0'>
  <div id='mainmenu'><?php common::printMainmenu($this->moduleName);?></div>
  <div id='modulemenu'><?php common::printModuleMenu($this->moduleName);?></div>
</div>
