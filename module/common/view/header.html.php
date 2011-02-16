<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include 'header.lite.html.php';
include 'colorbox.html.php';
?>
<div id='header'>
  <div class='g' id='topbar'>
    <div class='u-1-2'>
      <div class='cont a-left'>
        <?php
        echo $app->company->name . $lang->colon;
        if($app->company->website)  echo html::a($app->company->website,  $lang->company->website,  '_blank');
        if($app->company->backyard) echo html::a($app->company->backyard, $lang->company->backyard, '_blank');
        echo html::a('#', $lang->switchHelp, '', "onclick='toggleHelpLink();'");
        echo html::select('', $app->config->langs, $this->cookie->lang,  'class=switcher onchange="selectLang(this.value)"');
        echo html::select('', $app->lang->themes,  $this->cookie->theme, 'class=switcher onchange="selectTheme(this.value)"');
        ?>
      </div>
    </div>
    <div class='u-1-2'><div class='cont a-right'><?php commonModel::printTopBar();?></div></div>
  </div>
  <div id='navbar'>
    <div id='mainmenu'><?php commonModel::printMainmenu($this->moduleName); commonModel::printSearchBox();?></div>
    <div id='modulemenu'><?php commonModel::printModuleMenu($this->moduleName);?></div>
  </div>
</div>
