<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include 'header.lite.html.php';
include 'colorbox.html.php';
?>

<div id='topbar' class='yui-d0 yui-g'>
  <div class='yui-u first'>
    <div class='yui-b'>
      <?php
      echo $app->company->name . $lang->colon;
      if($app->company->website)  echo html::a($app->company->website,  $lang->company->website,  '_blank');
      if($app->company->backyard) echo html::a($app->company->backyard, $lang->company->backyard, '_blank');
      ?>
    </div>
  </div>
  <div class='yui-u a-right'><?php commonModel::printTopBar();?></div>
</div>
<div id='navbar' class='yui-d0'>
  <div id='mainmenu'><?php commonModel::printMainmenu($this->moduleName); commonModel::printSearchBox();?></div>
  <div id='modulemenu'><?php commonModel::printModuleMenu($this->moduleName);?></div>
</div>
