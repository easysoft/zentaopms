<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include 'header.lite.html.php';
include 'chosen.html.php';
//include 'validation.html.php';
?>
<?php if(empty($_GET['onlybody']) or $_GET['onlybody'] != 'yes'):?>
<?php $this->app->loadConfig('sso');?>
<?php if(!empty($config->sso->redirect)) js::set('ssoRedirect', $config->sso->redirect);?>
<?php
$isProgram   = $app->openApp == 'program';
$isProduct   = $app->openApp == 'product';
$isProject   = $app->openApp == 'project';
$isExecution = $app->openApp == 'execution';
$isReport    = $app->openApp == 'report';
$isQa        = $app->openApp == 'qa';
?>
<header id='header'>
  <div id='mainHeader'>
    <div class='container'>
      <div id='heading'>
        <?php if($isProduct)   echo isset($lang->product->switcherMenu) ? $lang->product->switcherMenu : '';?>
        <?php if($isQa)        echo isset($lang->qa->switcherMenu) ? $lang->qa->switcherMenu : '';?>
        <?php if($this->config->systemMode == 'new'):?>
        <?php if($isProgram)   echo isset($lang->program->switcherMenu) ? $lang->program->switcherMenu : '';?>
        <?php if($isProject)   echo $this->loadModel('project')->getSwitcher($this->session->PRJ, $app->rawModule, $app->rawMethod);?>
        <?php if($isExecution) echo $this->loadModel('execution')->getSwitcher($this->session->execution, $app->rawModule, $app->rawMethod);?>
        <?php elseif($this->config->systemMode == 'classic'):?>
        <?php if($isProject)   echo isset($lang->project->switcherMenu) ? $lang->project->switcherMenu : '';;?>
        <?php endif;?>
      </div>
      <nav id='navbar'><?php commonModel::printMainMenu($app->rawModule, $app->rawMethod);?></nav>
    </div>
  </div>
  <?php if(!in_array($app->rawModule, $lang->noMenuModule)):?>
  <div id='subHeader'>
    <div class='container'>
      <div id="pageNav" class='btn-toolbar'><?php if(isset($lang->modulePageNav)) echo $lang->modulePageNav;?></div>
      <nav id='subNavbar'><?php common::printModuleMenu($app->rawModule, $app->rawMethod);?></nav>
      <div id="pageActions"><div class='btn-toolbar'><?php if(isset($lang->TRActions)) echo $lang->TRActions;?></div></div>
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
<script>
adjustMenuWidth();
</script>
<main id='main' <?php if(!empty($config->sso->redirect)) echo "class='ranzhiFixedTfootAction'";?> >
  <div class='container'>
