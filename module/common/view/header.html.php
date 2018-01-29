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
<?php if(!empty($this->config->sso->redirect)) js::set('ssoRedirect', $this->config->sso->redirect);?>
<header id='header'>
  <div id='mainHeader'>
    <div class='container-fixed'>
      <hrgroup id='heading'>
        <?php if(empty($this->config->sso->redirect)):?>
        <h1 id='companyname' title='<?php printf($lang->welcome, $app->company->name);?>'><?php printf($lang->welcome, $app->company->name);?></h1>
        <?php endif;?>
      </hrgroup>
      <nav id='navbar'><?php commonModel::printMainmenu($this->moduleName);?></nav>
      <div id='toolbar'>
        <div id="extraNav">
          <?php //common::printTopBar();?>
          <?php common::printAboutBar();?>
        </div>
        <div id="userMenu">
          <?php common::printSearchBox();?>
          <ul id="userNav" class="nav nav-default">
            <?php list($adminName, $adminModule, $adminMethod) = explode('|', $lang->adminMenu);?>
            <li><?php echo html::a($this->createLink($adminModule, $adminMethod), $adminName);?></li>
            <li><?php echo '<a>通知</a>';?></li>
            <li>
              <a class="dropdown-toggle" data-toggle="dropdown">
                <div class="avatar avatar-sm bg-info avatar-circle"><?php echo strtoupper($app->user->account{0})?></div>
                <span class="user-name"><?php echo empty($app->user->realname) ? $app->user->account : $app->user->realname;?></span>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <?php
                list($taskName, $taskModule, $taskMethod) = explode('|', $lang->my->menu->task['link']);
                echo '<li>' . html::a($this->createLink($taskModule, $taskMethod), $taskName) . '</li>';
                list($bugName, $bugModule, $bugMethod) = explode('|', $lang->my->menu->bug['link']);
                echo '<li>' . html::a($this->createLink($bugModule, $bugMethod), $bugName) . '</li>';
                list($storyName, $storyModule, $storyMethod) = explode('|', $lang->my->menu->story['link']);
                echo '<li>' . html::a($this->createLink($storyModule, $storyMethod), $storyName) . '</li>';
                list($taskName, $taskModule, $taskMethod) = explode('|', $lang->my->menu->testtask['link']);
                echo '<li>' . html::a($this->createLink($taskModule, $taskMethod), $taskName) . '</li>';
                echo "<li class='divider'></li>";
                echo '<li>' . html::a($this->createLink('user', 'logout'), $lang->logout) . '</li>';
                echo "<li class='dropdown-submenu left'>";
                echo "<a href='javascript:;'>" . $lang->lang . "</a><ul class='dropdown-menu'>";
                foreach ($app->config->langs as $key => $value)
                {
                    echo "<li class='lang-option" . ($app->cookie->lang == $key ? " active" : '') . "'><a href='javascript:selectLang(\"$key\");' data-value='" . $key . "'>" . $value . "</a></li>";
                }
                echo '</ul></li>';
                ?>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div id='subHeader'>
    <div class='container-fixed'>
      <div id="pageNav"></div>
      <nav id='subNavbar'><?php common::printModuleMenu($this->moduleName);?></nav>
      <div id="pageActions"></div>
    </div>
  </div>
  <?php
  if(!empty($this->config->sso->redirect))
  {
      css::import($defaultTheme . 'bindranzhi.css');
      js::import($jsRoot . 'bindranzhi.js');
  }
  ?>
</header>

<main id='main' <?php if(!empty($this->config->sso->redirect)) echo "class='ranzhiFixedTfootAction'";?> >
<?php endif;?>
  <div class='container-fixed'>
