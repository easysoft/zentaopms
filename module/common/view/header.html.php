<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include 'header.lite.html.php';
include 'colorbox.html.php';
?>
<div id='header'>
  <table class='cont' id='topbar'>
    <tr>
      <td class='w-p50'>
        <?php
        echo $app->company->name . $lang->colon;
        if($app->company->website)  echo html::a($app->company->website,  $lang->company->website,  '_blank');
        if($app->company->backyard) echo html::a($app->company->backyard, $lang->company->backyard, '_blank');
        echo html::a('#', $lang->switchHelp, '', "onclick='toggleHelpLink();'");
        echo html::select('', $app->config->langs, $app->getClientLang(),  'onchange="selectLang(this.value)"');
        echo html::select('', $app->lang->themes,  $app->getClientTheme(), 'onchange="selectTheme(this.value)"');
        ?>
      </td>
      <td class='a-right'><?php commonModel::printTopBar();?></td>
    </tr>
  </table>
  <table class='cont' id='navbar'>
    <tr><td id='mainmenu'><?php commonModel::printMainmenu($this->moduleName); commonModel::printSearchBox();?></td></tr>
    <tr><td id='modulemenu'><?php commonModel::printModuleMenu($this->moduleName);?></td></tr>
  </table>
</div>
<div class='outer'>
