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
    <div id="globalBarLogo">
      <?php if(isset($config->xxserver->installed) and $config->xuanxuan->turnon) commonModel::printClientLink();?>
      <?php echo $lang->proVersion;?>
      <a href='<?php echo $lang->website;?>' class="btn btn-sm btn-link" target='_blank' title='<?php echo $config->version;?>'><i class="icon icon-zentao" style="font-size: 24px;"></i></a>
      <!--<a href='javascript:void(0)' class="btn btn-sm btn-link" type="button"><i class="icon icon-message"></i></a>-->
      <div id="globalSearchDiv">
        <div class="input-group">
          <div class="input-control search-box search-box-circle has-icon-left has-icon-right search-example" id="searchboxExample">
            <input id="globalSearchInput" type="search" class="form-control search-input" placeholder="<?php echo $lang->index->search;?>">
          </div>
          <span class="input-group-btn">
            <button id="globalSearchButton" class="btn btn-secondary" type="button"><i class="icon icon-search"></i></button>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
