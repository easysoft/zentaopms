<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include 'header.lite.html.php';
include 'chosen.html.php';
//include 'validation.html.php';
?>
<?php
/* Load hook files for current page. */
$extPath      = $this->app->getModuleRoot() . '/common/ext/view/';
$extHookRule  = $extPath . 'header.*.hook.php';
$extHookFiles = glob($extHookRule);
if($extHookFiles) foreach($extHookFiles as $extHookFile) include $extHookFile;
?>
<?php if(empty($_GET['onlybody']) or $_GET['onlybody'] != 'yes'):?>
<?php $this->app->loadConfig('sso');?>
<?php if(!empty($config->sso->redirect)) js::set('ssoRedirect', $config->sso->redirect);?>
<?php $moduleName = $app->rawModule;?>
<?php $methodName = $app->rawMethod;?>
<?php $isProgram  = (zget($lang->navGroup, $moduleName) == 'program');?>
<div id='menu'>
  <nav id='menuNav'><?php commonModel::printMainNav($moduleName);?></nav>
  <div id='menuFooter'>
    <button type='button' id='menuToggle'><i class='icon icon-sm icon-menu-collapse'></i></button>
  </div>
</div>
<header id='header'>
  <div id='mainHeader'>
    <div class='container'>
      <div id='heading'>
        <?php //if($isProgram && isset($lang->programSwapper)) echo $lang->programSwapper;?>
      </div>
      <nav id='navbar'><?php commonModel::printMainmenu($moduleName, $methodName);?></nav>
      <div id='toolbar'>
        <div id="userMenu">
          <?php common::printSearchBox();?>
          <ul id="userNav" class="nav nav-default">
            <li><?php common::printUserBar();?></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <?php if(!in_array($moduleName, $lang->noMenuModule)):?>
  <div id='subHeader'>
    <div class='container'>
      <div id="pageNav" class='btn-toolbar'><?php if(isset($lang->modulePageNav)) echo $lang->modulePageNav;?></div>
      <nav id='subNavbar'><?php common::printModuleMenu($this->moduleName);?></nav>
      <div id="pageActions"><div class='btn-toolbar'><?php if(isset($lang->modulePageActions)) echo $lang->modulePageActions;?></div></div>
    </div>
  </div>
<?php endif;?>
  <?php
  if(!empty($config->sso->redirect))
  {
      css::import($defaultTheme . 'bindranzhi.css');
      js::import($jsRoot . 'bindranzhi.js');
  }
  ?>
</header>

<?php endif;?>
<main id='main' <?php if(!empty($config->sso->redirect)) echo "class='ranzhiFixedTfootAction'";?> >
  <div class='container'>
