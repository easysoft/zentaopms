<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include 'header.lite.html.php';
include 'chosen.html.php';
//include 'validation.html.php';
?>
<?php if(empty($_GET['onlybody']) or $_GET['onlybody'] != 'yes'):?>
<?php $this->app->loadConfig('sso');?>
<?php 
    if(!empty($this->config->sso->redirect)) js::set('ssoRedirect', $this->config->sso->redirect);
?>
<header id='header'>
<?php if(empty($this->config->sso->redirect)):?>
  <div id='topbar'>
    <div class='pull-right' id='topnav'><?php commonModel::printTopBar();?></div>
    <h5 id='companyname'>
      <?php printf($lang->welcome, $app->company->name);?>
    </h5>
  </div>
<?php endif;?>
<?php
if(!empty($this->config->sso->redirect))
{
    css::import($defaultTheme . 'bindranzhi.css');
    js::import($jsRoot . 'bindranzhi.js');
}
?>
  <nav id='mainmenu'>
    <?php commonModel::printMainmenu($this->moduleName); commonModel::printSearchBox();?>
    <?php if(!empty($this->config->sso->redirect)):?>
    <div class='pull-right' id='topnav'><?php commonModel::printTopBar();?></div>
    <?php endif;?>
  </nav>
  <nav id="modulemenu">
    <?php commonModel::printModuleMenu($this->moduleName);?>
  </nav>
</header>

<div id='wrap' <?php if(!empty($this->config->sso->redirect)) echo "class='ranzhiFixedTfootAction'";?> >
<?php endif;?>
  <div class='outer'>
