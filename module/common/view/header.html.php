<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include 'header.lite.html.php';
include 'colorbox.html.php';
include 'chosen.html.php';
//include 'validation.html.php';
?>
<?php if(empty($_GET['onlybody']) or $_GET['onlybody'] != 'yes'):?>
<header id='header'>
  <div id='topbar'>
    <div class='pull-right' id='topnav'><?php commonModel::printTopBar();?></div>
    <h5 id='companyname'>
      <?php echo $app->company->name;?>
      <small class='actions'>
        <?php
        if($app->company->website)  echo html::a($app->company->website,  $lang->company->website,  '_blank');
        if($app->company->backyard) echo html::a($app->company->backyard, $lang->company->backyard, '_blank');      
        ?>
      </small>
    </h5>
  </div>
  <nav id='mainmenu'>
    <?php commonModel::printMainmenu($this->moduleName); commonModel::printSearchBox();?>
  </nav>
  <nav id="modulemenu">
    <?php commonModel::printModuleMenu($this->moduleName);?>
  </nav>
</header>

<div id='wrap'>
<?php endif;?>
  <div class='outer'>
