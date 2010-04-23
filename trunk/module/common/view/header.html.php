<?php
include 'header.lite.html.php';
include 'colorbox.html.php';
?>
<script language='Javascript'>
/* 自动执行的代码。*/
$(document).ready(function() 
{
    $("a.about").colorbox({width:800, height:330, iframe:true, transition:'elastic', speed:500, scrolling:false});
});
</script>

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
  <div class='yui-u a-right'><?php common::printTopBar();?></div>
</div>
<div id='navbar' class='yui-d0'>
  <div id='mainmenu'><?php common::printMainmenu($this->moduleName);?></div>
  <div id='modulemenu'><?php common::printModuleMenu($this->moduleName);?></div>
</div>
