<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include 'header.lite.html.php';
include 'chosen.html.php';
//include 'validation.html.php';
?>
<?php if(empty($_GET['onlybody']) or $_GET['onlybody'] != 'yes'):?>
<?php $this->app->loadConfig('sso');?>
<?php if(!empty($config->sso->redirect)) js::set('ssoRedirect', $config->sso->redirect);?>
<?php if($config->showMainMenu):?>
<header id='header'>
  <div id='mainHeader'>
    <div class='container'>
      <div id='heading'>
        <?php common::printHomeButton($app->tab);?>
        <?php echo isset($lang->switcherMenu) ? $lang->switcherMenu : '';?>
      </div>
      <nav id='navbar'><?php $activeMenu = commonModel::printMainMenu();?></nav>
      <div id='headerActions'><?php if(isset($lang->headerActions)) echo $lang->headerActions;?></div>
      <div id='toolbar'>
        <div id='userMenu'>
          <ul id="userNav" class="nav nav-default">
            <li class='dropdown dropdown-hover' id='globalCreate'><?php common::printCreateList();?></li>
            <li class='dropdown dropdown-hover has-avatar' id='userDropDownMenu'><?php common::printUserBar();?></li>
            <li class='dropdown dropdown-hover' id='visionSwitcher'><?php common::printVisionSwitcher();?></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <?php if(isset($lang->{$app->tab}->menu->$activeMenu) and is_array($lang->{$app->tab}->menu->$activeMenu) and isset($lang->{$app->tab}->menu->{$activeMenu}['subMenu'])):?>
  <?php $subMenuClass = $app->tab == 'admin' ? 'admin-tab-menu' : '';?>
  <div id='subHeader' class="<?php echo $subMenuClass;?>">
    <div class='container'>
      <div id="pageNav" class='btn-toolbar'><?php if(isset($lang->modulePageNav)) echo $lang->modulePageNav;?></div>
      <nav id='subNavbar'><?php common::printModuleMenu($activeMenu);?></nav>
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
<?php else:?>
<header id='header'>
  <div id='mainHeader' style="height: 0;"></div>
</header>
<?php endif;?>

<?php endif;?>
<script>
adjustMenuWidth();
if(window.navigator.userAgent.indexOf('xuanxuan') > 0)
{
    $('li.user-tutorial').addClass('hide');

    /* Fix double header covering #main. */
    $('document').ready(function()
    {
        $('#subHeader').parent().parent().children('#main').css('top', '100px');
    });
}
</script>
<main id='main' <?php if(!empty($config->sso->redirect)) echo "class='ranzhiFixedTfootAction'";?> >
  <div class='container'>
