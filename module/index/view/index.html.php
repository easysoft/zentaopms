<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 1947 2011-06-29 11:58:03Z wwccss $
 */
?>
<?php
include '../../common/view/header.lite.html.php';

$this->app->loadConfig('sso');
if(!empty($config->sso->redirect)) js::set('ssoRedirect', $config->sso->redirect);

$isProduct = (zget($lang->navGroup, $app->rawModule) == 'product');
$isProgram = (zget($lang->navGroup, $app->rawModule) == 'program');
$isProject = (zget($lang->navGroup, $app->rawModule) == 'project');

js::set('navGroup', $lang->navGroup);
js::set('tabsLang', $lang->index->tab);
js::set('menuItems', commonModel::getMainNavList($app->rawModule));
js::set('defaultOpen', $open);
?>
<div id='menu'>
  <nav id='menuNav' data-group='<?php echo $app->rawModule; ?>'>
    <ul class='nav nav-default' id='menuMainNav'>
    </ul>
    <ul class='nav nav-default'>
      <?php commonModel::getRecentExecutions();?>
    </ul>
  </nav>
  <div class="table-col col-right" id="moreExecution">
    <div class="list-group" id="executionList"></div>
  </div>
  <div id='menuFooter'>
    <ul id="userNav" class="nav">
      <li class='dropdown dropdown-hover'><?php common::printUserBar();?></li>
    </ul>
  </div>
</div>
<div id='pages'>
</div>
<div id='pagesBar'>
    <ul id='bars' class='nav nav-default'></ul>
    <div id='poweredBy'>
      <a href='<?php echo $lang->website;?>' target='_blank'><i class='icon-zentao'></i> <?php echo $lang->zentaoPMS . $config->version;?></a> &nbsp;
      <?php echo $lang->proVersion;?>
      <?php if(isset($config->xxserver->installed) and $config->xuanxuan->turnon) commonModel::printClientLink();?>
    </div>
</div>

<?php include '../../common/view/footer.lite.html.php';?>
