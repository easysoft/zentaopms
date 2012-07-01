<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include 'header.lite.html.php';
include 'colorbox.html.php';
include 'chosen.html.php';
?>
<div id='header'>
  <table class='cont' id='topbar'>
    <tr>
      <td class='w-p50'>
        <?php
        echo "<span id='companyname'>{$app->company->name}</span> ";
        if($app->company->website)  echo html::a($app->company->website,  $lang->company->website,  '_blank');
        if($app->company->backyard) echo html::a($app->company->backyard, $lang->company->backyard, '_blank');
        ?>
      </td>
      <td class='a-right'><?php commonModel::printTopBar();?></td>
    </tr>
  </table>
  <table class='cont' id='navbar'>
    <tr><td id='mainmenu'><?php commonModel::printMainmenu($this->moduleName); commonModel::printSearchBox();?></td></tr>
  </table>
</div>
<table class='cont' id='navbar'>
   <tr><td id='modulemenu'><?php commonModel::printModuleMenu($this->moduleName);?></td></tr>
</table>
<div id='wrap'>
  <div class='outer'>
