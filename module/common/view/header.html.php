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
$rawModule   = zget($lang->navGroup, $app->rawModule);
$isProgram   = $rawModule == 'program';
$isProduct   = $rawModule == 'product';
$isProject   = $rawModule == 'project';
$isExecution = $rawModule == 'execution';
$isReport    = $rawModule == 'report';
$isQa        = $rawModule == 'qa';
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
      <nav id='navbar'><?php commonModel::printMainmenu($app->rawModule, $app->rawMethod);?></nav>
      <div id='toolbar'>
        <?php if($isProgram)   echo isset($lang->program->mainMenuAction) ? $lang->program->mainMenuAction : '';?>
        <?php if($isProject)   echo $this->loadModel('project')->getMainAction($app->rawModule, $app->rawMethod);?>
        <?php if($isExecution) echo $this->execution->getMainAction($app->rawModule, $app->rawMethod);?>
        <?php if($isProduct)   echo isset($lang->product->mainMenuAction) ? $lang->product->mainMenuAction : '';?>
        <?php if($isReport)    echo isset($lang->report->mainMenuAction) ? $lang->report->mainMenuAction : '';?>
        <?php if($isQa)        echo isset($lang->qa->mainMenuAction) ? $lang->qa->mainMenuAction : '';?>
      </div>
    </div>
  </div>
  <?php if(!in_array($app->rawModule, $lang->noMenuModule)):?>
  <div id='subHeader'>
    <div class='container'>
      <div id="pageNav" class='btn-toolbar'><?php if(isset($lang->modulePageNav)) echo $lang->modulePageNav;?></div>
      <nav id='subNavbar'><?php common::printModuleMenu($app->rawModule);?></nav>
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
